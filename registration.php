<html>
    <head>
        <title>
            register.php
        </title>
        <link rel="stylesheet" href="theme.css">
    </head>
    <div class="topnav">
        <a href="index.php" name="home">Home</a>
        <div class="topnav-right">
            <a href="tetris.php" name="tetris">Play Tetris</a>
            <a href="leaderboard.php" name="leaderboard">Leaderboard</a>
        </div>
    </div>
    <div class="main">
        <div id="box">
            <form action="index.php" method="post">
                First Name: <input type='text' name='FirstName'><br>
                Last Name: <input type='text' name='LastName'><br>
                Username: <input type='text' name='Username'><br>
                Password: <input type='text' name='Password'><br>
                Confirm Password: <input type='text' name='Confirm'><br>
                Display Scores on Leaderboard:<br>
                <input type="radio" id='yes' name='display' value=1>
                <label for "yes">Yes</label><br>
                <input type="radio" id='no' name='display' value=0>
                <label for "no">No</label><br>
                <input type="submit" name='submit'>
            </form>
        </div>
    </div>