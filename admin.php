<?php
ob_start();
session_start();
require_once 'dbconnect.php';

if (!isset($_SESSION['user']) && !isset($_SESSION['admin'])){
	header("Location: index.php");
	exit;
}
if(isset($_SESSION['user'])) {
 	header("Location: home.php");
 	} 

$res=mysqli_query($conn, "SELECT * FROM user WHERE id=".$_SESSION['admin']);
$userRow=mysqli_fetch_array($res, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Welcome - <?php echo $userRow['first_name'].' '.$userRow['last_name']; ?></title>
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
   		<a class="navbar-brand pl-5 my-3" href="index.php">Your farmer's market</a>
   		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
   		<span class="navbar-toggler-icon"></span>
   		</button>
   		<div class="collapse navbar-collapse" id="navbarNav">
   			<ul class="navbar-nav">
   				<li class="nav-item active pl-5">
       				<a class="nav-link" href="index.php">Home<span class="sr-only">(current)</span></a>
      			</li>
      			<li class="nav-item pl-5">
        			<a class="nav-link" href="#">About us</a>
      			</li>
      			<li class="nav-item pl-5">
        			<a class="nav-link" href="#">Contact</a>
      			</li>
   			</ul>
   		</div> 		
   	</nav>
   </header>

<!-- form with content -->
	<div class="container mt-3">
		<h3>Hi Admin <?php echo $userRow['first_name'].' '.$userRow['last_name']; ?></h3>
		<a href="logout.php?logout" class='mt-3 btn btn-outline-dark'>Sign out</a>
		<a href='create.php'class='btn btn-warning float-right'>Add new item</a>
	</div>
	<ul class="col-8 mt-5 mx-auto list-unstyled">
	<?php 

	require_once "dbconnect.php";
	$sql = "SELECT * FROM item";
	
	$result = mysqli_query($conn, $sql);
	if($result->num_rows == 0){
		$row = "No result";
		$res = 0;
	} elseif($result->num_rows == 1){
		$row = $result->fetch_assoc();
		$res = 1;
	} else{
		$row = $result->fetch_all(MYSQLI_ASSOC);
		$res = 2;
	}

	if($res == 0){
		echo $row;
	}elseif($res == 1){
		echo $row["name"]." ". $row["price"]. " " .$row["image"];
	}else{
		foreach ($row as $value) {
			echo "<li class='media mt-2'>
			<img class='mr-3' width='250px' src='".$value["image"]."'>
			<div class='media-body'>
      			<h5 class='mt-0 mb-1'>" .$value["name"]."</h5>
      			<p>". $value["price"]." €/kg</p>
      		<a href='update.php?id=".$value["item_id"]."'><button type='button' class='btn btn-success mb-3 mt-4'>update</button></a><br>
      		<a href='delete.php?id=".$value["item_id"]."'><button type='button' class='btn btn-danger'>delete</button></a><br>
      		</div>
      	</li><br><hr/>";
		}
	}
?>	
	</ul>
	<!-- jQuery,Popper,Bootstrap-->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>

<?php ob_end_flush(); ?>