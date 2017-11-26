<?php require_once('setup.php'); ?>
<?php require_once('session.php'); ?>
<?php

$proj_id = $_GET['proj_id'];
if (isset($proj_id)) {
		$proj_owner_id = mysqli_fetch_row(mysqli_query($conn, "SELECT owner_id from projects where proj_id='" . $proj_id . "'"))[0];
		$proj_man_id = mysqli_fetch_row(mysqli_query($conn, "SELECT man_id from projects where proj_id='" . $proj_id . "'"))[0];
		$is_owner = FALSE;
		$is_man = FALSE;
		if ($_SESSION['user_id'] == $proj_owner_id) $is_owner = TRUE;
		if ($_SESSION['user_id'] == $proj_man_id) $is_man = TRUE;
		if ($is_owner || $is_man) {
			$tasks = mysqli_query($conn, "SELECT * from tasks where proj_id='" . $proj_id . "'");
		}
		else {
			$tasks = mysqli_query($conn, "SELECT * from tasks where proj_id='" . $proj_id . "' AND user_id='" . $_SESSION['user_id'] . "'");
		}
}

else if ($_GET['action'] == 'add' && !empty($_POST['proj_id'])) {
	if (!empty($_POST['name']) && !empty($_POST['username']) && !empty($_POST['deadline']) && !empty($_POST['urgency'])) {
		$task_user_id = mysqli_query($conn, "SELECT user_id from users where username='" . $_POST['username'] . "'");
		if (mysqli_num_rows($task_user_id) != 1) {
			echo '<script>alert("Invalid username!");</script>';
	        include('redirect.php');
		}
		$task_user_id = mysqli_fetch_row($task_user_id)[0];
		mysqli_query($conn, "INSERT INTO tasks (name,deadline,user_id,urgency,status,proj_id) VALUES ('" . $_POST['name'] . "','" . $_POST['deadline'] . "','" . $task_user_id . "','" . $_POST['urgency'] . "','In Progress','" . $_POST['proj_id'] . "')");

		include('redirect.php');
	}
	else {
		echo '<script>alert("Missing form data!");</script>';
        include('redirect.php');
	}
}
else if ($_GET['action'] == 'toggle' && !empty($_GET['task_id'])) {
		$task_status = mysqli_fetch_row(mysqli_query($conn, "SELECT status from tasks where task_id='" . $_GET['task_id'] . "'"))[0];
		if ($task_status == "In Progress") {
			mysqli_query($conn, "UPDATE tasks set status='Done' where task_id='" . $_GET['task_id'] . "'");
		}
		else {
			mysqli_query($conn, "UPDATE tasks set status='In Progress' where task_id='" . $_GET['task_id'] . "'");
		}

		die();
}
else if ($_GET['action'] == 'delete' && !empty($_GET['task_id'])) {
		mysqli_query($conn, "DELETE FROM tasks where task_id='" . $_GET['task_id'] . "'");
		die();
}
else {
	    include('redirect.php');
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

 <div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th>Task</th>
		<?php
			if ($is_man || $is_owner) {
				echo "<th>User</th>";
			}
		?>
        <th>Creation date</th>
		<th>Deadline</th>
		<th>Urgency</th>
		<th>Status</th>
		<?php
			if ($is_man || $is_owner) {
				echo '<th class="add-btn" data-toggle="modal" data-target="#addTaskModal">+</th>';
			}
		 ?>
      </tr>
    </thead>
    <tbody>
<?php
	for ($i = 0; $i < mysqli_num_rows($tasks); $i++) {
		echo "<tr>";
		$task = mysqli_fetch_row($tasks);
		echo "<td>" . $task[0] . "</td>";
		if ($is_man || $is_owner) {
			echo "<td>" . mysqli_fetch_row(mysqli_query($conn, "SELECT name from users where user_id='" . $task[4] . "'"))[0] . "</td>";
		}
		echo "<td>" . $task[2] . "</td>";
		echo "<td>" . $task[3] . "</td>";
		echo "<td>" . $task[5] . "</td>";
		echo "<td onclick='toggleTaskStatus(" . $proj_id . ", " . $task[1] . ");'>" . $task[6] . "</td>";
		if ($is_man || $is_owner) {
			echo "<td onclick='deleteTask(" . $proj_id . ", " . $task[1] . ");'><img style='float:right;' width=26px height=26px src='img/delete.svg'></img></td>";
		}
		echo "</tr>";
	}

 ?>
    </tbody>
  </table>
  </div>
</div>

</html>
