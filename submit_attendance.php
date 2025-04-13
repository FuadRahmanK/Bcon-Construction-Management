<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'bcon');

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $current_date = date('Y-m-d'); // Get the current date

    // Select all labours to mark attendance
    $sql = "SELECT firstname, lastname, email FROM credentials WHERE role = 'labour'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $labour_name = htmlspecialchars($row['firstname'] . ' ' . $row['lastname']);
            $email = htmlspecialchars($row['email']); // Get the email
            // Check if the labour is present based on the checkbox
            $status = isset($_POST['attendance'][$labour_name][$email]) ? 'present' : 'absent';

            // Check if attendance already exists for the current date
            $check_stmt = $conn->prepare("SELECT * FROM attendance WHERE labour_name = ? AND attendance_date = ? AND email = ?");
            $check_stmt->bind_param("sss", $labour_name, $current_date, $email);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result->num_rows > 0) {
                // Update existing record
                $update_stmt = $conn->prepare("UPDATE attendance SET status = ? WHERE labour_name = ? AND attendance_date = ? AND email = ?");
                $update_stmt->bind_param("ssss", $status, $labour_name, $current_date, $email);
                $update_stmt->execute();
                $update_stmt->close();
            } else {
                // Insert new record
                $insert_stmt = $conn->prepare("INSERT INTO attendance (labour_name, attendance_date, status, email) VALUES (?, ?, ?, ?)");
                $insert_stmt->bind_param("ssss", $labour_name, $current_date, $status, $email);
                $insert_stmt->execute();
                $insert_stmt->close();
            }

            $check_stmt->close();
        }
        // Redirect to home.html after successful submission
        header("Location: contr_home.html");
        exit();
    } else {
        echo "<p>No labours found.</p>";
    }

    // Close connections
    $conn->close();
}
?>
