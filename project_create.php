<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn = new mysqli("localhost", "root", "", "bcon");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $project_name = $_POST['project-name'];
    $location = $_POST['location'];
    $budget = $_POST['budget'];
    $requirements = $_POST['requirements'];
    $upload = $_FILES['plan']['name']; // Changed variable name to 'upload'
    $square_feet = $_POST['square-feet']; // Retrieve square feet from the form

    $upload_dir = "plan/";

    // Check if the upload directory exists, if not, create it
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true); // Create the directory with appropriate permissions
    }

    $file_path = $upload_dir . basename($upload);

    if (move_uploaded_file($_FILES['plan']['tmp_name'], $file_path)) {
        // Retrieve the owner's email from the session
        $owner_email = $_SESSION['email']; // Assuming the email is stored in the session

        // Update the SQL query to include the area
        $sql = "INSERT INTO projects (name, location, budget, requirements, upload, owner, area) 
                VALUES (?, ?, ?, ?, ?, ?, ?)"; // Added 'area' to the SQL query
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $project_name, $location, $budget, $requirements, $file_path, $owner_email, $square_feet); // Bind the square feet as well

        if ($stmt->execute()) {
            header("Location: owner_projects.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: Failed to upload file.";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Error: Invalid request method.";
}