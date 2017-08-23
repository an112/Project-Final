<?php  
session_start();
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);  
if ($conn->connect_error){ 
die($conn->connect_error); 
}
if(isset($_GET['lookmeup'])){
  $id = get_get($conn, 'lookmeup');
  $query = "SELECT visits FROM jobs where id='$id'";
  $searchresult = $conn->query($query);
  if(!$searchresult) die();
  $row = $searchresult->fetch_array(MYSQLI_ASSOC);
  $visits = $row[visits]+1;
  $query = "UPDATE jobs set visits = '$visits' where id='$id'";
  $searchresult = $conn->query($query);
  if(!$searchresult) die();
  $query = "SELECT * FROM jobs where id='$id'";
  $searchresult = $conn->query($query);
  if(!$searchresult) die();
}
if(isset($_GET['desc']) && isset($_GET['jobid']) && isset($_GET['posterid'])){
 
  $query =$conn->prepare("INSERT INTO submissions (posterid,jobid,submit,sender) VALUES (?,?,?,?)");
  $query->bind_param("iiss", $poster, $job,$text,$sender);
  $text = get_get($conn, 'desc');
  $poster = get_get($conn, 'posterid');
  $job = get_get($conn, 'jobid');
  $sender = $_SESSION[username];
  $query->execute();
  header("location:success.php");
}


echo <<<_END
<!DOCTYPE html>
<html lang="en">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
  <head>
   <link href="../css/styles.css" rel="stylesheet" type="text/css"> 
   <script type="text/javascript" src="../js/scripts.js">   </script>

  </head>
  <body>
  <div id="sidebar" class="visible">
  	   <div id="sidebar_button">
  	   		<button  onclick="toggleSidebar()"></button>
  	   </div>
           <p> Welcome $_SESSION[username]</p>
	   <p >Search Jobs</p>
	   <form method="get" action="home.php" onsubmit="hideIntro()">
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
	   		<input type="submit" value="GO" style="margin:10px" ></input>
	                
	   
	   </form>
           
  </div>
_END;

echo '<div id="main" >';


$searchresult->data_seek(0);
$row = $searchresult->fetch_array(MYSQLI_ASSOC);
echo <<<_END
<div class="description"><pre style="color:white;font-size:20px">
   Title: $row[title]                                                                   ID: $row[id]
   Study: $row[study]
Location: $row[location]
 Company: $row[company]

$row[description]

<label>Enter your information here(600 characters MAX)</label></pre>
<textarea form="sub" rows="4" cols="100" maxlength="600" name="desc"></textarea>
<form id="sub" method="get" action="lookmeup.php">
<input type="hidden" name="jobid" value=$row[id] />
<input type="hidden" name="posterid" value=$_SESSION[id] />
<input type="submit" value="Submit" style="margin:10px" />
</form>

</div>


_END;
echo'</div>';



echo <<<_END

   <div id="topmenu">
    <ul>
      <li><a href="home.php">Home</a></li>
      <li><a href="new.php">New Jobs</a></li>
      <li><a href="popular.php">Popular Jobs</a></li>
      <li style = "float:right;"><a href="account.php">Account</a></li>
    </ul>
   </div>
  </body>
</html>
_END;


  $result->close();
  $searchresult->close();
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