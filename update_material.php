<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bcon");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $quantity = $_POST['quantity']; // Changed from unit to quantity
    $price = $_POST['price'];

    // Update the material in the database
    $sql = "UPDATE resource SET quantity = ?, price = ? WHERE name = ?"; // Changed unit to quantity
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("dis", $quantity, $price, $name); // d for double, i for integer, s for string

    if ($stmt->execute()) {
        header("Location: inventory.php"); // Redirect back to inventory
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>