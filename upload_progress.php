<?php
session_start(); // Start the session to access session variables

// Check if the user is logged in and the email is set in the session
if (!isset($_SESSION['email'])) {
    die('Unauthorized access. Please log in.');
}

// Database connection
$servername = "localhost"; // Change as needed
$username = "root"; // Change as needed
$password = ""; // Change as needed
$dbname = "bcon"; // Change as needed

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the file upload
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_SESSION['email'];
    $description = $_POST['description'];
    $project = $_POST['project']; // Get the project from the form
    $owner = $_POST['owner']; // Get the owner from the form
    
    // Handle file upload
    $targetDir = "progress/"; // Changed directory to 'progress'
    $targetFile = $targetDir . basename($_FILES["file"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($_FILES["file"]["tmp_name"]);
    if ($check === false) {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["file"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
        echo "Sorry, only JPG, JPEG, & PNG files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Try to upload file
        if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
            // Prepare and bind
            $stmt = $conn->prepare("INSERT INTO progress (pic, `desc`, email, proj_name, own_name) VALUES (?, ?, ?, ?, ?)"); // Added own_name
            $stmt->bind_param("sssss", $targetFile, $description, $email, $project, $owner); // Added owner to bind parameters

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect to progress.html on successful insert
                header("Location: progress.php?upload_success=true"); // Added query parameter for success
                exit();
            } else {
                echo "Error: " . $stmt->error; // Log error if execution fails
            }

            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

$conn->close();
?>
