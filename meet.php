<?php
    session_start();
    $uname = "";
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
	$search_status = "";
    
    $q = "SELECT id FROM users WHERE username='$uname';";
    $result = mysqli_query($con, $q);
    $row = mysqli_fetch_array($result);
    $id = $row['id'];

    $_SESSION['username'] = $uname;

    $error = false;
    $error_msg = "";
    $err_name = "";
    if(isset($_POST['new_sch_sbumit'])){
        $ur_name = $_POST['your-name'];
        $selected_date = $_POST['select-time'];
        $selected_area = $_POST['area'];
        $rstrnt_name = $_POST['restaurnat-name'];
        
        if($ur_name != test_input($ur_name)){
            $error = true;
            $error_msg = "Invalid Your Name Input";
        }
        else if($selected_date != test_input($selected_date)){
            $error = true;
            $error_msg = "Invalid Date Input";
        }
        else if($selected_area != test_input($selected_area) || $selected_area == ""){
            $error = true;
            $error_msg = "Invalid Area Input";
        }
        else if($rstrnt_name != test_input($rstrnt_name)){
            $error = true;
            $error_msg = "Invalid Restaurant Name Input";
        }
        else{
            $error = false;
            $group_id = rand();
            $count = 0;
            $query = "SELECT * FROM meetings WHERE group_id = '$group_id';";
            $result = mysqli_query($con, $query);
            $count = mysqli_num_rows($result);
            while($count > 0){
                $group_id = rand();
                $query = "SELECT * FROM meetings WHERE group_id = '$group_id';";
                $result = mysqli_query($con, $query);
                $count = mysqli_num_rows($result);            
            }
            
            $query = "INSERT INTO `meetings` (`group_id`, `user_id`, `host_name`, `area`, `restaurant_name`, `date`)".
                        "VALUES ('$group_id', '$id', '$ur_name', '$selected_area', '$rstrnt_name', '$selected_date');";
            mysqli_query($con, $query);
        }
        if($error){
            $err_name = "new_schedule";
        }
    }

    if(isset($_POST['join_si']) && isset($_POST['join_gi'])){
        $s_id = $_POST['join_si'];
        $g_id = $_POST['join_gi'];
        $u_id = $id;
        $q = "INSERT INTO `groups`(`user_id`, `schedule_id`, `group_id`) VALUES('$u_id', '$s_id', '$g_id');";
        mysqli_query($con, $q);
    }
    if(isset($_POST['leave_si']) && isset($_POST['leave_gi'])){
        $s_id = $_POST['leave_si'];
        $g_id = $_POST['leave_gi'];
        $u_id = $id;
        $q = "DELETE FROM `groups` WHERE `user_id`='$u_id' AND `schedule_id`='$s_id' AND `group_id`='$g_id';";
        mysqli_query($con, $q);
    }
    if(isset($_POST['delete_si']) && isset($_POST['delete_gi'])){
        $s_id = $_POST['delete_si'];
        $g_id = $_POST['delete_gi'];
        $u_id = $id;
        $q = "DELETE FROM `groups` WHERE `schedule_id`='$s_id' AND `group_id`='$g_id';";
        mysqli_query($con, $q);
        $q = "DELETE FROM `meetings` WHERE `user_id`='$u_id' AND `schedule_id`='$s_id' AND `group_id`='$g_id';";
        mysqli_query($con, $q);
        
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
    <title>Meet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Charm:wght@400;700&family=Lobster&family=Montserrat:wght@400;700&family=Poppins:wght@400;500;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        <?php include "meet.css";?>
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-bar" id="nav_bar">
            <div class="logo"><h1>FoodShare</h1></div>
            <ul class="links">
                <li><a href="<?php if(isset($_COOKIE['foodshare_admin_remember_username']) || $_SESSION['type'] == 'Admin'){echo 'admin-home.php';}else{echo 'home.php';}?>">Home</a></li>
                <li><a href="donate.php">Donate</a></li>
                <li><a href="meet.php" class="set-bold">Meet</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="logout.php" id="logout">Logout</a></li>
            </ul>
        </div>
        <button id="top-btn" class="hide" onclick="scroll_to_top()">Top</button>
        <div class="contents">
            <p class="header-text">MEET</p>
            <div class="texts" style="text-align: center;">
                <span class="one">Smart scheduling Collaboration</span> <br>
                <span class="two">FoodShare lets you and your friends select date to host a meeting.</span>
                <br>
                <span class="three">Start Your Plan Now</span>
            </div>
            <div class="buttons">
                <button id="new_sch" onclick="setSchedule('new_sch')">Start New Schedule</button>
                <button id="available_sch" onclick="setSchedule('available_sch')">Available Schedule</button>
            </div>
            <div class="new_schedule <?php if($err_name!='new_schedule'){echo 'hide';}?>" id="new_schedule">
                <form action="" method="post">
                    <h3>Set A Schedule</h3>
                    <p class="cls">Your Name:<span style="color: #f00;">*</span></p>
                    <input type="text" name="your-name" placeholder="Enter your name" required>

                    <p class="cls">Select Date:<span style="color: #f00;">*</span></p>
                    <input type="datetime-local" name="select-time" placeholder="Enter your name" required>

                    <p class="cls">Select Area:<span style="color: #f00;">*</span></p>
                    <select name="area" id="area">
                        <option value='Barisal/Amtali'>Barisal/Amtali</option>
                        <option value='Barisal/Bakerganj'>Barisal/Bakerganj</option>
                        <option value='Barisal/Barguna'>Barisal/Barguna</option>
                        <option value='Barisal/Barisal'>Barisal/Barisal</option>
                        <option value='Barisal/Bhola'>Barisal/Bhola</option>
                        <option value='Barisal/Char Fasson'>Barisal/Char Fasson</option>
                        <option value='Barisal/Gournadi'>Barisal/Gournadi</option>
                        <option value='Barisal/Jhalokati'>Barisal/Jhalokati</option>
                        <option value='Barisal/Kuakata'>Barisal/Kuakata</option>
                        <option value='Barisal/Patuakhali'>Barisal/Patuakhali</option>
                        <option value='Barisal/Pirojpur'>Barisal/Pirojpur</option>
                        <option value='Barisal/Swarupkati'>Barisal/Swarupkati</option>
                        <option value='Chittagong/Bandarban'>Chittagong/Bandarban</option>
                        <option value='Chittagong/Chhagalnaiya'>Chittagong/Chhagalnaiya</option>
                        <option value='Chittagong/Chittagong'>Chittagong/Chittagong</option>
                        <option value='Chittagong/Daganbhuiyan'>Chittagong/Daganbhuiyan</option>
                        <option value='Chittagong/Hathazari'>Chittagong/Hathazari</option>
                        <option value='Chittagong/Khagrachhari'>Chittagong/Khagrachhari</option>
                        <option value='Chittagong/Mirsharai'>Chittagong/Mirsharai</option>
                        <option value='Chittagong/Parshuram'>Chittagong/Parshuram</option>
                        <option value='Chittagong/Patiya'>Chittagong/Patiya</option>
                        <option value='Chittagong/Rangamati'>Chittagong/Rangamati</option>
                        <option value='Chittagong/Raozan'>Chittagong/Raozan</option>
                        <option value='Chittagong/Sitakunda'>Chittagong/Sitakunda</option>
                        <option value='Chittagong/Sonagazi'>Chittagong/Sonagazi</option>
                        <option value='Dhaka/Aricha'>Dhaka/Aricha</option>
                        <option value='Dhaka/Baliakandi'>Dhaka/Baliakandi</option>
                        <option value='Dhaka/Dhaka' selected>Dhaka/Dhaka</option>
                        <option value='Dhaka/Ghorashal'>Dhaka/Ghorashal</option>
                        <option value='Dhaka/Gopalganj'>Dhaka/Gopalganj</option>
                        <option value='Dhaka/Gopalpur'>Dhaka/Gopalpur</option>
                        <option value='Dhaka/Madaripur'>Dhaka/Madaripur</option>
                        <option value='Dhaka/Madhabdi'>Dhaka/Madhabdi</option>
                        <option value='Dhaka/Madhupur'>Dhaka/Madhupur</option>
                        <option value='Dhaka/Manikganj'>Dhaka/Manikganj</option>
                        <option value='Dhaka/Mirzapur'>Dhaka/Mirzapur</option>
                        <option value='Dhaka/Monohardi'>Dhaka/Monohardi</option>
                        <option value='Dhaka/Munshiganj'>Dhaka/Munshiganj</option>
                        <option value='Dhaka/Rajbari'>Dhaka/Rajbari</option>
                        <option value='Dhaka/Shariatpur'>Dhaka/Shariatpur</option>
                        <option value='Khulna/Bagherhat'>Khulna/Bagherhat</option>
                        <option value='Khulna/Chuadanga'>Khulna/Chuadanga</option>
                        <option value='Khulna/Chuadanga'>Khulna/Chuadanga</option>
                        <option value='Khulna/Darshana'>Khulna/Darshana</option>
                        <option value='Khulna/Jhenaidah'>Khulna/Jhenaidah</option>
                        <option value='Khulna/Kaliganj'>Khulna/Kaliganj</option>
                        <option value='Khulna/Khulna'>Khulna/Khulna</option>
                        <option value='Khulna/Magura'>Khulna/Magura</option>
                        <option value='Khulna/Meherpur'>Khulna/Meherpur</option>
                        <option value='Khulna/Narail'>Khulna/Narail</option>
                        <option value='Khulna/Shatkhira'>Khulna/Shatkhira</option>
                        <option value='Mymensingh/Bhaluka'>Mymensingh/Bhaluka</option>
                        <option value='Mymensingh/Fulbaria'>Mymensingh/Fulbaria</option>
                        <option value='Mymensingh/Gouripur'>Mymensingh/Gouripur</option>
                        <option value='Mymensingh/Muktagachha'>Mymensingh/Muktagachha</option>
                        <option value='Mymensingh/Mymensingh'>Mymensingh/Mymensingh</option>
                        <option value='Mymensingh/Netrokona'>Mymensingh/Netrokona</option>
                        <option value='Mymensingh/Phulpur'>Mymensingh/Phulpur</option>
                        <option value='Mymensingh/Shambhuganj'>Mymensingh/Shambhuganj</option>
                        <option value='Mymensingh/Sherpur'>Mymensingh/Sherpur</option>
                        <option value='Rajshahi/Akkelpur'>Rajshahi/Akkelpur</option>
                        <option value='Rajshahi/Joypurhat'>Rajshahi/Joypurhat</option>
                        <option value='Rajshahi/Kalai'>Rajshahi/Kalai</option>
                        <option value='Rajshahi/Khetlal'>Rajshahi/Khetlal</option>
                        <option value='Rajshahi/Mundumala'>Rajshahi/Mundumala</option>
                        <option value='Rajshahi/Naogaon'>Rajshahi/Naogaon</option>
                        <option value='Rajshahi/Natore'>Rajshahi/Natore</option>
                        <option value='Rajshahi/Panchbibi'>Rajshahi/Panchbibi</option>
                        <option value='Rajshahi/Rahanpur'>Rajshahi/Rahanpur</option>
                        <option value='Rajshahi/Rajshahi'>Rajshahi/Rajshahi</option>
                        <option value='Rangpur/Gaibandha'>Rangpur/Gaibandha</option>
                        <option value='Rangpur/Kurigram'>Rangpur/Kurigram</option>
                        <option value='Rangpur/Lalmonirhat'>Rangpur/Lalmonirhat</option>
                        <option value='Rangpur/Nageshwari'>Rangpur/Nageshwari</option>
                        <option value='Rangpur/Nilphamari'>Rangpur/Nilphamari</option>
                        <option value='Rangpur/Panchagarh'>Rangpur/Panchagarh</option>
                        <option value='Rangpur/Parbatipur'>Rangpur/Parbatipur</option>
                        <option value='Rangpur/Rangpur'>Rangpur/Rangpur</option>
                        <option value='Rangpur/Saidpur'>Rangpur/Saidpur</option>
                        <option value='Rangpur/Thakurgaon'>Rangpur/Thakurgaon</option>
                        <option value='Rangpur/Ulipur'>Rangpur/Ulipur</option>
                        <option value='Sylhet/Barlekha'>Sylhet/Barlekha</option>
                        <option value='Sylhet/Beanibazar'>Sylhet/Beanibazar</option>
                        <option value='Sylhet/Chhatak'>Sylhet/Chhatak</option>
                        <option value='Sylhet/Habiganj'>Sylhet/Habiganj</option>
                        <option value='Sylhet/Kulaura'>Sylhet/Kulaura</option>
                        <option value='Sylhet/Maulvibazar'>Sylhet/Maulvibazar</option>
                        <option value='Sylhet/Maulvibazar,Sreemangal'>Sylhet/Maulvibazar,Sreemangal</option>
                        <option value='Sylhet/Osmaninogor'>Sylhet/Osmaninogor</option>
                        <option value='Sylhet/Sunamganj'>Sylhet/Sunamganj</option>
                        <option value='Sylhet/Sylhet'>Sylhet/Sylhet</option>
                        <option value='Sylhet/Zakiganj'>Sylhet/Zakiganj</option>
                    </select>

                    <p class="cls">Restaurant Name:<span style="color: #f00;">*</span></p>
                    <input type="text" name="restaurnat-name" placeholder="Enter restaurant name" required>
                    
                    <p class="errorBx"><?php if($error){echo $error_msg;}?></p>
                    
                    <button type="submit" name="new_sch_sbumit">Post Meeting</button>
                </form>
            </div>
            <div class="available_schedule hide" id="available_schedule">
                <div class="table" id='table'>
                    <?php
                    $qry = "SELECT * FROM meetings;";
                    $result = mysqli_query($con, $qry);
                    while($row = mysqli_fetch_array($result))
                    {?>
                        <div class="box" style="<?php if($id == $row['user_id']){echo 'background-color: #2B9DE7;';}?>">
                            <p class="meeting-head">Meeting</p><br>
                            <p class='meeting-body'>
                            Host Name: <?php echo $row['host_name'];?><br>
                            Area: <?php echo $row['area'];?><br>
                            Restaurant: <?php echo $row['restaurant_name'];?><br>
                            Date: <?php echo $row['date'];?> <br>
                            <div class="custom">
                            <?php if($id == $row['user_id']){?>
                                <button class="custom-button" name = "delete-btn" id="delete-btn" style="background-color: #E91A01;" onclick="deleteGroup(<?php echo $row['schedule_id'];?>,<?php echo $row['group_id'];?>)">Delete</button>
                                <?php }
                                else{
                                    $s_i = $row['schedule_id'];
                                    $g_i = $row['group_id'];
                                    $qr = "SELECT * FROM `groups` WHERE `user_id`=$id AND `schedule_id`='$s_i' AND `group_id`='$g_i';";
                                    $res = mysqli_query($con, $qr);
                                    $cnt = mysqli_num_rows($res);
                                    if($cnt > 0){
                                    ?>
                                        <button class="custom-button" style="background-color:#33C81C;" name="left-btn" id="left-btn" onclick="leaveGroup(<?php echo $s_i;?>,<?php echo $g_i;?>)">Leave</button>
                                    <?php
                                    }
                                    else{
                                    ?>
                                    <button class="custom-button" name = "join-btn" id="join-btn" onclick="joinGroup(<?php echo $s_i;?>,<?php echo $g_i;?>)">Join</button>
                            <?php }
                            }
                            ?>
                            </div>
                            </p>
                        </div>
                    <?php
                    }
                    ?>
                </div>
                <div class="search-head">
                    <form action="" method="post" id='searchform'>
                        <input type="text" name="search-field" id="search-field">
                        <button id=search-btn onclick="searchMeet('searchform')">Search</button>
                    </form>
                </div>
                <div class="search-bar">
                    
                    <?php 
                        if(isset($_POST['search-field'])){
                            $val = $_POST['search-field'];
                            if($val != test_input($val)){
                                echo "<script>alert('Invalid Input Entered')</script>";
                            }
                            $qry = "SELECT * FROM meetings WHERE area='$val';";
                            $result = mysqli_query($con, $qry);
                            while($row = mysqli_fetch_array($result))
                            {?>
                                <div class="box" style="<?php if($id == $row['user_id']){echo 'background-color: #2B9DE7;';}?>">
                                    <p class="meeting-head">Meeting</p><br>
                                    <p class='meeting-body'>
                                    Host Name: <?php echo $row['host_name'];?><br>
                                    Area: <?php echo $row['area'];?><br>
                                    Restaurant: <?php echo $row['restaurant_name'];?><br>
                                    Date: <?php echo $row['date'];?> <br>
                                    <div class="custom">
                                    <?php if($id == $row['user_id']){?>
                                        <button class="custom-button" name = "delete-btn" id="delete-btn" style="background-color: #E91A01;" onclick="deleteGroup(<?php echo $row['schedule_id'];?>,<?php echo $row['group_id'];?>)">Delete</button>
                                        <?php }
                                        else{
                                            $s_i = $row['schedule_id'];
                                            $g_i = $row['group_id'];
                                            $qr = "SELECT * FROM `groups` WHERE `user_id`=$id AND `schedule_id`='$s_i' AND `group_id`='$g_i';";
                                            $res = mysqli_query($con, $qr);
                                            $cnt = mysqli_num_rows($res);
                                            if($cnt > 0){
                                            ?>
                                                <button class="custom-button" style="background-color:#33C81C;" name="left-btn" id="left-btn" onclick="leaveGroup(<?php echo $s_i;?>,<?php echo $g_i;?>)">Leave</button>
                                            <?php
                                            }
                                            else{
                                            ?>
                                            <button class="custom-button" name = "join-btn" id="join-btn" onclick="joinGroup(<?php echo $s_i;?>,<?php echo $g_i;?>)">Join</button>
                                    <?php }
                                    }
                                    ?>
                                    </div>
                                    </p>
                                </div>
                            <?php
                            }
                            ?>
                            <?php
                        }
                    ?>
                </div>        
            </div>
            <form action="" method="post" id="joinform">
                <input type="hidden" name="join_si" id="join-inp1">
                <input type="hidden" name="join_gi" id="join-inp2">
            </form>
            <form action="" method="post" id="deleteform">
                <input type="hidden" name="delete_si" id="delete-inp1">
                <input type="hidden" name="delete_gi" id="delete-inp2">
            </form>
            <form action="" method="post" id="leaveform">
                <input type="hidden" name="leave_si" id="leave-inp1">
                <input type="hidden" name="leave_gi" id="leave-inp2">
            </form>
        </div>
        <br><br>
        <p class="hide" id="note"><span style="font-weight: bold;">Note:</span>
        Meeting Schedules which are expired 24 hours ago will be removed.</p>        
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
        function setSchedule(id){
            let doc_1 = document.getElementById('new_schedule');
            let doc_2 = document.getElementById('available_schedule');
            let note = document.getElementById('note');
            if(note.classList.contains('hide')){
                note.classList.remove('hide');
            }
            if(id == 'new_sch'){
                if(doc_1.classList.contains('hide')){
                    doc_1.classList.remove('hide');
                }
                if(!doc_2.classList.contains('hide')){
                    doc_2.classList.add('hide');
                }
            }
            else if(id == 'available_sch'){
                if(!doc_1.classList.contains('hide')){
                    doc_1.classList.add('hide');
                }
                if(doc_2.classList.contains('hide')){
                    doc_2.classList.remove('hide');
                }
            }
             
        }
        let area_name = "";
        function joinGroup(s_id, g_id){
            document.getElementById('join-inp1').value = s_id;
            document.getElementById('join-inp2').value = g_id;
            document.getElementById('joinform').submit();
        }
        function deleteGroup(s_id, g_id){
            document.getElementById('delete-inp1').value = s_id;
            document.getElementById('delete-inp2').value = g_id;
            document.getElementById('deleteform').submit();
        }
        function leaveGroup(s_id, g_id){
            document.getElementById('leave-inp1').value = s_id;
            document.getElementById('leave-inp2').value = g_id;
            document.getElementById('leaveform').submit();
        }
        function searchMeet(id){
            document.getElementById(id).submit();
        } 
    </script>
</body>
</html>