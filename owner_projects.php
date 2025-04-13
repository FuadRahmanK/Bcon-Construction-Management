<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bcon";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch projects from the database
$sql = "SELECT name, location, budget FROM projects";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Projects</title>
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
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
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

        .create-project-btn {
            display: block;
            background-color: #f4a460;
            color: black;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            transition: background-color 0.3s ease;
            margin: 0.5rem auto;
            width: fit-content;
        }

        .create-project-btn:hover {
            background-color: #e5915c;
        }

        .content {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            flex: 1;
            padding: 2rem;
            height: calc(100vh - 200px);
            overflow: hidden;
        }

        .left-section {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .project-cards {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            width: 100%;
            max-width: 800px;
            height: 100%;
            overflow-y: auto;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
            padding-bottom: 2rem;
        }

        .project-cards::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }

        .project-card {
            background-color: rgba(0, 0, 0, 0.7);
            border-radius: 10px;
            padding: 1.5rem;
            border: 1px solid rgba(244, 164, 96, 0.3);
            transition: transform 0.3s ease;
            cursor: pointer; /* Change cursor to pointer */
            text-decoration: none; /* Remove underline from links */
        }

        .project-card:hover {
            transform: translateY(-5px);
        }

        .project-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #f4a460;
            margin-bottom: 0.5rem;
        }

        .project-details {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .project-location, .project-budget {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #ffffff;
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
            <div class="left-section">
                <div class="logo">Your Projects</div>
            </div>
            <ul class="nav-links">
                <li><a href="home.html">Home</a></li>
                <li><a href="index.html" style="color: red; font-weight: bold;">Sign Out</a></li>
            </ul>
        </nav>
        <a href="create_project.html" class="create-project-btn">Create New Project</a>
    </header>

    <div class="content">
        <div class="project-cards">
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    echo '<a href="view_projects.php?name=' . urlencode($row["name"]) . '&location=' . urlencode($row["location"]) . '" class="project-card">';
                    echo '<div class="project-name">' . htmlspecialchars($row["name"]) . '</div>';
                    echo '<div class="project-details">';
                    echo '<div class="project-location">';
                    echo '<i class="fas fa-map-marker-alt"></i>';
                    echo '<span>' . htmlspecialchars($row["location"]) . '</span>';
                    echo '</div>';
                    echo '<div class="project-budget">';
                    echo '<i class="fas fa-rupee-sign"></i>'; // Changed dollar sign to rupee sign
                    echo '<span>' . htmlspecialchars($row["budget"]) . '</span>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>'; // Close the anchor tag
                }
            } else {
                echo '<p>No projects found.</p>';
            }
            $conn->close();
            ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 BuildRight Construction. All rights reserved.</p>
    </footer>
</body>
</html>