<?php
session_start();
?>
<html>
    <head>
        <title>tetris.php</title>
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
        <?php
        if (isset($_POST['submit'])){
            $servername = "localhost";
            $username = "ecm1417";
            $password = "password";
            $dbname = "tetris";

            $conn = mysqli_connect($servername, $username, $password, $dbname);

            $username = $_POST['Username'];
            $password = $_POST['Password'];
            $sql = "SELECT * FROM Users WHERE Username='".$username."' AND Password='".$password."'";
            $result = mysqli_query($conn, $sql);
            if ($result==False) {
                error_log("Error: %s\n", mysqli_error($conn));
                exit();
            }
            $check = mysqli_fetch_array($result);
          
            if (isset($check)){
                $_SESSION["loggedIn"]=$_POST['Username'];
            } 
            else {
                echo "<script>alert('Incorrect login details');</script>";
                header("Refresh:0; url=index.php");
            }
        }
        ?>
        <div id="box" align="center" style="width:350px; height:650px">
            <div id="tetris-bg"><!--Postion 1px,1px is perfect top left. Add 30px to translate by one box either way-->
                <input type="button" id="startButton" value="Start Game" onclick="startGame()">
                <script>
                    var theme = new Audio("tetris.mp3");
                    document.addEventListener('keydown', function(event) {
                        const key = event.key;
                        if (key=="ArrowLeft" ){
                            var possible=true;
                            for (var i=0;i<4;i++){
                                if (fallingCoords[i][0]==0 || coords[fallingCoords[i][0]-1][fallingCoords[i][1]]!="N"){
                                    possible=false;
                                    break;
                                }
                            }
                            if(possible==true){
                                for (var i=0;i<4;i++){
                                    fallingCoords[i][0]--;
                                    blockLeft(fallingBlocks[i],i);
                                }
                            }
                        }
                        else if (key=="ArrowRight"){
                            var possible=true;
                            for (var i=0;i<4;i++){
                                if (fallingCoords[i][0]==9 || coords[fallingCoords[i][0]+1][fallingCoords[i][1]]!="N"){
                                    possible=false;
                                    break;
                                }
                            }
                            if(possible==true){
                                for (var i=0;i<4;i++){
                                    fallingCoords[i][0]++;
                                    blockRight(fallingBlocks[i],i);
                                }
                            }
                        }
                        else if (key=="ArrowDown"){
                            clearInterval(interval);
                            dropPiece();
                            interval=setInterval(dropPiece,1000);
                        }
                    });
                    var coords = new Array(10);
                    for (var i=0; i<coords.length; i++){
                        coords[i]= new Array(20).fill("N");
                    }
                    var fallingCoords = [[0,0],[0,0],[0,0],[0,0]];
                    var fallingBlocks = new Array(4).fill(null);
                    var currentBlock="N";
                    var interval;
                    var canAdd=true;
                    var score=1;
                    
                    function startGame(){
                        theme.play();
                        var button = document.getElementById("startButton");
                        button.parentElement.removeChild(button);
                        currentBlock=getRandPiece();
                        var blockToAdd=getPiece(currentBlock);
                        var x = Math.floor(Math.random() *8);
                        var color="yellow";
                        switch (currentBlock) {
                            case "O":
                                color="darkgreen";
                                break;
                            case "T":
                                color="darkorange";
                                break;
                            case "Z":
                                color="blueviolet";
                                break;
                            case "S":
                                color="lightseagreen";
                                break;
                            case "I":
                                color="darkred"
                        
                            default:
                                break;
                        }
                        for (var i=0;i<4;i++){
                            var y=blockToAdd[i][1];
                            fallingCoords[i]=blockToAdd[i];
                            fallingCoords[i][0]+=x;
                            fallingBlocks[i]=addBlock(fallingCoords[i][0],fallingCoords[i][1],color);
                        }
                        interval = setInterval(dropPiece,1000);//When using function with no parameters for set interval dont use () after function name
                    }
                    function addBlock(x,y,color){
                        var block = document.createElement("div");
                        block.className = 'block';
                        block.style.left = 1+"px";
                        block.style.top = 1+"px";
                        block.style.backgroundColor = color;
                        block.style.transform="translate("+30*x+"px,"+30*y+"px)";
                        document.getElementById("tetris-bg").appendChild(block);
                        return block;
                    }
                    function dropBlock(i){
                        var block = fallingBlocks[i];
                        block.style.transform = "translate("+30*fallingCoords[i][0]+"px,"+30*fallingCoords[i][1]+"px)";
                    }
                    function blockLeft(block,i){
                        block.style.transform = "translate("+30*fallingCoords[i][0]+"px,"+30*fallingCoords[i][1]+"px)";
                    }
                    function blockRight(block,i){
                        block.style.transform = "translate("+30*fallingCoords[i][0]+"px,"+30*fallingCoords[i][1]+"px)";
                    }
                    function getPiece(piece){
                        const pieces = {"L":[[0,0],[0,1],[0,2],[1,2]],
                                    "Z":[[0,0],[1,0],[1,1],[2,1]],
                                    "S":[[0,1],[1,1],[1,0],[2,0]],
                                    "T":[[0,0],[1,0],[2,0],[1,1]],
                                    "O":[[0,0],[1,0],[0,1],[1,1]],
                                    "I":[[0,0],[0,1],[0,2],[0,3]]}
                        return pieces[piece];
                    }
                    function getRandPiece(){
                        const chars = "LZSTIO";
                        var randInt=Math.floor(Math.random() * 6);
                        return chars[randInt];
                    }
                    function dropPiece(){
                        if (canAdd){//Needed because for some reason clearInterval doesnt work in this function but does as event listenter.
                            var possible=true;
                            for(var i=0;i<4;i++){
                                if(coords[fallingCoords[i][0]][fallingCoords[i][1]+1] != "N" || fallingCoords[i][1]==19){
                                    possible=false;
                                    break;
                                }
                            }
                            if (possible == true){
                                for(var i=0;i<4;i++){
                                    fallingCoords[i][1]++;
                                    dropBlock(i);
                                }
                            }
                            else{
                                for (var i=0;i<4;i++){
                                    coords[fallingCoords[i][0]][fallingCoords[i][1]]=currentBlock;
                                    fallingBlocks[i].style.left=fallingCoords[i][0]*30+1+"px";
                                    fallingBlocks[i].style.top=fallingCoords[i][1]*30+1+"px";
                                    fallingBlocks[i].style.transform = "translate(0px,0px)"
                                }
                                var loop=true;
                                while(loop){
                                    var row=checkForRow();
                                    if (row==null){
                                        loop=false;
                                    }
                                    var blocksToGo =[];
                                    var blocksToDrop = [];
                                    var allBlocks = Array.from(document.getElementsByClassName('block'));
                                    for (var k=0;k<allBlocks.length;k++){
                                        var currentTop=allBlocks[k].offsetTop;
                                        if(currentTop<((row*30)+1)){
                                            blocksToDrop.push(allBlocks[k]);
                                        }
                                        else if (currentTop==((row*30)+1)){
                                            blocksToGo.push(allBlocks[k]);
                                        }
                                    }
                                    for(var k=0;k<blocksToGo.length;k++){
                                        blocksToGo[k].parentElement.removeChild(blocksToGo[k]);
                                    }
                                    for(var k=0;k<blocksToDrop.length;k++){
                                        var currentTop=blocksToDrop[k].offsetTop;
                                        blocksToDrop[k].style.top=currentTop+30;
                                    }
                                    for (var i=0;i<10;i++){
                                        for (var j=19;j>-1;j--){
                                            if(j==0){
                                                coords[i][j]="N";
                                            }
                                            else if(j<=row){
                                                coords[i][j]=coords[i][j-1];
                                            }
                                        }
                                    }
                                }
                                currentBlock=getRandPiece();
                                var blockToAdd=getPiece(currentBlock);
                                var x = Math.floor(Math.random() *8);
                                for (var i=0;i<4;i++){
                                    fallingCoords[i]=blockToAdd[i];
                                    fallingCoords[i][0]+=x;
                                    if (coords[fallingCoords[i][0]][fallingCoords[i][1]]!="N"){
                                        canAdd=false;
                                        interval = clearInterval(interval);
                                    }
                                }
                                if (canAdd){
                                    var color="yellow";
                                    switch (currentBlock) {
                                        case "O":
                                            color="darkgreen";
                                            break;
                                        case "T":
                                            color="darkorange";
                                            break;
                                        case "Z":
                                            color="blueviolet";
                                            break;
                                        case "S":
                                            color="lightseagreen";
                                            break;
                                        case "I":
                                            color="darkred"
                                    
                                        default:
                                            break;
                                    }
                                    score++;
                                    for (var i=0;i<4;i++){
                                        fallingBlocks[i]=addBlock(fallingCoords[i][0],fallingCoords[i][1],color);
                                    }
                                }
                                else{
                                    var done = document.createElement("div");
                                    done.className="GameOver";
                                    done.style.top="0px";
                                    done.style.left="50px";
                                    done.innerHTML= "Congrats!\n You scored "+score+" points";
                                    var button = document.createElement("input");
                                    button.setAttribute("type","button");
                                    button.setAttribute("value","Play Again");
                                    button.setAttribute("onclick","reload()");
                                    done.appendChild(button);
                                    document.getElementById("tetris-bg").appendChild(done);
                                    var xhttp=new XMLHttpRequest();
                                    xhttp.open("POST","leaderboard.php",true);
                                    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                                    xhttp.send("score="+score);
                                }
                            }
                        }
                    }
                    function checkForRow(){
                        for (var i=19;i>-1;i--){
                            var found=false;
                            for (var j=0;j<10;j++){
                                    if (coords[j][i]=="N"){
                                        found=true;
                                        break;
                                    }
                            }
                            if (!found){
                                return i;
                            }
                        }
                        return null;
                    }
                    function reload(){
                        location.reload();
                    }
                </script>
            </div>
        </div>
    </div>
</html>