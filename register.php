<?php 
ob_start(); 
session_start();

if (isset($_SESSION['user'])!="") {
	header("Location: home.php");	
}
if (isset($_SESSION['admin'])!="") {
	header("Location: admin.php");	
}

include_once 'dbconnect.php';

$error = false;

if (isset($_POST['btn-signup'])) {
	$first_name = trim($_POST['first_name']);
	$first_name = strip_tags($first_name);
	$first_name = htmlspecialchars($first_name);

	$last_name = trim($_POST['last_name']);
	$last_name = strip_tags($last_name);
	$last_name = htmlspecialchars($last_name);

	$email = trim($_POST['email']);
	$email = strip_tags($email);
	$email = htmlspecialchars($email);

	$password = trim($_POST['password']);
	$password = strip_tags($password);
	$password = htmlspecialchars($password);

	//first name validation
	if (empty($first_name)) {
		$error = true;
		$first_nameError = "Please enter your first name.";
	}else if(strlen($first_name)<3){
		$error = true;
		$first_nameError = "First name must have at least 3 characters.";
	}else if(!preg_match("/^[a-zA-Z]+$/", $first_name)){
		$error = true;
		$first_nameError = "First name must contain alphabets and space.";
	}

   //last name validation
	if (empty($last_name)) {
		$error = true;
		$last_nameError = "Please enter your last name.";
	}else if(strlen($last_name)<3){ //stringlength
		$error = true;
		$last_nameError = "Last name must have at least 3 characters.";
	}else if(!preg_match("/^[a-zA-Z]+$/", $last_name)){
		$error = true;
		$last_nameError = "Last name must contain alphabets and space.";
	}
	//email validation
	if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
		$error = true;
		$emailError = "Please enter valid email address";
	}else{
		$query = "SELECT email FROM user WHERE email='$email'";
		$result = mysqli_query($conn,$query);
		$count = mysqli_num_rows($result);
		if($count!=0){
			$error = true;
			$emailError = "Provided email is already in use.";
		}
	}

	//password validation
	if(empty($password)){
		$error = true;
		$passwordError = "Please enter password.";
	}else if(strlen($password)<6){
		$error = true;
		$passwordError = "Password must have at least 6 characters.";
	}

	//password hashing for security
	$password = hash('sha256',$password);

	//no errors -> continue signup
	if(!$error){
		$query = "INSERT INTO user(first_name,last_name,email,password) VALUES ('$first_name','$last_name','$email','$password')";
		//$passwordword - hashed already
		$res = mysqli_query($conn,$query);

		if($res){
			$errTyp = "success";
			$errMSG = "Successfully registered, you may login now";
			unset($first_name);
			unset($last_name);
			unset($email);
			unset($password);	
		}else{
			$errTyp = "danger";
			$errMSG = "Someting went wrong,try again later..";
		}
	}
}

 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="utf-8">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
 	<title>Your Farmer's Market - Registration</title>
 	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<!-- google-fonts -->
  	<link href="https://fonts.googleapis.com/css?family=Cinzel&display=swap" rel="stylesheet">
  	<link href="https://fonts.googleapis.com/css?family=Pontano+Sans&display=swap" rel="stylesheet">
 </head>
 <body>
 	<header>
<!-- navbar --->
   	<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-light">
   		<a class="navbar-brand pl-5 my-3">Your farmer's market</a>
   	</nav>
   </header>
   <!-- registration form -->
 	<div class="container mt-3">
 	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
 		
 		<h2>Register</h2>
 		<hr/>

 		<?php 
 		if(isset($errMSG)){

 		 ?>

 		<div class="alert alert-<?php echo $errTyp ?>">
 			<?php echo $errMSG; ?>
 		</div>
 	<?php 
 } ?>

 <input type="text" name="first_name" class="form-contol p-2" placeholder="Enter First Name" maxlength="50" value="<?php echo $first_name; ?>" />
 <span class="text-danger"><?php echo $first_nameError; ?></span>

<input type="text" name="last_name" class="form-contol p-2" placeholder="Enter Last Name" maxlength="50" value="<?php echo $last_name; ?>" />
 <span class="text-danger"><?php echo $last_nameError; ?></span>

 <input type="email" name="email" class="form-contol p-2" placeholder="Enter your email" maxlength="40" value="<?php echo $email; ?>"/>
 <span class="text-danger"><?php echo $emailError; ?></span>
 
 <input type="password" name="password" class="form-contol p-2" placeholder="Enter password" maxlength="15" />
 <span class="text-danger"><?php echo $passwordError; ?></span>
<button type="submit" class="btn btn-warning m-3" name="btn-signup">Sign Up</button>
<hr />
<p>Already registered?</p>
<a href="index.php" class="mt-2 mb-5 btn btn-outline-success">Sign in here</a>
 	</form>

 		
 </div>
 </body>
 </html>
 <?php ob_end_flush(); ?>