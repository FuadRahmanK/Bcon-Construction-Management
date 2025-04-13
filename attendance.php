<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Marking - Contractor Dashboard</title>
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
            justify-content: center;
        }

        .attendance-form {
            background: rgba(255, 255, 255, 0.1);
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            backdrop-filter: blur(10px);
            transition: transform 0.3s ease;
            width: 80%;
            margin: 1rem auto;
            color: white;
        }

        .attendance-form h2 {
            color: #fff;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.4);
            font-size: 1.8rem;
        }

        .attendance-form label {
            display: block;
            margin: 0.5rem 0;
            text-align: left;
            font-size: 1.1rem;
        }

        .attendance-form input[type="checkbox"] {
            margin-right: 0.5rem;
        }

        .attendance-form button {
            background-color: #f4a460;
            color: white;
            padding: 0.8rem;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: 600;
            margin-top: 1rem;
        }

        .attendance-form button:hover {
            background-color: #e5915c;
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
        <div class="attendance-form">
            <h2>Mark Attendance</h2>
            <form action="submit_attendance.php" method="POST">
                <?php
                // Database connection
                $conn = new mysqli('localhost', 'root', '', 'bcon');

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Select labours from credentials table
                $sql = "SELECT firstname, lastname, email FROM credentials WHERE role = 'labour'";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while($row = $result->fetch_assoc()) {
                        $fullName = htmlspecialchars($row['firstname'] . ' ' . $row['lastname']);
                        $email = htmlspecialchars($row['email']);
                        echo "<label><input type='checkbox' name='attendance[$fullName][$email]' value='1'> $fullName</label>";
                    }
                } else {
                    echo "<p>No labours found</p>";
                }
                $conn->close();
                ?>
                <button type="submit">Submit Attendance</button>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; 2023 BuildRight Construction. All rights reserved.</p>
    </footer>
</body>
</html>