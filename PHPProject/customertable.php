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
if(isset($_POST['delete'])){
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
    mysqli_query($conn,"DELETE FROM `reservation` WHERE CUSTID='$_POST[delete]'");
    $sql="SELECT * FROM `ordertb` WHERE CUSTID='$_POST[delete]'";
          $stmt = $conn->prepare($sql);
            $stmt->execute();
            $username = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
            if(count($username) ){
              foreach($username as $key){
                mysqli_query($conn,"DELETE FROM `itemorder` WHERE ORDERID='$key[ORDERID]'");
              }}
              mysqli_query($conn,"DELETE FROM `cart` WHERE CUSTID='$_POST[delete]'");
    mysqli_query($conn,"DELETE FROM `ordertb` WHERE CUSTID='$_POST[delete]'");
    mysqli_query($conn,"DELETE FROM `rating` WHERE CUSTID='$_POST[delete]'");
    mysqli_query($conn,"DELETE FROM `itemrating` WHERE CUSTID='$_POST[delete]'");
	mysqli_query($conn,"DELETE FROM `customer` WHERE CUSTID='$_POST[delete]'");
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
.styled-table {
    border-collapse: collapse;
    margin: auto;
    font-size: 0.9em;
    font-family: sans-serif;
    min-width: 750px;
    min-height: 350px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    font-weight: bold;
    font-family:'Comic Sans MS';
}
.styled-table thead tr  {
    background-color: #f48840;
    color: #ffffff;
    text-align: center;
    font-weight: bold;
    font-family:'Comic Sans MS';
}
.styled-table th,
.styled-table td  {
    padding: 12px 15px;
    font-weight: bold;
    font-family:'Comic Sans MS';
    text-align: center;
}
.styled-table tbody tr {
    border-bottom: 1px solid #dddddd;
    font-weight: bold;
    font-family:'Comic Sans MS';
    text-align: center;
}

.styled-table tbody tr:nth-of-type(even) {
    background-color: #f3f3f3;
    font-weight: bold;
    font-family:'Comic Sans MS';
    text-align: center;
}

.styled-table tbody tr:last-of-type {
    border-bottom: 2px solid black;
    font-weight: bold;
    font-family:'Comic Sans MS';
    text-align: center;
}
.styled-table tbody tr.active-row {
    font-weight: bold;
    color: #009879;
    font-family:'Comic Sans MS';
    text-align: center;
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
                        <form name='form1' method='post'  action='customertable.php'>
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
                            <form action='customertable.php' method='post'>
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
                            <form action='customertable.php' method='post'>
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
                            <form action='customertable.php' method='post'>
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
                            <form action='customertable.php' method='post'>
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
    <!-- page content -->
    <br><br><br><br><br><br><br><br>
    <?php
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

        $sql = "SELECT `CUSTID`, `CUSTNAME`, `USERNAME`, `EMAIL`, `ADDRESS`, `DOB`, `PHONE` FROM `customer`";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table class='styled-table'><thead><tr><th>ID</th><th>Name</th><th>Username</th><th>Email</th><th>Address</th><th>DOB</th><th>Phone</th><th>Delete</th></tr></thead>";
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["CUSTID"]. "</td><td>" . $row["CUSTNAME"]. "</td><td>" . $row["USERNAME"]. "</td><td>" . $row["EMAIL"]."</td><td>". $row["ADDRESS"]."</td><td>". $row["DOB"]. "</td><td>";
                 if(strlen($row["PHONE"])==7){
                     $row["PHONE"]="0".$row["PHONE"];
                }
                echo $row["PHONE"]. "</td><td>";
                echo"<form method='post' action='customertable.php' >";
                echo'<button type="submit" name="delete" value="'.$row["CUSTID"].'">Delete</button>';
                echo "</form>";
                echo "</td></tr>";	
            }
            echo "</table>";
        } else {
            echo "0 results";
        }

        $conn->close();
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
