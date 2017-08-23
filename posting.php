<?php  
session_start();
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);    
if ($conn->connect_error){ 
die($conn->connect_error); 
}
if(isset($_POST['title'])){
 $query =$conn->prepare("INSERT INTO jobs (title,study,location,company,posterid,description,visits)".
          "VALUES (?,?, ?, ?,?,?,?)");
 $query->bind_param("ssssisi",$title,$study,$location,$company,$posterid,$desc,$visits);
 $title = get_post($conn,'title');
 $study = get_post($conn,'study');
 $company = get_post($conn,'company');
 $desc = get_post($conn,'desc');
 $city = get_post($conn,'city');
 $province = get_post($conn,'province');
 $country = get_post($conn,'country');
 $location = $city.", ".$province.", ".$country;
 $posterid = $_SESSION[id];
 $visits = 0;
 //$query = "INSERT INTO jobs (title,study,location,company,id,posterid,description,visits)".
 //        "VALUES ('$title','$study', '$location', '$company','null','$posterid','$desc','0')";
 $query->execute();
 $result  = $query->get_result();

 if (!$result) header("location:success.php");

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

			echo '<label>Job ID</label><br>';
			echo '<input type="text" name="searchid" id="searchid" value=""></input><br>';
	   		echo '<input type="submit" value="GO" style="margin:10px" ></input>';
	  echo '</form>';
              
         
    echo '</div>';

echo <<<_END
    <div id="main" >
    <p> Post new job here </p>
  	  <form action="posting.php" method="post" id="posting">
	   		<label>Title</label><br>
			<input type="text" name="title" id="title" required></input><br>
			<label>Study</label><br>
			<input type="text" name="study" id="study" required></input><br>
                        <label>City</label><br>
			<input type="text" name="city" id="city"  required></input><br>
                        <label>Province</label><br>
                        <input type="text" name="province" id="province" maxlength="2" style="text-transform:uppercase" required></input><br>
                        <label>Country</label><br>
                        <input type="text" name="country" id="country" required></input><br>
			<label>Company</label><br>
			<input type="text" name="company" id="company" required></input><br>
			<label>Description</label><br>
			<textarea form="posting" rows="4" cols="100" maxlength="300" name="desc" id="desc" required></textarea>
			
	   		<input type="submit" value="POST" style="margin:10px"></input>
	   </form>
    </div>

_END;
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
  $query->close();
  $conn->close();

  

 ?>