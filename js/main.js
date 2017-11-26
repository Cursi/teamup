function openProject(id, toggleDrawer = true) {
    $("#mainContainer").load("../project.php?action=get&proj_id=" + id);
    $("#editProjectOldName").val(id);
    $("#addTaskProjId").val(id);
    $("#addUserProjId").val(id);

    if (toggleDrawer) $('.mdl-layout')[0].MaterialLayout.toggleDrawer();
}

function openMessageBoard(topic = null, toggleDrawer = true) {
    if (topic) $("#mainContainer").load("../messageBoard.php?topic=" + encodeURIComponent(topic));
    else $("#mainContainer").load("../messageBoard.php");
    if (toggleDrawer) $('.mdl-layout')[0].MaterialLayout.toggleDrawer();
};

function openNewsfeed() {
    $("#mainContainer").load("../newsfeed.php");
}

function openSettings() {
    $("#mainContainer").load("../settings.php");
}

function toggleTaskStatus(proj_id, task_id) {
    $.get("../tasksTable.php?action=toggle&task_id=" + task_id, function() {
        openProject(proj_id, false)
    });
}

function deleteTask(proj_id, task_id) {
    $.get("../tasksTable.php?action=delete&task_id=" + task_id, function() {
        openProject(proj_id, false)
    });
}

function deleteMember(proj_id, membership_id) {
    $.get("../membersTable.php?action=delete&membership_id=" + membership_id, function() {
        openProject(proj_id, false)
    });
}

function deleteMessage(topic, forum_id) {
    $.get("../messageBoard.php?action=delete&forum_id=" + forum_id, function() {
        openMessageBoard(topic, false);
    });
}

function messageClick(forum_id) {
    $("#commentsModalContainer").load("../comments.php?forum_id=" + forum_id);
    $("#commentFormForumId").val(forum_id);
}

function openPMs() {
    $("#mainContainer").load("../privateMessage.php");
}

$(document).ready(function()
{
	$(".mdl-layout-title").click(function()
	{
		location.reload();
	});

    $("#registerForm").submit(function(event)
	{
        var pass = $("#registerPasswordInput").val();
        var confpass = $("#registerPasswordConfirmInput").val();
        if (pass != confpass)
		{
            alert("Passwords do not match");
            event.preventDefault();
        }
    });

    $("#editSettingsForm").submit(function(event)
	{
        var pass = $("#editSettingsPassword").val();
        var confpass = $("#editSettingsConfirmPassword").val();
        if (pass != confpass)
		{
            alert("Passwords do not match");
            event.preventDefault();
        }
    });
    var d = new Date();
    var curr_date = d.getDate();
    var curr_month = d.getMonth() + 1; //Months are zero based
    var curr_year = d.getFullYear();
    $('#addTaskDeadline').val(curr_year + "-" + curr_month + "-"+  curr_date);
});
