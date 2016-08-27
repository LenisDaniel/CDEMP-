<?php
session_start();

    $_SESSION['name'] = $_POST['client_name'];
    $_SESSION['email'] = $_POST['client_email'];
    $_SESSION['username'] = $_POST['client_user'];
    $_SESSION['password'] = $_POST['client_pass'];    
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Okie Dokie- Register</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="assets1/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets1/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="assets1/css/form-elements.css">
        <link rel="stylesheet" href="assets1/css/style.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets1/ico/favicon.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets1/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets1/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets1/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets1/ico/apple-touch-icon-57-precomposed.png">

    </head>

    <body>

		<!-- Top menu -->
		<nav class="navbar navbar-inverse navbar-no-bg" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#top-navbar-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.php">Bootstrap Contact Form Template</a>
				</div>
				<!-- Collect the nav links, forms, and other content for toggling -->
				<div class="collapse navbar-collapse" id="top-navbar-1">
					<ul class="nav navbar-nav navbar-right">

					</ul>
				</div>
			</div>
		</nav>

        <!-- Top content -->
        <div class="top-content">
        	
            <div class="inner-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            <h1><strong>Okie Dokie at Home</strong> Registration</h1>
                            <!-- <div class="description">
                            	<p>
	                            	Let's find a nanny that will be the perfect match for your kids.
                            	</p>
                            </div> -->
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                        	<div class="form-top">
                        		<div class="form-top-left">
                        			<h3>Address Info</h3>
                            		<p>Step 2</p>
                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-pencil-square-o"></i>
                        		</div>
                            </div>
                            <div class="form-bottom contact-form">
			                    <form role="form" name="register_step2" id="register_step2" action="includes/register.inc.php?type=2" method="POST">
			                    	<div class="form-group">
			                    		<label class="" for="phone1">Phone 1:</label>
			                        	<input type="text" name="phone1" placeholder="777-777-7777..." class="contact-email form-control" id="phone1" pattern="\d{3}[\-]\d{3}[\-]\d{4}" required>
			                        </div>
			                        <div class="form-group">
			                        	<label class="" for="phone2">Phone 2:</label>
			                        	<input type="text" name="phone2" placeholder="777-777-7777..." class="contact-subject form-control" id="phone2" pattern="\d{3}[\-]\d{3}[\-]\d{4}">
			                        </div>
									<div class="form-group">
			                        	<label class="" for="address1">Address 1:</label>
			                        	<input type="text" name="address1" placeholder="Address 1..." class="contact-subject form-control" id="address1" required>
			                        </div>
										<div class="form-group">
			                        	<label class="" for="address2">Address 2:</label>
			                        	<input type="text" name="address2" placeholder="Address 2..." class="contact-subject form-control" id="address2">
			                        </div>
                                    <div class="form-group">
                                        <label class="" for="town">Town:</label>
                                        <input type="text" name="town" placeholder="Town..." class="contact-email form-control" id="town" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="" for="country">Country:</label>
                                        <input type="text" name="country" placeholder="Country..." class="contact-email form-control" id="country" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="" for="zipcode">Zip Code:</label>
                                        <input type="text" name="zipcode" placeholder="Zipcode..." class="contact-email form-control" id="zipcode" required>
                                    </div>
                                    <br/>
			                        <input type="submit" name="submitRegister2" id="submitRegister2" class="btn btn-primary btn-lg">
                                    <input type="reset" name="resetRegister2" id="resetRegister2" class="btn btn-info btn-lg">
			                    </form>
		                    </div>
                        </div>
                    </div>
                </div>
            </div>            
        </div>

        <!-- Javascript -->
        <script src="assets1/js/jquery-1.11.1.min.js"></script>
        <script src="assets1/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets1/js/jquery.backstretch.min.js"></script>
        <script src="assets1/js/retina-1.1.0.min.js"></script>
        <script src="assets1/js/scripts.js"></script>
        
        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->

    </body>

</html>