<?php 
include 'includes/session.php'; // Include session file
?>
<!DOCTYPE html>
<html>
<head>
    <title>Product Page</title>
    <style>
        a {
            color: black;
        }
    </style>
    <?php include 'includes/header.php'; // Include header ?>
</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
    <?php include 'includes/navbar.php'; // Include navbar ?>
    <div class="content-wrapper">
        <div class="container">
            <!-- Main content -->
            <section class="content">
                <?php 
                $conn = $pdo->open(); // Open database connection

                // Check if product slug is set
                if(isset($_GET['product'])) {
                    $slug = $_GET['product']; 

                    try {
                        $stmt = $conn->prepare("SELECT *, products.name AS prodname, category.name AS catname, products.id AS prodid FROM products LEFT JOIN category ON category.id=products.category_id WHERE slug = :slug");
                        $stmt->execute(['slug' => $slug]);
                        $product = $stmt->fetch();
                    } catch(PDOException $e) {
                        echo "There is some problem in connection: " . $e->getMessage();
                    }

                    // Update product view counter
                    $now = date('Y-m-d');
                    if($product['date_view'] == $now) {
                        $stmt = $conn->prepare("UPDATE products SET counter=counter+1 WHERE id=:id");
                        $stmt->execute(['id'=>$product['prodid']]);
                    } else {
                        $stmt = $conn->prepare("UPDATE products SET counter=1, date_view=:now WHERE id=:id");
                        $stmt->execute(['id'=>$product['prodid'], 'now'=>$now]);
                    }
                } else {
                    echo "Product not found.";
                }
                ?>
                <div class="row">
                    <div class="col-sm-9">
                        <?php if(isset($product)): ?>
                            <div class="callout" id="callout" style="display:none">
                                <button type="button" class="close"><span aria-hidden="true">&times;</span></button>
                                <span class="message"></span>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <!-- Display product image -->
                                    <img src="<?php echo (!empty($product['photo'])) ? 'images/'.$product['photo'] : 'images/noimage.jpg'; ?>" width="100%" class="zoom" data-magnify-src="images/large-<?php echo $product['photo']; ?>">
                                    <br><br>
                                    <!-- Voting form -->
                                    <div class='voting'>
                                        <?php
                                        if(isset($_SESSION['user']['id'])){
                                        // Check if the user has already voted for this product
                                        $stmt_check_vote = $conn->prepare("SELECT * FROM product_votes WHERE user_id = :user_id AND product_id = :product_id");
                                        $stmt_check_vote->execute(['user_id' => $_SESSION['user']['id'], 'product_id' => $product['prodid']]);
                                        $has_voted = $stmt_check_vote->fetch();

                                        if (!$has_voted) {
                                            // Display voting buttons if the user hasn't voted
                                            echo "
                                                <form method='POST'>
                                                    <input type='hidden' name='product_id' value='" . $product['id'] . "'>
                                                    Is this product beneficial for the environment? 
                                                    <button type='submit' name='vote' value='1'>YES</button>
                                                    <button type='submit' name='vote' value='-1'>NO</button>
                                                </form>
                                                <div class='message-container'></div>";
                                        } else {
                                            // Display a message if the user has already voted
                                            echo "<span>You have already voted for this product.</span>";
                                        }}
                                        ?>
                                    </div>
                                    <!-- Add to Cart form -->
                                    <form class="form-inline" id="productForm">
                                        <div class="form-group">
                                            <div class="input-group col-sm-5">
                                                <span class="input-group-btn">
                                                    <button type="button" id="minus" class="btn btn-default btn-flat btn-lg"><i class="fa fa-minus"></i></button>
                                                </span>
                                                <input type="text" name="quantity" id="quantity" class="form-control input-lg" value="1">
                                                <span class="input-group-btn">
                                                    <button type="button" id="add" class="btn btn-default btn-flat btn-lg"><i class="fa fa-plus"></i>
                                                    </button>
                                                </span>
                                                <input type="hidden" value="<?php echo $product['prodid']; ?>" name="id">
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-lg btn-flat"><i class="fa fa-shopping-cart"></i> Add to Cart</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-sm-6">
                                    <!-- Product details -->
                                    <h1 class="page-header"><?php echo $product['prodname']; ?></h1>
                                    <h3><b>&euro; <?php echo number_format($product['price'], 2); ?></b></h3>
                                    <p><b>Category:</b> <a href="category.php?category=<?php echo $product['cat_slug']; ?>"><?php echo $product['catname']; ?></a></p>
                                    <p><b>Description:</b></p>
                                    <p><?php echo $product['description']; ?></p>
                                </div>
                            </div>
                            <br>
                            <!-- Facebook comments -->
                            <div class="fb-comments" data-href="http://localhost/ecommerce/product.php?product=<?php echo $slug; ?>" data-numposts="10" width="100%"></div>
                        <?php endif; ?>
                    </div>
                    <div class="col-sm-3">
                        <?php include 'includes/sidebar.php'; // Include sidebar ?>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <?php 
    $pdo->close(); // Close database connection
    include 'includes/footer.php'; // Include footer 
    include 'includes/scripts.php'; // Include scripts
    ?>
    <script>
        // JavaScript code
        $(function(){
            $('#add').click(function(e){
                e.preventDefault();
                var quantity = $('#quantity').val();
                quantity++;
                $('#quantity').val(quantity);
            });
            $('#minus').click(function(e){
                e.preventDefault();
                var quantity = $('#quantity').val();
                if(quantity > 1){
                    quantity--;
                }
                $('#quantity').val(quantity);
            });

            // Display message after voting
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['vote']) && !$has_voted): 
					$vote = $_POST['vote']; // Get the vote value
								$product_id = $product['prodid']; // Get the product ID from the form submission
			
					// Insert the vote into the database
					$stmt = $conn->prepare("INSERT INTO product_votes (user_id, product_id, vote) VALUES (:user_id, :product_id, :vote)");
					$stmt->execute(['user_id' => $_SESSION['user']['id'], 'product_id' => $product_id, 'vote' => $vote]);
				?>
                var messageContainer = document.querySelector('.message-container');
                messageContainer.innerHTML = 'Thank you for your vote!';
                setTimeout(function() {
                    messageContainer.innerHTML = '';
                }, 3000); // Remove message after 3 seconds
            <?php endif; ?>
        });
    </script>
</div>
</body>
</html>
