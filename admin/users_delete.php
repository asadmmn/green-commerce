<?php
	include 'includes/session.php';

	if(isset($_POST['delete'])){
		$id = $_POST['id'];
		
		$conn = $pdo->open();

		try{


			            // Delete from green_businesses table first
						$stmt = $conn->prepare("DELETE FROM green_businesses WHERE user_id=:id");
						$stmt->execute(['id'=>$id]);
			
						$stmt = $conn->prepare("DELETE FROM residents WHERE user_id=:id");
						$stmt->execute(['id'=>$id]);
			
						$stmt = $conn->prepare("DELETE FROM product_votes WHERE user_id=:id");
						$stmt->execute(['id'=>$id]);
			
			$stmt = $conn->prepare("DELETE FROM users WHERE id=:id");
			$stmt->execute(['id'=>$id]);

			$_SESSION['success'] = 'User deleted successfully';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}

		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Select user to delete first';
	}

	header('location: users.php');
	
?>