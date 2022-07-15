<?php
session_start();
if(isset($_GET['logout'])){
  if(isset($_SESSION['user'])) unset($_SESSION['user']);
  if(isset($_SESSION['sad'])) unset($_SESSION['sad']);
  if(isset($_SESSION['admin'])) unset($_SESSION['admin']);
  session_destroy();
}

if(isset($_POST['Login'])) { 
$_GET['menu']=$_POST['menu'];
$name = $_POST['Username'];
$pass = $_POST['Password'];
$db="restaurant";
$dpass="";
$server="localhost";
$user="root";
$mysqli=new mysqli($server,$user,$dpass,$db);
if($mysqli->connect_error){ 
    exit('Error connecting to database'); 
  }
$sql="SELECT * FROM `customer` WHERE `USERNAME`='$name' and `PASSWORD`='$pass'";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$username = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
if(count($username) == 1){
  foreach($username as $key){
    $_SESSION['user']=$key['CUSTID'];
  }
}
if(count($username) == 0){
    $sql="SELECT * FROM `admin` WHERE `USERNAME`='$name' and `PASSWORD`='$pass'";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    $username = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    if(count($username) == 1){
    foreach($username as $key){
      $_SESSION['admin']=$key['ADID'];
      }}
    if(count($username) == 0){
      $sql="SELECT * FROM `sad` WHERE `USERNAME`='$name' and `PASSWORD`='$pass'";
      $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        $username = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if(count($username) == 1){
          foreach($username as $key){
            $_SESSION['sad']=$key['SADID'];
          }}
        }
        }
}
if(isset($_POST["submit"])) {
  
  $_GET['menu']=$_POST['menu'];
  $target_dir = "image/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    $uploadOk = 1;
  } else {
    $m= "File is not an image.";
    $s.='<script>alert("'.$m.'")</script>';
    echo $s;
    $uploadOk = 0;
  }


  // Check if file already exists
  if (file_exists($target_file)) {
  $m= "Sorry, file already exists.";
  
  $s.='<script>alert("'.$m.'")</script>';
  echo $s;
  $uploadOk = 0;
  }

  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 5000000) {
 $m="Sorry, your file is too large.";
 
 $s.='<script>alert("'.$m.'")</script>';
 echo $s;
  $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "jpg") {
  $m= "Sorry, only JPG files are allowed.";
  $s.='<script>alert("'.$m.'")</script>';
 echo $s;
  $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
  $m= "Sorry, your file was not uploaded.";
  $s.='<script>alert("'.$m.'")</script>';
 echo $s;
  // if everything is ok, try to upload file
  } else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $mysqli = new mysqli('localhost','root','','restaurant');
    if($mysqli->connect_error) exit('error connecting to db');
    $sql="SELECT MAX(ITEMID) as max FROM item";
    $stmt=$mysqli->prepare($sql);
    $stmt->execute();
    $max=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $max=$max[0]['max'] +1;
    $ratings=0;
    $rate=0;
    $x = strpos($target_file, ".jpg", $offset = 0);
    $target_file = substr($target_file,0,$x);
    if($_POST['disper']==0){
    $sql="INSERT INTO `item` VALUES ('$_POST[itemname]','$max','$_POST[menu]','$_POST[price]','$_POST[ingredients]','$target_file','0','0','$ratings','$rate')";
    }else $sql="INSERT INTO `item` VALUES ('$_POST[itemname]','$max','$_POST[menu]','$_POST[price]','$_POST[ingredients]','$target_file','1','$_POST[disper]','$ratings','$rate')";
    $stmt=$mysqli->prepare($sql);
    $stmt->execute();
  } else {
    echo "Sorry, there was an error uploading your file.";

  }
  }
  }
  if(isset($_POST['image'])){
    
  $_GET['menu']=$_POST['menu'];
    $mysqli = new mysqli('localhost','root','','restaurant');
    if($mysqli->connect_error) exit('error connecting to db');
    $target_dir = "image/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $flag =0;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
      $uploadOk = 1;
      $flag =0;
    } else {
      $m= "File is not an image.";
      $s='<script>alert("'.$m.'")</script>';
      echo $s;
      $uploadOk = 0;
      $flag=1;
    }
  
  
    // Check if file already exists
    if (file_exists($target_file) && !$flag ) {
      $m=  "Sorry, file already exists.";
    $s='<script>alert("'.$m.'")</script>';
    echo $s;
    $uploadOk = 0;
    $flag=1;
    }
  
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000 && !$flag) {
      $m=  "Sorry, your file is too large.";
    $s='<script>alert("'.$m.'")</script>';
    echo $s;
    $uploadOk = 0;
    $flag=1;
    }
  
    // Allow certain file formats
    if($imageFileType != "jpg" && !$flag ) {
      $m=  "Sorry, only JPG files are allowed.";
    $s='<script>alert("'.$m.'")</script>';
    echo $s;
    $uploadOk = 0;
    $flag=1;
    }
  
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0 && !$flag) {
      $m=  "Sorry, your file was not uploaded.";
    $s='<script>alert("'.$m.'")</script>';
    echo $s;
    // if everything is ok, try to upload file
    } else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        $x = strpos($target_file, ".jpg", $offset = 0);
        $target_file = substr($target_file,0,$x);
        $sql="UPDATE `item` SET `PICTURE`='$target_file' WHERE `ITEMID`='$_POST[itemid]'";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
  
    }
    }
  
  }
if(isset($_POST['update'])){
  
  $_GET['menu']=$_POST['menu'];
  $mysqli = new mysqli('localhost','root','','restaurant');
  if($mysqli->connect_error) exit('error connecting to db');
  if($_POST['discount']!= 0)
  $sql = "UPDATE `item` SET `ITEMNAME`='$_POST[itemname]',`PRICE`='$_POST[price]',`INGREDIENTS`='$_POST[ingredients]',`DISCOUNT`='1',`DISPER`='$_POST[discount]' WHERE `ITEMID`='$_POST[itemid]' ";
  else $sql = "UPDATE `item` SET `ITEMNAME`='$_POST[itemname]',`PRICE`='$_POST[price]',`INGREDIENTS`='$_POST[ingredients]',`DISCOUNT`='1',`DISPER`='0' WHERE `ITEMID`='$_POST[itemid]' ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
}
if(isset($_POST['delete'])){
  
  $_GET['menu']=$_POST['menu'];
  $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "restaurant";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }	
    mysqli_query($conn,"DELETE FROM `itemorder` WHERE `ITEMID`='$_POST[itemid]'");
    mysqli_query($conn,"DELETE FROM `itemrating` WHERE `ITEMID`='$_POST[itemid]'");
    mysqli_query($conn,"DELETE FROM `cart` WHERE `ITEMID`='$_POST[itemid]'");
    
    mysqli_query($conn,"DELETE FROM `adv` WHERE `ITEMID`='$_POST[itemid]'");
  $mysqli = new mysqli('localhost','root','','restaurant');
  if($mysqli->connect_error) exit('error connecting to db');
  $sql = "DELETE FROM `item` WHERE `ITEMID`='$_POST[itemid]'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
}
if(isset($_POST['cartadd'])){
  if(!isset($_SESSION['user'])){
    echo '<script>alert("Please Login First")</script>';
  }else{
  $_GET['menu']=$_POST['menu'];
  $mysqli = new mysqli('localhost','root','','restaurant');
  if($mysqli->connect_error) exit('error connecting to db');
  $sql = "SELECT `CUSTID`, `ITEMID`, `COUNT` FROM `cart` WHERE `CUSTID` = $_SESSION[user] and `ITEMID`= '$_POST[itemid]'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  $cart = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  if(count($cart)==1){
    foreach ($cart as $key){
      $count=$_POST['count']+$key['COUNT'];
    $sql = "UPDATE `cart` SET `COUNT`='$count' WHERE `CUSTID`='$_SESSION[user]'and`ITEMID`='$_POST[itemid]'";
    }
  }else $sql = "INSERT INTO `cart`(`CUSTID`, `ITEMID`, `COUNT`) VALUES ('$_SESSION[user]','$_POST[itemid]','$_POST[count]')";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
}}
if(isset($_POST['addrate'])){
  $_GET['menu']=$_POST['menu'];
  if(!isset($_SESSION['user'])){
    echo '<script>alert("Please log in first")</script>';
  }else{
    $mysqli = new mysqli('localhost','root','','restaurant');
    if($mysqli->connect_error) exit('error connecting to db');
    $sql ="INSERT INTO `itemrating`(`CUSTID`, `ITEMID`, `RATING`) VALUES ('$_SESSION[user]','$_POST[itemid]','$_POST[rate]')";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    $sql="SELECT * FROM item WHERE ITEMID=$_POST[itemid]";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    $itemr = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    foreach($itemr as $key){
      $rate=($key['RATE']+$_POST['rate'])/($key['RATINGS']+1);
      $ratings=$key['RATINGS']+1;
    $sql = "UPDATE `item` SET `RATINGS`='$ratings', `RATE`='$rate' WHERE `ITEMID`='$_POST[itemid]'";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    }
  }
  
}
if(isset($_POST['uprate'])){
  
  $_GET['menu']=$_POST['menu'];
  $mysqli = new mysqli('localhost','root','','restaurant');
  if($mysqli->connect_error) exit('error connecting to db');
  $sql ="UPDATE `itemrating` SET RATING='$_POST[rate]' WHERE `ITEMID`='$_POST[itemid]' and `CUSTID` = '$_SESSION[user]' ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  $sql="SELECT * FROM item WHERE ITEMID=$_POST[itemid]";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  $itemr = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  foreach($itemr as $key){
    $rate=($key['RATE']+$_POST['rate'])/($key['RATINGS']+1);
  $sql = "UPDATE `item` SET `RATINGS`='$key[RATINGS]+1', `RATE`='$rate' WHERE `ITEMID`='$_POST[itemid]'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  }
}
?>
<!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">

    <title>WOK.TOWN</title>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">


    <!-- Additional CSS Files -->
    <link rel="stylesheet" href="assets/css/fontawesome.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/owl.css">

  </head>

  <body>

    <!-- ***** Preloader Start ***** -->
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>  
    <!-- ***** Preloader End ***** -->

    <!-- Header -->
    <header class="">
      <nav class="navbar navbar-expand-lg">
        <div class="container">
          <a class="navbar-brand" href="index.php"><h2>WOK<em>.</em>TOWN</h2></a>
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home
                  <span class="sr-only">(current)</span>
                </a>
              </li> 
              <li class="nav-item">
                <a class="nav-link" href="book-table.php">Book a Table</a>
              </li>

              <li class="nav-item active">
                <a class="nav-link" href="menu.php">Menu</a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="testimonials.php">testimony</a>
              </li>
              <li class="nav-item">
              <div id="navbarCollapse" class="collapse navbar-collapse">
              <ul class="nav navbar-nav navbar-right ml-auto">	
                <?php
                if(isset($_SESSION['user'])){
                  $mysqli = new mysqli('localhost','root','','restaurant');
                  if($mysqli->connect_error) exit('error connecting to db');
                  $sql = "SELECT `USERNAME` FROM `customer`where `CUSTID`='$_SESSION[user]'";
                  $stmt = $mysqli->prepare($sql);
                  $stmt->execute();
                  $username = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                  foreach($username as $key){
                    $user=$key['USERNAME'];
                  }
                  
                  $s=' <li class="nav-item"><a class="dropdown-item" href="cart.php"><i style="font-size:24px;color:black" class="fa">&#xf07a;</i></a></li><li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">'.$user.'▼</a>
                  
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="orders.php">Orders</a>
                    <a class="dropdown-item" href="cusprofile.php">Profile</a>
                    <a class="dropdown-item" href="items.php?logout=1&menu='.$_GET['menu'].'">Logout</a>
                  </div>
              </li>';
              echo $s;
                }else if(isset($_SESSION['admin'])){
                  $mysqli = new mysqli('localhost','root','','restaurant');
                  if($mysqli->connect_error) exit('error connecting to db');
                  $sql = "SELECT `ADNAME` FROM `admin`where `ADID`='$_SESSION[admin]'";
                  $stmt = $mysqli->prepare($sql);
                  $stmt->execute();
                  $username = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                  foreach($username as $key){
                    $user=$key['ADNAME'];
                  }
                  
                  $s=' <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">'.$user.'▼</a>
                  
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="order.php">Orders</a>
                    <a class="dropdown-item" href="items.php?logout=1&menu='.$_GET['menu'].'">Logout</a>
                  </div>
              </li>';
              echo $s;
                }else if(isset($_SESSION['sad'])){
                  $mysqli = new mysqli('localhost','root','','restaurant');
                  if($mysqli->connect_error) exit('error connecting to db');
                  $sql = "SELECT `SADNAME` FROM `sad`where `SADID`='$_SESSION[sad]'";
                  $stmt = $mysqli->prepare($sql);
                  $stmt->execute();
                  $username = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                  foreach($username as $key){
                    $user=$key['SADNAME'];
                  }
                  
                  $s='  <li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">'.$user.'▼</a>
                  
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="order.php">Orders</a>
                    <a class="dropdown-item" href="sad.php">Admin Panel</a>
                    <a class="dropdown-item" href="items.php?logout=1&menu='.$_GET['menu'].'">Logout</a>
                  </div>
              </li>';
              echo $s;
                }
                else{
                if(!isset($_POST['Login']) || (!isset($_SESSION['sad']) && !isset($_SESSION['admin']) && !isset($_SESSION['user']))){ 
                  if(isset($_POST['Login']) && (!isset($_SESSION['sad']) && !isset($_SESSION['admin']) && !isset($_SESSION['user']))){ echo '<script>alert("Login Failed")</script>';}
                $m= "<li class='nav-item' style='margin:auto;margin-right:20px;padding-top:0px;'>
                <a data-toggle='dropdown' class='dropdown-toggle'  href='#'>LOGIN</a>
                <ul class='dropdown-menu form-wrapper'>					
                    <li>
                        <form name='form1' method='post'  action='items.php?menu=".$_GET['menu']."'>
                        <input type='hidden' id='menu' name='menu' value='".$_GET['menu']."'>
                            <div class='form-group'>
                                <input type='text' class='form-control' placeholder='Username' name='Username' required='required'>
                            </div>
                            <div class='form-group'>
                                <input type='password' class='form-control' placeholder='Password' name='Password' required='required'>
                            </div>
                            <input type='submit' class='btn btn-primary btn-block' name='Login' value='Login'>
                            <div class='form-footer'>
                                <a href='#'>Forgot Your password?</a>
                            </div>
                        </form>
                    </li>
                </ul>
                </li>";
                echo $m;
                }
                
                if(!isset($_POST['Signup'])){
                    $m="<li>
                    <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle get-started-btn mt-1 mb-1'>Sign up</a>
                    <ul class='dropdown-menu form-wrapper'>					
                        <li>
                            <form action='items.php?menu=".$_GET['menu']."' method='post'>
                            <input type='hidden' id='menu' name='menu' value='".$_GET['menu']."'>
                                <p class='hint-text'>Fill this form to create an account!</p>
                                <div class='form-group'>
                                    <input type='text' class='form-control' name='Name' placeholder='Name' required='required'>
                                </div>
                                <div class='form-group'>
                                    <input type='text' class='form-control' name='Username' placeholder='Username' required='required'>
                                </div>
                                <div class='form-group'>
                                    <input type='password' class='form-control' id='Password' name='Password' placeholder='Password' required='required'>
                                </div>
                                <div class='form-group'>
                                    <input type='password' class='form-control' id='Confirm_password' name='Confirm_password' placeholder='Confirm Password' required='required'>
                                </div>
                                <div class='form-group'>
                                    <input type='email' class='form-control' name='Email' placeholder='Email' required='required'>
                                </div>
                                <div class='form-group'>
                                    <label for='inputAddress'>Address</label>
                                    <input type='text' class='form-control' name='Address' id='inputAddress' placeholder='1234 Main St'>
                                </div>
                                <div class='form-group'>
                                    <label for='inputAddress'>Date of Birth</label>
                                    <input type='date' class='form-control' name='DOB' placeholder='date' required='required'>
                                </div>
                                <div class='form-group'>
                                    <label for='phone'>Phone number</label>
                                    <input type='tel' class='form-control' name='Phone' placeholder='00000000' name='phone' pattern='[0-9]{8}' >
                                </div>
                                <input type='submit' class='btn btn-primary btn-block' name='Signup' value='Signup'>
                            </form>
                        </li>
                    </ul>
                </li>
                </ul>
			</li>";
                echo $m;
                }
                 if(isset($_POST['Signup'])){
                    $name=$_POST['Name'];
                    $uname=$_POST['Username'];
                    $pass=$_POST['Password'];
                    $confirm_pass=$_POST['Confirm_password'];
                    $email=$_POST['Email'];
                    $add=$_POST['Address'];
                    $dob=$_POST['DOB'];
                    $phone=$_POST['Phone'];
                    //if pass not equal to confirm pass
                    if(strcmp($pass, $confirm_pass) != 0){
                      echo '<script>alert("Password Doesn\'t match")</script>';
                        $m="<li>
                    <a href='#' data-toggle='dropdown'  class='btn btn-primary dropdown-toggle get-started-btn mt-1 mb-1'>Sign up</a>
                    <ul class='dropdown-menu form-wrapper'>					
                        <li>
                            <form action='items.php?menu=".$_GET['menu']."' method='post'>
                            <input type='hidden' id='menu' name='menu' value='".$_GET['menu']."'>
                                <p class='hint-text'>Please make sure to confirm your pass truely!</p>
                                <div class='form-group'>";
                                $m.=
                                    "<input type='text' class='form-control' name='Name' value=".$name." required='required'>
                                </div>
                                <div class='form-group'>";
                                    $m.="<input type='text' class='form-control' name='Username' value=".$uname." required='required'>
                                </div>
                                <div class='form-group'>";
                                    $m.="<input type='password' class='form-control' id='Password' name='Password' placeholder='Password' required='required'>
                                </div>
                                <div class='form-group'>
                                    <input type='password' class='form-control' id='Confirm_password' name='Confirm_password' placeholder='Confirm Password' required='required'>
                                </div>
                                <div class='form-group'>";
                                    $m.="<input type='email' class='form-control' name='Email' value=".$email." required='required'>
                                </div>
                                <div class='form-group'>
                                    <label for='inputAddress'>Address</label>";
                                    $m.="<input type='text' class='form-control' name='Address' id='inputAddress' value=".$add.">
                                </div>
                                <div class='form-group'>
                                    <label for='inputAddress'>Date of Birth</label>";
                                    $m.="<input type='date' class='form-control' name='DOB' value=".$dob." required='required'>
                                </div>
                                <div class='form-group'>
                                    <label for='phone'>Phone number</label>";
                                    $m.="<input type='tel' class='form-control' name='Phone' value=".$phone." name='phone' pattern='[0-9]{8}'>
                                </div>
                                <input type='submit' class='btn btn-primary btn-block' name='Signup' value='Signup'>
                            </form>
                        </li>
                    </ul>
                    </li>
                    </ul>
			        </li>";
                    echo $m;
                    }
                    else{
                    $name = $_POST['Username'];
                    $pass = $_POST['Password'];
                    $db="restaurant";
                    $dpass="";
                    $server="localhost";
                    $user="root";
                    $mysqli=new mysqli($server,$user,$dpass,$db);
                    $sql="SELECT * FROM `customer` WHERE `USERNAME`='$uname'";
                    $result=$mysqli->query($sql);
                    $rownumc=$result->num_rows;
                    $sql="SELECT * FROM `admin` WHERE `USERNAME`='$uname'";
                    $result=$mysqli->query($sql);
                    $rownuma=$result->num_rows;
                    $sql="SELECT * FROM `sad` WHERE `USERNAME`='$uname'";
                    $result=$mysqli->query($sql);
                    $rownums=$result->num_rows;
                    //if username mwjood bl cus or admin or sadmin
                    if($rownumc > 0 || $rownuma > 0 || $rownums > 0){
                      echo '<script>alert("Username Already Taken")</script>';
                        $m="<li>
                    <a href='#' data-toggle='dropdown'  class='btn btn-primary dropdown-toggle get-started-btn mt-1 mb-1'>Sign up</a>
                    <ul class='dropdown-menu form-wrapper'>					
                        <li>
                            <form action='items.php?menu=".$_GET['menu']."' method='post'>
                            <input type='hidden' id='menu' name='menu' value='".$_GET['menu']."'>
                                <p class='hint-text'>USED USERNAME !</p>
                                <div class='form-group'>";
                                $m.=
                                    "<input type='text' class='form-control' name='Name' value=".$name." required='required'>
                                </div>
                                <div class='form-group'>";
                                    $m.="<input type='text' class='form-control' id='Username' name='Username' placeholder='Username' required='required'>
                                </div>
                                <div class='form-group'>";
                                    $m.="<input type='password' class='form-control' id='Password' placeholder='Password' name='Password' required='required'>
                                </div>
                                <div class='form-group'>
                                    <input type='password' class='form-control' id='Confirm_password' placeholder='Confirm_password' name='Confirm_password' required='required'>
                                </div>
                                <div class='form-group'>";
                                    $m.="<input type='email' class='form-control' name='Email' value=".$email." required='required'>
                                </div>
                                <div class='form-group'>
                                    <label for='inputAddress'>Address</label>";
                                    $m.="<input type='text' class='form-control' name='Address' id='inputAddress' value=".$add.">
                                </div>
                                <div class='form-group'>
                                    <label for='inputAddress'>Date of Birth</label>";
                                    $m.="<input type='date' class='form-control' name='DOB' value=".$dob." required='required'>
                                </div>
                                <div class='form-group'>
                                    <label for='phone'>Phone number</label>";
                                    $m.="<input type='tel' class='form-control' name='Phone' value=".$phone." name='phone' pattern='[0-9]{8}'>
                                </div>
                                <input type='submit' class='btn btn-primary btn-block' name='Signup' value='Signup'>
                            </form>
                        </li>
                    </ul>
                    </li>
                    </ul>
			        </li>";
                    echo $m;
                    }
                    else{
                            
                            $sql="SELECT MAX(CUSTID) AS max FROM `customer`";
                            $stmt=$mysqli->prepare($sql);
                            $stmt->execute();
                            $max=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                            $max=$max[0]['max'] +1;
                
                            $sql="INSERT INTO `customer`(`CUSTID`, `CUSTNAME`, `USERNAME`, `PASSWORD`, 
                            `EMAIL`, `ADDRESS`, `DOB`, `PHONE`) VALUES (?,?,?,?,?,?,?,?)";
                            $stmt=$mysqli->prepare($sql);
                            $stmt->bind_param("isssssss",$max,$name,$uname,$pass,$email,$add,$dob,$phone);
                            $stmt->execute();
                            $stmt->close();
                            $m="<li>
                    <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle get-started-btn mt-1 mb-1'>Sign up</a>
                    <ul class='dropdown-menu form-wrapper'>					
                        <li>
                            <form action='items.php?menu=".$_GET['menu']."' method='post'>
                            <input type='hidden' id='menu' name='menu' value='".$_GET['menu']."'>
                                <p class='hint-text'>Fill this form to create an account!</p>
                                <div class='form-group'>
                                    <input type='text' class='form-control' name='Name' placeholder='Name' required='required'>
                                </div>
                                <div class='form-group'>
                                    <input type='text' class='form-control' name='Username' placeholder='Username' required='required'>
                                </div>
                                <div class='form-group'>
                                    <input type='password' class='form-control' id='Password' name='Password' placeholder='Password' required='required'>
                                </div>
                                <div class='form-group'>
                                    <input type='password' class='form-control' id='Confirm_password' name='Confirm_password' placeholder='Confirm Password' required='required'>
                                </div>
                                <div class='form-group'>
                                    <input type='email' class='form-control' name='Email' placeholder='Email' required='required'>
                                </div>
                                <div class='form-group'>
                                    <label for='inputAddress'>Address</label>
                                    <input type='text' class='form-control' name='Address' id='inputAddress' placeholder='1234 Main St'>
                                </div>
                                <div class='form-group'>
                                    <label for='inputAddress'>Date of Birth</label>
                                    <input type='date' class='form-control' name='DOB' placeholder='date' required='required'>
                                </div>
                                <div class='form-group'>
                                    <label for='phone'>Phone number</label>
                                    <input type='tel' class='form-control' name='Phone' placeholder='00000000' name='phone' pattern='[0-9]{8}' >
                                </div>
                                <input type='submit' class='btn btn-primary btn-block' name='Signup' value='Signup'>
                            </form>
                        </li>
                    </ul>
                </li>
                </ul>
			</li>";echo $m;
                    }
                }
            }}
                ?>
            </ul>
          </div>
        </div>
      </nav>
    </header>

    <!-- Page Content -->
    <!-- Banner Starts Here -->
    <div class="heading-page header-text">
      <section class="page-heading">
        <div class="container">
          <div class="row">
            <div class="col-lg-12">
              <div class="text-content">
                <h4>Menu</h4>
                <h2>Find the perfect meal for you!</h2>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
    
    <!-- Banner Ends Here -->

    <section class="blog-posts grid-system">
      <div class="container">
        <div class="all-blog-posts">
          <div class="row">
            <?php
              $i=1;
              if( isset($_GET['menu'])|| isset($_POST['menu']) ){
              $mysqli = new mysqli('localhost','root','','restaurant');
              if($mysqli->connect_error) exit('error connecting to db');
              if( isset($_GET['menu']))
              $sql = 'select * from item where menuid='.$_GET['menu'];
              else{ $sql = 'select * from item where menuid='.$_POST['menu'];
                $_GET['menu'] = $_POST['menu'];}
              $stmt = $mysqli->prepare($sql);
              $stmt->execute();
              $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
              if(isset($_SESSION['admin']) || isset($_SESSION['sad'])){
              foreach ($items as $key){
              $s='<div class="col-md-3 col-sm-6">';
              $s.='<div class="blog-post">';
              $s.='<div class="blog-thumb">';
              $s.='<img src="'.$key['PICTURE'].'.jpg'.'" alt="">'.'</div>';
              $s.='<div class="down-content">';
              $s.='<form action="items.php" method="post">';
              $s.='<input type="hidden" id="itemid" name="itemid" value="'.$key['ITEMID'].'">';
              $s.='<input type="hidden" id="menu" name="menu" value="'.$_GET['menu'].'">';
              $s.='<ul class="post-tags">';
              $s.='<li class="item-list"><label>Price:</label></li>';
              $s.='<li class="item-list"><input style="width:100px; "type="number" id="price" name="price" value ="'.$key['PRICE'].'" required="required" step="250" "></li></ul>';
              $s.='<ul class="post-tags">';
              $s.='<li class="item-list"><label>Discount:</label></li>';
              settype($key['DISPER'],"integer");
              $s.='<li class="item-list"><input style="width:50px; "type="number" id="discount" name="discount" value ="'.$key['DISPER'].'"required="required" step="1"></li></ul>';
              $s.='<input style="margin-bottom: 4px; " type="text" id="itemname" name="itemname" value ="'.$key['ITEMNAME'].'"required="required">';
              $s.='<textarea id="ingredients" name="ingredients" rows="8" cols="20"required="required">'.$key['INGREDIENTS'].'</textarea>';
              $s.='<div class="row">';
              $s.='<div class="col-lg-12">';
              $s.='<ul class="post-tags">';
              $s.='<li class="item-list"><button class="button5" type="submit" name="update" value="update">Update</button></li>';
              $s.='<li class="item-list"><button class="button5" type="submit" name="delete" value="delete">Delete</button></li></ul>';
              $s.='</form></div></div>';
              $s.='<form method="POST" enctype="multipart/form-data">';
              
              $s.='<input type="hidden" id="itemid" name="itemid" value="'.$key['ITEMID'].'">';
                $s.='<input type="hidden" id="menuid" name="menuid" value="'.$key['MENUID'].'">';
                $s.='<div class="row">';
                $s.='<div class="col-lg-12">';
                $s.='<ul class="post-tags">';
                $s.='<li class="item-list"><input type="file" name="fileToUpload" id="fileToUpload"></li>';
                $s.='<li class="item-list"><button class="button5" type="submit" name="image" value="image">Update Image</button></li></ul>';
                $s.='</form></div></div>';
              $s.='</div></div></div>';
              echo $s;
              $i++;
              }
              $s='<div class="col-md-3 col-sm-6">';
              $s.='<div class="blog-post">';
              $s.='<div class="blog-thumb">';
              $s.='<form method="POST" enctype="multipart/form-data">';
              $s.='<input type="file" name="fileToUpload" id="fileToUpload">';
              $s.='<div class="down-content">';
              $s.='<input type="hidden" id="menu" name="menu" value="'.$_GET['menu'].'">';
              $s.='<label for="price">Price:</label><br/><input type="text" id="price" name="price" size="10"><br/>';
              $s.='<label for="itemname">Item Name:</label><input type="text" id="itemname" name="itemname" size="10"><br/>';
              $s.='<label for="disper">Discount Percentage:</label><input type="text" id="disper" name="disper" size="10"><br/>';
              $s.='<label for="ingredients">Ingredients:</label><br/><textarea id="ingredients" name="ingredients" rows="3" cols="20"></textarea><br/>';
              $s.='<br/><input type="submit" value="Upload" name="submit">';
              $s.='</form>';
              $s.='</div></div></div>';
              echo $s;
            }else{ foreach ($items as $key){
              
              $s='<div class="col-md-3 col-sm-6">';
              $s.='<div class="blog-post">';
              $s.='<div class="blog-thumb">';
              $s.='<img src="'.$key['PICTURE'].'.jpg'.'" alt="">'.'</div>';
              $s.='<div class="down-content">';
              if($key['DISCOUNT']==0){
                $s.='<div style= "height:49px;"><span >'.number_format((float)$key['PRICE'], 2, '.', ',').' LBP</span></div>';
              }else{
                settype($disPrice, "double");
                $disPrice = $key['PRICE']-($key['PRICE']*$key['DISPER']/100.00);
                $s.='<span><del>'.number_format((float)$key['PRICE'], 2, '.', ',').' LBP</del> '.number_format((float)$disPrice, 2, '.', ',').' LBP</span>';
              }
              $s.='<h4 style= "height:48px;">'.$key['ITEMNAME'].'</h4>';
              $s.='<p style= "height:218px;">'.$key['INGREDIENTS'].$key['ITEMID'].'</p>';
              $s.='<div class="post-options">';
              $s.='<div class="row">';
              $s.='<div class="col-lg-12">';
              $s.='<form action="items.php" method="post">';
              $s.='<input type="hidden" id="itemid" name="itemid" value="'.$key['ITEMID'].'">';
              $s.='<input type="hidden" id="menu" name="menu" value="'.$_GET['menu'].'">';
              $s.='<ul class="post-tags">';
              if(isset($_SESSION['user'])){
              $sql = "SELECT * FROM `itemrating` where `ITEMID`='$key[ITEMID]' and CUSTID = $_SESSION[user]";  
              $stmt = $mysqli->prepare($sql);
              $stmt->execute();
              $rating = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);} else $rating=array();
              if(count($rating)==0 || !isset($_SESSION['user'])){
                
                $val=number_format((float)5, 2, '.', ',');
                $s.='<li class="item-list"><input style="width:60px; "type="number" id="rate" name="rate" value ="'.$val.'"required="required" step="0.05"></li>';
              $s.='<li class="item-list"><button class="button5" type="submit" name="addrate" value="addrate">Rate</button></li>';
              $s.='</ul>';
              }else {
              foreach($rating as $key1){
                $val=number_format((float)$key1['RATING'], 2, '.', ',');
              $s.='<li class="item-list"><input style="width:60px; "type="number" id="rate" name="rate" value ="'.$val.'"required="required" max="5" step="0.05"></li>';
              $s.='<li class="item-list"><button class="button5" type="submit" name="uprate" value="uprate">Rate</button></li>';
              $s.='</ul>';}}
              $s.='<ul class="post-tags">';
              $s.='<li class="item-list"><input style="width:40px; "type="number" id="count" name="count" value ="1"required="required" step="1"></li>';
              $s.='<li class="item-list"><button class="button5" type="submit" name="cartadd" value="cartadd">Add To Cart</button></li>';
              $s.='</ul>';
              $s.='</form>';
              $s.='</div></div></div>';
              $s.='</div></div></div>';
              echo $s;
              $i++;
            }}
            }else echo "not";
            ?>
          </div>
        </div>
      </div>
    </section>

    
    <footer>
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <ul class="social-icons">
              <li><a href="#">Ali Isamil</a></li>
              <li><a href="#">Sarah Zaidan</a></li>
            </ul>
          </div>
        </div>
      </div>
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Additional Scripts -->
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/owl.js"></script>
    <script src="assets/js/slick.js"></script>
    <script src="assets/js/isotope.js"></script>
    <script src="assets/js/accordions.js"></script>

    <script language = "text/Javascript"> 
      cleared[0] = cleared[1] = cleared[2] = 0; //set a cleared flag for each field
      function clearField(t){                   //declaring the array outside of the
      if(! cleared[t.id]){                      // function makes it static and global
          cleared[t.id] = 1;  // you could use true and false, but that's more typing
          t.value='';         // with more chance of typos
          t.style.color='#fff';
          }
      }
    </script>

  </body>
</html>