<?php
    session_start();
    include("database.php");
    // $timeSt = $_SERVER['REQUEST_TIME'];
    $OTPStatus = 0;
    $temp = 0;
    $now = new DateTime("now", new DateTimeZone('Asia/Dhaka'));
    $now = $now->format('Y-m-d H:i:s');
    $endTime = "";
    if(!isset($_SESSION['inputname'])){
        header('location: register.php');
    }
    if(isset($_SESSION) && isset($_POST['otp'])){
        $temp = 1;
        $now = new DateTime("now", new DateTimeZone('Asia/Dhaka'));
        $now = $now->format('Y-m-d H:i:s');
        // $timeSt = $_SERVER['REQUEST_TIME'];
        $name = $_SESSION['inputname'];
        $pass = $_SESSION['inputpass'];
        $encrypted_pass = password_hash($pass, PASSWORD_BCRYPT);
        $email = $_SESSION['inputemail'];
        $mobile = $_SESSION['inputmobile'];
        $q = "SELECT * FROM otptable WHERE username = '$name' AND password = '$pass' ORDER BY otp_id DESC;";
        $result = mysqli_query($con, $q);
        if(mysqli_num_rows($result) == 1){
            $row = mysqli_fetch_array($result);
            $endTime = $row['receivedTime'];
            $otpDb = $row['otp'];

            $qDelete = "DELETE FROM otptable WHERE username = '$name' and password = '$pass';";
            mysqli_query($con, $qDelete);
        }
        $otpGiven = $_POST['otp'];
        if(($otpGiven == $otpDb) && (strtotime($now) - strtotime($endTime) <= 600)){
            $OTPStatus = 1;
            $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
            setcookie('foodshare_admin_remember_username', "", time()-1, '/', $domain, false);
            setcookie('foodshare_user_remember_username', "", time()-1, '/', $domain, false);
            $insert_q = "INSERT INTO `users` (`username`, `email`, `mobile`, `password`) VALUES ('$name', '$email', '$mobile', '$encrypted_pass');";
            mysqli_query($con, $insert_q);
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Poppins:wght@400;500;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        *{
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        .container{
            height: 100vh;
            width: 100%;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }
        .container .card{
            width: 350px;
            height: 250px;
            background-color: #B6DDFD;
            border: 1px solid #0294ce;
        }
        .container .card .head-part{
            display: flex;
            align-items: center;
            justify-content: center;
            height: 75px;
            background-color: #5170C9;
            font-size: 24px;
            font-weight: bold;
            font-family: 'Roboto';
            color: #fff;
        }
        .container .card .body-part{
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 150px;
            font-family: 'Roboto';
            font-size: 14px;
            margin: 0 10px;
        }
        .container .card .body-part input{
            font-family: 'Roboto';
            font-size: 14px;
            margin-top: 10px;
            height: 30px;
            width: 140px;
            outline: none;
        }
        #btn,
        .btns{
            font-family: 'Roboto';
            margin-top: 5px;
            font-size: 13px;
            padding: 5px 10px;
            background-color: #005050;
            color: #fff;
            border: 2px solid;
            border-color: #fff #000 #000 #fff;
            cursor: pointer;
        }
        #errorBox{
            color: #f00;
            font-size: 16px;
            font-weight: bold;
            font-family: 'Poppins', sans-serif;
        }
        #btn:hover{
            border-color:  #000 #fff #fff #000;
        }
        .hide-it{
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card" id="crd">
            <div class="head-part">
                <p>Enter OTP</p>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" class="body-part">
                <p style="text-align: center;" <?php if($temp == 1){?>class="hide-it"<?php
                }?>>Check you email for 6 digits OTP.<br>OTP will be expired after 10 minutes.<br></p>
                <input type="text" id="otp-input" name="otp" required <?php if($temp == 1){
                    ?>class="hide-it"<?php
                }?>>
                <button type="submit" name="submit" id="btn" <?php if($temp == 1){?>class="hide-it"<?php
                }?>>Submit</button>
                <p id="errorBox"><?php if(strtotime($now) - strtotime($endTime) > 600 && $temp == 1){
                    echo "OTP is expired."; session_destroy();}

                    else if($OTPStatus == 0 && $temp == 1){echo "OTP not matched."; session_destroy();}

                    else if((strtotime($now) - strtotime($endTime) <= 600) && $OTPStatus == 1 && $temp == 1){
                        ?>
                        <p style="font-size: 16px;">Successfully Registered.  <br>
                        Click the button to login. <br></p>
                        <button class="btns"><a href="login.php" style="text-decoration: none; color: #fff;">Enter</a></button>
                        <?php session_destroy();}?>
                
                </p>
            </form>
        </div>
    </div>
    
    <script type="text/javascript">

    </script>
</body>
</html>
