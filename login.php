<?php
    session_start();
    
	include("database.php");
	
	$err = '';

    
    if(isset($_COOKIE['foodshare_admin_remember_username']) &&
    isset($_COOKIE['foodshare_user_remember_username'])){
        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
        setcookie('foodshare_admin_remember_username', "", time()-1, '/', $domain, false);
        setcookie('foodshare_user_remember_username', "", time()-1, '/', $domain, false);
    }
    else if(isset($_COOKIE['foodshare_admin_remember_username'])){
        $_SESSION['username'] = $_COOKIE['foodshare_admin_remember_username'];
        $_SESSION['type'] = 'Admin';
        header('location: admin-home.php');
    }
    else if(isset($_COOKIE['foodshare_user_remember_username'])){
        $_SESSION['username'] = $_COOKIE['foodshare_user_remember_username'];
        $_SESSION['type'] = 'User';
        header('location: home.php');
    }

    if(isset($_POST['username'])){
        $name = $_POST['username'];
        $pass = $_POST['password'];

        $q = "SELECT * FROM users WHERE username = '".$name."';";
        $result = mysqli_query($con, $q);
        $count = mysqli_num_rows($result);
                            
        if($count == 1){
            $row = mysqli_fetch_array($result);
            $type = $row['type'];
            $_SESSION['username'] = $name;
            $_SESSION['type'] = $type;
            $passFromDB = $row['password'];
            $verifyPass = password_verify($pass, $passFromDB);
            if($verifyPass){
                if($type == "User"){
                    if(isset($_POST['remember-me'])){
                        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
                        setcookie('foodshare_user_remember_username', $name, time()+(60*60*24*30), '/', $domain, false);
                    }
                    header('location: home.php');
                
                }
                else if($type == "Admin"){
                    if(isset($_POST['remember-me'])){
                        $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
                        setcookie('foodshare_admin_remember_username', $name, time()+(60*60*24*30), '/', $domain, false);
                    }
                    header('location: admin-home.php');
                    
                }
            }
            else{
                $err = 'Invalid username or password';    
            }
        }
        else{
            $err = 'Invalid username or password';
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        <?php include "login.css" ?>
        .disp-chk{
            display: flex;
            justify-content: left;
            align-items:center;
        }
        .disp-chk span{
            font-size: 14px;
        }

    </style>
    
</head>
<body>
    <div class="container">
        <div class="title1"><h1><span id="t1">F</span><span id="t2">o</span><span id="t3">o</span><span id="t4">d</span><span id="t5">S</span><span id="t6">h</span><span id="t7">a</span><span id="t8">r</span><span id="t9">e</span></h1></div>
        <form method="post">
            <h1>Sign In</h1>
            <label for="username">Username</label>
            <input type="text" name="username" id="uname" placeholder="Enter your username" required>
            
            <div class="passlc">
                <label for="password">Password</label>
                <i class="far fa-eye-slash" id="togglePass" onclick="passwordVisibility('togglePass','pass')"></i>
            </div>
            <input type="password" name="password" id="pass" placeholder="Enter your password" required>
            <div class="disp-chk"><input style="margin:0 0 0 40px;" type="checkbox" name="remember-me"><span>Remember Me</span></div>
            <p id="errorBox"><?php echo $err;?></p>
            <button type="submit">Login</button>
            <p>Not have any account? <a href="register.php">Register</a></p>
        </form>
    </div>
    
    <!-- <script type="text/javascript" src="script.js"></script> -->
    <script>
        const t_id = ["t1","t2","t3","t4",
                    "t5","t6","t7","t8","t9"];
        let count = 0;
        let index = 0;
        let currentId = '';
        (function type(){
            for(var i=0; i<count; i++){
                document.getElementById(t_id[i]).classList.remove("set-crimson");
            }
            if(count === t_id.length){
                count = 0;
            }
            currentId = t_id[count];
            document.getElementById(currentId).classList.add("set-crimson");
            count++;
                    
            setTimeout(type, 800);
        }());

        function passwordVisibility(toggleId, id){
            let togglePass = document.getElementById(toggleId);
            let pass = document.getElementById(id);
            let getType = pass.getAttribute('type');
            if(getType === 'password'){
                pass.setAttribute('type', 'text');
                togglePass.classList.remove('fa-eye-slash');
                togglePass.classList.add('fa-eye');
            }
            else if(getType === 'text'){
                pass.setAttribute('type', 'password');
                togglePass.classList.remove('fa-eye');
                togglePass.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>