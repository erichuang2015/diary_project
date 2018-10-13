<?php	

	session_start();

	$error="";
	
	//Logout the user
	//destory the cookie
	if(array_key_exists("logout", $_GET)){
	
		unset($_SESSION);
		setcookie("id","",time()-60*60);
		$_COOKIE["id"]= "";
		
		session_destroy();
	}
	
	//check if they already login dont show the login page again
	//redirect to loginPage
	
	else if ((array_key_exists("id",$_SESSION)AND $_SESSION['id']) OR
	(array_key_exists("id",$_COOKIE)AND $_COOKIE['id'])){
		
		header("Location: loginPage.php");
	}

/*
	else if ($_SESSION['id'] OR array_key_exists("id",$_COOKIE)){
		
		header("Location: loginPage.php");
	}
	*/

	if(array_key_exists("submit", $_POST)){

	//print_r($_POST);
	
	include("connection.php");
	
	
	//end of connect data base
	
	
	
	
	//test case for no name or email or pw(start).
	
	if (!$_POST['email']){
		
		$error .= "Email is require<br>";
	}
	
	if (!$_POST['password']){
		
		$error .= "Password is require<br>";
	}
	
	if ($error !=""){
		
		$error = "<p>There were error(s) in your form:</p>".$error;
		
	}
	
	else{
		
		//check is the user going to signUp
		if($_POST['signUp']== '1'){
			
		
		//Check is the email been taken(Start)
		$query = "SELECT `id` FROM `users` WHERE email = 
		
		'".mysqli_real_escape_string($link, $_POST['email'])."'";
		
		$result = mysqli_query($link, $query);
		
		if(mysqli_num_rows($result) > 0){
			
			//Error msg for email been taken
			$error = "email is taken";
			
		}else{
			
			//Store new user
			//mysqli_real_escape_string - protect from SQL injection
			$query = "INSERT INTO users (`name`, `email`, `password`)VALUES 
			
			('".mysqli_real_escape_string($link, $_POST['name'])."',
			'".mysqli_real_escape_string($link, $_POST['email'])."',
			'".mysqli_real_escape_string($link, $_POST['password'])."'
			)";
			
			if(!mysqli_query($link, $query)){
				
				$error = "<p>Could not sign up now. Please try again</p>";
				
			}else{
				
				
				//security users password using hashed password (Start)
				$password = $_POST['password'];
				
				$hashed_password = password_hash($password, PASSWORD_DEFAULT, array('cost' => 10 ));
				
				//print_r($hashed_password);
				
				$query = "UPDATE `users` SET password = '$hashed_password' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";
				
				
				/*
				$query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id =".mysqli_insert_id($link)." LIMIT 1";
				*/
				mysqli_query($link, $query);
				
				//security users password using hashed password (End)
				
				
				//Stay log in?
				$_SESSION['id'] = mysqli_insert_id($link);
				
				//Button been clicked
				if($_POST['stayLoggedIn'] == '1')
				{
				//Set cookies for 1 year
					setcookie("id", mysqli_insert_id($link),time()+60*60*24*365);
				}
				//echo "Sign up successful";
				
				//Sign up successful, redirect to log in page
				header("Location: loginPage.php");
				}
				
			}
		}
		
		else{
			//echo"Logging In....";
			
			$query = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
			
			$result = mysqli_query($link, $query);
			
			$row = mysqli_fetch_array($result);
			
			if(isset($row)){
				
				$password = $_POST['password'];
				
				if(password_verify($password, $row['password'])){
					
					$_SESSION['id'] = $row['id'];
					
					if($_POST['stayLoggedIn'] == '1'){
						
						setcookie("id", $row['id'], time() +60*60*24*356);
					}
					
					header("Location: loginPage.php");
					
				}else{
				
				echo($row['password'])."<br>";
				echo($password);
				
				$error= "That email/password combination could not be found 1";

				}
			}else{
				
				$error= "That email/password combination could not be found 2";

			}
			
			/*
			$query = "SELECT * FROM users WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";
			
			$result = mysqli_query($link, $query);
			
			$row = mysqli_fetch_array($result);
			
			if(array_key_exists("id", $row)){
				
				$hashed_password = md5(md5($row['id']).$_POST['password']);
				
				//user input password
				//$hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
				
				
				//password store in database
				//$hased = $row['password'];
				


				//if (password_verify($hased, $hashed_password)){
					if($hashed_password == $row['password']){
					$_SESSION['id'] = $row['id'];
					
					if($_POST['stayLoggedIn'] == '1'){
						
						setcookie("id", $row['id'], time() +60*60*24*356);
					}
					
					header("Location: loginPage.php");
					
				}else{
				
				$error= "That email/password combination could not be found. 1";

				}
			}else{
				
				$error= "That email/password combination could not be found. 2";

				}
			}
		}*/
		
	
	//test case for no name or email or pw(end).
			}
		}
	}
?>

<?php include("header.php");?>
  
	<div class="container" id="homePagecontainer">
	
		<h1>Secret Diary</h1>
			<p><strong>Save what you write.</strong></p>
	
			<div id="error"><?php if ($error!="") {
		echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
} ?></div>
		<!--Horizontal form-->
		<!--https://getbootstrap.com/docs/4.1/components/forms/#validation-->
		<form method = "post" id="signUpForm">
			<p><strong>Sing Up Now !!!</strong></p>
			<fieldset class="form-group">
				<input class="form-control" name="name" type="name" placeholder="Your Name">
			</fieldset>
		
			<fieldset class="form-group">
				<input class="form-control" name="email" type="email" placeholder="Your Email address">
			</fieldset>

			<fieldset class="form-group">
				<input class="form-control" name="password" type="password" placeholder="Password">
			</fieldset>
			
			<div class="checkbox">
				<label>
					<input name="stayLoggedIn" type="checkbox" value=1>Stay login
				</label>
			</div>
		
			<input name="signUp" type="hidden" value="1">
			
		
			<fieldset class="form-group">
				<input class="btn btn-success" type="submit" name="submit" value="Sign up!">
			</fieldset>
			
			<p><a class="toggleForms">Log In</a></p>
		</form>

		<form method = "post" id="logInFrom">
			
			<p><strong>Use your User Name and Password for Log In</strong></p>
		
			<fieldset class="form-group">
					<input class="form-control" name="email" type="email" placeholder="Your Email address">
			</fieldset>
		
			<fieldset class="form-group">
				<input class="form-control" name="password" type="password" placeholder="Password">
			</fieldset>
		
			<div class="checkbox">
				<label>
					<input name="stayLoggedIn" type="checkbox" value=1>Stay login
				</label>
			</div>
		
			<input name="signUp" type="hidden" value="0">
		
			<fieldset class="form-group">
				<input class="btn btn-success" type="submit" name="submit" value="Log In!">
			</fieldset>

			<p><a class="toggleForms"> Sign Up</a></p>
		</form>
	</div>

    <?php include("footer.php"); ?>