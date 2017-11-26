<?php require_once('setup.php'); ?>
<?php
    $name = $pass = $username = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $username = $_POST["username"];
        $name = $_POST["name"];
        $pass = $_POST["password"];

        if (isset($username) && isset($name) && isset($pass)) {
            $result = mysqli_query($conn, "SELECT * FROM users WHERE username='" . $username . "'");
            if (mysqli_num_rows($result) <= 0) {
                if (mysqli_query($conn, "INSERT INTO users (username,pass,name) VALUES ('" . $username . "','" . $pass . "','" . $name . "')")) {
                    $result = mysqli_query($conn, "SELECT * FROM users WHERE username='" . $username . "'");
                    $user = mysqli_fetch_row($result);
                    session_start();
                    $_SESSION['username'] = $user[0];
                    $_SESSION['name'] = $user[2];
                    $_SESSION['user_id'] = $user[3];
                    include('redirect.php');
                }
                else {
                    echo '<script>alert("Could not insert user!");</script>';
                    include('redirect.php');
                }
            }
            else {
                echo '<script>alert("Username already exists!");</script>';
                include('redirect.php');
            }
        }
        else {
            echo '<script>alert("Invalid form data!");</script>';
            include('redirect.php');
        }

    }
    else {
        echo '<script>alert("Invalid method!");</script>';
        include('redirect.php');
    }

?>
