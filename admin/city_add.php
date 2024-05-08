
<?php
	include 'includes/session.php';

	if(isset($_POST['add'])){
		$name = $_POST['name'];
		$zip = $_POST['zip'];

		$conn = $pdo->open();

		$stmt = $conn->prepare("SELECT *, COUNT(*) AS numrows FROM city WHERE name=:name");
		$stmt->execute(['name'=>$name]);
		$row = $stmt->fetch();

		if($row['numrows'] > 0){
			$_SESSION['error'] = 'Category already exist';
		}
		else{
			try{
				$stmt = $conn->prepare("INSERT INTO city (name,zip_code) VALUES (:name,:zip_code)");
				$stmt->execute(['name'=>$name,'zip_code'=>$zip]);
				$_SESSION['success'] = 'City added successfully';
			}
			catch(PDOException $e){
				$_SESSION['error'] = $e->getMessage();
			}
		}

		$pdo->close();
	}
	else{
		$_SESSION['error'] = 'Fill up category form first';
	}

	header('location: city.php');

?>