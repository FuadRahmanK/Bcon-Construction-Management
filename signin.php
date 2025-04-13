<?php
    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = $_POST['email'];
        $password = $_POST['password'];
    } else {
        $email = "";
        $password = "";
    }

    $conn = new mysqli("localhost", "root", "", "bcon");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Login validation
    if(isset($_POST['login'])) {
        // Sanitize inputs
        $email = mysqli_real_escape_string($conn, $email);
        $password = mysqli_real_escape_string($conn, $password);

        // Query database
        $sql = "SELECT * FROM credentials WHERE email = '$email' AND password = '$password'";
        $result = mysqli_query($conn, $sql);

        if(mysqli_num_rows($result) == 1) {
            // Successful login
            $row = mysqli_fetch_assoc($result);
            session_start();
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $row['role'];  // Store the role in session
            
            // Role-based redirects
            if($row['role'] == 'owner') {
                header("Location: home.html");
                exit();
            } elseif($row['role'] == 'contractor') {
                header("Location: contr_home.html");
                exit();
            } elseif($row['role'] == 'labour') {
                header("Location: labour_home.html");
                exit();
            } else {
                // Unknown role
                header("Location: index.html?error=invalid");
                exit();
            }
        } else {
            // Failed login - incorrect email or password
            header("Location: index.html?error=invalid");
            exit();
        }
    }

    // Close connection 
    $conn->close();
?>