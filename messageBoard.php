<?php require_once('setup.php'); ?>
<?php require_once('session.php'); ?>

<br><br>
<div style="font-size:2.4em">Message Board</div>

<?php

	if ($_GET['action'] == 'add') {
		if (!empty($_POST['title']) && !empty($_POST['body']) && !empty($_POST['topic'])) {
			mysqli_query($conn, "INSERT INTO forum (title,body,topic,author_id) VALUES ('" . $_POST['title'] . "','" . $_POST['body'] . "','" . $_POST['topic'] . "','" . $_SESSION['user_id'] . "')");
			header("Location: https://teamup.westeurope.cloudapp.azure.com");
			die();
		}
		else {
			echo '<script>alert("Missing form data!");</script>';
			include('redirect.php');
		}
	}
	else if ($_GET['action'] == 'delete' && !empty($_GET["forum_id"])) {
		mysqli_query($conn, "DELETE from forum where forum_id='" . $_GET["forum_id"] . "'");
		die();
	}
	else {
		$topics = mysqli_query($conn, "SELECT distinct topic from forum");
		echo "<select style='padding-left:5px;padding-bottom:0;font-size:18px;' class='form-control' id='topicSelect'>";
		for ($i = 0; $i < mysqli_num_rows($topics); $i++) {
			echo "<option ";
			$topic = mysqli_fetch_row($topics)[0];
			if ($i == 0) $default_topic = $topic;
			if ($topic == $_GET['topic']) {
				echo "selected='selected'";
			}
			echo ">" . $topic . "</option>";
		}
	    echo "</select>";
		echo '<script>$("#topicSelect").one("change", function(event) {
		        $("#mainContainer").load("../messageBoard.php?topic=" + encodeURIComponent($(this).val()));
		    });</script>';
	}
 ?>

<style>
.table-responsive
{
	width:94%;
	margin-left:3%;
}
thead
{
	background-color:rgb(20, 107, 183);
	color:rgb(250,250,250);
}
tr:hover
{
	cursor:pointer;
	background-color:rgba(0,0,0,0.05);
}
table
{
	border-collapse: collapse;
   border:2px solid rgb(20, 107, 183);
}
td
{
	vertical-align:middle!important;
}
</style>
<br><br>
 <div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th>Title</th>
        <th>Initiated by</th>
        <th>Topic</th>
        <th>Message</th>
		<th class="add-btn" data-toggle="modal" data-target="#addMessageModal">+</th>
      </tr>
    </thead>
    <tbody>
	<?php
		if (!isset($_GET['topic'])) {
			$topic = $default_topic;
		}
		else {
			if (mysqli_num_rows(mysqli_query($conn, "SELECT * from forum where topic='" . $_GET['topic'] . "'")) != 0) {
				$topic = $_GET['topic'];
			}
			else {
				$topic = $default_topic;
			}
		}
		$messages = mysqli_query($conn, "SELECT * from forum where topic='" . $topic . "'");

		for ($i = 0; $i < mysqli_num_rows($messages); $i++) {
			$mess = mysqli_fetch_row($messages);
			$is_owner = FALSE;
			if ($_SESSION['user_id'] == $mess[3]) $is_owner = TRUE;
			echo "<tr>";
			echo "<td data-toggle='modal' data-target='#CommentsModal' onclick='messageClick(" . $mess[5] . ");'>" . $mess[0] . "</td>";
			echo "<td data-toggle='modal' data-target='#CommentsModal' onclick='messageClick(" . $mess[5] . ");'>" . mysqli_fetch_row(mysqli_query($conn, "SELECT name from users where user_id='" . $mess[3] . "'"))[0] . "</td>";
			echo "<td data-toggle='modal' data-target='#CommentsModal' onclick='messageClick(" . $mess[5] . ");'>" . $mess[2] . "</td>";
			echo "<td data-toggle='modal' data-target='#CommentsModal' onclick='messageClick(" . $mess[5] . ");'>" . $mess[1] . "</td>";
			echo "<td>";
			if ($is_owner) {
				echo "<img onclick='deleteMessage(\"" . $mess[2] . "\", " . $mess[5] . ");' style='z-index:100!important;float:right;' width=26px height=26px src='img/delete.svg'></img>";
			}
			echo "</td>";
			echo "</tr>";
		}

	 ?>

    </tbody>
  </table>
  </div>
