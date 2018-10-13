<?php

	session_start();

	//check is there a cookie
	if(array_key_exists("id", $_COOKIE)&& $_COOKIE ['id']){
		
		//update the session to cookie
		$_SESSION['id'] = $_COOKIE['id'];
	}
	
	//check is there a session
	if(array_key_exists("id", $_SESSION)){
		
		//Logged in
		//echo "<p>Logged IN! <a href='index.php?logout=1'> Log out</a></p>";
		
		//Get the diary already stored
		include("connection.php");
      
		$query = "SELECT diary FROM `users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
		$row = mysqli_fetch_array(mysqli_query($link, $query));
 
      $diaryContent = $row['diary'];
		
	} else{
		
		//Not logged in
		//direct to home page
		
		header("Location: index.php");
		
	}
	
	include("header.php");
?>

<nav class="navbar navbar-light bg-faded navbar-fixed-top">
  

  <a class="navbar-brand" href="#">Secret Diary</a>

    <div class="pull-xs-right">
      <a href ='index.php?logout=1'>
        <button class="btn btn-success-outline" type="submit">Logout</button></a>
    </div>

</nav>
	
	<div class="container-fluid" id="containerLoggedInPage">
	
		<textarea id="diary" class="form-control"><?php echo $diaryContent; ?></textarea>
	
	</div>


<?
	
	include("footer.php");

?>