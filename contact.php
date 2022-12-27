<?php
    session_start();
    include("database.php");

    if(!isset($_COOKIE['foodshare_user_remember_username']) && 
        !isset($_COOKIE['foodshare_admin_remember_username']) && 
        !isset($_SESSION['username'])){
        header('location: login.php');
    }
    else if(isset($_SESSION['username'])){
        $uname=$_SESSION['username'];
        $_SESSION['type'] = $_SESSION['type'];
    }
    else if(isset($_COOKIE['foodshare_user_remember_username'])){
        $uname = $_COOKIE['foodshare_user_remember_username'];
    }
    else if(isset($_COOKIE['foodshare_admin_remember_username'])){
        $uname = $_COOKIE['foodshare_admin_remember_username'];
    }
    $_SESSION['username'] = $uname;

    
    if(isset($_POST['comment-btn'])){
        if($uname == ""){
            header('location: login.php');
        }
        $name = $_POST['name'];
        $email = $_POST['email'];
        $comment = $_POST['comment'];
        if($name != test_input($name)){
            echo "<script>alert('Invalid Name Input');</script>";
        }
        else if($email != test_input($email)){
            echo "<script>alert('Invalid Email Input');</script>";
        }
        else if($comment != test_input($comment)){
            echo "<script>alert('Invalid Comment Input');</script>";
        }
        else if(strlen($comment) >10){
            echo "<script>alert('Comment is too long. <br>Maximum character 1000');</script>";
        }
        else{
            $q = "INSERT INTO messages('username','name','email','message') VALUES ('$uname', '$name', '$email', '$comment');";
            mysqli_query($con, $q);
        }
    }

    function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Poppins:wght@400;500;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        :root{
            --black: #000;
            --white: #fff;
            --color1: #DC143C;
            --color2: #DC143C;
            --bg: transparent;
        }
        
        .set-bold{
            font-weight: bold;
        }
        .hide{
            display: none;
        }
        .container{
            width: 100%;
            height: 100vh;
        }
        .container .nav-bar{
            width: 100%;
            height: 70px;
            padding: 0 20px;
            position: fixed;
            top: 0;
            background-color: var(--bg);
            color: var(--color2);
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
        .container .nav-bar ul{
            display: flex;
            flex-direction: row;
        }
        .container .nav-bar ul li{
            list-style: none;
            margin-left: 10px;
            margin-right: 10px;
        }
        .container .nav-bar ul li a{
            text-decoration: none;
            color: var(--color2);
            font-size: 16px;
            position: relative;
            background-size: 0;
        }
        .container .nav-bar ul li a::after{
            content: '';
            position: absolute;
            left: 0;
            height: 2px;
            bottom: -2px;
            width: 100%;
            border-bottom: 2px solid var(--color2);
            transform: scale(0, 1);
            transition: transform 0.3s ease;
        }
        .container .nav-bar ul li a:hover::after{
            transform: scale(1, 1);
        }
        .container .nav-bar ul li #btn{
            background-color: transparent;
            border: none;
            font-size: 14px;
            cursor: pointer;
            color: var(--white);
        }
        #top-btn{
            height: 35px;
            width: 35px;
            position: fixed;
            bottom: 10px;
            right: 10px;
            background-color: #FFC2CE;
            border: none;
            outline: none;
            border: 1px solid #DC62CC;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
        }
        .content{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .content form{
            box-shadow: 0 0 20px 2px #A2A2A2;
            width: 400px;
            height: 400px;
            margin-top: 100px;
            border-radius: 5px;
			display: flex;
			flex-direction: column;
			align-items: center;
			
        }
        .content form h2{
            margin: 10px;
            text-align: center;
            color: var(--color1);
        }
        .content form input,
        .content form textarea{
            margin: 10px 10px 0 10px;
            width: 65%;
            font-family: 'Roboto', sans-serif;
            padding: 3px 5px;
            outline: none;
        }

        .content form textarea{
            resize: none;
        }
        .content form button{
            margin: 10px 10px 0 10px;
            background-color: var(--color1);
            color: #fff;
            font-size: 14px;
            font-family: 'Roboto', sans-serif;
            padding: 5px 10px;
            outline: none;
            border: 2px solid var(--color1);
            border-radius: 5px;
        }
        .content form button:hover{
            background-color: #fff;
            color: var(--color1);
            cursor: pointer;
        }
        
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-bar" id="nav_bar">
            <div class="logo"><h1>FoodShare</h1></div>
            <ul class="links">
                <li><a href="<?php if(isset($_COOKIE['foodshare_admin_remember_username']) || $_SESSION['type'] == 'Admin'){echo 'admin-home.php';}else{echo 'home.php';}?>">Home</a></li>
                <li><a href="donate.php">Donate</a></li>
                <li><a href="meet.php">Meet</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php" class="set-bold">Contact</a></li>
                <li><a href="logout.php" id="logout">Logout</a></li>
            </ul>
        </div>
        <button id="top-btn" class="hide" onclick="scroll_to_top()">Top</button>
        <div class="content">
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                <h2>Contact</h2>
                <input type="text" name="name" id="name" placeholder="Enter your name" required>
                <input type="email" name="email" id="email" placeholder="Enter your email" required>
                <textarea name="comment" id="comment" cols="30" rows="10" placeholder="Write your comment here"></textarea>
                <br>
                <button type="submit" name="comment-btn">Send</button>
            </form>
        </div>
    </div>



    <script type="text/javascript">
        let stat = 0;
        window.onscroll = function(e){
            
            let topPos = window.pageYOffset || document.documentElement.scrollTop;      
            if(topPos > 20 && stat == 0){
                stat = 1;
                document.documentElement.style.setProperty('--color2', "#fff");
                document.documentElement.style.setProperty('--bg', "#DC143C");
                var temp = document.getElementById('top-btn')
                if(temp.classList.contains('hide')){
                    temp.classList.remove('hide');
                }
    
            }
            else if(topPos <= 20 && stat == 1){
                stat = 0;
                document.documentElement.style.setProperty('--bg', "transparent");
                document.documentElement.style.setProperty('--color2', "#DC143C");   
                var temp = document.getElementById('top-btn')
                if(!temp.classList.contains('hide')){
                    temp.classList.add('hide');
                }
            }
        }

        function scroll_to_top(){
            document.documentElement.scrollTop = 0;
            window.pageYOffset = 0;
            document.body.scrollTop = 0;
        }
    </>
</body>
</html>