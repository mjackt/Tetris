<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            index.php
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
            <?php
            if (isset($_POST['submit'])){
                if($_POST['Confirm'] != $_POST['Password']){
                    echo "<script>alert('Passwords do not match');</script>";
                    header("Refresh:0; url=registration.php");
                }
                else{
                    $servername="localhost";
                    $username="ecm1417";
                    $password="password";
                    $dbname="tetris";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    $username=$_POST['Username'];
                    $password=$_POST['Password'];
                    $firstName=$_POST['FirstName'];
                    $lastName=$_POST['LastName'];
                    $display=$_POST['display'];

                    $sql = "INSERT INTO Users (UserName,FirstName,LastName,Password,Display) VALUES ('".$username."', '".$firstName."', '".$lastName."', '".$password."', '".$display."')";
                    $conn->query($sql);
                    $_SESSION["loggedIn"]=$_POST['Username'];
                }
            }
            if (isset($_SESSION["loggedIn"])){ 
                echo "
            <p style='font-size: 50px;font-family: Impact;color: rgb(255, 255, 255);text-align: center;'>
                Welcome to Tetris
            </p>
            <p style='font-size: 50px;font-family: Impact;color: rgb(20, 200, 255);text-align: center;'>
                <a href='tetris.php'>Click here to play</a>
            </p>";
            }
            else {
                echo "
                <form action='tetris.php' method='post'>
                Username: <input type='text' name='Username'><br>
                Password: <input type='text' name='Password'><br>
                <input type='submit' name='submit'>
                <p>
                Dont have an account? <a href='registration.php'>Register now</a>
                </p>
            </form>
            ";
            }
            ?>
        </div>
    </div>   
</html>
