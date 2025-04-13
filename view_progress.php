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

// Get the owner's email from the session (assuming it's stored in session)
session_start();
$owner_email = $_SESSION['email'] ?? ''; // Retrieve the email of the signed-in user

// Fetch all progress data for the owner
$sql = "SELECT pic, `desc`, email, proj_name FROM progress WHERE own_mail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $owner_email);
$stmt->execute();
$result = $stmt->get_result();

// Prepare to fetch progress data
$names = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $names[] = $row; // Store all progress data
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Construction Company</title>
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
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1504307651254-35680f356dfd');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            padding: 1rem;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
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
            font-size: 1.8rem;
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

        .container {
            flex: 1;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
            width: 80%;
            margin: 1rem 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-decoration: none;
            color: white;
            cursor: pointer;
        }

        .dashboard-card img {
            width: 100px;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        footer {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Bcon</div>
            <ul class="nav-links">
                <li><a href="home.html">Home</a></li>
                <li><a href="index.html" style="color: red; font-weight: bold;">Sign Out</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <?php
        if (!empty($names)) {
            foreach ($names as $name) {
                echo "<div class='dashboard-card'>
                        <img src='" . $name['pic'] . "' alt='Progress Image'>
                        <h3>" . $name['desc'] . "</h3>
                        <p>Owner Email: " . htmlspecialchars($name['email']) . "</p>
                        <p>Project Name: " . htmlspecialchars($name['proj_name']) . "</p>
                      </div>";
            }
        } else {
            echo "<div class='dashboard-card'>No progress found</div>";
        }
        $stmt->close();
        $conn->close();
        ?>
    </div>

    <footer>
        <p>&copy; 2023 BuildRight Construction. All rights reserved.</p>
    </footer>
</body>
</html>