<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bcon");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get project name and location from the URL
$project_name = isset($_GET['name']) ? $_GET['name'] : '';
$project_location = isset($_GET['location']) ? $_GET['location'] : '';

// Fetch project details from the database based on name and location
$sql = "SELECT * FROM projects WHERE name = ? AND location = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $project_name, $project_location);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            line-height: 1.6;
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1504307651254-35680f356dfd');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            width: 100%;
        }

        header {
            padding: 1rem;
            background-color: transparent; /* Changed to transparent */
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
            content: "🏗";
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

        footer {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            text-align: center;
            padding: 1rem;
            width: 100%;
            position: fixed;
            bottom: 0;
        }

        .project-card {
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            margin: 20px auto;
            text-decoration: none;
            color: white;
            max-width: 800px;
            display: flex; /* Changed to flex */
            flex-direction: row; /* Align items in a row */
            gap: 20px; /* Space between details and image */
            transition: transform 0.3s;
        }

        .project-card:hover {
            transform: scale(1.02);
        }

        .project-name {
            font-size: 1.8rem;
            font-weight: 700;
            color: #f4a460;
            margin-bottom: 1rem;
        }

        .project-details {
            display: flex;
            flex-direction: column;
            gap: 0.8rem;
            flex: 1; /* Allow details to take available space */
        }

        .project-location, .project-budget, .project-requirements {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
        }

        .project-location i, .project-budget i {
            color: #f4a460;
            width: 20px;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Project Details</div>
            <ul class="nav-links">
                <li><a href="home.html">Home</a></li>
                <li><a href="index.html" style="color: red; font-weight: bold;">Sign Out</a></li>
            </ul>
        </nav>
    </header>

    <div class="content">
        <div class="project-card">
            <?php
            if ($result->num_rows > 0) {
                // Output data of the project
                $row = $result->fetch_assoc();
                echo '<div class="project-details">';
                echo '<div class="project-name">' . htmlspecialchars($row['name']) . '</div>';
                echo '<div class="project-location">';
                echo '<i class="fas fa-map-marker-alt"></i>';
                echo '<span>' . htmlspecialchars($row['location']) . '</span>';
                echo '</div>';
                echo '<div class="project-budget">';
                echo '<i class="fas fa-rupee-sign"></i>';
                echo '<span>' . htmlspecialchars($row['budget']) . '</span>';
                echo '</div>';
                echo '<div class="project-requirements"><strong>Requirements:</strong> ' . htmlspecialchars($row['requirements']) . '</div>';
                echo '<div class="uploaded-plan"><strong>Uploaded Plan:</strong> <a href="' . htmlspecialchars($row['upload']) . '" style="color: #f4a460;">View Plan</a></div>'; // Redirect to image
                echo '</div>';
            } else {
                echo '<p>No project details found.</p>';
            }
            $stmt->close();
            $conn->close();
            ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 BuildRight Construction. All rights reserved.</p>
    </footer>
</body>
</html>