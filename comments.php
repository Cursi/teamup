<?php require_once('setup.php'); ?>
<?php require_once('session.php'); ?>

<?php
    if ($_GET['action'] == 'add' && !empty($_POST['forum_id'])) {
        if (!empty($_POST['comment'])) {
            mysqli_query($conn, "INSERT INTO comments (user_id,forum_id,body) VALUES('" . $_SESSION['user_id'] . "','" . $_POST['forum_id'] . "','" . $_POST['comment'] . "')");
            include('redirect.php');
        }
        else {
            echo '<script>alert("Missing form data");</script>';
            include('redirect.php');
        }
    }
    else if (!empty($_GET['forum_id'])) {
        $comments = mysqli_query($conn, "SELECT * from comments where forum_id='" . $_GET['forum_id'] . "'");
        for ($i = 0; $i < mysqli_num_rows($comments); $i++) {
            $comm = mysqli_fetch_row($comments);
            $comm_user = mysqli_fetch_row(mysqli_query($conn, "SELECT name from users where user_id='" . $comm[1] . "'"))[0];
            echo '<div class="form-group">';
		    echo '<label for="Comment">' . $comm[4] . '</label>';
			echo '<label for="User" style="float:right !important;">By ' . $comm_user . '</label>';
		    echo '</div>';
        }
    }
    else {
        die();
    }
 ?>
