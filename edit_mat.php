<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bcon");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the material name from the URL
$material_name = isset($_GET['name']) ? $_GET['name'] : '';

// Fetch the existing material data
$sql = "SELECT * FROM resource WHERE name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $material_name);
$stmt->execute();
$result = $stmt->get_result();
$material = $result->fetch_assoc();

if (!$material) {
    echo "Material not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Material</title>
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
            justify-content: center; /* Center the content vertically */
            align-items: center; /* Center the content horizontally */
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
        .form-container {
            display: flex;
            justify-content: center;
            width: 100%;
            padding: 20px;
            flex-grow: 1; /* Allow form container to grow and push footer down */
        }
        .form-card {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 40px; /* Increased padding for larger form */
            border-radius: 10px;
            backdrop-filter: blur(5px);
            width: 100%;
            max-width: 800px; /* Increased max-width for larger form */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5); /* Added shadow for card effect */
            text-align: center; /* Center the text inside the form card */
        }
        h2 {
            font-size: 2rem; /* Increased size of the heading */
            margin-bottom: 20px; /* Added margin for spacing */
        }
        .form-group {
            margin-bottom: 1rem;
            text-align: left; /* Align labels to the left */
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #f4a460;
            font-size: 1.2rem; /* Increased font size for labels */
        }
        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 0.8rem; /* Increased padding for inputs */
            border: 1px solid rgba(244, 164, 96, 0.3);
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 1.1rem; /* Increased font size for input text */
        }
        select {
            width: 100%;
            padding: 0.8rem; /* Increased padding for select */
            border: 1px solid rgba(244, 164, 96, 0.3);
            border-radius: 5px;
            background-color: rgba(50, 50, 50, 0.8); /* Changed background color for better visibility */
            color: white;
            font-size: 1.1rem; /* Increased font size for select */
        }
        select option {
            color: white; /* Set option text color to white */
        }
        button[type="submit"] {
            background-color: #f4a460;
            color: black;
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease;
            display: block;
            margin: 1rem auto;
            font-size: 1.2rem; /* Increased font size for button */
        }
        button[type="submit"]:hover {
            background-color: #e5915c;
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

    <div class="form-container">
        <div class="form-card">
            <h2>Edit Material</h2>
            <form action="update_material.php" method="POST">
                <input type="hidden" name="name" value="<?php echo htmlspecialchars($material['name']); ?>">
                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="<?php echo htmlspecialchars($material['quantity']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($material['price']); ?>" required>
                </div>
                <button type="submit">Update Material</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 BuildRight Construction. All rights reserved.</p>
    </footer>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>