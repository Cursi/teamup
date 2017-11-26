<?php require_once('setup.php'); ?>
<?php
    $pass = $username = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $username = $_POST["username"];
        $pass = $_POST["password"];

        if (isset($username) && isset($pass)) {
            $result = mysqli_query($conn, "SELECT * FROM users WHERE username='" . $username . "' AND pass='" . $pass . "'");
            if (mysqli_num_rows($result) == 1) {
                $user = mysqli_fetch_row($result);
                session_start();
                $_SESSION['username'] = $user[0];
                $_SESSION['name'] = $user[2];
                $_SESSION['user_id'] = $user[3];
                include('redirect.php');
            }
            else {
                echo '<script>alert("Invalid user or password");</script>';
                include('redirect.php');
            }
        }
        else {
            echo '<script>alert("Empty username or password!");</script>';
            include('redirect.php');
        }
    }
    else {
        echo '<script>alert("Invalid method!");</script>';
        include('redirect.php');
    }

?>
