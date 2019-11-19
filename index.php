<?php 
ob_start();
session_start();
require_once 'dbconnect.php';

if(isset($_SESSION['user'])) {
 	header("Location: home.php");
 	} 
if(isset($_SESSION['admin'])) {
 	header("Location: admin.php");
 	}

 $error = false;

 if(isset($_POST['btn-login'])){
 	$email = trim($_POST['email']);
 	$email = strip_tags($email);
 	$email = htmlspecialchars($email);

 	$password = trim($_POST['password']);
 	$password = strip_tags($password);
 	$password = htmlspecialchars($password);

 	if (empty($email)) {
 		$error = true;
 		$emailError = "Please enter your email address.";
 	}else if( !filter_var($email,FILTER_VALIDATE_EMAIL)){
 		$error = true;
 		$emailError = "Please enter valid email address.";
 	}

 	if(empty($password)){
 		$error = true;
 		$passwordError = "Please enter your password.";
 	}

 	if (!$error){
 		$password = hash('sha256',$password);

 		$res = mysqli_query($conn, "SELECT id,first_name,last_name,email,password,status FROM user WHERE email='$email'");
 		$row = mysqli_fetch_array($res,MYSQLI_ASSOC);
 		$count = mysqli_num_rows($res);

 		if($count == 1 && $row['password'] == $password) {
 			if($row['status']=="admin"){
 				$_SESSION['admin'] = $row['id'];
 				header("Location:admin.php");
 			}else{
 				$_SESSION['user'] = $row['id'];
 				header("Location: home.php");
 			}
 		}else{
 			$errMSG = "Incorrect Credentials, try again..";
 		}
 	}
 }

?>

<!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="utf-8">
 	<meta http-equiv="X-UA-Compatible" content="IE=edge">
 	<title>Your farmer's market - Login</title>
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

 	<div class="container my-4">
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" autocomplete="off">
		
		<h2>Sign In</h2>

		<?php 
		if(isset($errMSG)){
		echo $errMSG; ?>

		<?php 
		} ?>

		<input type="email" name="email" class="form-contol p-2" placeholder="Your email" value="<?php echo $email; ?>" maxlength="40" />
		<span class="text-danger"><?php echo $emailError; ?></span>

		<input type="password" name="password" class="form-contol p-2" placeholder="Your password" maxlength="15" />
		<span class="text-danger"><?php echo $passwordError; ?></span>
		<button type="submit" name="btn-login" class="m-3 btn btn-outline-success">Sign in</button>
		<hr />
		<p>Not registered yet? Click here to sign up</p>
		<a href="register.php"><button type="button" class="mt-2 mb-5 btn btn-outline-warning">Register</button></a>
	</form>
   </div>		
</body>
</html>
<?php ob_end_flush(); ?>