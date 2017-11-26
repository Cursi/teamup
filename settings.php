<?php require_once('setup.php'); ?>
<?php require_once('session.php'); ?>
<?php

    if ($_GET['action'] == 'edit') {
        if (!empty($_POST['name'])) {
            mysqli_query($conn, "UPDATE users set name='" . $_POST['name'] . "' WHERE user_id='" . $_SESSION['user_id'] . "'");
            $_SESSION['name'] = $_POST['name'];
        }
        if (!empty($_POST['pass'])) {
            mysqli_query($conn, "UPDATE users set pass='" . $_POST['pass'] . "' WHERE user_id='" . $_SESSION['user_id'] . "'");
        }
        include('redirect.php');
    }

    $user = mysqli_fetch_row(mysqli_query($conn, "SELECT * from users where user_id='" . $_SESSION['user_id'] . "'"));
    $user_username = $user[0];
    $user_name = $user[2];
	$user_pass = str_repeat("*", strlen($user[1]));

?>

<style>

#name, #username, #password
{
	color:rgb(20, 107, 183);
}

</style>

<html>

	<div style="font-size:2em;letter-spacing:1px;">
		<br><br>
		<div> Name </div>
		<br>
		<div id="name"><?php echo $user_name; ?></div>
		<br><br>
		<div>Username</div>
		<br>
		<div id="username"><?php echo $user_username; ?></div>
		<br><br>
		<div>Password</div>
		<br>
		<div id="password"><?php echo $user_pass; ?></div>
		<button style="padding:8px;font-size:16px;cursor:pointer;" class="btn btn-secondary reversedColor my-2 my-sm-0" data-toggle="modal" data-target="#editSettingsModal">Edit personal data</button>
	</div>



</html>
