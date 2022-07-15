<?php
session_start();
if(isset($_GET['logout'])){
  if(isset($_SESSION['user'])) unset($_SESSION['user']);
  if(isset($_SESSION['sad'])) unset($_SESSION['sad']);
  if(isset($_SESSION['admin'])) unset($_SESSION['admin']);
  session_destroy();
}
if(isset($_POST['image'])){
  $mysqli = new mysqli('localhost','root','','restaurant');
  if($mysqli->connect_error) exit('error connecting to db');
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
    $m=  "Sorry, file already exists.";
    $s.='<script>alert("'.$m.'")</script>';
    echo $s;
  $uploadOk = 0;
  }

  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 5000000) {
    $m=  "Sorry, your file is too large.";
    $s.='<script>alert("'.$m.'")</script>';
    echo $s;
  $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "jpg") {
    $m=  "Sorry, only JPG files are allowed.";
    $s.='<script>alert("'.$m.'")</script>';
    echo $s;
  $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    $m=  "Sorry, your file was not uploaded.";
    $s.='<script>alert("'.$m.'")</script>';
    echo $s;
  // if everything is ok, try to upload file
  } else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $x = strpos($target_file, ".jpg", $offset = 0);
    $target_file = substr($target_file,0,$x);
      $sql="UPDATE `menu` SET `PICTURE`='$target_file' WHERE `MENUID`='$_POST[menuid]'";
      $stmt = $mysqli->prepare($sql);
      $stmt->execute();

  }
  }
}
if(isset($_POST["submit"])) {
  $target_dir = "image/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }


  // Check if file already exists
  if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;
  }

  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 5000000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "jpg") {
  echo "Sorry, only JPG files are allowed.";
  $uploadOk = 0;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.";
  // if everything is ok, try to upload file
  } else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
    $mysqli = new mysqli('localhost','root','','restaurant');
    if($mysqli->connect_error) exit('error connecting to db');
    $sql="SELECT MAX(MENUID) as max FROM menu";
    $stmt=$mysqli->prepare($sql);
    $stmt->execute();
    $max=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $max=$max[0]['max'] +1;
    $sql="INSERT INTO `menu` (`MENUNAME`, `MENUID`, `PICTURE`) VALUES (?,?,?)";
    $stmt=$mysqli->prepare($sql);
  
    $x = strpos($target_file, ".jpg", $offset = 0);
    $target_file = substr($target_file,0,$x);
    $stmt->bind_param("sis",$_POST["menuname"],$max,$target_file);
    $stmt->execute();
  } else {
    echo "Sorry, there was an error uploading your file.";

  }
  }
  }
if(isset($_POST['Login'])) { 
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
if(isset($_POST['update'])){
  $mysqli = new mysqli('localhost','root','','restaurant');
  if($mysqli->connect_error) exit('error connecting to db');
  $sql = "UPDATE `menu` SET `MENUNAME`='$_POST[menuname]' WHERE `MENUID`='$_POST[menuid]' ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
}
if(isset($_POST['delete'])){
  $mysqli = new mysqli('localhost','root','','restaurant');
  if($mysqli->connect_error) exit('error connecting to db');
  $sql = 'select * from item where menuid='.$_POST['menuid'];
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  foreach($items as $key){
    $sql = "DELETE FROM `item` WHERE `ITEMID`='$key[ITEMID]'";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
  }
  $sql = "DELETE FROM `menu` WHERE `MENUID`='$_POST[menuid]'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
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
                    <a class="dropdown-item" href="menu.php?logout=1">Logout</a>
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
                    <a class="dropdown-item" href="menu.php?logout=1">Logout</a>
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
                    <a class="dropdown-item" href="menu.php?logout=1">Logout</a>
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
                        <form name='form1' method='post'  action='menu.php'>
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
                            <form action='menu.php' method='post'>
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
                            <form action='menu.php' method='post'>
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
                            <form action='menu.php' method='post'>
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
                            <form action='menu.php' method='post'>
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
              $mysqli = new mysqli('localhost','root','','restaurant');
              if($mysqli->connect_error) exit('error connecting to db');
              $sql = 'select * from menu';
              $stmt = $mysqli->prepare($sql);
              $stmt->execute();
              $menus = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);  
              if(isset($_SESSION['admin']) || isset($_SESSION['sad'])){
                foreach ($menus as $key){
                $s='<div class="col-md-3 col-sm-6">';
                $s.='<div class="blog-post">';
                $s.='<div class="blog-thumb">';
                $s.='<a href="items.php?menu='.$key['MENUID'].'"><img src="'.$key['PICTURE'].'.jpg'.'" alt="">'.'</a></div>';
                $s.='<div class="down-content">';
                $s.='<form action="menu.php" method="post">';
                $s.='<input type="hidden" id="menuid" name="menuid" value="'.$key['MENUID'].'">';
                $s.='<input style="margin-bottom: 4px; " type="text" id="menuname" name="menuname" value ="'.$key['MENUNAME'].'"required="required">';
                $s.='<div class="row">';
                $s.='<div class="col-lg-12">';
                $s.='<ul class="post-tags">';
                $s.='<li class="item-list"><button class="button5" type="submit" name="update" value="update">Update</button></li>';
                $s.='<li class="item-list"><button class="button5" type="submit" name="delete" value="delete">Delete</button></li></ul>';
                $s.='</form></div></div>';
                $s.='<form method="POST" enctype="multipart/form-data">';
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
                $s.='<label for="menuname">Menu Name:</label><input type="text" id="menuname" name="menuname" size="10"><br />';
                $s.='<br/><input type="submit" value="Upload" name="submit">';
                $s.='</form>';
                $s.='</div></div></div>';
                echo $s;
              }else{ foreach ($menus as $key){
                $s='<div class="col-md-3 col-sm-6">';
                $s.='<div class="blog-post">';
                $s.='<div class="blog-thumb">';
                $s.='<a href="items.php?menu='.$key['MENUID'].'"><img src="'.$key['PICTURE'].'.jpg'.'" alt="">'.'</a></div>';
                $s.='<div class="down-content">';
                $s.='<a href="items.php?menu='.$key['MENUID'].'"><h4>'.$key['MENUNAME'].'</h4></a>';
                $s.='</div></div></div>';
                echo $s;
                
                $i++;
              }}
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