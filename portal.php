<?php require_once('setup.php'); ?>
<?php require_once('session.php'); ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://code.getmdl.io/1.3.0/material.indigo-pink.min.css">
	<script defer src="https://code.getmdl.io/1.3.0/material.min.js"></script>

	<script src="js/main.js"></script>
</head>

<body>

<!-- Always shows a header, even in smaller screens. -->
<div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
  <header class="mdl-layout__header" style="background-color:white;color:rgb(20, 107, 183);">
    <div class="mdl-layout__header-row">
      <!-- Title -->
      <span class="mdl-layout-title">
	  <img width=40px height=40px src="img/logo.svg"></img>
	  <div style="display:inline-block;">TeamUP</div>
	  </span>
      <!-- Add spacer, to align navigation to the right -->
      <div class="mdl-layout-spacer"></div>
      <!-- Navigation. We hide it in small screens. -->
      <nav class="mdl-navigation"> <!-- onclick="alert('Next HackaTown!');" -->
      <a class="mdl-navigation__link uppBtn" id="messages" onclick="openPMs();"><img width=26px height=26px src="img/message.svg"></img></a>
      <a class="mdl-navigation__link uppBtn" id="settings" onclick="openSettings();"><img width=26px height=26px src="img/settings.svg"></img></a>
	  <a class="mdl-navigation__link uppBtn" id="logout" href="logout.php"><img width=26px height=26px src="img/logout.svg"></img></a>
      </nav>
    </div>
  </header>
  <div class="mdl-layout__drawer">
    <span class="mdl-layout-title">
	  <img width=40px height=40px src="img/logo.svg"></img>
	  <div style="display:inline-block;color:rgb(20, 107, 183)!important;">TeamUP</div>
	</span>
    <nav class="mdl-navigation" style="padding-top:0px;">
	  <div style="font-size:16px;margin-left:25px;">Logged in as</div>
	  <div style="font-size:16px;color:rgb(20, 107, 183);text-align:center;cursor:pointer;" onclick="openSettings();$('.mdl-layout')[0].MaterialLayout.toggleDrawer();"><strong>
		  <?php
          	echo $_SESSION['name'];
		?></strong></div>

		<hr>
	 <div style="text-align:center;font-weight:bold;font-size:16px;">Projects</div>
	 <?php
	 	$result = mysqli_query($conn, "SELECT distinct proj_id from proj_members where user_id='" . $_SESSION["user_id"] . "'");
		 for ($i = 0; $i < mysqli_num_rows($result); $i++) {
			 $projid = mysqli_fetch_row($result)[0];
			 $proj_name = mysqli_query($conn, "SELECT name from projects where proj_id='" . $projid . "'");
			  printf("<a class='mdl-navigation__link' onclick='openProject(" . $projid . ");'>" . mysqli_fetch_row($proj_name)[0] . "</a>");
		 }
	 ?>
	 <a class="mdl-navigation__link" data-toggle="modal" data-target="#addProjectModal"><strong>Create new project</strong></a>
<hr>
	<a class="mdl-navigation__link" style="font-weight:bold;font-size:16px;" onclick="openMessageBoard();">Message Board</a>
    <!-- <a class="mdl-navigation__link" data-toggle="modal" data-target="#addMessageModal"><strong>Create new message</strong></a> -->
	<hr>
	</nav>
  </div>
  <main class="mdl-layout__content">
    <div id="mainContainer" class="page-content"><!-- Your content goes here --></div>
  </main>
</div>
<script>openNewsfeed();</script>

<div class="modal fade" id="addProjectModal" tabindex="-1" role="dialog" aria-labelledby="addProjectLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addProjectLabel">Add Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  <form method="post" action="project.php?action=add">
		    <div class="form-group">
		      <label for="addProjectName">Enter Name</label>
		      <input type="text" class="form-control" id="addProjectName" placeholder="Name" name="name" required>
		    </div>
		    <div class="form-group">
		      <label for="addProjectManager">Manager</label>
		      <input type="text" class="form-control" id="addProjectManager" placeholder="Manager" name="manager" required>
		    </div>
			<div class="form-group">
		      <label for="addProjectDescription">Description</label>
		      <input type="text" class="form-control" id="addProjectDescription" placeholder="Description" name="desc" required>
		    </div>
		    <button type="submit" class="btn btn-primary">Create</button>
		  </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editProjectModal" tabindex="-1" role="dialog" aria-labelledby="editProjectLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProjectLabel">Edit Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  <form method="post" action="project.php?action=edit">
		    <div class="form-group">
		      <label for="editProjectName">Enter new name</label>
		      <input type="text" class="form-control" id="editProjectName" placeholder="Name" name="name">
		    </div>
		    <div class="form-group">
		      <label for="editProjectManager">Manager</label>
		      <input type="text" class="form-control" id="editProjectManager" placeholder="Manager" name="manager">
		    </div>
			<div class="form-group">
		      <label for="editProjectDescription">Description</label>
		      <textarea class="form-control" id="editProjectDescription" placeholder="Description" name="desc"></textarea>
		    </div>
			<input type="text" id="editProjectOldName" name="proj_id" hidden>
		    <button type="submit" class="btn btn-primary">Save</button>
		  </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editSettingsModal" tabindex="-1" role="dialog" aria-labelledby="editSettingsLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editSettingsLabel">Edit personal data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
		  <form id="editSettingsForm" method="post" action="settings.php?action=edit">
		    <div class="form-group">
		      <label for="editSettingsName">Enter new name</label>
		      <input type="text" class="form-control" id="editSettingsName" placeholder="Name" name="name">
		    </div>
			<div class="form-group">
		      <label for="editSettingsPassword">Password</label>
		      <input type="password" class="form-control" id="editSettingsPassword" placeholder="Password" name="pass">
		    </div>
			<div class="form-group">
		      <label for="editSettingsConfirmPassword">Confirm password</label>
		      <input type="password" class="form-control" id="editSettingsConfirmPassword" placeholder="Confirm password" name="confirm_pass">
		    </div>
		    <button type="submit" class="btn btn-primary">Save</button>
		  </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="addTaskModal" tabindex="-1" role="dialog" aria-labelledby="addTaskLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addTaskLabel">Add new task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<div class="modal-body">
		  <form id="addTaskForm" method="post" action="tasksTable.php?action=add">
		    <div class="form-group">
		      <label for="addTaskName">Enter name</label>
		      <input type="text" class="form-control" id="addTaskName" placeholder="Name" name="name" required>
		    </div>
			<div class="form-group">
		      <label for="addTaskUser">Enter user</label>
		      <input type="text" class="form-control" id="addTaskUser" placeholder="User Name" name="username" required>
		    </div>
			<div class="form-group">
		      <label for="addTaskDeadline">Deadline</label>
		      <input type="date" class="form-control" id="addTaskDeadline" placeholder="Deadline" name="deadline" required>
		    </div>
			<div class="form-group">
		      <label for="addTaskUrgency">Enter urgency</label>
		      <input type="text" class="form-control" id="addTaskUrgency" placeholder="Urgency" name="urgency" required>
		    </div>
			<input id="addTaskProjId" type=text name="proj_id" hidden>
		    <button type="submit" class="btn btn-primary">Save</button>
		  </form>
      </div>
     </div>
    </div>
   </div>

    <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserLabel">Add new user</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<div class="modal-body">
		  <form id="addUserForm" method="post" action="membersTable.php?action=add">
		    <div class="form-group">
		      <label for="addUserName">Enter name</label>
		      <input type="text" class="form-control" id="addUserName" placeholder="Name" name="name" required>
		    </div>
			<div class="form-group">
		      <label for="addUserRole">Enter role</label>
			  <select class="form-control" id="addUserRole" placeholder="Role" name="role" required>
				<option>Project Manager</option>
				<option>Member</option>
				<option>Volunteer</option>
			  </select>
		    </div>
			<input id="addUserProjId" type="text" name="proj_id" hidden>
		    <button type="submit" class="btn btn-primary">Save</button>
		  </form>
      </div>
     </div>
    </div>
   </div>

   <div class="modal fade" id="addMessageModal" tabindex="-1" role="dialog" aria-labelledby="addMessageLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUserLabel">Add new message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<div class="modal-body">
		  <form id="addMessageForm" method="post" action="messageBoard.php?action=add">
		    <div class="form-group">
		      <label for="addMessageTitle">Enter title</label>
		      <input type="text" class="form-control" id="addMessageTitle" placeholder="Title" name="title" required>
		    </div>
			<div class="form-group">
		      <label for="addMessageBody">Message</label>
		      <textarea class="form-control" id="addMessageBody" placeholder="Message" name="body" required></textarea>
		    </div>
			<div class="form-group">
		      <label for="addMessageTopic">Enter topic</label>
		      <input type="text" class="form-control" id="addMessageTopic" placeholder="Topic" name="topic" required>
		    </div>
		    <button type="submit" class="btn btn-primary">Save</button>
		  </form>
      </div>
     </div>
    </div>
   </div>

   <div class="modal fade" id="CommentsModal" tabindex="-1" role="dialog" aria-labelledby="CommentsLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="CommentsLabel">Comments</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<div class="modal-body">
	  	<div id="commentsModalContainer"></div>
		  <form id="addCommentForm" method="post" action="comments.php?action=add">
			<div class="form-group">
		      <textarea class="form-control" id="addComment" placeholder="Comment" name="comment" required></textarea>
			  <input id="commentFormForumId" type="text" name="forum_id" hidden>
		    </div>
		    <button type="submit" class="btn btn-primary">Send</button>
		  </form>
      </div>
     </div>
    </div>
   </div>

<div class="modal fade" id="sendPmModal" tabindex="-1" role="dialog" aria-labelledby="sendPmLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="sendPmLabel">Add new message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
		<div class="modal-body">
		  <form id="sendPmForm" method="post" action="privateMessage.php?action=send">
			  <div class="form-group">
				<label for="sendPmUser">Receiver Name</label>
				<input class="form-control" id="sendPmUser" placeholder="Receiver" name="receiver" required>
			  </div>
			<div class="form-group">
		      <label for="sendPmMessage">Message</label>
		      <textarea class="form-control" id="sendPmMessage" placeholder="Message" name="body" required></textarea>
		    </div>
		    <button type="submit" class="btn btn-primary">Send</button>
		  </form>
      </div>
     </div>
    </div>
   </div>

</body>

</html>
