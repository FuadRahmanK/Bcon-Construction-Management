<?php
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

// Fetch materials from the resource table
$sql = "SELECT name, quantity, unit, price FROM resource"; // No id needed
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contractor - Resource Management</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1541888946425-d81bb19240f5?ixlib=rb-4.0.3');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center the content horizontally */
            justify-content: space-between; /* Ensure footer is at the bottom */
        }
        header {
            padding: 1rem;
            width: 100%; /* Ensure header takes full width */
        }
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem; /* Decreased font size */
            font-weight: bold;
            color: #f4a460;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
        }
        .logo::before {
            content: "🏗️";
            margin-right: 10px;
            font-size: 1.5rem; /* Decreased font size */
        }
        .nav-links {
            list-style: none;
            display: flex;
            gap: 2rem;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 600;
        }
        .main-container {
            display: flex;
            justify-content: center;
            width: 100%; /* Changed to fill the page */
            margin: 0; /* Removed margin */
            padding: 20px;
        }
        .left-container { 
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px; /* Increased padding for more space */
            border-radius: 10px;
            backdrop-filter: blur(5px);
            width: 90%; /* Increased width of the container */
            max-width: 1000px; /* Increased maximum width */
        }
        table {
            width: 100%; /* Set table width to fill the container */
            border-collapse: collapse;
            margin-top: 40px;
            border: none; /* Hide table borders */
        }
        th, td {
            padding: 10px; /* Decreased padding for less spacing */
            text-align: left;
            border: none; /* Hide cell borders */
            font-size: 1.2rem; /* Decreased text size */
        }
        th {
            background-color: rgba(255, 255, 255, 0.1);
            color: #FFD700;
            padding-bottom: 15px; /* Decreased spacing below header */
        }
        tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        h2 {
            color: #FFD700;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-align: center;
            margin-bottom: 20px;
            font-size: 1.5rem; /* Decreased font size for the title */
        }
        footer {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            text-align: center;
            padding: 1rem;
            width: 100%; /* Fill the width of the footer */
            position: relative; /* Ensure footer is positioned correctly */
            margin-top: auto; /* Push footer to the bottom */
        }
        .add-material-btn {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color:rgb(0, 0, 0);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .add-material-btn:hover {
            background-color: #d69a3d;
        }
        .edit-btn {
            display: inline-block;
            padding: 5px 10px;
            background-color: #f4a460;
            color: black;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .edit-btn:hover {
            background-color: #e5915c;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Bcon</div>
            <ul class="nav-links">
                <li><a href="contr_home.html">Home</a></li>
                <li><a href="index.html" style="color: red; font-weight: bold;">Sign Out</a></li>
            </ul>
        </nav>
    </header>

    <div class="main-container">
        <div class="left-container">
            <h2>Material Inventory</h2>
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Unit</th>
                        <th>Price</th>
                        <th></th> <!-- New Edit column -->
                    </tr>
                </thead>
                <tbody id="materialList">
                    <?php
                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>" . htmlspecialchars($row['name']) . "</td>
                                    <td>" . htmlspecialchars($row['quantity']) . "</td>
                                    <td>" . htmlspecialchars($row['unit']) . "</td>
                                    <td>" . htmlspecialchars($row['price']) . " per unit</td>
                                    <td><a href='edit_mat.php?name=" . urlencode($row['name']) . "' class='edit-btn'>Edit</a></td> <!-- Edit button -->
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No materials found.</td></tr>"; // Updated colspan to 5
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
            <a href="add_material.html" class="add-material-btn">Add Material</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 BuildRight Construction. All rights reserved.</p>
    </footer>

</body>
</html>