<?php
	include 'includes/session.php';

	if(isset($_POST['editcity'])){ // Changed from 'edit' to 'editcity' to match button name
		$city_id = $_POST['edit_id']; // Changed from 'userid' to 'edit_id' to match the hidden input name
		$city_name = $_POST['edit_name'];
        $zip= $_POST['zip'];
		// echo  "dfd $city_id" ;
		$conn = $pdo->open();

		try{
			$stmt = $conn->prepare("UPDATE city SET name=:name ,zip_code=:zip WHERE id=:id");
			$stmt->execute(['name'=>$city_name, 'id'=>$city_id ,'zip_code'=>$zip]);

			$_SESSION['success'] = 'City updated ';
		}
		catch(PDOException $e){
			$_SESSION['error'] = $e->getMessage();
		}
		
		$pdo->close();

		header('location: city.php'); // Redirect to appropriate page after updating
	}
?>
