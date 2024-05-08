<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
<style>a{
	color:black;
}</style>
	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <!-- <div class="container"> -->

	      <!-- Main content -->
	      <section class="content" >
	        <div class="row">
	        	<div class="col-sm-9">
	        		<?php
	        			if(isset($_SESSION['error'])){
	        				echo "
	        					<div class='alert alert-danger'>
	        						".$_SESSION['error']."
	        					</div>
	        				";
	        				unset($_SESSION['error']);
	        			}
	        		?>
	        		<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
		                <ol class="carousel-indicators">
		                  <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
		                  <li data-target="#carousel-example-generic" data-slide-to="1" class=""></li>
		                  <li data-target="#carousel-example-generic" data-slide-to="2" class=""></li>
		                </ol>
		                <div class="carousel-inner">
		                  <div class="item ">
		                    <img src="images/first_slide.jpg" alt="First slide">
						
		                  </div>
						  <div class="item active">
            <img src="images/second_slide.jpg" alt="Second slide">
        </div>
        <div class="item">
            <img src="images/third_slide.jpg" alt="Third slide">
        </div>
						  
		                </div>
						
		                <a class="left carousel-control" href="#carousel-example-generic" data-slide="prev">
		                  <span class="fa fa-angle-left"></span>
		                </a>
		                <a class="right carousel-control" href="#carousel-example-generic" data-slide="next">
		                  <span class="fa fa-angle-right"></span>
		                </a>
		            </div>
		            
		       		<?php
		       			$month = date('m');
		       			$conn = $pdo->open();

		       			try{
		       			 	$inc = 3;	
						    $stmt = $conn->prepare("SELECT *, SUM(quantity) AS total_qty FROM details LEFT JOIN sales ON sales.id=details.sales_id LEFT JOIN products ON products.id=details.product_id WHERE MONTH(sales_date) = '$month' GROUP BY details.product_id ORDER BY total_qty DESC LIMIT 6");
						    $stmt->execute();
						    foreach ($stmt as $row) {
						    	$image = (!empty($row['photo'])) ? 'images/'.$row['photo'] : 'images/noimage.jpg';
						    	$inc = ($inc == 3) ? 1 : $inc + 1;
	       						if($inc == 1) echo "<div class='row'>";
	       						echo "<h2>Monthly Top Sellers</h2>
	       							<div class='col-sm-4'>
	       								<div class='box box-solid'>
		       								<div class='box-body prod-body'>
		       									<img src='".$image."' width='100%' height='230px' class='thumbnail'>
		       									<h5><a href='product.php?product=".$row['slug']."'>".$row['name']."</a></h5>
		       								</div>
		       								<div class='box-footer'>
		       									<b>&euro; ".number_format($row['price'], 2)."</b>
		       								</div>
	       								</div>
	       							</div>
	       						";
	       						if($inc == 3) echo "</div>";
						    }
						    if($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>"; 
							if($inc == 2) echo "<div class='col-sm-4'></div></div>";
						}
						catch(PDOException $e){
							echo "There is some problem in connection: " . $e->getMessage();
						}
						$stmt = $conn->prepare("SELECT p.id, p.name, p.slug, p.description, p.price, p.photo, COUNT(v.id) AS total_votes
                        FROM products p
                        JOIN product_votes v ON p.id = v.product_id
                        GROUP BY p.id
                        ORDER BY total_votes DESC
                        LIMIT 3");
$stmt->execute();
$products = $stmt->fetchAll(); // Fetch all rows
echo "<div class='mt-5'>";
echo "<h2>Featured Products</h2>";
echo "<div class='row   d-flex flex-nowrap overflow-auto m-5 p-5'style='display:flex; align-items:center;'>"; // Update row class to include flex and overflow properties

// Display top-voted products in small cards
foreach ($products as $product) {
    $productName = $product["name"];
    $productImage = "images/" . strtolower(str_replace(" ", "-", $productName)) . ".jpg"; // Assuming images are jpg format
   
	echo "
	<div class='col-sm-4'>
		<div class='box box-solid'>
			<div class='box-body prod-body'>
			<a href='product.php?product=".  $product["slug"] ."'style='color:black;'>		<img src='images/" . $product['photo'] . "' width='100%' height='230px' class='thumbnail'>
				<h5>" . $product['name']. "</h5>
			</div>
			<div class='box-footer'>
				<b>&euro; " . number_format($product['price'], 2) . "</b></a>
				<div class='voting'>
					
					<div class='message-container'></div
					</div></div>        
			</div>
	
";
// <form method='POST'>
					// 	<input type='hidden' name='product_id' value='" . $product['id'] . "'>
					// 	Is this product beneficial for the environment? <button type='submit' name='vote' value='1'>YES</button>
					// 	<button type='submit' name='vote' value='-1'>NO</button>
					// </form>
    echo "</div>";
    echo "</div>";
}

echo "</div>"; // End row
echo "</div>"; // End container

?>

		        </div> <!-- Close the col-sm-9 -->
	        	<div class="col-sm-3 mr-5 pr-5">
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
