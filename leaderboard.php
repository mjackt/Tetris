<?php
session_start();
if (isset($_POST['score'])){
    $servername="localhost";
    $dbusername="ecm1417";
    $password="password";
    $dbname="tetris";
    $conn = new mysqli($servername, $dbusername, $password, $dbname);

    $username=$_SESSION['loggedIn'];
    $score=$_POST['score'];

    $sql = "SELECT Username FROM Users WHERE Username='".$username."' AND Display=1";
    $result = mysqli_query($conn, $sql);
    if ($result==False) {
        error_log("Error: %s\n", mysqli_error($conn));
        exit();
    }
    $check = mysqli_fetch_array($result);

    if (isset($check)){
        $sql = "INSERT INTO Scores (Username,Score) VALUES ('".$username."', '".$score."')";
        $conn->query($sql);
    }
}
?>
<html>
    <head>
        <title>leaderboard.php</title>
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
            <table align="center">
                <tr><th bgcolor="blue">Username</th><th bgcolor="blue">Score</th></tr>
                <?php
                $servername="localhost";
                $username="ecm1417";
                $password="password";
                $dbname="tetris";
                
                $conn = new mysqli($servername, $username, $password, $dbname);
                $sql = "SELECT * FROM Scores ORDER BY Score DESC";
                $results = mysqli_query($conn, $sql);
                while($row = $results->fetch_array()){
                    echo "<tr><td>" . $row['Username'] . "</td><td>" . $row['Score'] . "</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
</html>