<?php
session_start();
if(isset($_GET['logout'])){
  if(isset($_SESSION['user'])) unset($_SESSION['user']);
  if(isset($_SESSION['sad'])) unset($_SESSION['sad']);
  if(isset($_SESSION['admin'])) unset($_SESSION['admin']);
  session_destroy();
}

if(isset($_POST['Login'])) 
                { 
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
if(isset($_POST['updateadv'])){
  $mysqli = new mysqli('localhost','root','','restaurant');
  if($mysqli->connect_error) exit('error connecting to db');
  $sql = "SELECT `ITEMNAME`, `ITEMID` FROM `item` WHERE `ITEMNAME` = '$_POST[itemname]'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  $sql1=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  if(count($sql1)){
  foreach($sql1 as $key){
  $sql= "UPDATE `adv` SET `EVENTDES`='$_POST[eventdes]',`ITEMID`= '$key[ITEMID]' WHERE `ADVID` = '$_POST[advid]'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  if($_POST['itemid']== $key['ITEMID']){
  if($_POST['discount']!= 0)
  $sql = "UPDATE `item` SET `PRICE`='$_POST[price]',`DISCOUNT`='1',`DISPER`='$_POST[discount]' WHERE `ITEMID`='$key[ITEMID]' ";
  else $sql = "UPDATE `item` SET `PRICE`='$_POST[price]',`DISCOUNT`='0',`DISPER`='0' WHERE `ITEMID`='$key[ITEMID]' ";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  }}
  }else echo '<script>alert("Wrong item name")</script>';
}
if(isset($_POST['updateevent'])){
  $mysqli = new mysqli('localhost','root','','restaurant');
  if($mysqli->connect_error) exit('error connecting to db');
  $sql = "UPDATE `entity_11` SET `EVENTDES`='$_POST[eventdes]',`EVENTNAME`='$_POST[eventname]',`MINPRICE`='$_POST[minprice]',`MAXPRICE`='$_POST[maxprice]' WHERE `EVENTID`='$_POST[eventid]'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
}
if(isset($_POST['deleteevent'])){
  $mysqli = new mysqli('localhost','root','','restaurant');
  if($mysqli->connect_error) exit('error connecting to db');
  $sql = "DELETE FROM `entity_11` WHERE `EVENTID`='$_POST[eventid]'";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
}
if(isset($_POST["image"])) {
  $target_dir = "image/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    $uploadOk = 1;
    $flag=0;
  } else {
    echo "File is not an image.";
    echo '<script>alert("'.$m.'")</script>';
    $uploadOk = 0;
    $flag=1;
  }


  // Check if file already exists
  if (file_exists($target_file) && !$flag) {
    $m= "Sorry, file already exists.";
  echo '<script>alert("'.$m.'")</script>';
  $uploadOk = 0;
  $flag=1;
  }

  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 5000000 && !$flag) {
    $m= "Sorry, your file is too large.";
  echo '<script>alert("'.$m.'")</script>';
  $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "jpg" && !$flag) {
    $m= "Sorry, only JPG files are allowed.";
  echo '<script>alert("'.$m.'")</script>';
  $uploadOk = 0;
  $flag=1;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0 && !$flag) {
    $m= "Sorry, your file was not uploaded.";
  echo '<script>alert("'.$m.'")</script>';
  $flag=1;
  // if everything is ok, try to upload file
  } else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $mysqli = new mysqli('localhost','root','','restaurant');
  if($mysqli->connect_error) exit('error connecting to db');
      $sql="UPDATE `entity_11` SET `PICTURE`='$target_file' WHERE `EVENTID`='$_POST[eventid]'";
      $stmt = $mysqli->prepare($sql);
      $stmt->execute();

  }
  }
}
if(isset($_POST["submit"])) {
  $mysqli = new mysqli('localhost','root','','restaurant');
  if($mysqli->connect_error) exit('error connecting to db');
  $target_dir = "image/";
  $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check['0']>"720" || $check['1']>"480"){
    $m= "Invalid dimensions";
    echo '<script>alert("'.$m.'")</script>';
    $uploadOk = 0;
    $flag=1;
  }
  if($check !== false) {
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    echo '<script>alert("'.$m.'")</script>';
    $uploadOk = 0;
    $flag=1;
  }


  // Check if file already exists
  if (file_exists($target_file) && !$flag) {
    $m= "Sorry, file already exists.";
  echo '<script>alert("'.$m.'")</script>';
  $uploadOk = 0;
  $flag=1;
  }

  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 5000000 && !$flag) {
    $m= "Sorry, your file is too large.";
  echo '<script>alert("'.$m.'")</script>';
  $uploadOk = 0;
  }

  // Allow certain file formats
  if($imageFileType != "jpg" && !$flag) {
    $m= "Sorry, only JPG files are allowed.";
  echo '<script>alert("'.$m.'")</script>';
  $uploadOk = 0;
  $flag=1;
  }

  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0 && !$flag) {
    $m= "Sorry, your file was not uploaded.";
  echo '<script>alert("'.$m.'")</script>';
  $flag=1;
  // if everything is ok, try to upload file
  } else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    $sql="SELECT MAX(EVENTID) as max FROM entity_11";
    $stmt=$mysqli->prepare($sql);
    $stmt->execute();
    $max=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $max=$max[0]['max'] +1;
    $sql="INSERT INTO `entity_11` (`EVENTDES`, `PICTURE`, `EVENTID`, `EVENTNAME`, `MINPRICE`, `MAXPRICE`) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt=$mysqli->prepare($sql);
    $stmt->bind_param("ssisdd",$_POST['description'],$target_file,$max,$_POST['eventname'],$_POST['minprice'],$_POST['maxprice']);
    $stmt->execute();
  } 
  }
  }
?><!DOCTYPE html>
<html lang="en">

  <head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>Welcome to Wok Town</title>

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
              <li class="nav-item active">
                <a class="nav-link" href="index.php">Home
                  <span class="sr-only">(current)</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="book-table.php">Book a Table</a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="menu.php">Menu</a>
              </li>

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
                  
                  $s=' <li class="nav-item active"><a class="dropdown-item" href="cart.php"><i style="font-size:24px;color:black" class="fa">&#xf07a;</i></a></li><li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">'.$user.'▼</a>
                  
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="orders.php">Orders</a>
                    <a class="dropdown-item" href="cusprofile.php">Profile</a>
                    <a class="dropdown-item" href="index.php?logout=1">Logout</a>
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
                    <a class="dropdown-item" href="index.php?logout=1">Logout</a>
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
                    <a class="dropdown-item" href="index.php?logout=1">Logout</a>
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
                        <form name='form1' method='post'  action='index.php'>
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
                
                if(!isset($_POST['Signup']) ){
                    $m="<li>
                    <a href='#' data-toggle='dropdown' class='btn btn-primary dropdown-toggle get-started-btn mt-1 mb-1'>Sign up</a>
                    <ul class='dropdown-menu form-wrapper'>					
                        <li>
                            <form action='index.php' method='post'>
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
                            <form action='index.php' method='post'>
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
                            <form action='index.php' method='post'>
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
                            <form action='index.php' method='post'>
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

<!-- ***** AD starts here ***** -->

    <div class="main-banner header-text">
      <div class="container-fluid">
        <div class="owl-banner owl-carousel">
          <?php
                      $mysqli = new mysqli('localhost','root','','restaurant');
                      if($mysqli->connect_error) exit('Error connecting to database');
                      $sql='select A.EVENTDES,A.ADVID,I.DISCOUNT, I.DISPER, I.PRICE, I.PICTURE, I.ITEMNAME,I.ITEMID from adv as A join item as I on A.ITEMID=I.ITEMID group by I.ITEMID';
                      $stmt=$mysqli->prepare($sql);
                      $stmt->execute();
                      $adv=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                      if(isset($_SESSION['admin']) || isset($_SESSION['sad'])){
                        foreach($adv as $key){
                          $s='<div class="item">';
                          $s.='<img width="720px" height="480px" src="'.$key['PICTURE'].'.jpg" alt="">';
                          $s.='<div class="item-content">';
                          $s.='<div class="main-content">';
                          $s.='<div class="meta-category">';
                          $s.='<form action="index.php" method="post">';
                          $s.='<input type="hidden" id="itemid" name="itemid" value="'.$key['ITEMID'].'">';
                          $s.='<input type="hidden" id="advid" name="advid" value="'.$key['ADVID'].'">';
                          $s.='<ul class="post-info">';
                          $s.='<li class="item-list"><label>Price:</label></li>';
                          $disprice=$key['PRICE']*$key['DISPER']/100;
                          settype($key['PRICE'],"double");
                          $s.='<li class="item-list"><input style="width:100px; "type="number" id="price" name="price" value ="'.$key['PRICE'].'" required="required" step="250" "></li></ul>';
                          $s.='<ul class="post-info">';
                          $s.='<li class="item-list"><label>Discount:</label></li>';
                          settype($key['DISPER'],"integer");
                          $s.='<li class="item-list"><input style="width:50px; "type="number" id="discount" name="discount" value ="'.$key['DISPER'].'"required="required" step="1"></li></ul>';
                          $s.='</div>';
                          $s.='<input style="margin-bottom: 4px; " type="text" id="itemname" name="itemname" value ="'.$key['ITEMNAME'].'"required="required">';
                          $s.='<ul class="post-info">';
                          $s.='<li><textarea id="eventdes" name="eventdes" rows="3" cols="20"required="required">'.$key['EVENTDES'].'</textarea></li>';
                          $s.='</ul>';
                          $s.='<div class="row">';
                          $s.='<div class="col-lg-12">';
                          $s.='<ul class="post-info">';
                          $s.='<li class="item-list"><button class="button5" type="submit" name="updateadv" value="updateadv">Update</button></li></ul>';
                          $s.='</form></div></div>';
                          
                          $s.='</div>';
                          $s.='</div>';
                          $s.='</div>';
                          echo $s;
                        }
                      }else{
                        foreach($adv as $key){
                          $s='<div class="item">';
                          $s.='<img width="720px" height="480px" src="'.$key['PICTURE'].'.jpg" alt="">';
                          $s.='<div class="item-content">';
                          $s.='<div class="main-content">';
                          $s.='<div class="meta-category">';
                          if($key['DISCOUNT']){
                            $disprice=$key['PRICE']*((100-$key['DISPER'])/100);
                          $s.='<span><del>'.number_format((float)$key['PRICE'], 2, '.', ',').' </del> - '.number_format((float)$disprice, 2, '.', ',').'</span>';}
                          else $s.='<span>'.number_format((float)$key['PRICE'], 2, '.', ',').'</span>';
                          $s.='</div>';
                          $s.='<h4>'.$key['ITEMNAME'].'</h4>';
                          $s.='<ul class="post-info">';
                          $s.='<li>'.$key['EVENTDES'].'</li>';
                          $s.='</ul>';
                          $s.='</div>';
                          $s.='</div>';
                          $s.='</div>';
                          echo $s;
                      }}

        ?>
        </div> <!-- carouse ends  -->
      </div>
    </div>
    <!-- Banner Ends Here -->
    <section class="blog-posts grid-system">
      <div class="container">
        <div class="all-blog-posts">
          <h2 class="text-center">Events</h2>
          <br>

          <div class="row">
            <?php
              $mysqli = new mysqli('localhost','root','','restaurant');
              if($mysqli->connect_error) exit('Error connecting to database');
              $sql='select * from entity_11';
              $stmt=$mysqli->prepare($sql);
              $stmt->execute();
              
              $events=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
              if(isset($_SESSION['admin']) || isset($_SESSION['sad'])){
                foreach ($events as $key){
                $s='<div class="col-md-4 col-sm-6">';
                $s.='<div class="blog-post">';
                $s.='<div class="blog-thumb">';
                $s.='<img src="'.$key['PICTURE'].'" alt="">'.'</div>';
                $s.='<div class="down-content">';
                $s.='<form action="index.php" method="post">';
                $s.='<input type="hidden" id="eventid" name="eventid" value="'.$key['EVENTID'].'">';
                $s.='<ul class="post-tags">';
                $s.='<li class="item-list"><label>Min Price:</label></li>';
                $s.='<li class="item-list"><input style="width:100px; "type="number" id="minprice" name="minprice" value ="'.$key['MINPRICE'].'" required="required" step="250" "></li></ul>';
                $s.='<ul class="post-tags">';
                $s.='<li class="item-list"><label>Max Price:</label></li>';
                settype($key['DISPER'],"integer");
                $s.='<li class="item-list"><input style="width:100px; "type="number" id="maxprice" name="maxprice" value ="'.$key['MAXPRICE'].'"required="required" step="250"></li></ul>';
                $s.='<input style="margin-bottom: 4px; " type="text" id="eventname" name="eventname" value ="'.$key['EVENTNAME'].'"required="required">';
                $s.='<textarea id="eventdes" name="eventdes" rows="8" cols="20"required="required">'.$key['EVENTDES'].'</textarea>';
                $s.='<div class="row">';
                $s.='<div class="col-lg-12">';
                $s.='<ul class="post-tags">';
                $s.='<li class="item-list"><button class="button5" type="submit" name="updateevent" value="update">Update</button></li>';
                $s.='<li class="item-list"><button class="button5" type="submit" name="deleteevent" value="delete">Delete</button></li></ul>';
                $s.='</form></div></div>';
                $s.='<form method="POST" enctype="multipart/form-data">';
                $s.='<input type="hidden" id="eventid" name="eventid" value="'.$key['EVENTID'].'">';
                $s.='<div class="row">';
                $s.='<div class="col-lg-12">';
                $s.='<ul class="post-tags">';
                $s.='<li class="item-list"><input type="file" name="fileToUpload" id="fileToUpload"></li>';
                $s.='<li class="item-list"><button class="button5" type="submit" name="image" value="image">Update Image</button></li></ul>';
                $s.='</form></div></div>';
                $s.='</div></div></div>';
                echo $s;
                }
                  $s='<div class="col-md-4 col-sm-6">';
                    $s.='<div class="blog-post">';
                      $s.='<div class="blog-thumb">';
                      $s.='<form method="POST" enctype="multipart/form-data">';
                        $s.='<input type="file" name="fileToUpload" id="fileToUpload">';
                      $s.='</div>';
                      $s.='<div class="down-content">';
                        $s.='<label for="eventname">MinPrice - MaxPrice:</label><br><span> <input type="text" id="minprice" name="minprice" size="5"> - <input type="text" id="maxprice" name="maxprice" size="5"> </span><br/>';
                        $s.='<label for="eventname">Event Name:</label><br/><input type="text" id="eventname" name="eventname">';
                        $s.='<p><label for="description">Event Description:</label><br/><textarea id="description" name="description" rows="3" cols="22"></textarea></p>';
                        $s.='<div class="post-options">';
                          $s.='<div class="row">';
                            $s.='<div class="col-lg-12">';
                              $s.='<ul class="post-tags">';
                                $s.='<li><input type="submit" value="Upload" name="submit"></li>';
                              $s.='</ul>';
                            $s.='</div>';
                          $s.='</div>';
                        $s.='</div>';
                        $s.='</form>';
                      $s.='</div>';
                    $s.='</div>';
                  $s.='</div>';
                  echo $s;
              }else{ foreach ($events as $key){
                $s='<div class="col-md-4 col-sm-6">';
                $s.='<div class="blog-post">';
                $s.='<div class="blog-thumb">';
                $s.='<img src="'.$key['PICTURE'].'" alt="">'.'</div>';
                $s.='<div class="down-content">';
                $s.='<div style= "height:49px;"><span >'.number_format((float)$key['MINPRICE'], 2, '.', ',').' - '.number_format((float)$key['MAXPRICE'], 2, '.', ',').' LBP</span></div>';
                $s.='<a href="book-table.php"><h4 style= "height:48px;">'.$key['EVENTNAME'].'</h4></a>';
                $s.='<p style= "height:218px;">'.$key['EVENTDES'].'</p>';
                $s.='<div class="post-options">';
                $s.='<div class="row">';
                $s.='<div class="col-lg-12">';
                $s.='<ul class="post-tags">';
                $s.='<li><i class="fa fa-bullseye"></i></li>';
                $s.='<li><a href="book-table.php">Book a Table</a></li>';
                $s.='</ul>';
                $s.='</div></div></div>';
                $s.='</div></div></div>';
                echo $s;
              }}
          ?>
          </div>
        </div>
      </div>
    </section>

    <section class="call-to-action">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <div class="main-content">
              <div class="row">
                <div class="col-lg-8">
                  <span>Book a table</span>
                  <h4>Sed doloribus accusantium reiciendis et officiis.</h4>
                </div>
                <div class="col-lg-4">
                  <div class="main-button">
                    <a href="book-table.php">Book a table</a>
                  </div>
                </div>
              </div>
            </div>
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
