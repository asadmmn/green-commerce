<?php include 'includes/session.php'; ?>
<?php
  if(isset($_SESSION['user'])){
    header('location: cart_view.php');
  }

  if(isset($_SESSION['captcha'])){
    $now = time();
    if($now >= $_SESSION['captcha']){
      unset($_SESSION['captcha']);
    }
  }

?>
<?php include 'includes/header.php'; ?>
<style>
    option span {
        display: block;
    }
</style>

<body class="hold-transition register-page">
<div class="register-box">
    <?php
      if(isset($_SESSION['error'])){
        echo "
          <div class='callout callout-danger text-center'>
            <p>".$_SESSION['error']."</p> 
          </div>
        ";
        unset($_SESSION['error']);
      }

      if(isset($_SESSION['success'])){
        echo "
          <div class='callout callout-success text-center'>
            <p>".$_SESSION['success']."</p> 
          </div>
        ";
        unset($_SESSION['success']);
      }

?>
    <div class="register-box-body">
   <h2 class="text-center"> <a href="index.php" class="logo ">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <!-- <span class="logo-mini"><b>G</b>C</span> -->
   <span> G<i class="fa fa-leaf text-green"></i>een </span><span>City</span>
    <!-- logo for regular state and mobile devices -->
    <!-- <span class="logo-lg"><img src="includes/images/logo.png" alt="Logo"></span> -->
</a>
</h2>
        <p class="login-box-msg">Register a new membership</p>

        <form action="register.php" method="POST">
          <div class="form-group has-feedback">
            <input type="text" class="form-control" name="firstname" placeholder="Firstname" value="<?php echo (isset($_SESSION['firstname'])) ? $_SESSION['firstname'] : '' ?>" required>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="text" class="form-control" name="lastname" placeholder="Lastname" value="<?php echo (isset($_SESSION['lastname'])) ? $_SESSION['lastname'] : '' ?>"  required>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="email" class="form-control" name="email" placeholder="Email" value="<?php echo (isset($_SESSION['email'])) ? $_SESSION['email'] : '' ?>" required>
            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input type="password" class="form-control" name="repassword" placeholder="Retype password" required>
            <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
          </div>
          <div class="form-group">
            <label for="registration_type">Registration Type:</label>
            <select class="form-control" id="registration_type" name="registration_type" required>
              <option value="">Select Registration Type</option>
              
              <option value="resident">Resident</option>
              <option value="business">Green Business Enterprise</option>
            </select>
          </div>
          <div id="resident_fields" style="display: none;">
            <div class="form-group">
              <label for="location">Location:</label>
              <!-- <input type="text" class="form-control" name="location" placeholder="Location"> -->
            <select name="location" id="" class="form-control">
            <option value=""></option>
            <?php
             
             $conn = $pdo->open();
             try{
               $stmt = $conn->prepare("SELECT * FROM city");
               $stmt->execute();
               foreach($stmt as $row){
                echo "<option value='" . $row['name'] . "'><span>" . $row['name'] . "</span><br> &nbsp;<span class='ml-3 mt-4'>" . $row['zip_code'] . "</span></option>";
 
               }
             }
             catch(PDOException $e){
               echo "There is some problem in connection: " . $e->getMessage();
             }

             $pdo->close();

           ?>
            </select>
            </div>
            <div class="form-group">
              <label for="age_group">Age Group:</label>
              <input type="text" class="form-control" name="age_group" placeholder="Age Group">
              
            </div>
            <div class="form-group">
              <label for="gender">Gender:</label>
             
              <select name="gender" class="form-control" id="">
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
            </div>
            <div class="form-group">
              <label for="environmental_interest">Areas of Environmental Interest:</label>
              <!-- <textarea class="form-control" name="environmental_interest" placeholder="Areas of Environmental Interest"></textarea> -->
              <select class="form-control" name="environmental_interest"">
            <option value=""></option>
            <?php
             
             $conn = $pdo->open();
             try{
               $stmt = $conn->prepare("SELECT * FROM category");
               $stmt->execute();
               foreach($stmt as $row){
                echo "<option value='" . $row['name'] . "'><span>" . $row['name'] . "</span><br> &nbsp;<span class='ml-3 mt-4'>" . $row['zip_code'] . "</span></option>";
 
               }
             }
             catch(PDOException $e){
               echo "There is some problem in connection: " . $e->getMessage();
             }

             $pdo->close();

           ?>
            </select>
            </div>
          </div>
          <div id="business_fields" style="display: none;">
            <div class="form-group">
              <label for="company_name">Company Name:</label>
              <input type="text" class="form-control" name="company_name" placeholder="Company Name">
            </div>
            <div class="form-group">
              <label for="contact_info">Contact Information:</label>
              <input type="text" class="form-control" name="contact_info" placeholder="Contact Information">
            </div>
            <div class="form-group">
              <label for="eco_products_services">Details of Eco-Friendly Products/Services Offered:</label>
              <textarea class="form-control" name="eco_products_services" placeholder="Details of Eco-Friendly Products/Services Offered"></textarea>
            </div>
          </div>
          <?php
            if(!isset($_SESSION['captcha'])){
              echo '
                <di class="form-group" style="width:100%;">
                  <div class="g-recaptcha" data-sitekey="6LevO1IUAAAAAFX5PpmtEoCxwae-I8cCQrbhTfM6"></div>
                </di>
              ';
            }
          ?>
          <hr>
          <div class="row">
            <div class="col-xs-4">
                <button type="submit" class="btn btn-primary btn-block btn-flat" name="signup"><i class="fa fa-pencil"></i> Sign Up</button>
            </div>
          </div>
        </form>
        <br>
        <a href="login.php">I already have a membership</a><br>
        <a href="index.php"><i class="fa fa-home"></i> Home</a>
    </div>
</div>
  
<?php include 'includes/scripts.php' ?>
<script>
$(document).ready(function(){
  $('#registration_type').on('change', function(){
    var registrationType = $(this).val();
    if(registrationType == 'resident'){
      $('#resident_fields').show();
      $('#business_fields').hide();
    } else if(registrationType == 'business'){
      $('#resident_fields').hide();
      $('#business_fields').show();
    } else {
      $('#resident_fields').hide();
      $('#business_fields').hide();
    }
  });
});
</script>
</body>
</html>
