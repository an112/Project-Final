<?php  
session_start();
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);  
if ($conn->connect_error){ 
die($conn->connect_error); 
}


echo <<<_END
<!DOCTYPE html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <head>
   <link href="../css/styles.css" rel="stylesheet" type="text/css"> 
   <script type="text/javascript" src="../js/scripts.js"></script>
  </head>
  <body>
  

  <div id="sidebar" class="visible">
  	   <div id="sidebar_button">
  	   		<button  onclick="toggleSidebar()"></button>
  	   </div>
          <p> Welcome $_SESSION[username]</p>
	   <p >Search Jobs</p>
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
	   		<input type="submit" value="GO" style="margin:10px"></input>
	    
	   
	   </form>
  </div>
_END;

echo '<div id="main" >';
$query = "SELECT * FROM jobs ORDER BY visits DESC";
$searchresult = $conn->query($query);
if(!$searchresult) die();
$rows = $searchresult->num_rows;
for($i=0;$i<$rows;$i++){
   $searchresult->data_seek($i);
   $row = $searchresult->fetch_array(MYSQLI_ASSOC);
   echo '<div class="content">';
   echo '<p style="color:white">';
   
   echo 'Title: '. $row[title];
   echo '<br>Company: '. $row[company];
   echo '<br>Location: '.$row[location]; 
   echo '</p>';   
   if(strcmp($_SESSION[type],"employee")==0){
   echo '<form action="lookmeup.php" method="get">';
   echo '<input type="hidden" name="lookmeup" value="'.$row['id'].'"></input>';
   echo '<input type="submit" style = "float:right;margin:10px" value="I am interested"></input>';
   echo '</form>';
   } 
   echo '</div>';
}

echo'</div>';


echo <<<_END

   <div id="topmenu">
    <ul>
      <li><a  href="home.php">Home</a></li>
      <li><a href="new.php">New Jobs</a></li>
      <li><a class="active" href="#">Popular Jobs</a></li>
      <li style = "float:right;"><a href="account.php">Account</a></li>
    </ul>
   </div>
  </body>
</html>
_END;

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
  $result->close();
  $searchresult->close();
  $conn->close();
?>