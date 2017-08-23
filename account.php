

<?php  
session_start();

require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);  
if ($conn->connect_error){ 
die($conn->connect_error); 
}
if(isset($_POST['username']) && isset($_POST['password']) && isset($_POST['accountType']) && isset($_POST['email'])){

// $query = "INSERT INTO userinfo (id,username,password,type,email)".
//          "VALUES ('null','$username', '$token', '$type','$email')";

$query =$conn->prepare("INSERT INTO userinfo ( username, password, type, email) VALUES ( ?, ?,? ,?)");
$query->bind_param("ssss", $username, $token,$type, $email);
 $username = get_post($conn,'username');
 $password = get_post($conn,'password');
 $type = get_post($conn,'accountType');
 $email = get_post($conn,'email');
 $salt1 = "qm&h*";
 $salt2 = "pg!@";
 $token = hash('ripemd128', "$salt1$password$salt2");
 $query->execute();
 $result  = $query->get_result();

 if (!$result) header("location:success.php");
}

if(isset($_POST['enterusername']) && isset($_POST['enterpassword'])){
 $username = get_post($conn,'enterusername');
 $password = get_post($conn,'enterpassword');
 $salt1 = "qm&h*";
 $salt2 = "pg!@";
 $check = hash('ripemd128', "$salt1$password$salt2");
 $query = "SELECT * FROM userinfo WHERE username='$username'";
 $result = $conn->query($query);
 if (!$result) die("Cannot access mysql");
 $row = $result->fetch_array(MYSQLI_ASSOC);
 $result->close();
 if(strcmp($check,$row['password'])==0){
    
    $_SESSION['username'] = $username;
    $_SESSION['password'] = $password;
    $_SESSION['type'] = $row["type"];
    $_SESSION['id'] = $row["id"];
    $_SESSION['email'] = $row["email"];
    header("location:home.php");
 }
 else{
    


    header("location:account.php");
 }
}



/*
$query  = "SELECT * FROM userinfo";
$result = $conn->query($query);
if (!$result) die ("Database access failed: " . $conn->error);

$rows = $result->num_rows;
*/




echo <<<_END
 <html>
 <head>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <link href="../css/styles.css" rel="stylesheet" type="text/css"> 
 <script type="text/javascript" src="../js/scripts.js"></script>



 </head>
  <body>
     <div id="sidebar" class="visible">
  	   <div id="sidebar_button">
  	   		<button  onclick="toggleSidebar()"></button>
  	   </div>
           <p> Welcome $_SESSION[username]</p>
	   <p>Search Jobs</p>
	   <form method="get" action="home.php">
	   		<label>Title</label><br>
			<input type="text" name="searchtitle" id="searchtitle" value=""></input><br>
_END;
                        $query = "SELECT DISTINCT study FROM jobs";
                        $result = $conn->query($query);
                        if(!$result) die($conn->error);
                        $rows = $result->num_rows;
			echo '<label>Field of Study</label><br>';
			echo '<select name="searchstudy" id="searchstudy">';
                        echo '<option value=""></option>';
                        echo '<option value="'.$_SESSION["username"].'"></option>';
                        for($i = 0; $i < $rows; $i++){
                             $result->data_seek($i);
                             $row = $result->fetch_array(MYSQLI_ASSOC);
                             
                             echo '<option value="'.$row['study'].'">'.$row['study'].'</option>';
                        }
			echo '</select><br>';
                        $query = "SELECT DISTINCT location FROM jobs";
                        $result = $conn->query($query);
                        if(!$result) die($conn->error);
                        $rows = $result->num_rows;
			echo '<label>Location</label><br>';
			echo '<select name="searchlocation" id="searchlocation">';
                        echo '<option value=""></option>';
                        for($i = 0; $i < $rows; $i++){
                             $result->data_seek($i);
                             $row = $result->fetch_array(MYSQLI_ASSOC);
                             echo '<option value="'.$row['location'].'">'.$row['location'].'</option>';
                        }
			echo '</select><br>';
                        $query = "SELECT DISTINCT company FROM jobs";
                        $result = $conn->query($query);
                        if(!$result) die($conn->error);
                        $rows = $result->num_rows;
			echo '<label>Company</label><br>';
			echo '<select name="searchcompany" id="searchcompany">';
                        echo '<option value=""></option>';
                        for($i = 0; $i < $rows; $i++){
                             $result->data_seek($i);
                             $row = $result->fetch_array(MYSQLI_ASSOC);
                             echo '<option value="'.$row['company'].'">'.$row['company'].'</option>';
                        }
			echo '</select><br>';
                        
echo <<<_END
			<label>Job ID</label><br>
			<input type="text" name="searchid" id="searchid" value=""></input><br>
	   		<input type="submit" value="GO" style="margin:10px" ></input>
	                
	   
	   </form>
           
  </div>
 <div id="main" >
          <p> Sign up </p>
  	  <form action="account.php" method="post">
	   		<label>Username (letters and numbers only)</label><br>
			<input type="text" name="username" id="username" required></input><p id="usernameerror"></p><br>
			<label>E-mail</label><br>
			<input type="email" name="email" id="email" required></input><br>
                        <label>Password</label><br>
			<input type="password" name="password" id="password" onkeyup="checkPass()" required></input><br>
			<label>Re-enter Password</label><br>
			<input type="password" name="reenter" id="reenter" onkeyup="checkPass()" required></input><p id="passworderror" style = "color:red;"></p><br>
			<label>Employer or Employee</label><br>
			<select name="accountType" id="accountType">
					<option value="employer">Employer</option>
					<option value="employee">Employee</option>
			</select>
			
	   		<input type="submit" value="CREATE" style="margin:10px"></input>
	   </form>
           <p> If you already have an account</p>
           <form action="account.php" method="post">
                        <label>Enter Username</label><br>
                        <input type="text" name="enterusername" id="enterusername" required></input><br>     
                        <label>Enter Password</label><br>
                        <input type="password" name="enterpassword" id="enterpassword" required></input><br>
                        <input type="submit" value="SIGN IN" style="margin:10px"></input>
           </form>
           
          
  </div>

 _END;


echo <<<_END
   
   <div id="topmenu">
    <ul>
      <li><a  href="home.php">Home</a></li>
      <li><a href="new.php">New Jobs</a></li>
      <li><a href="popular.php">Popular Jobs</a></li>
      <li style = "float:right;"><a class="active" href="account.php">Account</a></li>
    </ul>
   </div>
  </body>
 </html>

_END;


/*for ($j = 0 ; $j < $rows ; ++$j)
  {
    $result->data_seek($j);
    $row = $result->fetch_array(MYSQLI_NUM);

    echo <<<_END
  <div style = "float:right;">
    id $row[0]
     username $row[1]
  pass $row[2]
     type $row[3]
  </div>

_END;
}*/
  $query->close();
  $result->close();
  $conn->close();
  function get_post($conn, $var)
  {
    $var = $conn->real_escape_string($_POST[$var]);
    $var = sanitizeString($var);    
    return $var;
  }
  function sanitizeString($var)  {  
   $var = stripslashes($var);    
   $var = strip_tags($var);    
   $var = htmlentities($var);  
   return $var;  
  }
  function get_get($conn, $var)
  {
    $var = $conn->real_escape_string($_GET[$var]);
    $var = sanitizeString($var);    
    return $var;
  }

 ?>
