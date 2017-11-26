<?php require_once('setup.php'); ?>
<?php require_once('session.php'); ?>

<?php
    if ($_GET['action'] == 'send') {
        if (!empty($_POST['body']) && !empty($_POST['receiver'])) {
            $recv_id = mysqli_query($conn, "SELECT user_id from users where username='" . $_POST['receiver'] . "'");
            if (mysqli_num_rows($recv_id) == 1) {
                $recv_id = mysqli_fetch_row($recv_id)[0];
                mysqli_query($conn, "INSERT INTO pms (message,receive_id,send_id) VALUES ('" . $_POST['body'] . "','" . $recv_id . "','" . $_SESSION['user_id'] . "')");
                include('redirect.php');
            }
            else {
                echo '<script>alert("Invalid receiver!");</script>';
                include('redirect.php');
            }
        }
        else {
            echo '<script>alert("Missing form data!");</script>';
            include('redirect.php');
        }
    }

 ?>

<style>

.pmContainer
{
		display:flex;
        flex-direction: column;
        align-items: flex-start;
		margin-left:3%;
		width:94%;
		background-color:rgb(20, 107, 183);
		color:rgb(250,250,250);
		border: 2px solid;
		border-color:white;
		box-shadow:1px 1px 1px 1px rgba(0,0,0,0.2);
		border-radius:5px;
		margin-top:14px;
		margin-bottom:14px;
		padding:10px;
}

.pmBody
{
	font-size:17px;
}

</style>

<?php
    $pms = mysqli_query($conn, "SELECT * from pms where receive_id='" . $_SESSION['user_id'] . "'");
    for ($i = 0; $i < mysqli_num_rows($pms); $i++)
	{
        $pm = mysqli_fetch_row($pms);
        $pm_body = $pm[1];
        $pm_time = $pm[4];
        $pm_sender = mysqli_fetch_row(mysqli_query($conn, "SELECT * from users where user_id='" . $pm[3] . "'"))[0];

		echo '<div class="pmContainer">';
        echo '<div class="pmBody">' . $pm_body . '</div>';
		echo '<div style="font-size:12px;align-self:flex-end;">Sent by ' . $pm_sender  . ' at ' . $pm_time . '</div>';
		echo '</div>';
    }
?>

<button style="padding:12px;font-size:16px;cursor:pointer;" class="btn btn-secondary reversedColor my-2 my-sm-0" data-toggle="modal" data-target="#sendPmModal">Send private message</button>
