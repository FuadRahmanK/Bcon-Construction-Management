<?php
session_start();

// Check if the user is logged in and has an email in the session
if (!isset($_SESSION['email'])) {
    die("Access denied. Please log in.");
}

// Database connection
$conn = new mysqli('localhost', 'root', '', 'bcon');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the email from the session
$email = $_SESSION['email'];

// Prepare the SQL statement to fetch attendance records
$sql = "SELECT attendance_date, labour_name, status FROM attendance WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Start HTML output
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance View</title>
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
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
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

        h1 {
            color: #f4a460;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            text-align: center;
        }

        table {
            width: 80%; /* Decreased width of the table */
            border-collapse: collapse;
            margin: 20px auto; /* Center the table */
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            overflow: hidden;
            border: none; /* Hide borders of the table */
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f4a460;
            color: white;
        }

        .present {
            color: green;
        }

        .absent {
            color: red;
        }

        footer {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            text-align: center;
            padding: 1rem;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            margin-top: auto;
            position: relative; /* Ensure footer stays at the bottom */
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">Labour Dashboard</div>
            <ul class="nav-links">
            <li><a href="labour_home.html">Home</a></li>
                <li><a href="index.html" style="color: red; font-weight: bold;">Sign Out</a></li>
            </ul>
        </nav>
        <h1>Attendance Records</h1>
    </header>

    <table>
        <tr>
            <th>Attendance Date</th>
            <th>Labour Name</th>
            <th>Status</th>
        </tr>
        <?php
        // Fetch and display the attendance records
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $statusClass = ($row['status'] === 'present') ? 'present' : 'absent';
                echo "<tr>
                        <td>" . htmlspecialchars($row['attendance_date']) . "</td>
                        <td>" . htmlspecialchars($row['labour_name']) . "</td>
                        <td class='$statusClass'>" . htmlspecialchars($row['status']) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No attendance records found.</td></tr>";
        }
        ?>
    </table>

    <footer>
        <p>&copy; 2023 BuildRight Construction. All rights reserved.</p>
    </footer>
</body>
</html>
<?php
// Close the database connection
$stmt->close();
$conn->close();
?>