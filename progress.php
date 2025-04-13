<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Labour View</title>
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
            max-width: 600px; /* Decreased max-width for form */
            margin: 0 auto;
            padding: 1rem; /* Decreased padding */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .form-group {
            margin-bottom: 1.5rem; /* Decreased margin for closer spacing */
            width: 100%;
            max-width: 100%; /* Adjusted max-width for form groups */
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem; /* Decreased margin */
            color: #f4a460;
            font-size: 1.2rem; /* Decreased font size */
        }

        .form-group input[type="file"] {
            width: 100%;
            padding: 1rem; /* Decreased padding */
            border: 2px solid #f4a460;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.1);
            font-size: 1rem; /* Decreased font size */
        }

        .form-group textarea {
            width: 100%;
            padding: 1rem; /* Decreased padding */
            border: 2px solid #f4a460;
            border-radius: 5px;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            font-size: 1rem; /* Decreased font size */
        }

        .form-group select {
            width: 100%;
            padding: 1rem; /* Decreased padding */
            border: 2px solid #f4a460;
            border-radius: 5px;
            background-color: rgba(0, 0, 0, 0.7); /* Changed background color */
            color: white;
            font-size: 1rem; /* Decreased font size */
        }

        .form-group select option {
            background-color: rgba(255, 255, 255, 0.1);
            color: white; /* Set option text color to white */
        }

        .submit-btn {
            padding: 16px 32px; /* Decreased padding */
            background-color: #f4a460;
            color: black;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 1.2rem; /* Decreased font size */
        }

        .submit-btn:hover {
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
            <div class="logo">Labour Dashboard</div>
            <ul class="nav-links">
                <li><a href="labour_home.html">Home</a></li>
                <li><a href="index.html" style="color: red; font-weight: bold;">Sign Out</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <form action="upload_progress.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="projectSelect">Select a project:</label>
                <select id="projectSelect" name="project" required>
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

                    // Fetch project names
                    $sql = "SELECT name FROM projects"; // Assuming the table is named 'projects'
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($row["name"]) . '">' . htmlspecialchars($row["name"]) . '</option>';
                        }
                    } else {
                        echo '<option value="">No projects available</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="ownerSelect">Select an owner:</label>
                <select id="ownerSelect" name="owner" required>
                    <?php
                    // Fetch owner names from credentials who have role 'owner'
                    $sql = "SELECT firstname, lastname, email FROM credentials WHERE role = 'owner'"; // Assuming the table is named 'credentials'
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($row["email"]) . '">' . htmlspecialchars($row["firstname"] . ' ' . $row["lastname"]) . '</option>';
                        }
                    } else {
                        echo '<option value="">No owners available</option>';
                    }

                    $conn->close();
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="fileInput">Select a photo:</label>
                <input type="file" id="fileInput" name="file" accept=".jpg, .jpeg, .png" required />
            </div>
            <div class="form-group">
                <label for="descriptionInput">Enter a description:</label>
                <textarea id="descriptionInput" name="description" rows="12" required></textarea> <!-- Decreased rows -->
            </div>
            <button type="submit" class="submit-btn">Submit</button>
        </form>
    </div>

    <footer>
        <p>&copy; 2023 BuildRight Construction. All rights reserved.</p>
    </footer>
</body>
</html>