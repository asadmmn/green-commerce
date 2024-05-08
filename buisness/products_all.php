<?php
	include 'includes/session.php';

	$output = '';

	$conn = $pdo->open();

	$stmt = $conn->prepare("SELECT * FROM products where user_id = $user->id");
	$stmt->execute();
	foreach($stmt as $row){
		$output .= "
			<option value='".$row['id']."' class='append_items'>".$row['name']."</option>
		";
	}

	$pdo->close();
	echo json_encode($output);

?>