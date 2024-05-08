<?php include 'includes/session.php'; ?>
<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper">

	<?php include 'includes/navbar.php'; ?>
	 
	  <div class="content-wrapper">
	    <div class="container">

	      <!-- Main content -->
	      <section class="content">

          <section class="contact">
        <div class="container">
            <h2 class="text-center">Contact Us</h2>
            <div class="row">
                <div class="col-md-6">
                    <div class="contact_form">
                        <h3>Send Us a Message</h3>
                        <form>
                            <div class="form-group">
                                <input type="text" class="form-control" name="name" placeholder="Your Name">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Your Email">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="message" placeholder="Your Message"></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Send Message</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="contact_info">
                        <h3>Contact Information</h3>
                        <p><i class="fa fa-phone"></i> 07448 132 643</p>
                        <p><i class="fa fa-envelope"></i> muhammadsohailaslam50@gmail.com</p>
                        <p><i class="fa fa-address-book"></i> 86, Henley Road Ilford, Essex IG1 2TX, London, United Kingdom</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

          </section>  
        </div>
      </div>
  	<?php include 'includes/footer.php'; ?>
</div>

<?php include 'includes/scripts.php'; ?>
</body>
</html>
