<?php
    session_start();
    if (isset($_SESSION['user_id'])) {
        header("Location: https://teamup.westeurope.cloudapp.azure.com/portal.php");
        die();
    }
?>

<!DOCTYPE html>
<html>

<script>

function refreshPage()
{
	location.reload();
}

</script>

<head>
	 <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script src="js/main.js"></script>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary justify-content-between">
        <a class="navbar-brand" style="cursor:pointer;" onclick="refreshPage();">
            <img width=40px height=40px src="img/logo_white.svg"></img>
            <div style="display:inline-block;">TeamUP</div>
        </a>
        <div class"form-inline">
            <button class="btn btn-secondary" data-toggle="modal" data-target="#registerModal">Register</button>
            <button class="btn btn-secondary" data-toggle="modal" data-target="#loginModal">Log in</button>
        </div>
    </nav>

    <div id="firstPageCon">
        <img id="firstPageLogo" src="img/logo.svg"></img>
        <div id="firstPageTitle">TeamUP</div>
        <div id="firstPageDesc"> Use <strong style="color:rgb(20, 107, 183);">TeamUP</strong> to easily manage your team work!</div>

        <button style="font-size:1.3em;cursor:pointer;"; class="btn btn-secondary reversedColor my-2 my-sm-0" type="submit" data-toggle="modal" data-target="#registerModal">Get started</button>
    </div>

    <div id="registerModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="color:rgb(20, 107, 183)!important;" "class="modal-title">Register</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="registerForm" method="post" action="register.php">
                        <div class="form-group">
                            <label for="registerUsernameInput">Enter Username</label>
                            <input type="text" class="form-control" id="registerUsernameInput" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <label for="registerDisplayName">Enter Your Name</label>
                            <input type="text" class="form-control" id="registerDisplayNameInput" name="name" placeholder="John Smit" required>
                        </div>
                        <div class="form-group">
                            <label for="registerPasswordInput">Enter Password</label>
                            <input type="password" class="form-control" id="registerPasswordInput" name="password" placeholder="Password" required>
                        </div>
                        <div class="form-group">
                            <label for="registerPasswordConfirmInput">Enter Password Again</label>
                            <input type="password" class="form-control" id="registerPasswordConfirmInput" placeholder="Confirm Password" required>
                        </div>
                        <button style="background-color:rgb(20, 107, 183)!important;cursor:pointer!important;" type="submit" class="float-right btn btn-primary">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="loginModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 style="color:rgb(20, 107, 183)!important;" class="modal-title">Log in</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="loginForm" method="post" action="login.php">
                        <div class="form-group">
                            <label for="loginUsernameInput">Enter Username</label>
                            <input type="text" class="form-control" id="loginUsernameInput" name="username" placeholder="Username" required>
                        </div>
                        <div class="form-group">
                            <label for="loginPasswordInput">Enter Password</label>
                            <input type="password" class="form-control" id="loginPasswordInput" name="password" placeholder="Password" required>
                        </div>
                        <button style="background-color:rgb(20, 107, 183)!important; cursor:pointer!important;" type="submit" class="float-right btn btn-primary">Log in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
