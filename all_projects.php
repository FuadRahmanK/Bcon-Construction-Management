<?php
session_start();
$conn = new mysqli("localhost", "root", "", "bcon");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all projects
$sql = "SELECT p.name, p.location, p.area, p.budget, p.upload, p.owner, c.firstname, c.lastname 
        FROM projects p 
        JOIN credentials c ON p.owner = c.email";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contractor Dashboard</title>
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
            height: 100vh; /* Set height to 100vh to make the page fixed */
            display: flex;
            flex-direction: column;
        }

        header {
            padding: 1rem;
            position: relative;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .logo {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            font-weight: bold;
            color: #f4a460;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
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
            letter-spacing: 0.5px;
        }

        .container {
            flex: 1;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem 1rem;
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Align items to the start */
            overflow: hidden; /* Prevent overflow */
        }

        .dashboard-grid {
            display: flex;
            flex-direction: column; 
            gap: 2rem;
            width: 100%;
            align-items: flex-start; 
            overflow-y: auto; /* Make only the dashboard grid scrollable */
            max-height: calc(100vh - 200px); /* Adjust height to allow space for footer */
            scrollbar-width: none; /* Firefox */
        }

        .dashboard-grid::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.1);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: left; 
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
            width: 100%; 
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start; 
            text-decoration: none;
            color: black; 
            cursor: pointer;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            background: rgba(46, 139, 87, 0.2);
        }

        .dashboard-card h2 {
            color: orange; 
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
            font-size: 2.2rem; 
        }

        .dashboard-card p {
            color: black; 
            font-size: 1.2rem; 
            font-weight: bold; 
        }

        footer {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            text-align: center;
            padding: 1rem;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Contractor Dashboard</div>
            <ul class="nav-links">
                <li><a href="contr_home.html">Home</a></li>
                <li><a href="index.html" style="color: red; font-weight: bold;">Sign Out</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <div class="dashboard-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="dashboard-card">
                        <h2><?php echo htmlspecialchars($row['name']); ?></h2>
                        <p>Location: <?php echo htmlspecialchars($row['location']); ?></p>
                        <p>Area: <?php echo htmlspecialchars($row['area']); ?> sq ft</p>
                        <p>Budget: $<?php echo htmlspecialchars($row['budget']); ?></p>
                        <p>Owner: <?php echo htmlspecialchars($row['firstname'] . ' ' . $row['lastname']); ?></p>
                        <a href="<?php echo htmlspecialchars($row['upload']); ?>" style="color: #f4a460;">View Plan</a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No projects found.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 BuildRight Construction. All rights reserved.</p>
    </footer>
</body>
</html>
<?php
$conn->close();