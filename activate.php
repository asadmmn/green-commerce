<?php include 'includes/session.php'; ?>
<?php
$output = '';
if (!isset($_GET['code']) OR !isset($_GET['user'])) {
    $output .= '
        <div class="alert alert-danger">
            <h4><i class="icon fa fa-warning"></i> Error!</h4>
            Code to activate account not found.
        </div>
        <h4>You may <a href="index.php">Signup</a> .</h4>
    ';
} else {
    $conn = $pdo->open();

    $stmt = $conn->prepare("SELECT * FROM users WHERE activate_code=:code AND id=:id");
    $stmt->execute(['code' => $_GET['code'], 'id' => $_GET['user']]);
    $row = $stmt->fetch();

    if ($row) {
        if ($row['status']) {
            $output .= '
                <div class="alert alert-danger">
                    <h4><i class="icon fa fa-warning"></i> Error!</h4>
                    Account already activated.
                </div>
                <h4>You may <a href="login.php">Login</a> or back to <a href="indexx.php">Homepage</a>.</h4>
            ';
        } else {
            try {
                $stmt = $conn->prepare("UPDATE users SET status=:status WHERE id=:id");
                $stmt->execute(['status' => 1, 'id' => $row['id']]);
                
                $_SESSION['user'] = $row;
                if($row['registration_type'] == 'admin') {
                    header('location: admin/home.php');
                    exit(); // Add an exit after header redirect
                } elseif($row['registration_type'] == 'resident') {
                    header('location: indexx.php');
                    exit(); // Add an exit after header redirect
                } elseif($row['registration_type'] == 'business') {
                    header('location: business/home.php');
                    exit(); // Add an exit after header redirect
                } else {
                    // Handle other registration types or default redirect
                    header('location: login.php');
                    exit(); // Add an exit after header redirect
                }
                
            } catch (PDOException $e) {
                $output .= '
                    <div class="alert alert-danger">
                        <h4><i class="icon fa fa-warning"></i> Error!</h4>
                        '.$e->getMessage().'
                    </div>
                    <h4>You may <a href="index.php">Signup</a> </h4>
                ';
            }
        }
    } else {
        $output .= '
            <div class="alert alert-danger">
                <h4><i class="icon fa fa-warning"></i> Error!</h4>
                Cannot activate account. Wrong code.
            </div>
            <h4>You may <a href="signup.php">Signup</a> or back to <a href="index.php">Homepage</a>.</h4>
        ';
    }
    
    $pdo->close();
}
?>

