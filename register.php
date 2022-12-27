<?php
    session_start();
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    include("database.php");
    
	$status = 0;
    $hasError = -1;
    $name = "";
    $pass = "";
    $cpass = "";
    $email = "";
    $mobile = "";
    $otp = 0;
	
    $org_email = ""; // Enter email address made for this project or any of your emails
    $org_pass = ""; // Enter the password(Required Verified Password After 2022) of that email
    
	
	if(isset($_POST['register'])){
        $name = $_POST['uname'];
        $pass = $_POST['pass'];
        $cpass = $_POST['cpassword'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];

        $qn = "SELECT * FROM users WHERE username='$name';";
        $qe = "SELECT * FROM users WHERE email='$email';";
        
        $res_n = mysqli_query($con, $qn);
        $res_e = mysqli_query($con, $qe);
        if(strlen($name)){
            for($i=0; $i<strlen($name); $i+=1){
                if(!($name[$i]>='a' && $name[$i]<='z') && !($name[$i]>='0' && $name[$i]<='9') && $name[$i] !='_'){
                    $nameError = "Username supports a-z, 0-9 and _";
                    $hasError = 1;    
                }
            }
        }
        if(strlen($name) < 5){
            $nameError = "Username must contain atleast 5 characters";
            $hasError = 1;
        }
        
        else if(mysqli_num_rows($res_n) > 0){
            $nameError = "Username already taken";
            $hasError = 1;
        }
        else if(mysqli_num_rows($res_e) > 0){
            $emailError = "Email already taken";
            $hasError = 1;
        }
        else if(strlen($pass) < 6){
            $passLenErr = "Password requires atleast 6 characters";
            $hasError = 1;
        }
        else if($pass != $cpass){
            $passNotMatchErr = "Password not matched";
            $hasError = 1;
        }
        else if(strlen($mobile) != 11){
            $hasError = 1;
            $numError = "Mobile number requires 11 characters";
        }
        else if(!is_numeric($mobile)){
            $hasError = 1;
            $numError = "Mobile number is invalid";
        }
        else{
            require_once "PHPMailer/src/PHPMailer.php";
            require_once "PHPMailer/src/Exception.php";
            require_once "PHPMailer/src/SMTP.php";
            $hasError = 0;
            $otp = rand(100000, 999999);
            $_SESSION['session_otp'] = $otp;
            $timestamp = $_SERVER['REQUEST_TIME'];
            $newdate = new DateTime("now", new DateTimeZone('Asia/Dhaka') );
            $dateApplied = $newdate->format('Y-m-d H:i:s');
            $message = "We've sent you this email to verify that you're trying to register in FoodShare.".
                    "Your verification code is <strong>".$otp."</strong><br>".
                    "This otp will be expired after 10 minutes.".
                    "<br><br>Thank you for visiting our site.";
            $subject = "Email verification from FoodShare";
            $_SESSION['insert-query'] = $q;
            $status = 1; 

            $mail = new PHPMailer();                              // Enable verbose debug output
            $mail->SMTPDebug = 0;
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Mailer = "smtp";
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $org_email;                 // SMTP username
            $mail->Password = $org_pass;
                                       // SMTP password
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->setFrom($org_email, 'Food Share');
            $mail->addAddress($email);     // Add a recipient           // Name is optional
            $mail->addReplyTo($org_email, "Food Share");

            // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
            $mail->isHTML(true);                                  // Set email format to HTML

            $mail->Subject = $subject;
            $mail->Body = $message;
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            if(!$mail->send()){
                echo 'Message could not be sent.<br>';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            }
            else
            {
                $sql = "INSERT INTO `otptable` (`username`, `password`, `otp`, `receivedTime`) VALUES ('$name', '$pass', '$otp', '$dateApplied');";
                mysqli_query($con, $sql);
                $_SESSION['inputname'] = $name;
                $_SESSION['inputpass'] = $pass;
                $_SESSION['inputemail'] = $email;
                $_SESSION['inputmobile'] = $mobile;
                header('location: verification-form.php');
                
            }
        }

    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Poppins:wght@400;500;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        <?php include "register.css"?>
            
    </style>
</head>
<body>
    <div class="reg-container" <?php if($status == 1){?>id="hideit"<?php }?>>
        <div class="title1"><h1><span id="t1">F</span><span id="t2">o</span><span id="t3">o</span><span id="t4">d</span><span id="t5">S</span><span id="t6">h</span><span id="t7">a</span><span id="t8">r</span><span id="t9">e</span></h1></div>
        <form  method="post" id="myForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <h1>Sign Up</h1>
            <!-- username -->
            <label for="uname">Username</label>
            <input type="text" name="uname" id="uname" placeholder="Enter your username" value="<?php echo $name;?>" required>
            <p <?php if(isset($nameError)){?> id="errorBox"<?php }?>>
                <?php if(isset($nameError)){ echo $nameError; }?></p>

            <!-- password -->
            <div class="passlc">
                <label for="pass">Password</label>
                <i class="far fa-eye-slash" id="togglePass" onclick="passwordVisibility('togglePass','pass')"></i>
            </div>
            <input type="password" name="pass" id="pass" placeholder="Enter your password" value="<?php echo $pass;?>" required>
            <p <?php if(isset($passLenErr)){?> id="errorBox"<?php }?>>
                <?php if(isset($passLenErr)){ echo $passLenErr; }?></p>

            <!-- confirm password -->
            <div class="passlc">
                <label for="cpassword">Confirm Password</label>
                <i class="far fa-eye-slash" id="ctogglePass" onclick="passwordVisibility('ctogglePass','cpass')"></i>
            </div>
            <input type="password" name="cpassword" id="cpass" placeholder="Enter your password" value="<?php echo $cpass;?>" required>
            <p <?php if(isset($passNotMatchErr)){?> id="errorBox"<?php }?>>
                <?php if(isset($passNotMatchErr)){ echo $passNotMatchErr; }?></p>

            <!-- email -->
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Enter your email" value="<?php echo $email;?>" required>
            <p <?php if(isset($emailError)){?> id="errorBox"<?php }?>>
                <?php if(isset($emailError)){ echo $emailError; }?></p>

            <!-- mobile -->
            <label for="mobile">Mobile</label>
            <input type="text" name="mobile" id="mobile" placeholder="Enter your mobile number" value="<?php echo $mobile;?>" required>
            <p <?php if(isset($numError)){?> id="errorBox"<?php }?>>
                <?php if(isset($numError)){ echo $numError; }?></p>
            
            <button type="submit" name="register">Register</button>
            <p>Already have an account? <a href="login.php">Login</a></p>
        </form>

    </div>


    <!-- <script type="text/javascript" src="script.js"></script> -->
    <script type="text/javascript">
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