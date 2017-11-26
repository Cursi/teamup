<?php require_once('setup.php'); ?>
<?php require_once('session.php'); ?>

<br><br>
<div style="font-size:2.4em;">News feed</div>
<br>

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

<body>
 <div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th>Title</th>
        <th>Initiated by</th>
        <th>Topic</th>
        <th>Message</th>
      </tr>
    </thead>
    <tbody>
	<?php
		$messages = mysqli_query($conn, "SELECT * from forum order by date desc");

		for ($i = 0; $i < mysqli_num_rows($messages); $i++) {
			$mess = mysqli_fetch_row($messages);
			echo "<tr data-toggle='modal' data-target='#CommentsModal' onclick='messageClick(" . $mess[5] . ");'>";
			echo "<td>" . $mess[0] . "</td>";
			echo "<td>" . mysqli_fetch_row(mysqli_query($conn, "SELECT name from users where user_id='" . $mess[3] . "'"))[0] . "</td>";
			echo "<td>" . $mess[2] . "</td>";
			echo "<td>" . $mess[1] . "</td>";
			echo "</tr>";
		}

	 ?>

    </tbody>
  </table>
  </div>
</div>

</body>

</html>
