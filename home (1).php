<?php  
session_start();
require_once 'login.php';
$conn = new mysqli($hn, $un, $pw, $db);    
if ($conn->connect_error){ 
die($conn->connect_error); 
}
if(isset($_GET['searchtitle']) && isset($_GET['searchstudy']) && isset($_GET['searchlocation']) && isset($_GET['searchcompany']) && isset($_GET['searchid']) ){
   $title = get_get($conn, 'searchtitle');
   $study = get_get($conn, 'searchstudy');
   $location = get_get($conn, 'searchlocation');
   $company = get_get($conn, 'searchcompany');
   $id = get_get($conn, 'searchid');
   $query = "SELECT * FROM jobs where title like '%$title%' and study like '%$study%' and location like '%$location%'
             and company like '%$company%' and id like '%$id%'" ;
   $searchresult = $conn->query($query);
   if(!$searchresult) die();
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
  <script>
  function hideIntro(){
     var w = document.getElementById('intro');
     w.style.visibility= 'hidden';
  }
</script>
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
          if(strcmp($_SESSION[type],"employer")==0){
              echo '<button style="margin:10px; height:30px; width:100px"><a style ="text-decoration: none;color:black" href="posting.php">Post a Job</a></button>';
              echo '<button style="margin:10px; height:30px; width:100px"><a style ="text-decoration: none;color:black" href="mail.php">My Mail</a></button>';
          }       

         
  echo '</div>';


echo '<div id="main" >';


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
echo <<<_END
      <div id="intro">
      Welcome to the Job Search<br>
      We hope that you find the perfect job for you
      </div>
_END;
echo'</div>';


echo <<<_END

   <div id="topmenu">
    <ul>
      <li><a class="active" href="home.php">Home</a></li>
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
  $conn->close();

  

 ?>