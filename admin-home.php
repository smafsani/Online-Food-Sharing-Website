<?php 
    session_start();
    $uname = "";
    if(!isset($_COOKIE['foodshare_admin_remember_username']) && !isset($_SESSION['username'])){
        header('location: login.php');
    }
    else if(isset($_SESSION['username']) && $_SESSION['type'] != 'Admin'){
        header('location: login.php');
    }
    else if(isset($_SESSION['username'])){
        $uname=$_SESSION['username'];
        $_SESSION['type'] = $_SESSION['type'];
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
    <title>Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Poppins:wght@400;500;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        <?php 
            include "admin-home.css";
        ?>
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-bar" id="nav-bar">
            <div class="logo"><h1>FoodShare</h1></div>
            <ul class="links">
                <li><a href="admin-home.php" class="set-bold">Home</a></li>
                <li><a href="donate.php">Donate</a></li>
                <li><a href="meet.php">Meet</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="logout.php" id="logout">Logout</a></li>
            </ul>
        </div>
        <button id="top-btn" class="hide" onclick="scroll_to_top()">Top</button>
        <div class="content">
            <img id="showImages" src="" alt="image collections">
            <div class="texts">
                <h1>Let's Share Food</h1> <br>
                <p>May be your extra foods can help some poor people. <br>
                Please do not waste your foods. <br>Better <strong><a href="donate.php" style="text-decoration: none; color: var(--color1);">donate</a></strong> it.</p>
            </div>
        </div>
        <div class="manage">
            <h1 style="text-align: center; color: var(--color1);">Manage</h2>
            <p>Hello Admin!
                You have the access to manage users and other media of FoodShare.
            </p>
            <button type="submit" class="common-button" onclick="gotoManage('manage')">Manage</button>


        </div>
        <div class="donate-meet">
            <div class="donate" id="donate">
                <p class="text-style1">We have distributed and used a lots of plates of food and spread happiness.</p>
                <p class="text-style2">1 Million+ people were helped by having food.</p>
                <p class="text-style3">Every day the excess foods are collected and used to feed an nearly of 2500+ people, across 5 cities in Bangladesh.</p>
                <button type="submit" name="submit-donate" class="btn" onclick="gotoManage('donate')">Donate</button>
            </div>
            <div class="meet" id="meet">
                <p class="text-style1">We have arranged meetings for people. People meet here and have lunch together.</p>
                <p class="text-style2">1000+ of people meet here and enjoy lunch.</p>
                <p class="text-style3">Every day come here to meet new people and spend some valuable time to refresh their daily life.</p>
                <button type="submit" name="submit-donate" class="btn"onclick="gotoManage('meet')">Meet</button>
            </div>
        </div>
        <div class="about-us" id="about-us">
            <p class="about-title" style="text-align: center; font-size: 50px;">About <span>Us</span></p>
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

        const imgs = ["images/donate1.jpeg","images/donate2.jpg",
        "images/donate3.jpg","images/donate4.jpg"];
        
        let count = 0;
        let index = 0;
        let currentId = '';
        (function type(){
            count = count % 4;
            let doc = document.getElementById("showImages");
            doc.src = imgs[count];
            count++;
            setTimeout(type, 3000);
        }());

        function gotoManage(text){
            if(text == 'manage'){
                window.location.href = "manage.php";
            }
            else if(text == 'donate'){
                window.location.href = "donate.php";
            }
            else if(text == 'meet'){
                window.location.href = "meet.php";
            }
        }
    </script>
</body>
</html>