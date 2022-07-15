<?php
session_start();
if(isset($_GET['logout'])){
  if(isset($_SESSION['user'])) unset($_SESSION['user']);
  if(isset($_SESSION['sad'])) unset($_SESSION['sad']);
  if(isset($_SESSION['admin'])) unset($_SESSION['admin']);
  session_destroy();
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
?><!DOCTYPE html>
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
<style>
    .form-control {
	box-shadow: none;		
	font-weight: normal;
    }
    .form-control:focus {
	border-color: #f48840;
	box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }
body {
    background: white;
}
.profile-button {
    background: #f48840;
    box-shadow: none;
    border: none
}

.profile-button:hover {
    background: #f48840;
}

.profile-button:focus {
    background: #f48840;
    box-shadow: none
}

.profile-button:active {
    background: #f48840;
    box-shadow: none
}

.back:hover {
    color: #f48840;
    cursor: pointer;
}

.labels {
    font-size: 14px;
    font-weight:bold;
}
.width{
    width:900px;
}
.placeholder{
    color:red;
    font-size: 14px;

}
.vl1 {
    width: 70%;
    border-left: 1px solid #D3D3D3;
    height: 151.5%;
    position: absolute;
    left: 30.99999999999999999%;
    margin-left: -3px;
    top: 0;
    }
    .vl2 {
        width: 70%;
    border-left: 2px solid #D3D3D3;
    height: 151.5%;
    position: absolute;
    left: 62.8%;
    margin-left: -3px;
    top: 0;
    }

</style>
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
      <nav class="navbar navbar-expand-lg navbar-default navbar-light">
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

              <li class="nav-item">
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
                    <a class="dropdown-item" href="profile.php">Profile</a>
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
                        <form name='form1' method='post'  action='cusprofile.php'>
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
                            <form action='cusprofile.php' method='post'>
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
                            <form action='cusprofile.php' method='post'>
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
                            <form action='cusprofile.php' method='post'>
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
                            <form action='cusprofile.php' method='post'>
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
    <br><br><br>
    <?php 
    $db="restaurant";
    $dpass="";
    $server="localhost";
    $user="root";
    $mysqli=new mysqli($server,$user,$dpass,$db);
    $sql="SELECT  `CUSTNAME` as namee , `USERNAME` as username, `EMAIL`as email,`ADDRESS` as addres,
    `DOB` as dob, `PHONE`as phone  FROM `customer` WHERE CUSTID='$_SESSION[user]'";
    $stmt=$mysqli->prepare($sql);
    $stmt->execute();
    $info=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $name=$info[0]['namee'];
    $uname=$info[0]['username'];
    $email=$info[0]['email'];
    $phone=$info[0]['phone'];
    $add=$info[0]['addres'];
    $dob=$info[0]['dob'];
    if(strlen($phone)==7){
        $phone="0".$phone;
    }
    if(!isset($_POST['save'])){
                
        $m='<form action="cusprofile.php" method="POST">
        <div class="container rounded bg-white mt-5 mb-5">
        <div class="row">
            <div style="border-left:1px solid #dee2e6!important;border-bottom:1px solid #dee2e6!important;border-top:1px solid #dee2e6!important;margin-left:auto;margin-right:auto;" class="col-md-5 border-right width">
                <div class="p-3 py-5">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="text-right">Profile Settings</h4>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-6"><label class="labels">Name</label><input type="text" class="form-control" name="name" placeholder="name" value='.$name.' required="required"></div>
                        <div class="col-md-6"><label class="labels">Username</label><input type="text" name="uname" class="form-control"name="uname" value='.$uname.' placeholder="username" required="required"></div>
                        <div class="col-md-6"><label class="labels">Old Password</label><input type="password" class="form-control" name="oldpass" placeholder="Old password" ></div>
                        <div class="col-md-6"><label class="labels">New Password</label><input type="password" class="form-control" name="newpass" placeholder="New password"></div>
                        </div>
                    <div class="row mt-3">
                        <div class="col-md-12"><label class="labels">Phone Number</label><input type="tel" class="form-control" name="phone"placeholder="enter phone number" value='.$phone.' pattern="[0-9]{8}" required="required"></div>
                        <div class="col-md-12"><label class="labels">Address</label><input type="text" class="form-control" name="add" value='.$add.' required="required"></div>
                        <div class="col-md-12"><label class="labels">Email</label><input type="email" class="form-control" name="email" placeholder="enter email id" value='.$email.' required="required"></div>
                        <div class="col-md-12"><label class="labels" for="inputAddress">Date of Birth</label><input type="date" class="form-control" name="DOB" placeholder="date" value='.$dob.' required="required"></div>
                    </div>
                    <div class="mt-5 text-center"><input type="submit" class="btn btn-primary profile-button"  name="save" value="Save Profile"></div>
                </div>
            </div>
            </div>
        </div>
    </div>
    </div>
    </form>';
    
echo $m;
    }
     
    if(isset($_POST['save'])){
            $name=$_POST['name'];
            //$uname=$_POST['uname'];
            $email=$_POST['email'];
            $address=$_POST['add'];
            $phone=$_POST['phone'];
            if(strlen($phone)==7){
                $phone="0".$phone;
            }
            $dob=$_POST['DOB'];
            if($_POST['oldpass']==0 || $_POST['oldpass']=='' ||$_POST['oldpass']==null ){
            if(strcmp($_POST['uname'],$uname)!=0  ){
                $usname=$_POST['uname'];
                $sql="SELECT * FROM `customer` WHERE `USERNAME`='$usname'";
                $result=$mysqli->query($sql);
                $rownumc=$result->num_rows;
                $sql="SELECT * FROM `admin` WHERE `USERNAME`='$usname'";
                $result=$mysqli->query($sql);
                $rownuma=$result->num_rows;
                $sql="SELECT * FROM `sad` WHERE `USERNAME`='$usname'";
                $result=$mysqli->query($sql);
                $rownums=$result->num_rows;
                        //if username mwjood bl cus or admin or sadmin
                    if($rownumc > 0 || $rownuma > 0 || $rownums > 0){
                        $sql="UPDATE `customer` SET `CUSTNAME`='$name',`EMAIL`='$email',`ADDRESS`='$address',`PHONE`='$phone',`DOB`='$dob' WHERE CUSTID='$_SESSION[user]'";
                        $stmt=$mysqli->prepare($sql);
                        $stmt->execute();
                        echo"<script>
                    alert('Used username,other info updated!') </script>";
                        $m='<form action="cusprofile.php" method="POST">
                            <div class="container rounded bg-white mt-5 mb-5">
                                <div class="row">
                                <div style="border-left:1px solid #dee2e6!important;border-bottom:1px solid #dee2e6!important;border-top:1px solid #dee2e6!important;margin-left:auto;margin-right:auto;" class="col-md-5 border-right width">
                                    <div class="p-3 py-5">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="text-right">Profile Settings</h4>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6"><label class="labels">Name</label><input type="text" class="form-control" name="name" placeholder="name" value='.$name.'  required="required"></div>
                                            <div class="col-md-6"><label class="labels">Username</label><input type="text" class="form-control" name="uname" placeholder="Username" value='.$uname.' required="required"></div>
                                            <div class="col-md-6"><label class="labels">Old Password</label><input type="password" class="form-control" name="oldpass" placeholder="Old password" ></div>
                        <div class="col-md-6"><label class="labels">New Password</label><input type="password" class="form-control" name="newpass" placeholder="New password"></div>
                                            </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12"><label class="labels">Phone Number</label><input type="tel" class="form-control" name="phone" value='.$phone.'  pattern="[0-9]{8}" required="required"></div>
                                            <div class="col-md-12"><label class="labels">Address</label><input type="text" class="form-control" name="add" value='.$add.' required="required"></div>
                                            <div class="col-md-12"><label class="labels">Email</label><input type="email" class="form-control" name="email" placeholder="enter email id" value='.$email.' required="required"></div>
                                            <div class="col-md-12"><label class="labels" for="inputAddress">Date of Birth</label><input type="date" class="form-control" name="DOB" placeholder="date" value='.$dob.' required="required"></div>
                                            </div>
                                        <div class="mt-5 text-center"><input type="submit" class="btn btn-primary profile-button"  name="save" value="Save Profile"></div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                        </form>';
                echo $m; 
                }
                else{
                    $sql="UPDATE `customer` SET `CUSTNAME`='$name',`USERNAME`='$uname' ,`EMAIL`='$email',`ADDRESS`='$address',`PHONE`='$phone',`DOB`='$dob' WHERE CUSTID='$_SESSION[user]'";
                    $stmt=$mysqli->prepare($sql);
                    $stmt->execute();
                    echo"<script>
                    alert('Profile updated successfully!') </script>";
                    $m='<form action="cusprofile.php" method="POST">
                            <div class="container rounded bg-white mt-5 mb-5">
                                <div class="row">
                                <div style="border-left:1px solid #dee2e6!important;border-bottom:1px solid #dee2e6!important;border-top:1px solid #dee2e6!important;margin-left:auto;margin-right:auto;" class="col-md-5 border-right width">
                                    <div class="p-3 py-5">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="text-right">Profile</h4></div>
                                        <div class="row mt-2">
                                            <div class="col-md-6"><label class="labels">Name</label><input type="text" class="form-control" name="name" placeholder="name" value='.$name.'  required="required"></div>
                                            <div class="col-md-6"><label class="labels">Username</label><input type="text" class="form-control" name="uname" value='.$uname.' required="required"></div>
                                            <div class="col-md-6"><label class="labels">Old Password</label><input type="password" class="form-control" name="oldpass" placeholder="Old password" ></div>
                        <div class="col-md-6"><label class="labels">New Password</label><input type="password" class="form-control" name="newpass" placeholder="New password"></div>
                                            </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12"><label class="labels">Phone Number</label><input type="tel" class="form-control" name="phone" value='.$phone.'  pattern="[0-9]{8}" required="required"></div>
                                            <div class="col-md-12"><label class="labels">Address</label><input type="text" class="form-control" name="add" value='.$address.' required="required"></div>
                                            <div class="col-md-12"><label class="labels">Email</label><input type="email" class="form-control" name="email" placeholder="enter email id" value='.$email.' required="required"></div>
                                            <div class="col-md-12"><label class="labels" for="inputAddress">Date of Birth</label><input type="date" class="form-control" name="DOB" placeholder="date" value='.$dob.' required="required"></div>
                                            </div>
                                        <div class="mt-5 text-center"><input type="submit" class="btn btn-primary profile-button"  name="save" value="Save Profile"></div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                        </form>';
                echo $m; 
                
            
        }
        }
        else{
            $name=$_POST['name'];
            $uname=$_POST['uname'];
            $email=$_POST['email'];
            $address=$_POST['add'];
            $phone=$_POST['phone'];
            if(strlen($phone)==7){
                $phone="0".$phone;
            }
            $dob=$_POST['DOB'];
            $sql="UPDATE `customer` SET `CUSTNAME`='$name',`USERNAME`='$uname' ,`EMAIL`='$email',`ADDRESS`='$address',`PHONE`='$phone',`DOB`='$dob' WHERE CUSTID='$_SESSION[user]'";
            $stmt=$mysqli->prepare($sql);
            $stmt->execute();
            echo"<script>
            alert('Profile updated successfully!') </script>";
            $sql="SELECT `CUSTNAME` as namee , `USERNAME` as username, `EMAIL`as email,`ADDRESS` as addres,
            `DOB` as dob, `PHONE`as phone  FROM `customer` WHERE CUSTID='$_SESSION[user]'";
            $stmt=$mysqli->prepare($sql);
            $stmt->execute();
            $info=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $name=$info[0]['namee'];
            $uname=$info[0]['username'];
            $email=$info[0]['email'];
            $phone=$info[0]['phone'];
            if(strlen($phone)==7){
                $phone="0".$phone;
            }
            $address=$info[0]['addres'];
            $dob=$info[0]['dob'];
            $m='<form action="cusprofile.php" method="POST">
                    <div class="container rounded bg-white mt-5 mb-5">
                        <div class="row">
                        <div style="border-left:1px solid #dee2e6!important;border-bottom:1px solid #dee2e6!important;border-top:1px solid #dee2e6!important;margin-left:auto;margin-right:auto;" class="col-md-5 border-right width">
                            <div class="p-3 py-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="text-right">Profile</h4> </div>
                                <div class="row mt-2">
                                    <div class="col-md-6"><label class="labels">Name</label><input type="text" class="form-control" name="name" placeholder="name" value='.$name.' required="required"></div>
                                    <div class="col-md-6"><label class="labels">Username</label><input type="text" class="form-control" name="uname" value='.$uname.' required="required"></div>
                                    <div class="col-md-6"><label class="labels">Old Password</label><input type="password" class="form-control" name="oldpass" placeholder="Old password" ></div>
                        <div class="col-md-6"><label class="labels">New Password</label><input type="password" class="form-control" name="newpass" placeholder="New password"></div>
                                    </div>
                                <div class="row mt-3">
                                    <div class="col-md-12"><label class="labels">Phone Number</label><input type="tel" class="form-control" name="phone" value='.$phone.' pattern="[0-9]{8}" required="required"></div>
                                    <div class="col-md-12"><label class="labels">Address</label><input type="text" class="form-control" name="add" value='.$address.' required="required"></div>
                                    <div class="col-md-12"><label class="labels">Email</label><input type="email" class="form-control" name="email"  value='.$email.' required="required"></div>
                                    <div class="col-md-12"><label class="labels" for="inputAddress">Date of Birth</label><input type="date" class="form-control" name="DOB" placeholder="date" value='.$dob.' required="required"></div>
                                </div>
                                <div class="mt-5 text-center"><input type="submit" class="btn btn-primary profile-button"  name="save" value="Save Profile"></div>
                            </div>
                        </div>
                    </div>
                    </div>
                    </div>
                </div>
                </form>';
        echo $m; 

        }
        

    }
    elseif(($_POST['oldpass']!=0 || $_POST['oldpass']!='' || $_POST['oldpass'] != null) && $_POST['newpass']!=0 || $_POST['newpass']!='' || $_POST['newpass'] != null){
        $pass=$_POST['oldpass'];
        $newpass=$_POST['newpass'];
        $sql="SELECT * FROM `customer` WHERE `CUSTID`='$_SESSION[user]' and `PASSWORD`='$pass'";
        $result=$mysqli->query($sql);
        $rownum=$result->num_rows;
        if($rownum != 0){
            $sql="UPDATE `customer` SET `PASSWORD`='$newpass' WHERE CUSTID='$_SESSION[user]'";
            $stmt=$mysqli->prepare($sql);
            $stmt->execute();
        }
        if($rownum == 0)
        {
            echo "<script>alert('Wrong old password not updatedddd!')</script>";
        }
            if(strcmp($_POST['uname'],$uname)!=0  ){
                $usname=$_POST['uname'];
                $sql="SELECT * FROM `customer` WHERE `USERNAME`='$usname'";
                $result=$mysqli->query($sql);
                $rownumc=$result->num_rows;
                $sql="SELECT * FROM `admin` WHERE `USERNAME`='$usname'";
                $result=$mysqli->query($sql);
                $rownuma=$result->num_rows;
                $sql="SELECT * FROM `sad` WHERE `USERNAME`='$usname'";
                $result=$mysqli->query($sql);
                $rownums=$result->num_rows;
                        //if username mwjood bl cus or admin or sadmin
                    if($rownumc > 0 || $rownuma > 0 || $rownums > 0){
                        $sql="UPDATE `customer` SET `CUSTNAME`='$name',`EMAIL`='$email',`ADDRESS`='$address',`PHONE`='$phone',`DOB`='$dob' WHERE CUSTID='$_SESSION[user]'";
                        $stmt=$mysqli->prepare($sql);
                        $stmt->execute();
                        echo"<script>
                    alert('Used username,other info updated!') </script>";
                        $m='<form action="cusprofile.php" method="POST">
                            <div class="container rounded bg-white mt-5 mb-5">
                                <div class="row">
                                <div style="border-left:1px solid #dee2e6!important;border-bottom:1px solid #dee2e6!important;border-top:1px solid #dee2e6!important;margin-left:auto;margin-right:auto;" class="col-md-5 border-right width">
                                    <div class="p-3 py-5">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="text-right">Profile Settings</h4>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-6"><label class="labels">Name</label><input type="text" class="form-control" name="name" placeholder="name" value='.$name.'  required="required"></div>
                                            <div class="col-md-6"><label class="labels">Username</label><input type="text" class="form-control" name="uname" placeholder="Username" value='.$uname.' required="required"></div>
                                            <div class="col-md-6"><label class="labels">Old Password</label><input type="password" class="form-control" name="oldpass" placeholder="Old password" ></div>
                        <div class="col-md-6"><label class="labels">New Password</label><input type="password" class="form-control" name="newpass" placeholder="New password"></div>
                                            </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12"><label class="labels">Phone Number</label><input type="tel" class="form-control" name="phone" value='.$phone.'  pattern="[0-9]{8}" required="required"></div>
                                            <div class="col-md-12"><label class="labels">Address</label><input type="text" class="form-control" name="add" value='.$add.' required="required"></div>
                                            <div class="col-md-12"><label class="labels">Email</label><input type="email" class="form-control" name="email" placeholder="enter email id" value='.$email.' required="required"></div>
                                            <div class="col-md-12"><label class="labels" for="inputAddress">Date of Birth</label><input type="date" class="form-control" name="DOB" placeholder="date" value='.$dob.' required="required"></div>
                                            </div>
                                        <div class="mt-5 text-center"><input type="submit" class="btn btn-primary profile-button"  name="save" value="Save Profile"></div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                        </form>';
                echo $m; 
                }
                else{
                    $sql="UPDATE `customer` SET `CUSTNAME`='$name',`USERNAME`='$uname' ,`EMAIL`='$email',`ADDRESS`='$address',`PHONE`='$phone',`DOB`='$dob' WHERE CUSTID='$_SESSION[user]'";
                    $stmt=$mysqli->prepare($sql);
                    $stmt->execute();
                    echo"<script>
                    alert('Profile updated successfully!') </script>";
                    $m='<form action="cusprofile.php" method="POST">
                            <div class="container rounded bg-white mt-5 mb-5">
                                <div class="row">
                                <div style="border-left:1px solid #dee2e6!important;border-bottom:1px solid #dee2e6!important;border-top:1px solid #dee2e6!important;margin-left:auto;margin-right:auto;" class="col-md-5 border-right width">
                                    <div class="p-3 py-5">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="text-right">Profile</h4></div>
                                        <div class="row mt-2">
                                            <div class="col-md-6"><label class="labels">Name</label><input type="text" class="form-control" name="name" placeholder="name" value='.$name.'  required="required"></div>
                                            <div class="col-md-6"><label class="labels">Username</label><input type="text" class="form-control" name="uname" value='.$uname.' required="required"></div>
                                            <div class="col-md-6"><label class="labels">Old Password</label><input type="password" class="form-control" name="oldpass" placeholder="Old password" ></div>
                        <div class="col-md-6"><label class="labels">New Password</label><input type="password" class="form-control" name="newpass" placeholder="New password"></div>
                                            </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12"><label class="labels">Phone Number</label><input type="tel" class="form-control" name="phone" value='.$phone.'  pattern="[0-9]{8}" required="required"></div>
                                            <div class="col-md-12"><label class="labels">Address</label><input type="text" class="form-control" name="add" value='.$address.' required="required"></div>
                                            <div class="col-md-12"><label class="labels">Email</label><input type="email" class="form-control" name="email" placeholder="enter email id" value='.$email.' required="required"></div>
                                            <div class="col-md-12"><label class="labels" for="inputAddress">Date of Birth</label><input type="date" class="form-control" name="DOB" placeholder="date" value='.$dob.' required="required"></div>
                                            </div>
                                        <div class="mt-5 text-center"><input type="submit" class="btn btn-primary profile-button"  name="save" value="Save Profile"></div>
                                    </div>
                                </div>
                            </div>
                            </div>
                            </div>
                        </div>
                        </form>';
                echo $m; 
                
            
        }
        }
        else{
            $name=$_POST['name'];
            $uname=$_POST['uname'];
            $email=$_POST['email'];
            $address=$_POST['add'];
            $phone=$_POST['phone'];
            if(strlen($phone)==7){
                $phone="0".$phone;
            }
            $dob=$_POST['DOB'];
            $sql="UPDATE `customer` SET `CUSTNAME`='$name',`USERNAME`='$uname' ,`EMAIL`='$email',`ADDRESS`='$address',`PHONE`='$phone',`DOB`='$dob' WHERE CUSTID='$_SESSION[user]'";
            $stmt=$mysqli->prepare($sql);
            $stmt->execute();
            echo"<script>
            alert('Profile updated successfully!') </script>";
            $sql="SELECT `CUSTNAME` as namee , `USERNAME` as username, `EMAIL`as email,`ADDRESS` as addres,
            `DOB` as dob, `PHONE`as phone  FROM `customer` WHERE CUSTID='$_SESSION[user]'";
            $stmt=$mysqli->prepare($sql);
            $stmt->execute();
            $info=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $name=$info[0]['namee'];
            $uname=$info[0]['username'];
            $email=$info[0]['email'];
            $phone=$info[0]['phone'];
            if(strlen($phone)==7){
                $phone="0".$phone;
            }
            $address=$info[0]['addres'];
            $dob=$info[0]['dob'];
            $m='<form action="cusprofile.php" method="POST">
                    <div class="container rounded bg-white mt-5 mb-5">
                        <div class="row">
                        <div style="border-left:1px solid #dee2e6!important;border-bottom:1px solid #dee2e6!important;border-top:1px solid #dee2e6!important;margin-left:auto;margin-right:auto;" class="col-md-5 border-right width">
                            <div class="p-3 py-5">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="text-right">Profile</h4> </div>
                                <div class="row mt-2">
                                    <div class="col-md-6"><label class="labels">Name</label><input type="text" class="form-control" name="name" placeholder="name" value='.$name.' required="required"></div>
                                    <div class="col-md-6"><label class="labels">Username</label><input type="text" class="form-control" name="uname" value='.$uname.' required="required"></div>
                                    <div class="col-md-6"><label class="labels">Old Password</label><input type="password" class="form-control" name="oldpass" placeholder="Old password" ></div>
                        <div class="col-md-6"><label class="labels">New Password</label><input type="password" class="form-control" name="newpass" placeholder="New password"></div>
                                    </div>
                                <div class="row mt-3">
                                    <div class="col-md-12"><label class="labels">Phone Number</label><input type="tel" class="form-control" name="phone" value='.$phone.' pattern="[0-9]{8}" required="required"></div>
                                    <div class="col-md-12"><label class="labels">Address</label><input type="text" class="form-control" name="add" value='.$address.' required="required"></div>
                                    <div class="col-md-12"><label class="labels">Email</label><input type="email" class="form-control" name="email"  value='.$email.' required="required"></div>
                                    <div class="col-md-12"><label class="labels" for="inputAddress">Date of Birth</label><input type="date" class="form-control" name="DOB" placeholder="date" value='.$dob.' required="required"></div>
                                </div>
                                <div class="mt-5 text-center"><input type="submit" class="btn btn-primary profile-button"  name="save" value="Save Profile"></div>
                            </div>
                        </div>
                    </div>
                    </div>
                    </div>
                </div>
                </form>';
        echo $m; 

        }
        }
 }
?>    
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