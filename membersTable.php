<?php require_once('setup.php'); ?>
<?php require_once('session.php'); ?>

<?php

$proj_id = $_GET['proj_id'];
if (isset($proj_id)) {
	$proj_owner = mysqli_fetch_row(mysqli_query($conn, "SELECT owner_id from projects where proj_id='" . $proj_id . "'"))[0];
	$members = mysqli_query($conn, "SELECT * from proj_members where proj_id='" . $proj_id . "'");
	$is_owner = FALSE;
	if ($_SESSION['user_id'] == $proj_owner) $is_owner = TRUE;
}
else if ($_GET['action'] == 'add' && !empty($_POST['proj_id'])) {
	if (!empty($_POST['name']) && !empty($_POST['role'])) {
		$member_user_id = mysqli_query($conn, "SELECT user_id from users where username='" . $_POST['name'] . "'");
		if (mysqli_num_rows($member_user_id) != 1) {
			echo '<script>alert("Invalid username!");</script>';
			include('redirect.php');
		}
		$member_user_id = mysqli_fetch_row($member_user_id)[0];

		mysqli_query($conn, "SELECT user_id from users where username='" . $_POST['name'] . "'");

		mysqli_query($conn, "DELETE FROM proj_members where user_id='" . $member_user_id . "' AND proj_id='" . $_POST['proj_id'] . "' AND role!=''");
		mysqli_query($conn, "INSERT INTO proj_members (user_id,proj_id,role) VALUES ('" . $member_user_id . "','" . $_POST['proj_id'] . "','" . $_POST['role'] . "')");

		if ($_POST['role'] == "Project Manager") {
			mysqli_query($conn, "DELETE FROM proj_members where user_id!='" . $member_user_id . "' AND proj_id='" . $_POST['proj_id'] . "' AND role='Project Manager'");
			mysqli_query($conn, "UPDATE projects set man_id='" . $member_user_id . "' where proj_id='" . $_POST['proj_id'] . "'");
		}

        include('redirect.php');
	}
	else {
		echo '<script>alert("Missing form data!");</script>';
        include('redirect.php');
	}
}
else if ($_GET['action'] == 'delete' && !empty($_GET['membership_id'])) {
	$del_membership = mysqli_fetch_row(mysqli_query($conn, "SELECT * from proj_members where membership_id='" . $_GET['membership_id'] . "'"));
	if ($del_membership[0] == "Project Manager") {
		mysqli_query($conn, "UPDATE projects set man_id='0' where proj_id='" . $del_membership[2] . "'");
	}
	mysqli_query($conn, "DELETE FROM proj_members where membership_id='" . $_GET['membership_id'] . "'");
}
else {
	die();
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

#tbl
{
	font-size:1.2em;
}
</style>

 <div class="table-responsive">
  <table class="table" id="tbl">
    <thead>
      <tr>
        <th>Name</th>
        <th>Role</th>
		<?php
			if ($is_owner) {
				echo "<th data-toggle='modal' data-target='#addUserModal' class='add-btn'>+</th>";
			}
		?>
      </tr>
    </thead>
    <tbody>
<?php
	for ($i = 0; $i < mysqli_num_rows($members); $i++) {
		$membership = mysqli_fetch_row($members);
		if ($membership[0]) {
			$mem = mysqli_fetch_row(mysqli_query($conn, "SELECT * from users where user_id='" . $membership[1] . "'"));
			echo "<tr>";
			echo "<td>" . $mem[2] . "</td>";
			echo "<td>" . $membership[0] . "</td>";
			if ($is_owner) {
				echo "<td onclick='deleteMember(" . $proj_id . ", " . $membership[3] . ");'><img style='float:right;' width=26px height=26px src='img/delete_user.svg'></img></td>";
			}
			echo "</tr>";
		}
	}

?>
    </tbody>
  </table>
  </div>
</div>

</html>
