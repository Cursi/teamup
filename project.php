<?php require_once('setup.php'); ?>
<?php require_once('session.php'); ?>
<?php
    if ($_GET['action'] == 'get' && isset($_GET['proj_id'])) {
        $proj = mysqli_fetch_row(mysqli_query($conn, "SELECT * from projects where proj_id='" . $_GET['proj_id'] . "'"));
        $proj_name = $proj[1];
        $proj_desc = $proj[2];
        $proj_owner = mysqli_fetch_row(mysqli_query($conn, "SELECT name from users where user_id='" . $proj[0] . "'"))[0];
        $proj_man = mysqli_fetch_row(mysqli_query($conn, "SELECT name from users where user_id='" . $proj[4] . "'"))[0];
    }
    else if ($_GET['action'] == 'add') {
        if (isset($_POST["name"]) && isset($_POST["desc"]) && isset($_POST["manager"])) {
            if (mysqli_num_rows(mysqli_query($conn, "SELECT * from users where username='" . $_POST["manager"] . "'")) == 0) {
                echo '<script>alert("Invalid manager!");</script>';
                include('redirect.php');
            }
            if (mysqli_num_rows(mysqli_query($conn, "SELECT * from projects where name='" . $_POST["name"] . "'")) != 0) {
                echo '<script>alert("Project already exists!");</script>';
                include('redirect.php');
            }
            else {
                $man_id = mysqli_fetch_row(mysqli_query($conn, "SELECT user_id from users where username='" . $_POST["manager"] . "'"))[0];
                mysqli_query($conn, "INSERT INTO projects (name,description,man_id,owner_id) VALUES ('" . $_POST["name"] . "','" . $_POST['desc'] . "','" . $man_id . "','" . $_SESSION['user_id'] . "')");
                $proj_id = mysqli_fetch_row(mysqli_query($conn, "SELECT proj_id from projects where name='" . $_POST["name"] . "'"))[0];
                mysqli_query($conn, "INSERT INTO proj_members (user_id,proj_id,role) VALUES ('" . $_SESSION['user_id'] . "','" . $proj_id . "','')");
                mysqli_query($conn, "INSERT INTO proj_members (user_id,proj_id,role) VALUES ('" . $man_id . "','" . $proj_id . "','Project Manager')");
                include('redirect.php');
            }
        }
        else {
            echo '<script>alert("Missing form data!");</script>';
            include('redirect.php');
        }
    }
    else if ($_GET['action'] == 'edit') {
        if (!isset($_POST['proj_id'])) {
            echo '<script>alert("Project id not specified!");</script>';
            include('redirect.php');
        }
        else {
            if (mysqli_num_rows(mysqli_query($conn, "SELECT * from projects where proj_id='" . $_POST["proj_id"] . "'")) == 0) {
                echo '<script>alert("Project does not exists!");</script>';
                include('redirect.php');
            }
            if (!empty($_POST["name"])) {
                mysqli_query($conn, "UPDATE projects SET name='" . $_POST['name'] . "' WHERE proj_id='" . $_POST["proj_id"] . "'");
            }
            if (!empty($_POST["desc"])) {
                mysqli_query($conn, "UPDATE projects SET description='" . $_POST['desc'] . "' WHERE proj_id='" . $_POST["proj_id"] . "'");
            }
            if (!empty($_POST["manager"])) {
                if (mysqli_num_rows(mysqli_query($conn, "SELECT * from users where username='" . $_POST["manager"] . "'")) == 0) {
                    echo '<script>alert("Invalid manager!");</script>';
                    include('redirect.php');
                }
                $man_id = mysqli_fetch_row(mysqli_query($conn, "SELECT user_id from users where username='" . $_POST["manager"] . "'"))[0];
                $old_man_id = mysqli_fetch_row(mysqli_query($conn, "SELECT man_id from projects where proj_id='" . $_POST["proj_id"] . "'"))[0];
                mysqli_query($conn, "DELETE FROM proj_members where proj_id='" . $_POST['proj_id'] . "' AND user_id='" . $old_man_id . "' AND role='Project Manager'");
                mysqli_query($conn, "DELETE FROM proj_members where proj_id='" . $_POST['proj_id'] . "' AND user_id='" . $man_id . "' AND role!=''");
                mysqli_query($conn, "INSERT INTO proj_members (user_id,proj_id,role) VALUES ('" . $man_id . "','" . $_POST["proj_id"] . "','Project Manager')");
                mysqli_query($conn, "UPDATE projects SET man_id='" . $man_id . "' WHERE proj_id='" . $_POST["proj_id"] . "'");
            }
            include('redirect.php');
        }
    }
    else {
        include('redirect.php');
    }
?>

<style>

#projName
{
	font-size:2.4em;
	color:rgb(20, 107, 183);
}

#projOwn, #projMan, #projDesc
{
	color:rgb(20, 107, 183);
}

</style>

<div style="font-size:1.2em;letter-spacing:1px;">
	<br><br>
    <div id="projName"><?php echo $proj_name; ?></div>
	<br><br>
	<div> Description </div>
	<br>
    <div id="projDesc"><?php echo $proj_desc; ?></div>

	<br>
    <div>Tasks</div>
	<br>
    <?php include 'tasksTable.php' ?>
    <br>
	<div style="font-size:1.2em;"> <strong>Team</strong> </div>
	<br>
	<div> Project leader </div>
    <div id="projOwn"><?php echo $proj_owner; ?></div>
	<br>
	<div> Project manager </div>
    <div id="projMan"><?php echo $proj_man; ?></div>
	<br>
	<div> Members </div>
	<br>
    <?php include 'membersTable.php' ?>
    <?php
        if ($proj_owner == $_SESSION['user_id']) {
            echo '<button style="padding:8px;font-size:16px;cursor:pointer;" class="btn btn-secondary reversedColor my-2 my-sm-0"  data-toggle="modal" data-target="#editProjectModal">Edit project</button>';
        }
     ?>
    <br><br><br>
</div>
