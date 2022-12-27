<?php
    session_start();
    include("database.php");
	
    if(!isset($_COOKIE['foodshare_admin_remember_username']) && !isset($_SESSION['username'])){
        header('location: login.php');
    }
    else if(isset($_SESSION['username']) && $_SESSION['type'] != 'Admin'){
        header('location: login.php');
    }
    else if(isset($_SESSION['username'])){
        $uname = $_SESSION['username'];
        $_SESSION['type'] = $_SESSION['type'];
    }
    else if(isset($_COOKIE['foodshare_admin_remember_username'])){
        $uname = $_COOKIE['foodshare_admin_remember_username'];
    }
    $_SESSION['username'] = $uname;
    
    $uid = 0;
    $urpass = "";
    $wrong_pass = false;
    if(isset($_POST['du-submit'])){
        $uid = $_POST['user-id'];
        $urpass = $_POST['ur-pass'];
        $q = "SELECT * FROM users WHERE username='$uname';";
        $res = mysqli_query($con, $q);
        $count = mysqli_num_rows($res);
        if($count == 1){
            $row = mysqli_fetch_array($res);
            $urpass_db = $row['password'];
            $verify_pass = password_verify($urpass, $urpass_db);
            if($verify_pass){
                $qdelete = "DELETE FROM users WHERE id='$uid'";
                mysqli_query($con, $qdelete);
                header('location: manage.php');
            }
            else{
                echo "<script>alert('Invalid Password')</script>";
            }
        }
    }
    else if(isset($_POST['rd-submit'])){
        $dun = $_POST['donor-un'];
        $fdn = $_POST['food-n'];
        $type = $_POST['food-type'];
        
        if(strtolower($type) == 'food'){
            $qdelete = "DELETE FROM donated_food WHERE username='$dun' and food_name='$fdn';";
            mysqli_query($con, $qdelete);
        }
        else if(strtolower($type) == 'ingredient'){
            $qdelete = "DELETE FROM donated_ingredient WHERE username='$dun' and ingredient_name='$fdn';";
            mysqli_query($con, $qdelete);
        }
        header('location: manage.php'); 
    }
    else if(isset($_POST['clear-expired'])){
        $qry = "SELECT * FROM donated_food";
        $result = mysqli_query($con, $qry);
        while($row = mysqli_fetch_array($result)){
            $date_db = $row['date'];
            $date = new DateTime("now", new DateTimeZone('Asia/Dhaka') );
            $now = $date->format('Y-m-d H:i:s');
            $interval = (strtotime($now) - strtotime($date_db))/60;
            $interval = ($interval/60)/24;
            if($interval >= 7){
                $unm = $row['username'];
                $fnm = $row['food_name'];
                $q = "DELETE FROM donated_food WHERE username='$unm' AND food_name='$fnm' AND date='$date_db';";
                mysqli_query($con, $q);
            }   
        }
        $qry = "SELECT * FROM donated_ingredient";
        $result = mysqli_query($con, $qry);
        while($row = mysqli_fetch_array($result)){
            $date_db = $row['date'];
            $date = new DateTime("now", new DateTimeZone('Asia/Dhaka') );
            $now = $date->format('Y-m-d H:i:s');
            $interval = (strtotime($now) - strtotime($date_db))/60;
            $interval = ($interval/60)/24;
            if($interval > 30){
                $unm = $row['username'];
                $fnm = $row['ingredient_name'];
                $q = "DELETE FROM donated_ingredient WHERE username='$unm' AND ingredient_name='$fnm' AND date='$date_db';";
                mysqli_query($con, $q);
            }   
        }
        header('location: manage.php');
    }
    else if(isset($_POST['otp-submit'])){
        $qry = "SELECT * FROM otptable;";
        $result = mysqli_query($con, $qry);
        while($row = mysqli_fetch_array($result)){
            $date = new DateTime("now", new DateTimeZone('Asia/Dhaka') );
            $now = $date->format('Y-m-d H:i:s');
            $time_db = $row['receivedTime'];
            $interv = strtotime($now) - strtotime($time_db);
            $interv = ($interv / 60) / 60;
            echo "<script>alert('OK');</script>";
            if($interv >= 1){
                $id = $row['otp_id'];
                $q = "DELETE FROM otptable WHERE otp_id=$id;";
                mysqli_query($con, $q);
                $a_i = 101;
                $q = "ALTER TABLE otptable AUTO_INCREMENT='$a_i';";
                mysqli_query($con, $q);
                header('location: manage.php');
            }
        }
    }
    else if(isset($_POST['clear-expired-meet'])){
        $qry = "SELECT * FROM meetings";
        $result = mysqli_query($con, $qry);
        while($row = mysqli_fetch_array($result)){
            $date_db = $row['date'];
            $date_db = new DateTime($date_db, new DateTimeZone('Asia/Dhaka'));
            $date_ = $date_db->format('Y-m-d H:i:s');
            
            $date = new DateTime("now", new DateTimeZone('Asia/Dhaka') );
            $now = $date->format('Y-m-d H:i:s');
            
            $interval = (strtotime($now) - strtotime($date_))/60;
            $interval = ($interval/60)/24;
            if($interval >= 1){
                $s_id = $row['schedule_id'];
                $g_id = $row['group_id'];
                $u_id = $row['user_id'];
                $q = "DELETE FROM meetings WHERE schedule_id='$s_id' AND group_id='$g_id' AND user_id='$u_id';";
                mysqli_query($con, $q);
                $q = "DELETE FROM groups WHERE schedule_id='$s_id' AND group_id='$g_id';";
                mysqli_query($con, $q);
                
            }   
        }
        header('location: manage.php');
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Poppins:wght@400;500;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        <?php include "manage.css";?>
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
                <li><a href="contact.php">Contact</a></li>
                <li><a href="logout.php" id="logout">Logout</a></li>
            </ul>
        </div>
        <button id="top-btn" class="hide" onclick="scroll_to_top()">Top</button>
        <div class="contents">
            <div class="buttons">
                <button id="user-id-btn" class="newbutton border_right" onclick="setStatus('user')">Users</button>
                <button id="media-id-btn" class="newbutton" onclick="setStatus('media')">Donations</button>
                <button id="otp-id-btn" class="newbutton" onclick="setStatus('otp')">OTP</button>
                <button id="meet-id-btn" class="newbutton" onclick="setStatus('meet')">Meetings</button>
                <button id="grp-id-btn" class="newbutton" onclick="setStatus('grp')">Groups</button>
    
            </div>
            <div class="users-information" id="users-information-id">
                <p class="header1">Users</p>
                <table>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Password</th>
                        <th>Type</th>
                    </tr>
                <?php
                $qry = "SELECT * FROM users;";
                $result = mysqli_query($con, $qry);
                while($row = mysqli_fetch_array($result)){?>
                    <tr>
                        <td><?php echo $row['id'];?></td>
                        <td><?php echo $row['username'];?></td>
                        <td><?php echo $row['email'];?></td>
                        <td><?php echo $row['mobile'];?></td>
                        <td><?php echo $row['password'];?></td>
                        <td><?php echo $row['type'];?></td>
                    </tr>
                <?php
                }?>
                </table>
                <br><br>
                <h3>Delete A User</h3>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <p class="cls">User ID:<span style="color: #f00;">*</span></p>
                    <input type="text" name="user-id" class="inp" placeholder="Enter User ID" value="<?php if($uid == 0){echo "";}else{echo $uid;}?>" required>
                    
                    <p class="cls">Your Password:<span style="color: #f00;">*</span></p>
                    <input type="password" name="ur-pass" class="inp" id="inp-pass" placeholder="Enter Your Password" value="<?php echo $urpass;?>" required>
                    <br>
                    <input type="checkbox" id="chkbox" style="margin-left: 10px; margin-top: 5px;" onclick="show_hide_pass()"><span style="font-size: 12px;"> Show Password</span>
                    <br>
                    <div class="btn">
                        <button type="submit" class="common-button" name="du-submit">Delete</button>
                    </div>
                    <br>                    
                    <p style="margin-left: 10px;"><span class="set-bold">Note:</span> Your password is required to verify that the Admin himself is trying to remove a user.</p>
                </form>
                    
            </div>
            <div class="media-information hide" id="media-information-id">
                <p class="header1">Foods</p>
                <table id="table1">
                    <tr>
                        <th>Username</th>
                        <th>Fullname</th>
                        <th>Food Name</th>
                        <th>Quantity</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Date</th>
                    </tr>
                <?php
                $qry1 = "SELECT * FROM donated_food;";
                $result = mysqli_query($con, $qry1);
                
                while($row = mysqli_fetch_array($result)){?>
                    
                    <tr>
                        <td><?php echo $row['username'];?></td>
                        <td><?php echo $row['fullname'];?></td>
                        <td><?php echo $row['food_name'];?></td>
                        <td><?php echo $row['quantity'];?></td>
                        <td><?php echo $row['mobile'];?></td>
                        <td><?php echo $row['address'];?></td>
                        <td><?php echo $row['date'];?></td>
                    </tr>
                <?php
                }?>
                </table>

                <p class="header1">Ingredients</p>
                <table id="table2">
                    <tr>
                        <th>Username</th>
                        <th>Fullname</th>
                        <th>Food Name</th>
                        <th>Quantity</th>
                        <th>Mobile</th>
                        <th>Address</th>
                        <th>Date</th>
                    </tr>
                <?php
                $qry2 = "SELECT * FROM donated_ingredient;";
                $result = mysqli_query($con, $qry2);
                while($row = mysqli_fetch_array($result)){?>
                    <tr>
                        <td><?php echo $row['username'];?></td>
                        <td><?php echo $row['fullname'];?></td>
                        <td><?php echo $row['ingredient_name'];?></td>
                        <td><?php echo $row['quantity'];?></td>
                        <td><?php echo $row['mobile'];?></td>
                        <td><?php echo $row['address'];?></td>
                        <td><?php echo $row['date'];?></td>
                    </tr>
                <?php
                }?>
                </table>

                <br> 
                <button id="use-btn" class="use-btn" onclick="remove_donation()">Delete</button>
                <br>

                <form method="post" style="margin:0 0 20px 5px;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <p style="font-family: sans-serif; margin-top:20px;">Clear the food donations posted 1 week ago and ingredient donations posted 1 month ago.</p>
                    <button type="submit" name="clear-expired" class="common-button">Clear</button>
                </form>

                <form method="post" class="hide" id="remove-donation" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <h3>Remove Donation</h3>

                    <p class="cls">Username:<span style="color: #f00;">*</span></p>
                    <input type="text" name="donor-un" class="inp" id="donor-un" placeholder="Enter Donor Username" value="<?php if($uid == 0){echo "";}else{echo $uid;}?>" required>
                    
                    <p class="cls">Food Name:<span style="color: #f00;">*</span></p>
                    <input type="text" name="food-n" class="inp" id="food-n" placeholder="Enter Food Name" value="<?php if($uid == 0){echo "";}else{echo $uid;}?>" required>
                    
                    <p class="cls">Type(Food/Ingredient):<span style="color: #f00;">*</span></p>
                    <input type="text" name="food-type" class="inp" id="food-type" placeholder="Enter Type" value="<?php if($uid == 0){echo "";}else{echo $uid;}?>" required>
                    
                    <div class="btn">
                        <button type="submit" class="common-button" name="rd-submit">Remove</button>
                    </div>
                    <br>                    
                    <p style="margin-left: 10px;"><span class="set-bold">Note:</span> Your password is required to verify that the Admin himself is trying to remove a user.</p>
                </form>
            </div>

            <!-- OTP -->
            <div class="otp-information hide" id="otp-information-id">
                <p class="header1">OTP</p>
                <table>
                    <tr>
                        <th>OTP ID</th>
                        <th>Username</th>
                        <th>Received Time</th>
                        <th>Date</th>
                    </tr>
                <?php
                $qry = "SELECT * FROM otptable;";
                $result = mysqli_query($con, $qry);
                while($row = mysqli_fetch_array($result)){?>
                    <tr>
                        <td><?php echo $row['otp_id'];?></td>
                        <td><?php echo $row['username'];?></td>
                        <td><?php echo $row['otp'];?></td>
                        <td><?php echo $row['receivedTime'];?></td>
                    </tr>
                <?php
                }?>
                </table>
                <br><br>

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <h3>Clear Expired OTP</h3>
                    <button type="submit" class="common-button" style="margin-left: 10px;" name="otp-submit">Clear</button>
                    <br>                    
                </form>
                    
            </div>
            <!-- Meeting -->
            <div class="meet-information hide" id="meet-information-id">
                <p class="header1">Meetings</p>
                <table>
                    <tr>
                        <th>Schedule ID</th>
                        <th>Group ID</th>
                        <th>User ID</th>
                        <th>Host Name</th>
                        <th>Area</th>
                        <th>Restaurant</th>
                        <th>Date</th>
                    </tr>
                <?php
                $qry = "SELECT * FROM meetings;";
                $result = mysqli_query($con, $qry);
                while($row = mysqli_fetch_array($result)){?>
                    <tr>
                        <td><?php echo $row['schedule_id'];?></td>
                        <td><?php echo $row['group_id'];?></td>
                        <td><?php echo $row['user_id'];?></td>
                        <td><?php echo $row['host_name'];?></td>
                        <td><?php echo $row['area'];?></td>
                        <td><?php echo $row['restaurant_name'];?></td>
                        <td><?php echo $row['date'];?></td>
                    </tr>
                <?php
                }?>
                </table>

                <form method="post" style="margin:0 0 20px 5px;" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <p style="font-family: sans-serif; margin-top:20px;">Clear the meeting schedules expired 24 hours ago.</p>
                    <button type="submit" name="clear-expired-meet" class="common-button">Clear</button>
                </form>
            </div>

            <div class="grp-information hide" id="grp-information-id">
                <p class="header1">Meetings Groups</p>
                <table>
                    <tr>
                        <th>User ID</th>
                        <th>Schedule ID</th>
                        <th>Group ID</th>
                    </tr>
                <?php
                $qry = "SELECT * FROM groups;";
                $result = mysqli_query($con, $qry);
                while($row = mysqli_fetch_array($result)){?>
                    <tr>
                        <td><?php echo $row['user_id'];?></td>
                        <td><?php echo $row['schedule_id'];?></td>
                        <td><?php echo $row['group_id'];?></td>
                        
                    </tr>
                <?php
                }?>
                </table>
            </div>
        </div>
    </div>



    <script type="text/javascript">
        let stat = 0;
        let del_don = 0;
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
        function show_hide_pass(){
            let doc = document.getElementById('chkbox');
            let doc2 = document.getElementById('chkbox2');
            let doc_pass = document.getElementById('inp-pass');
            let doc_pass2 = document.getElementById('inp-pass2');
            if(doc.checked){
                doc_pass.setAttribute('type', 'text');
            }
            else{
                doc_pass.setAttribute('type', 'password');
            }
            if(doc2.checked){
                doc_pass2.setAttribute('type', 'text');
            }
            else{
                doc_pass2.setAttribute('type', 'password');
            }
        }

        let table1 = document.getElementById('table1');
        let table2 = document.getElementById('table2');
        
        for(var i=0; i<table1.rows.length; i++){
            table1.rows[i].onclick = function(){
                fill_inputs(this.cells[0].innerHTML, this.cells[2].innerHTML, "Food");
            };
        }
        for(var i=0; i<table2.rows.length; i++){
            table2.rows[i].onclick = function(){
                fill_inputs(this.cells[0].innerHTML, this.cells[2].innerHTML, "Ingredient");
            };
        }
        function fill_inputs(un, fn, ty){
            if(del_don == 1){
                document.getElementById('donor-un').value = un;
                document.getElementById('food-n').value = fn;
                document.getElementById('food-type').value = ty;
            }
        }
        function remove_donation(){
            del_don = 1;
            document.getElementById("remove-donation").classList.remove('hide');
            document.getElementById("use-btn").classList.add('hide');
        }


        function setStatus(text){
            let doc_user = document.getElementById("user-id-btn");
            let doc_media = document.getElementById("media-id-btn");
            let doc_otp = document.getElementById("otp-id-btn");
            let doc_meet = document.getElementById("meet-id-btn");
            let doc_grp = document.getElementById("grp-id-btn");
            
            let doc_user_info = document.getElementById("users-information-id");
            let doc_media_info = document.getElementById("media-information-id");
            let doc_otp_info = document.getElementById("otp-information-id");
            let doc_meet_info = document.getElementById("meet-information-id");
            let doc_grp_info = document.getElementById("grp-information-id");
             
            if(text == 'user'){
                if(!doc_user.classList.contains('border_right')){
                    doc_user.classList.add('border_right');
                }
                if(doc_user_info.classList.contains('hide')){
                    doc_user_info.classList.remove('hide');
                }
            }
            else{
                if(doc_user.classList.contains('border_right')){
                    doc_user.classList.remove('border_right');
                }
                if(!doc_user_info.classList.contains('hide')){
                    doc_user_info.classList.add('hide');
                }
            }
            if(text == 'media'){
                if(!doc_media.classList.contains('border_left')){
                    doc_media.classList.add('border_left');
                }
                if(doc_media_info.classList.contains('hide')){
                    doc_media_info.classList.remove('hide');
                }
            }
            else{
                if(doc_media.classList.contains('border_left')){
                    doc_media.classList.remove('border_left');
                }
                if(!doc_media_info.classList.contains('hide')){
                    doc_media_info.classList.add('hide');
                }
            }
            if(text == 'otp'){
                if(!doc_otp.classList.contains('border_left')){
                    doc_otp.classList.add('border_left');
                }
                if(doc_otp_info.classList.contains('hide')){
                    doc_otp_info.classList.remove('hide');
                }
            }
            else{
                if(doc_otp.classList.contains('border_left')){
                    doc_otp.classList.remove('border_left');
                }
                if(!doc_otp_info.classList.contains('hide')){
                    doc_otp_info.classList.add('hide');
                }
            }
            if(text == 'meet'){
                if(!doc_meet.classList.contains('border_left')){
                    doc_meet.classList.add('border_left');
                }
                if(doc_meet_info.classList.contains('hide')){
                    doc_meet_info.classList.remove('hide');
                }
            }
            else{
                if(doc_meet.classList.contains('border_left')){
                    doc_meet.classList.remove('border_left');
                }
                if(!doc_meet_info.classList.contains('hide')){
                    doc_meet_info.classList.add('hide');
                }
            }
            if(text == 'grp'){
                if(!doc_grp.classList.contains('border_left')){
                    doc_grp.classList.add('border_left');
                }
                if(doc_grp_info.classList.contains('hide')){
                    doc_grp_info.classList.remove('hide');
                }
            }
            else{
                if(doc_grp.classList.contains('border_left')){
                    doc_grp.classList.remove('border_left');
                }
                if(!doc_grp_info.classList.contains('hide')){
                    doc_grp_info.classList.add('hide');
                }
            }
            

        }
    </script>
</body>
</html>