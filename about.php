<?php
    session_start();
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
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
        .hide{
            display: none;
        }
        .text-style1{
            margin: 10px;
            font-size: 20px;
            color: var(--color1);
        }
        .text-style2{
            font-size: 40px;
            margin: 20px 10px;
        }
        .text-style3{
            font-size: 14px;
            margin: 20px 10px;
            color: #646464;
        }
        .text-style4{
            font-size: 22px; 
            text-align: center;
            word-spacing: 10px;
            padding: 40px 40px;
        }
        .set-bold{
            font-weight: bold;
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
        .about-us{
            margin-top: 100px;
        }
        .about-us .about-title{
            margin-bottom: 50px;
            text-align: center; 
            font-size: 50px;
        }
        .about-us .about-title span{
            color: var(--white);
            background-color: var(--color1);
            padding: 0 5px;
            border-radius: 5px;
        }
        .about-us .one{
            font-size: 34px; 
            text-align: center; 
            margin-top: 40px;
        }
        .cards{
            margin: 80px;
            display: flex;
            flex-direction: row;
            justify-content: space-between;
        }
        .cards .card{
            width: 31%;
            height: auto;
            min-height: 390px;
            text-align: center;
            box-shadow: 0 0 10px 2px #8F8F8F;
            transition: all 0.3s ease;
        }
        .cards .card:hover{
            box-shadow: 0 0 10px 2px #000;
        }
        .cards .card .card-head{
            font-size: 26px;
        }
        .cards .card .card-body{
            padding: 30px 10px;
            font-size: 16px;
            color: #7F7F7F;
        }
        .cards .card .icon{
            width: 80px;
            height: auto;
            margin: 20px;
        }

        .about-us .reviews .review-box{
            width: 100%;
            height: 500px;
            background-color: #222;
            
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
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-bar">
            <div class="logo"><h1>FoodShare</h1></div>
            <ul class="links">
                <li><a href="<?php if(isset($_COOKIE['foodshare_admin_remember_username']) || $_SESSION['type'] == 'Admin'){echo 'admin-home.php';}else{echo 'home.php';}?>">Home</a></li>
                <li><a href="donate.php">Donate</a></li>
                <li><a href="meet.php">Meet</a></li>
                <li><a href="about.php" class="set-bold">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="logout.php" id="logout">Logout</a></li>
            </ul>
        </div>
        <button id="top-btn" class="hide" onclick="scroll_to_top()">Top</button>
        <div class="about-us" id="about-us">
            <p class="about-title">About <span>Us</span></p>
            <div class="texts">
                <p class="text-style4">&QUOT;Donating food is not a problem. Because what we need is the vision and desire to help people. 
                    Think how wonderful it would be to see that nobody will starve by hunger. - FoodShare&QUOT;</p>
                <p style="padding: 0 20px; font-size: 15px;">Food Share is working for a goal of making the world hunger free and not wasting any food. And also we are working on gethering meetings for people where people can be acquainted with each. We are hoping for further improvement of our community to serve people with more functionality.
                    We are aiming to bring benefits across the society and help provide access to food to those who don't have enough means to access it.
                    Food Share works along with the Governments, Corporates with Social Responsibility, other partners and volunteers to create a local chapter of excess food collection organization that will be spread across various cities, 
                    addressing the two major challenges - using the excess foods instead of wasting those and providing a communication system where people can meet together.
                </p>
                <br><br>
                <p class="one"><span style="color: var(--white); background-color: var(--color1);">&nbsp;Our&nbsp;</span><span style="color: var(--white); background-color: #252341;">&nbsp;Goals&nbsp;</span></p>
                <p class="text-style1" style="color: var(--black); text-align: center;">To bring a social change in every individual in order to reduce food waste and to make platform for human interaction.</p>
                <p class="one"><span style="color: var(--white); background-color: var(--color1);">&nbsp;Our&nbsp;</span><span style="color: var(--white); background-color: #252341;">&nbsp;Actions&nbsp;</span></p>
                <div class="cards">
                    <div class="card">
                        <img class="icon" src="images/not-waste-food.png" alt="">
                        <p class="card-head">Use Excess Foods</p>
                        <p class="card-body">To raise awareness encouraging people to reduce food loss and
                            food waste by sharing excess foods.</p>
                    </div>
                    <div class="card">
                        <img class="icon" src="images/not-waste-food.png" alt="">
                        <p class="card-head">Use Excess Foods</p>
                        <p class="card-body">To raise awareness encouraging people to reduce food loss and
                            food waste by sharing excess foods.</p>
                    </div>
                    <div class="card">
                        <img class="icon" src="images/not-waste-food.png" alt="">
                        <p class="card-head">Use Excess Foods</p>
                        <p class="card-body">To raise awareness encouraging people to reduce food loss and
                            food waste by sharing excess foods.</p>
                    </div>
                </div>    
                <br>
            </div>
            <div class="reviews">
                <p class="about-title">Reviews</p>
                <div class="review-box">

                </div>
                <br>
            </div>
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
    </script>
</body>
</html>