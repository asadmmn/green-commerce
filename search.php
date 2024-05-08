<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<style>
    a {
        color: black;
    }
</style>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">
    <?php include 'includes/navbar.php'; ?>
    <div class="content-wrapper">
        <div class="container">
            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-sm-9">
                        <?php
                        $conn = $pdo->open();
                        $keyword = ''; 
						$price = '';
						
						if (isset($_POST['price_filter'])) {
                            $price = $_POST['price_filter'];
                        }

						// Initialize keyword variable
                        if (isset($_POST['keyword'])) {
                            $keyword = $_POST['keyword'];
                        }
                        $status = 'approved';
                        $query = "SELECT * FROM products WHERE status = :status";
                        $params = array('status' => $status);

                        // Adding keyword filter if provided
                        if (!empty($keyword)) {
                            $query .= " AND name LIKE CONCAT('%', :keyword, '%')";
                            $params['keyword'] = $keyword;
                        }

                        // Adding price filter if provided
                        if (!empty($price)) {
                            $query .= " AND price <= :price";
                            $params['price'] = $_POST['price_filter'];
                        }

                        $stmt = $conn->prepare($query);
                        $stmt->execute($params);

                        if ($stmt->rowCount() < 1) {
                            echo '<h1 class="page-header">No results found</h1>';
                        } else {
                            echo '<h1 class="page-header">Search results</h1>';
                            try {
                                $inc = 3;
                                foreach ($stmt as $row) {
                                    if (!empty($keyword)) {
                                        $highlighted = preg_filter('/' . preg_quote($keyword, '/') . '/i', '<b>$0</b>', $row['name']);
                                    }
                                    $image = (!empty($row['photo'])) ? 'images/' . $row['photo'] : 'images/noimage.jpg';
                                    $inc = ($inc == 3) ? 1 : $inc + 1;
                                    if ($inc == 1) echo "<div class='row'>";
                                    echo "
                                        <div class='col-sm-4 text-dark'>
                                            <div class='box box-solid'>
                                                <div class='box-body prod-body'>
                                                <a class='text-dark' href='product.php?product=" . $row['slug'] . "'style='color:black;'><img src='" . $image . "' width='100%' height='230px' class='thumbnail'>
													<b>&euro;" . $highlighted . "</b>
                                                </div>
                                                <div class='box-footer'>
                                                    <b>&euro; " . number_format($row['price'], 2) . "</b></a>
													
                                                </div>
                                            </div>
                                        </div>
                                    ";
                                  
                                    if ($inc == 3) echo "</div>";
                                }
                                if ($inc == 1) echo "<div class='col-sm-4'></div><div class='col-sm-4'></div></div>";
                                if ($inc == 2) echo "<div class='col-sm-4'></div></div>";
                            } catch (PDOException $e) {
                                echo "There is some problem in connection: " . $e->getMessage();
                            }
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
