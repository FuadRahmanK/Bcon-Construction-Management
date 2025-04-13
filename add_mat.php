<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bcon");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form data
    $material_name = isset($_POST['material-name']) ? $_POST['material-name'] : '';
    $quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 0;
    $unit = isset($_POST['unit']) ? $_POST['unit'] : '';
    $price = isset($_POST['price']) ? $_POST['price'] : 0.0;

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO resource (name, quantity, unit, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sisd", $material_name, $quantity, $unit, $price);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to inventory.php if material added successfully
        header("Location: inventory.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
}
$conn->close();
?>
