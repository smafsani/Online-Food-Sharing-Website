<?php
    session_start();
    include("database.php");
    $inputname = $_SESSION['submitName'];
    $inputpass = $_SESSION['submitPass'];
    $timeStamp = $_SERVER['REQUEST_TIME'];
    $query = "SELECT otp, receivedTime FROM otptable WHERE username = '$inputname' and password = '$inputpass' and status = 0;";
    $reslt = mysqli_query($con, $query);
    
    //$query = "DELETE FROM otptable WHERE username = '$inputname' and password = '$inputpass' and status = 0;";
    //mysqli_query($con, $query);
    
    if(mysqli_num_rows($reslt) == 1){
        $row = mysqli_fetch_array($reslt);
        $endTime = $row['receivedTime'];
        $otpDb = $row['otp'];
        $OTPStatus = 0;
    }
    else{
        echo "KI SOMOSSA VAI";
        //header('location: register.php');
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Account</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Poppins:wght@400;500;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        *{
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }
        .container{
            height: 100vh;
            width: 100%;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }
        .container .card{
            width: 300px;
            height: 220px;
            border: 2px solid #4ea2c4;
            background-color: #B6DDFD;
            color: #fff;
            display: flex;
            flex-direction: column;
            text-align: center;
            font-family: 'Roboto';
        }
        .container .card h2{
            text-align: center;
            background-color: #5170C9;
            padding: 15px 0;
        }
        .container .card p{
            font-size: 14px;
            color: #000;
        }
        .container .card button a{
            color: #fff;;
            text-decoration: none;
        }
        .container .card button{
            padding: 6px 20px;
            margin-top: 10px;
            background-color: #5170C9;
            border: 1px solid #4ea2c4;
            font-weight: bold;
            cursor: pointer;
            font-size: 16px;

        }
    </style>
</head>
<body>
    <div class="container">

        <div class="card">
            <h2>Congratulations</h2>
            <br>
            <div class="content">
                <?php if(($timeStamp - $endTime <= 600) && $OTPStatus == 1){?>
                <p>You has been registered.<br>Click the button below to enter homepage.</p>
                <button><a href="home.html">ENTER</a></button>
                <?php }else if($timeStamp - $endTime > 600){?><p>You have not been registered.<br>OTP is expired</p>
                <?php }else if($OTPStatus == 0){?><p>You have not been registered.<br>OTP not matched.</p><?php } echo $OTPStatus;?>
            </div>
        </div>

    </div>
</body>
</html>