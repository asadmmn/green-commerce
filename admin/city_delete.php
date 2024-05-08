<?php
	include 'includes/session.php';

	if(isset($_POST['confirmDelete'])){
		$id = $_POST['delete_id'];
		
		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("DELETE FROM city WHERE id=:id");
			$stmt->execute(['id'=>$id]);

			$_SESSION['success'] = 'City deleted successfully';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Select city to delete first';
	}

	header('location: city.php');
	
?>