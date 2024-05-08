<?php include 'includes/session.php'; ?>
<?php
$slug = $_GET['category'];

$conn = $pdo->open();

try {
    $stmt = $conn->prepare("SELECT * FROM category WHERE cat_slug = :slug");
    $stmt->execute(['slug' => $slug]);
    $cat = $stmt->fetch();
    $catid = $cat['id'];
} catch (PDOException $e) {
    echo "There is some problem in connection: " . $e->getMessage();
}

$pdo->close();
?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

    <?php include 'includes/navbar.php'; ?>

    <div class="content-wrapper">
        <div class="container">

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-sm-9">
                        <form method="GET" action="">
                            <select name="price">
                                <option value="">select price</option>
                                <option value="100">$100 price</option>
                                <option value="200">$200 price</option>
                                <option value="300">$300 price</option>
                                <option value="400">$400 price</option>
                                <option value="500">$500 price</option>
                                <option value="500">$600 price</option>
                                <option value="500">$700 price</option>
                                <option value="500">$800 price</option>
                                <option value="500">$900 price</option>
                                <option value="500">$1000 price</option>
                                <option value="500">$1100 price</option>
                            </select>
                            <input type="hidden" name="category" value="<?php echo $slug; ?>">
                            <button type="submit">Filter</button>
                        </form>
                        <h1 class="page-header"><?php echo $cat['name']; ?></h1>
                        <?php
$conn = $pdo->open();
try {
    $inc = 3;
    $price = isset($_GET['price']) ? $_GET['price'] : '';
    if ($price) {
        $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :catid AND price <= :price AND status='approved'");
        $stmt->execute(['catid' => $catid, 'price' => $price]);
    } else {
        $stmt = $conn->prepare("SELECT * FROM products WHERE category_id = :catid AND status ='approved' ");
        $stmt->execute(['catid' => $catid]);
    }
    foreach ($stmt as $row) {
        $image = (!empty($row['photo'])) ? 'images/' . $row['photo'] : 'images/noimage.jpg';
        $inc = ($inc == 3) ? 1 : $inc + 1;
        if ($inc == 1) echo "<div class='row'>";
        echo "
            <div class='col-sm-4'>
                <div class='box box-solid'>
                    <div class='box-body prod-body'>
                    <a href='product.php?product=" . $row['slug'] . "'style='color:black;'><img src='" . $image . "' width='100%' height='230px' class='thumbnail'>
                        <h5>" . $row['name'] . "</h5>
                    </div>
                    <div class='box-footer'>
                        <b>&euro; " . number_format($row['price'], 2) . "</b></a>
                        <div class='voting'>
                            <form method='POST'>
                                <input type='hidden' name='product_id' value='" . $row['id'] . "'>";
                                if(isset($_SESSION['user']['id'])){
        // Check if the user has already voted for this product
        $stmt_check_vote = $conn->prepare("SELECT * FROM product_votes WHERE user_id = :user_id AND product_id = :product_id");
        $stmt_check_vote->execute(['user_id' => $_SESSION['user']['id'], 'product_id' => $row['id']]);
        $has_voted = $stmt_check_vote->fetch();

        if (!$has_voted) {
            // Display voting buttons if the user hasn't voted
            echo "
                Is this product beneficial for the environment? <button type='submit' name='vote' value='1'>YES</button>
                <button type='submit' name='vote' value='-1'>NO</button>";
        } else {
            // Display a message if the user has already voted
            echo "<span>You have already voted for this product.</span>";
        }
    }
        echo "
                            </form>
                        </div>
                    </div>
                </div>
            </div>";

        // Handle voting only if the user hasn't voted already
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote']) && !$has_voted) {
            $vote = $_POST['vote']; // Get the vote value
            // Insert the vote into the database
            $stmt_insert_vote = $conn->prepare("INSERT INTO product_votes (user_id, product_id, vote) VALUES (:user_id, :product_id, :vote)");
            $stmt_insert_vote->execute(['user_id' => $_SESSION['user']['id'], 'product_id' => $row['id'], 'vote' => $vote]);

            // Display message after vote
            echo "<script>
                var messageContainer = document.querySelector('.message-container');
                messageContainer.innerHTML = 'Thank you for your vote!';
                setTimeout(function() {
                    messageContainer.innerHTML = '';
                }, 3000); // Remove message after 3 seconds
            </script>";
        }

        if ($inc == 3) echo "</div>";
    }
    if ($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>";
    if ($inc == 2) echo "<div class='col-sm-4'></div></div>";
} catch (PDOException $e) {
    echo "There is some problem in connection: " . $e->getMessage();
}
$pdo->close();
?>

                    </div>
                    <div class="col-sm-3">
                        <?php include 'includes/sidebar.php'; ?>
                    </div>
                </div>
            </section>

        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
<script>
    alert;
</script>