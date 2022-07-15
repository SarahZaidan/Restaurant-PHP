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
if(isset($_POST['update'])){
  $mysqli = new mysqli('localhost','root','','restaurant');
  if($mysqli->connect_error) exit('error connecting to db');
  foreach($_POST['result'] as $key){
    if($_POST[$key]==0){
      $sql = "DELETE FROM `cart` WHERE `CUSTID`='$_POST[custid]'and`ITEMID`='$key'";  
    }else $sql = "UPDATE `cart` SET `COUNT`='$_POST[$key]' WHERE `CUSTID`='$_POST[custid]'and`ITEMID`='$key'";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
  }
}
if(isset($_POST['order'])){
  $mysqli = new mysqli('localhost','root','','restaurant');
  if($mysqli->connect_error) exit('error connecting to db');
  $sql = "SELECT MAX(ORDERID) as MAX1 FROM `ordertb`";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  $max = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
  foreach($max as $key){
    $max1=$key['MAX1']+1;
  }
  $i=$_SESSION['user'];
  $j=0;
  date_default_timezone_set('Asia/Beirut');
  $date = date('y-m-d h:i:s');
  $sql = "INSERT INTO `ordertb` VALUES ('$max1','$i','$j','$_POST[total]','$date')";
  $stmt = $mysqli->prepare($sql);
  $stmt->execute();
  
  foreach($_POST['result'] as $key){
    $sql = "INSERT INTO `itemorder` VALUES ('$key','$max1','$_POST[$key]')";
    $stmt = $mysqli->prepare($sql);
    $stmt->execute();
    $sql = "DELETE FROM `cart` WHERE `CUSTID`='$_POST[custid]'and`ITEMID`='$key'"; 
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
                  
                  $s=' <li class="nav-item active"><a class="dropdown-item" href="cart.php"><i style="font-size:24px;color:#f48840" class="fa">&#xf07a;</i></a></li><li class="nav-item dropdown">
                  <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">'.$user.'▼</a>
                  
                  <div class="dropdown-menu">
                    <a class="dropdown-item" href="orders.php">Orders</a>
                    <a class="dropdown-item" href="cusprofile.php">Profile</a>
                    <a class="dropdown-item" href="cart.php?logout=1">Logout</a>
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
                    <a class="dropdown-item" href="cart.php?logout=1">Logout</a>
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
                    <a class="dropdown-item" href="cart.php?logout=1">Logout</a>
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
                        <form name='form1' method='post'  action='cart.php'>
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
                            <form action='cart.php' method='post'>
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
                            <form action='cart.php' method='post'>
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
                            <form action='cart.php' method='post'>
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
                            <form action='cart.php' method='post'>
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
              </li> 
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
      <?php
        if(isset($_SESSION['user'])){
        $mysqli = new mysqli('localhost','root','','restaurant');
        if($mysqli->connect_error) exit('error connecting to db');
        $sql = "SELECT * FROM cart as c join item as i on c.ITEMID=i.ITEMID where CUSTID='$_SESSION[user]'";
        $stmt = $mysqli->prepare($sql);
        $stmt->execute();
        $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        if(count($items)==0){
          if(!isset($_POST['order'])){
          echo '<br><br><br><br><H1 style="text-align:center;color: #f48840;font-size:50px;font-weight:bold;">NO DOLLAR?</h1><H1 style="text-align:center;color: #f48840;font-size:200px;">♡</h1><br><br><br><br>';
          }else echo '<br><br><br><br><H1 style="text-align:center;color: #f48840;font-size:50px;font-weight:bold;">ORDER PLACED</h1><H1 style="text-align:center;color: #f48840;font-size:200px;">♡</h1><br><br><br><br>';
        }else{
        echo '<div style="width:100%;">';
        echo '<table class="cart">';
          echo '<tr><th></th>';
          echo '<th>ITEM</th>';
          echo '<th style="text-align:center">QUANTITY</th>';
          echo '<th style="text-align:center">PRICE</th>';
          echo '<th style="text-align:center">TOTAL</th></tr>';
          echo '<form action="cart.php" method="post">';
            echo '<input type="hidden" name="custid" id="custid" value="'.$_SESSION['user'].'">';
            $total=0;
            $ary=array();
            foreach($items as $key){
              $s='';
              $s.='<tr class="carttr">';
                $s.='<td style="width:20%;"><img src="'.$key['PICTURE'].'.jpg" style="height : 100px;" alt=""></td>';
                $s.='<td style="width:20%; color: #f48840;font-size:18px;font-weight:bold;">'.strtoupper($key['ITEMNAME']).'</td>';
                $s.='<td style="text-align:center;color: #f48840;font-size:18px;font-weight:bold;width:20%">';
                $s.='<input style="width:50px;border:0px;" type="number" class="cartnb" name="'.$key['ITEMID'].'" value = "'.$key['COUNT'].'"></td>';
                if($key['DISCOUNT'] == 0){
                  $price = $key['PRICE'];
                }else $price = $key['PRICE']*((100-$key['DISPER'])/100);
                $s.='<td style="text-align:center;width:20%;font-size:18px;font-weight:bold;">'.number_format((float)$price, 2, '.', ',').' LBP</td>';
                $totalitem = $key['COUNT']*$key['PRICE'];
                $total+=$totalitem;
                $s.='<td style="text-align:center;width:20%;font-size:18px;font-weight:bold;">'.number_format((float)$totalitem, 2, '.', ',').' LBP</td>';
                $ary[]=$key['ITEMID'];
              $s.='</tr>';
              echo $s;
            }
          echo '<tr><td></td><td></td>';
            echo '<td style="text-align:center"><button class="button5" type="submit" name="update" value="update">Update</button>';
            echo '<th style="text-align:right">TOTAL:</th>';
            echo '<td style="text-align:center;color: #f48840;font-size:18px;font-weight:bold;">'.number_format((float)$total, 2, '.', ',').' LBP</td></tr>';
            echo '<tr><td></td><td></td><td></td><td></td><td style="text-align:center;font-weight:bold;"><button class="button5" type="submit" name="order" value="order">Place Order</button></td></tr>';
        echo '</table></div>';
        echo '<input type="hidden" name="total" value="'. $total. '">';
        foreach($ary as $value)
        {
            echo '<input type="hidden" name="result[]" value="'. $value. '">';
        }
      }
    }else echo '<br><br><br><br><H1 style="text-align:center;color: #f48840;font-size:50px;font-weight:bold;">Please Login First</h1><H1 style="text-align:center;color: #f48840;font-size:200px;">♡</h1><br><br><br><br>';
      ?>
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