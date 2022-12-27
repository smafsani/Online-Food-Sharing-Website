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
    $name = $food_name = $quantity = $mobile = $addr = $err_msg = "";
    $name_i = $ing_name = $ing_quantity = $ing_mobile = $ing_addr = "";
 
    $error = false;
    $formName = "";
    $username_to = $uname;
    $_SESSION['username'] = $username_to;
 
    if(isset($_POST['submit-food'])){
        $name = $_POST['name'];
        $food_name = $_POST['food-name'];
        $quantity = $_POST['quantity'];
        $mobile = $_POST['mobile'];
        $addr = $_POST['address'];
        $formName = "food";
        if($name != test_input($name)){
            $error = true;
            $err_msg = "Invalid name input";
        }
        else if($food_name != test_input($food_name)){
            $error = true;
            $err_msg = "Invalid food name input";
        }
        else if($quantity != test_input($quantity)){
            $error = true;
            $err_msg = "Invalid quantity input";
        }
        else if($mobile != test_input($mobile) || strlen($mobile)!=11 || !is_numeric($mobile)){
            $error = true;
            $err_msg = "Invalid mobile input";
        }
        else if($addr != test_input($addr)){
            $error = true;
            $err_msg = "Invalid address input";
        }
        else if($error == false){
            $date = new DateTime("now", new DateTimeZone('Asia/Dhaka') );
            $dateApplied = $date->format('Y-m-d H:i:s');
            $sql = "INSERT INTO `donated_food` (`username`, `fullname`, `food_name`, `quantity`, `mobile`, `address`, `date`) VALUES ('$username_to', '$name', '$food_name', '$quantity', '$mobile', '$addr', '$dateApplied');";
            mysqli_query($con, $sql);
            $name = $food_name = $quantity = $mobile = $addr = $err_msg = "";
        }
        
    }

    if(isset($_POST['submit-ingredient'])){
        $name_i = $_POST['name_i'];
        $ing_name = $_POST['ing-name'];
        $ing_quantity = $_POST['ing-quantity'];
        $ing_mobile = $_POST['ing-mobile'];
        $ing_addr = $_POST['ing-address'];
        $formName = 'ingredient';

        if($name_i != test_input($name_i)){
            $error = true;
            $err_msg = "Invalid name input";
        }
        else if($ing_name != test_input($ing_name)){
            $error = true;
            $err_msg = "Invalid ingredient name input";
        }
        else if($ing_quantity != test_input($ing_quantity)){
            $error = true;
            $err_msg = "Invalid quantity input";
        }
        else if($ing_mobile != test_input($ing_mobile) || strlen($ing_mobile)!=11 || !is_numeric($ing_mobile)){
            $error = true;
            $err_msg = "Invalid mobile input";
        }
        else if($ing_addr != test_input($ing_addr)){
            $error = true;
            $err_msg = "Invalid address input";
        }
        else if($error == false){
            $date = new DateTime("now", new DateTimeZone('Asia/Dhaka') );
            $dateApplied = $date->format('Y-m-d H:i:s');
            $sql = "INSERT INTO `donated_ingredient` (`username`, `fullname`, `ingredient_name`, `quantity`, `mobile`, `address`, `date`) VALUES ('$uname', '$name_i', '$ing_name', '$ing_quantity', '$ing_mobile', '$ing_addr', '$dateApplied');";
            mysqli_query($con, $sql);
            $name_i = $ing_name = $ing_quantity = $ing_mobile = $ing_addr = "";
        }
    }

    else if(isset($_POST['erase'])){
        $un = $_POST['name-era'];
        $fn = $_POST['fi-name'];
        if($un != test_input($un)){    
            echo "<script>alert('Invalid Name Input.');</script>";
        }
        else if($fn != test_input($fn)){    
            echo "<script>alert('Invalid Food Or Ingredient Name Input.');</script>";
        }
        else if(!isset($_POST['checkB1']) && !isset($_POST['checkB2'])){    
            echo "<script>alert('Please Check A Type.');</script>";
        }
        else{
            $q = "";
            if(isset($_POST['checkB1'])){
                $q = "DELETE FROM donated_food WHERE username='$un' AND food_name='$fn';";
            }
            else if(isset($_POST['checkB2'])){
                $q = "DELETE FROM donated_ingredient WHERE username='$un' AND ingredient_name='$fn';";
            }
            if($q != ""){
                mysqli_query($con, $q);
            }
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
    <title>Donate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Lobster&family=Montserrat:wght@400;700&family=Poppins:wght@400;500;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        <?php
            include "donate.css";
        ?>
    </style>
</head>
<body>
    <div class="container">
        <div class="nav-bar" id="nav_bar">
            <div class="logo"><h1>FoodShare</h1></div>
            <ul class="links">
                <li><a href="<?php if(isset($_COOKIE['foodshare_admin_remember_username']) || $_SESSION['type'] == 'Admin'){echo 'admin-home.php';}else{echo 'home.php';}?>">Home</a></li>
                <li><a href="donate.php" class="set-bold">Donate</a></li>
                <li><a href="meet.php">Meet</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="logout.php" id="logout">Logout</a></li>
            </ul>
        </div>
        <button id="top-btn" class="hide" onclick="scroll_to_top()">Top</button>
        <div class="content">
            <p class="text-line">Donate Your Excess Food.</p>
            <p class="text-line2">"Food Share provides the efficient way to donate your excess foods.
               <br> Your donation will help families across the country."                
            </p>
            <p class="text-line3">
                We accept donations of all kinds of foods from anyone who wants to donate his/her excess foods.
                We also accept donations of groceries from donors.
                We also partner with food companies who donate ingredients and services to help people. 
                If you’re interested in learning more check out our about section.
                <br><br>
                Why not help people?
            </p>
            <div class="buttons">
                <button onclick="displayCard('donate-food')">Donate Foods</button>
                <button onclick="displayCard('donate-ing')">Donate Ingredients</button>
            </div>
            <div class="donate-food hide" id="donate-food">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <h3 style="color: var(--color1);">Donate Food</h3>
                    <p class="cls">Name:<span style="color: #f00;">*</span></p>
                    <input type="text" name="name" class="cls" placeholder="Enter Your Name" value="<?php echo $name;?>" required>

                    <p class="cls">Food Name:<span style="color: #f00;">*</span></p>
                    <input type="text" name="food-name" class="cls" placeholder="Enter Food Name" value="<?php echo $food_name;?>" required>

                    <p class="cls">Quantity:<span style="color: #f00;">*</span></p>
                    <input type="text" name="quantity" class="cls" placeholder="Enter Food Quantity" value="<?php echo $quantity;?>" required>
                    
                    <p class="cls">Mobile:<span style="color: #f00;">*</span></p>
                    <input type="text" name="mobile" class="cls" placeholder="01*********" value="<?php echo $mobile;?>" required>

                    <p class="cls">Address:<span style="color: #f00;">*</span></p>
                    <textarea name="address" cols="30" rows="4" class="cls" placeholder="Enter Your Address" required><?php if($addr != ""){echo $addr;}?></textarea>
                    <p class="err-class"><?php if($error == true) {echo($err_msg);}?></p>
                    
                    <button type="submit" name="submit-food" class="common-button" onclick="setStatus('donate-food')">Donate</button>
                </form>
            </div>

            <div class="donate-ing hide" id="donate-ing">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <h3 style="color: var(--color1);">Donate Ingredient</h3>
                    <p class="cls">Name:<span style="color: #f00;">*</span></p>
                    <input type="text" name="name_i" class="cls" placeholder="Enter Your Name" value="<?php echo $name_i;?>" required>
            
                    <p class="cls">Ingredient Name:<span style="color: #f00;">*</span></p>
                    <input type="text" name="ing-name" class="cls" placeholder="Enter Ingredient Name" value="<?php echo $ing_name;?>" required>

                    <p class="cls">Quantity:<span style="color: #f00;">*</span></p>
                    <input type="text" name="ing-quantity" class="cls" placeholder="Enter Ingredient Quantity" value="<?php echo $ing_quantity;?>" required>

                    <p class="cls">Mobile:<span style="color: #f00;">*</span></p>
                    <input type="text" name="ing-mobile" class="cls" placeholder="01*********" value="<?php echo $ing_mobile;?>" required>

                    <p class="cls">Address:<span style="color: #f00;">*</span></p>
                    <textarea name="ing-address" cols="30" rows="4" class="cls" placeholder="Enter Your Address"><?php if($ing_addr != ""){echo $ing_addr;}?></textarea>
                    <p class="err-class"><?php if($error == true) {echo($err_msg);}?></p>
                    <button type="submit" class="common-button" name="submit-ingredient">Donate</button>
                </form>
            </div>

            <p style="margin-top: 40px" class="checkBtn">Check your current donations here.<button type="submit" name="check" onclick="show_donations()">Donation</button></p>
            <div class="donations hide" id="donations_id">
                <h2 style="text-align:center; color: var(--color1);">Your Donations</h2>
                <p style="font-family: sans-serif;margin: 10px;">Note: All your donations of food that you posted 1 week ago and donations of ingredient that you posted 1 month ago will be removed by FoodShare.</p>
                <div class="card">
                    <table>
                        <?php
                        if($uname != ""){
                            $qry = "SELECT * FROM donated_food WHERE username='$uname'";
                            $result = mysqli_query($con, $qry);
                            while($rows = mysqli_fetch_array($result)){
                        ?>
                            <tr>
                                <td><?php echo "Your Name: ", $rows['fullname'];?> <span>Type: Food</span><br>
                                    <?php echo "Food Name: ", $rows['food_name'];?>
                                    <span><?php echo "Quantity: ", $rows['quantity'];?></span><br>
                                    <?php echo "Mobile: ", $rows['mobile'];?><br>
                                    <?php echo "Date: ", $rows['date'];?><br>
                                    <?php echo "Address: ";?><address><?php echo $rows['address'];?></address>
                                </td>
                            </tr>
                        <?php 
                            }
                            $qry = "SELECT * FROM donated_ingredient WHERE username='$uname'";
                            $result = mysqli_query($con, $qry);
                            while($rows = mysqli_fetch_array($result)){
                        ?>
                            <tr>
                                <td><?php echo "Your Name: ", $rows['fullname'];?> <span>Type: Ingredient</span><br>
                                    <?php echo "Food Name: ", $rows['ingredient_name'];?>
                                    <span><?php echo "Quantity: ", $rows['quantity'];?></span><br>
                                    <?php echo "Mobile: ", $rows['mobile'];?><br>
                                    <?php echo "Date: ", $rows['date'];?><br>
                                    <?php echo "Address: ";?><address><?php echo $rows['address'];?></address>
                                </td>
                            </tr>
                        <?php 
                            }
                        }
                        ?>
                    </table>
                </div>
                <div class="delete-donation">
                    <h2 style="text-align:center; color: var(--color1);">Delete Donation</h2>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <p class="cls inp">Username:<span style="color: #f00;">*</span></p>
                        <input type="text" name="name-era" class="inp" placeholder="Enter Your Username" required>
            
                        <p class="cls">Food/Ingredient Name:<span style="color: #f00;">*</span></p>
                        <input type="text" name="fi-name" class="inp" placeholder="Enter Name" required>
                        
                        <p class="cls">Type:<span style="color: #f00;">*</span></p>
                        <input type="checkbox" id="chk1" name="checkB1" onclick="checkType('chk1')">Food
                        <input style="margin-left: 15px;" type="checkbox" id="chk2" name="checkB2" onclick="checkType('chk2')">Ingredient    
                        <br>
                        <button type="submit" class="common-button" name="erase">Erase</button>
                    </form>
                </div>
            </div>
        </div>
        <br><br>
        
    </div>



    <script type="text/javascript">
        let stat = 0;
        let btn_name = "";
        let err = '<?php echo $error;?>';
        let fn = '<?php echo $formName;?>';
        
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
        if(err == true){
            if(fn == 'food'){
                let doc = document.getElementById("donate-food");
                if(doc.classList.contains('hide')){
                    doc.classList.remove('hide');
                }
            }
            if(fn == 'ingredient'){
                let doc = document.getElementById('donate-ing');
                if(doc.classList.contains('hide')){
                    doc.classList.remove('hide');
                }
            }
        }
        function displayCard(id){
            let doc = document.getElementById(id);
            if(doc.classList.contains('hide')){
                doc.classList.remove('hide');
            }
            else{
                doc.classList.add('hide');
            }
            if(id == 'donate-food'){
                let doc2 = document.getElementById('donate-ing');
                if(!doc2.classList.contains('hide')){
                    doc2.classList.add('hide')
                }
            }
            else if(id == 'donate-ing'){
                let doc2 = document.getElementById('donate-food');
                if(!doc2.classList.contains('hide')){
                    doc2.classList.add('hide')
                }
            }
        }
        function show_donations(){
            let docu = document.getElementById("donations_id");
            if(docu.classList.contains('hide')){
                docu.classList.remove('hide');
            }
        }
        function scroll_to_top(){
            document.documentElement.scrollTop = 0;
            window.pageYOffset = 0;
            document.body.scrollTop = 0;
        }
        document.getElementById('chk1').checked=true; 
        function checkType(id){
            let doc1 = document.getElementById('chk1');
            let doc2 = document.getElementById('chk2');
            if(id == "chk1"){
                if(doc2.checked){
                    doc2.checked = false;
                }
            }
            else if(id == "chk2"){
                if(doc1.checked){
                    doc1.checked = false;
                }
            }
        }
        // let table_elements= document.getElementsByTagName('td');
        // for(var i=0; i<table_elements.length;i++)
        // {
        //     (table_elements)[i].addEventListener("click", function(){
        //         console.log(this.innerHTML);
        //     });
        // }
    </script>
</body>
</html>