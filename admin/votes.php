<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/navbar.php'; ?>
  <?php include 'includes/menubar.php'; ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Votes
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Votes</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header with-border">
              <a href="#addnew" data-toggle="modal" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> New</a>
            </div>
            <div class="box-body">
              <table id="example1" class="table table-bordered">
                <thead>
                
                  <th>LOCATION</th>

                  
                  <th>Product</th>
                  <th>Vote</th>
                  
                </thead>
                <tbody>
                  
<?php 
 // Establish database connection
 $conn = $pdo->open();
 
 try {
     // Prepare SQL query
     $stmt = $conn->prepare("SELECT city.name AS city_name, 
     CASE
         WHEN users.registration_type = 'resident' THEN residents.location
         ELSE green_businesses.company_name
     END AS user_name,
     products.name AS product_name,
     COUNT(DISTINCT product_votes.id) AS vote_count
FROM product_votes
INNER JOIN users ON product_votes.user_id = users.id
LEFT JOIN residents ON users.id = residents.user_id AND users.registration_type = 'resident'
LEFT JOIN green_businesses ON users.id = green_businesses.user_id AND users.registration_type = 'business'
LEFT JOIN city ON residents.location = city.name
INNER JOIN products ON product_votes.product_id = products.id
GROUP BY city_name, user_name, product_name;
");
 
     // Execute the query
     $stmt->execute();
 
     // Fetch all rows as an associative array
     $product_votes = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
     // Output the results
     foreach ($product_votes as $vote) {
         echo  "
                           <tr>
                             
                             <td>".$vote['user_name'] ."</td>
                             <td>".$vote['product_name'] ."</td>
                             <td>".$vote['vote_count'] ."</td>
                           </tr>
                         ";
     }
 }
 catch(PDOException $e){
     echo $e->getMessage();
 }
 
 // Close the database connection
 $pdo->close();
 ?>
 
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
     
  </div>
  	<?php include 'includes/footer.php'; ?>
    <?php include 'includes/users_modal.php'; ?>

</div>
<!-- ./wrapper -->

<?php include 'includes/scripts.php'; ?>
</body>
</html>
