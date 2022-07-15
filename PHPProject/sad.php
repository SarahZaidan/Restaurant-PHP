<?php
session_start();
if(isset($_GET['logout'])){
  if(isset($_SESSION['user'])) unset($_SESSION['user']);
  if(isset($_SESSION['sad'])) unset($_SESSION['sad']);
  if(isset($_SESSION['admin'])) unset($_SESSION['admin']);
  session_destroy();
}
//lezm ello enu l address , phone , maxres
     $db="restaurant";
     $dpass="";
     $server="localhost";
     $user="root";
     $mysqli=new mysqli($server,$user,$dpass,$db);
     if(isset($_POST['changeadd'])){
        $add=$_POST['Address'];
        $sql="UPDATE `rest` SET `ADDRESS`='$add'";
        $stmt=$mysqli->prepare($sql);
        $stmt->execute();
        echo "<script>alert('Address updated!')</script>";
    }
     if(isset($_POST['changephone'])){
        $phone=$_POST['Phone'];
        $sql="UPDATE `rest` SET `PHONE`='$phone'";
        $stmt=$mysqli->prepare($sql);
        $stmt->execute();
        echo "<script>alert('Phone updated!')</script>";
    }
    
    if(isset($_POST['changemaxr'])){
        $maxr=$_POST['Maxr'];
        $sql="UPDATE `rest` SET `MAXRES`='$maxr'";
        $stmt=$mysqli->prepare($sql);
        $stmt->execute();
        echo "<script>alert('Maximum reservation updated!')</script>";
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
<style>

    input {
    width: 100%;
    }
    .btn-primary:hover, .btn-primary:focus {		
	border-color: #f48840;
	background-color: #f48840;
	color: #fff;
    outline: none;;
    }
   .btn-primary,.btn-primary:active {
	color: #fff;
	background: #f48840;
	padding-top: 8px;
	padding-bottom: 6px;
	vertical-align: middle;
	border: none;
    }
    .form-control1 {
	box-shadow: none;		
	font-weight: normal;
	font-size: 5px;
    }
    .form-control1:focus {
	border-color: #050505;
	box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }
   .text{
    width: 100%;
    padding: 12px 20px;
    margin: 8px 0;
    display: inline-block;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
    font-size: 13px;
    }
    .m{
        width:400px;
        height:500px;
        text-align:left;
    }
    .submit {
    margin-left:80%;
    width:20%;
    background-color: #F48840;
    color: white;
    padding: 14px 20px;
    font-size: 13px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-align:center;
    }

    .submit:hover {
    background-color: #F48840;
    }

    .div1 {
    border-radius: 5px;
    background-color: #f2f2f2;
    font-family:16px 'Roboto',sans-serif;
    width:100%;
    
    }
    fieldset{
    
        color:#1e1e1e;
    }

    legend {
    border-radius: 1px;
        color:#1e1e1e;
    padding: 5px 10px;
    text-align: left;
    }
    .split {
    height: 100%;
    width:33.3333333333333333%;
    position: fixed;
    z-index: 1;
    top: 50px;
    overflow-x: hidden;
    padding-top: 0px;
    }

    .left {
    left: 0;
    }

    .right {
    right: 0;  
    }

    .centered {
    position: absolute;
    top: 50%;
    left: 48%;
    transform: translate(-50%, -50%);
    text-align: center;
    }
    .vl1 {
    border-left: 1px solid #D3D3D3;
    height: 730px;
    position: absolute;
    left: 33.333333333333333%;
    margin-left: -3px;
    top: 0;
    }
    .vl2 {
    border-left: 1px solid #D3D3D3;
    height: 730px;
    position: absolute;
    left: 66.66666666666666666666%;
    margin-left: -3px;
    top: 0;
    }
    .label{
        text-align: left;
        font-size:13px;
    }
    .center{
        left:30%;
        width:40%;
        height:100%;
        text-align:left;
    }
    .centerbutton{
    margin-left:80px;
    width:48%;
    background-color: white;
    color: #F48840;
    padding: 10px 15px;
    font-size: 16px;
    border: 1px solid #F48840;
    border-radius: 4px;
    cursor: pointer;
    text-align:center;
    }
    </style>
</head>

<body>
    <div id="preloader">
        <div class="jumper">
            <div></div>
            <div></div>
            <div></div>
        </div>
    </div>  
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
                    <a class="dropdown-item" href="cusprofile.php">Profile</a>
                    <a class="dropdown-item" href="sad.php?logout=1">Logout</a>
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
                    <a class="dropdown-item" href="sad.php?logout=1">Logout</a>
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
                        <form name='form1' method='post'  action='sad.php'>
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
                            <form action='sad.php' method='post'>
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
                            <form action='sad.php' method='post'>
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
                            <form action='sad.php' method='post'>
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
                            <form action='sad.php' method='post'>
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
    <?php
     $sql="SELECT `ADDRESS` , `PHONE` as phone, `MAXRES` as maxres FROM `rest`";
     $stmt=$mysqli->prepare($sql);
     $stmt->execute();
     $info=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
     $maxr=$info[0]['maxres'];
     $phone=$info[0]['phone'];
     if(strlen($phone)==7){
         $phone="0".$phone;
     }
     $address='';
     $address=$info[0]["ADDRESS"];
     if(!isset($_POST['changeadd']) && !isset($_POST['changephone']) && !isset($_POST['changemaxr'])  ){
     $m='<div class="split left">
     <div class="centered">
     <div class="m">
        <fieldset><legend><h4><bold>Edit restuarant info:</bold><h4></legend>
        <form class="form-inline" method="Post" action="sad.php">
            <input class="text" type="text" class="form-control1" placeholder="Address" name="Address" value="'.$address.'" required="">
            <button class="submit" type="submit" name="changeadd" class="btn btn-primary mb-2">Change</button>   
        </form>
        <form class="form-inline" method="Post" action="sad.php">
        <input class="text" type="tel" class="form-control1" name="Phone" value='.$phone.' required="">
        <button class="submit" type="submit" name="changephone" class="btn btn-primary mb-2">Change</button>       
        </form>
        <form class="form-inline" method="Post" action="sad.php">
            <input class="text" type="text" class="form-control1" name="Maxr" value='.$maxr.' required="">
            <button class="submit" type="submit" name="changemaxr" class="btn btn-primary mb-2">Change</button> 
        </form>
        <br><br>
        <form class="form-inline" method="Post" action="customertable.php">
                    <button class="centerbutton" type="submit" name="managecus" class="btn btn-primary mb-2">manage customers</button>
        </form>
        <br>
        <form class="form-inline" method="Post" action="admintable.php">
                    <button class="centerbutton" type="submit" name="manageadmin" class="btn btn-primary mb-2">manage admins</button>
        </form>
        </fieldset>
    </div>
    </div>
    </div>';
     echo $m;
     }
     else{
        $m='<div class="split left">
        <div class="centered">
        <div class="m">
           <fieldset><legend><h4><bold>Edit restuarant info:</bold><h4></legend>
           <form class="form-inline" method="Post" action="sad.php">
               <input class="text" type="text" class="form-control1" placeholder="Address" name="Address" value='.$address.' required="">
               <button class="submit" type="submit" name="changeadd" class="btn btn-primary mb-2">Change</button>   
           </form>
           <form class="form-inline" method="Post" action="sad.php">
           <input class="text" type="tel" class="form-control1" name="Phone" value='.$phone.' required="">
           <button class="submit" type="submit" name="changephone" class="btn btn-primary mb-2">Change</button>       
           </form>
           <form class="form-inline" method="Post" action="sad.php">
               <input class="text" type="text" class="form-control1" name="Maxr" value='.$maxr.' required="">
               <button class="submit" type="submit" name="changemaxr" class="btn btn-primary mb-2">Change</button> 
           </form>
           <form class="form-inline" method="Post" action="customertable.php">
                       <button class="centerbutton" type="submit" name="managecus" class="btn btn-primary mb-2">manage customers</button>
           </form>
           <form class="form-inline" method="Post" action="admintable.php">
                       <button class="centerbutton" type="submit" name="manageadmin" class="btn btn-primary mb-2">manage admins</button>
           </form>
           </fieldset>
       </div>
       </div>
       </div>';
        echo $m;

     }


     ?>
    
    <div class="vl1"></div>
  <?php 
        if(isset($_POST['addcus'])){
            $name=$_POST['Name'];
            $uname=$_POST['Username'];
            $pass=$_POST['Password'];
            $email=$_POST['Email'];
            $add=$_POST['Address'];
            $dob=$_POST['DOB'];
            $phone=$_POST['Phone'];   
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
                $m='<script> alert("Used username")</script>';
                echo $m;
                $m="<div class='split center'>
                <div class='centered'>
                <fieldset><legend><h4><bold>Customer regestration:</bold><h4></legend>
                <form class='form-inline' method='Post' action='sad.php'>
                    <input class='text' type='text' class='form-control1' name='Name' value=".$name." required='required'>
                    <input class='text' type='text' class='form-control1'  name='Username' placeholder='Username' required='required'>
                    <input class='text' type='password' class='form-control1' name='Password' placeholder='Password' required='required'>
                    <input class='text' type='email' class='form-control1' name='Email' value=".$email." required='required'>
                    <input class='text' type='text' class='form-control1' name='Address' id='inputAddress' value=".$add.">
                    <label class='label' for='inputAddress'>Date of Birth</label>
                    <input class='text' type='date' class='form-control1' name='DOB' value=".$dob." required='required'>
                    <label class='label' for='phone'>Phone number</label>
                        <input class='text' type='tel' class='form-control1' name='Phone' value=".$phone."  name='phone' >
                        <button class='submit' type='submit' name='addcus' class='btn btn-primary mb-2'>Add</button>       
                        </form>                
                    </fieldset>
                </div>
            </div>";
            echo $m;
            }
            else{
            echo '<div style="color:red;margin-left:0">User is added successfully</div>';
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
            $m='<script> alert("Customer added!")</script>';
                echo $m;
            $m="<div class='split center'>
            <div class='centered'>
            <fieldset><legend><h4><bold>Customer regestration:</bold><h4></legend>
            <form class='form-inline' method='Post' action='sad.php'>
                <input class='text' type='text' class='form-control1' name='Name' placeholder='Name' required='required'>
                <input class='text' type='text' class='form-control1'  name='Username' placeholder='Username' required='required'>
                <input class='text' type='password' class='form-control1' name='Password' placeholder='Password' required='required'>
                <input class='text' type='email' class='form-control1' name='Email' placeholder='Email' required='required'>
                <input class='text' type='text' class='form-control1' name='Address' id='inputAddress' placeholder='Address'>
                <label class='label' for='inputAddress'>Date of Birth</label>
                <input class='text' type='date' class='form-control1' name='DOB' placeholder='date' required='required'>
                <label class='label' for='phone'>Phone number</label>
                <input class='text' type='tel' class='form-control1' name='Phone' placeholder='Phone' name='phone' >
                <button class='submit' type='submit' name='addcus' class='btn btn-primary mb-2'>Add</button>       
            </form>
            </fieldset>
            </div>
            </div>";
            echo $m;
            }
        }
        if(!isset($_POST['addcus'])){
            $m="<div class='split center'>
            <div class='centered'>
            <fieldset><legend><h4><bold>Customer regestration:</bold><h4></legend>
            <form class='form-inline' method='Post' action='sad.php'>
                <input class='text' type='text' class='form-control1' name='Name' placeholder='Name' required='required'>
                <input class='text' type='text' class='form-control1'  name='Username' placeholder='Username' required='required'>
                <input class='text' type='password' class='form-control1' name='Password' placeholder='Password' required='required'>
                <input class='text' type='email' class='form-control1' name='Email' placeholder='Email' required='required'>
                <input class='text' type='text' class='form-control1' name='Address' id='inputAddress' placeholder='Address'>
                <label class='label' for='inputAddress'>Date of Birth</label>
                <input class='text' type='date' class='form-control1' name='DOB' placeholder='date' required='required'>
                <label class='label' for='phone'>Phone number</label>
                <input class='text' type='tel' class='form-control1' name='Phone' placeholder='Phone' name='phone' >
                <button class='submit' type='submit' name='addcus' class='btn btn-primary mb-2'>Add</button>
                </form>
            </fieldset>

      </div>
    </div>";
    echo $m;
           
        }
?>
  
<div class="vl2"></div>


      <?php 
      if(!isset($_POST['addadmin'])){
            $m="<div class='split right'>
            <div class='centered'>
            <div class='m'>
            <fieldset><legend><h4><bold>Admin regestration:</bold><h4></legend>
                    <form class='form-inline' method='Post' action='sad.php'>
                        <input class='text' type='text' class='form-control1' name='Name' placeholder='Name' required='required'>
                        <input class='text' type='text' class='form-control1'  name='Username' placeholder='Username' required='required'>
                        <input class='text' type='password' class='form-control1' name='Password' placeholder='Password' required='required'>
                        <input class='text' type='email' class='form-control1' name='Email' placeholder='Email' required='required'>
                        <input class='text' type='tel' class='form-control1' name='Phone' placeholder='Phone' name='phone' >
                        <button class='submit' type='submit' name='addadmin' class='btn btn-primary mb-2'>Add</button>       
                    </form>
            </fieldset>
        </div>
        </div>
        </div>";
        echo $m;
      }
      if(isset($_POST['addadmin'])){
        $name=$_POST['Name'];
        $uname=$_POST['Username'];
        $pass=$_POST['Password'];
        $email=$_POST['Email'];
        $phone=$_POST['Phone'];   
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
            $m='<script> alert("Used username")</script>';
            echo $m;
            $m="<div class='split right'>
            <div class='centered'>
            <div class='m'>
        <fieldset><legend><h4>Admin regestration:</bold><h4></legend>
                <form class='form-inline' method='Post' action='sad.php'>
                    <input class='text' type='text' class='form-control1' name='Name' value=".$name." required='required'>
                    <input class='text' type='text' class='form-control1'  name='Username' placeholder='Username' required='required'>
                    <input class='text' type='password' class='form-control1' name='Password' placeholder='Password' required='required'>
                    <input class='text' type='email' class='form-control1' name='Email' value=".$email." required='required'>
                    <input class='text' type='tel' class='form-control1' name='Phone' value=".$phone." name='phone' >
                    <button class='submit' type='submit' name='addadmin' class='btn btn-primary mb-2'>Add</button>       
                </form>
            </fieldset>
        </div>
        </div>
        </div>";
        echo $m;
        }
        else{
            echo '<div style="color:red;margin-left:0">User is added successfully</div>';
            $sql="SELECT MAX(ADID) AS max FROM `admin`";
            $stmt=$mysqli->prepare($sql);
            $stmt->execute();
            $max=$stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            $max=$max[0]['max'] +1;
            $sql="INSERT INTO `admin`(`ADID`, `ADNAME`, `USERNAME`, `PASSWORD`, `EMAIL`, `PHONE`) VALUES (?,?,?,?,?,?)";
            $stmt=$mysqli->prepare($sql);
            $stmt->bind_param("issssi",$max,$name,$uname,$pass,$email,$phone);
            $stmt->execute();
            $stmt->close();
            $m='<script> alert("Admin added!!")</script>';
                echo $m;
            $m="<div class='split right'>
            <div class='centered'>
            <div class='m'>
            <fieldset><legend><h4><bold>Admin regestration:</bold><h4></legend>
                    <form class='form-inline' method='Post' action='sad.php'>
                        <input class='text' type='text' class='form-control1' name='Name' placeholder='Name' required='required'>
                        <input class='text' type='text' class='form-control1'  name='Username' placeholder='Username' required='required'>
                        <input class='text' type='password' class='form-control1' name='Password' placeholder='Password' required='required'>
                        <input class='text' type='email' class='form-control1' name='Email' placeholder='Email' required='required'>
                        <input class='text' type='tel' class='form-control1' name='Phone' placeholder='Phone' name='phone' >
                        <button class='submit' type='submit' name='addadmin' class='btn btn-primary mb-2'>Add</button>       
                    </form>
            </fieldset>
          </div>
          </div>
        </div>";
        echo $m;
        }
      }

      
      ?>
   
    
     

    

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



