<?php 

	require_once "dbconnect.php";

	if(isset($_GET["id"])){
		$id = $_GET["id"];
		$sql = "DELETE FROM `item` WHERE item_id = $id";

		if(mysqli_query($conn ,$sql)){
			echo 'Record Deleted<br><a class="btn btn-danger" href="admin.php">Back</a>';
		}

	}
?>