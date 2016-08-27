<?php
session_start();

if(isset($_GET['member'])){
    $_SESSION['member'] = $_GET['member'];    
}

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
                        			<h3>General Info</h3>
                            		<p>Step 1</p>
                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-pencil-square-o"></i>
                        		</div>
                            </div>
                            <div class="form-bottom contact-form">
			                    <form method="post" action="register_step2.php">
			                    	<div class="form-group">
			                    		<label class="" for="client_name">Full Name:</label>
			                        	<input type="text" name="client_name" placeholder="Your Full Name..." class="contact-email form-control" id="client_name" required>
			                        </div>
			                        <div class="form-group">
			                        	<label class="" for="client_email">Email:</label>
			                        	<input type="email" name="client_email" placeholder="Email..." class="contact-subject form-control" id="client_email" required>
			                        </div>
									<div class="form-group">
			                        	<label class="" for="client_user">Username:</label>
			                        	<input type="text" name="client_user" placeholder="Username..." class="contact-subject form-control" id="client_user" required>
			                        </div>
										<div class="form-group">
			                        	<label class="" for="client_pass">Password:</label>
			                        	<input type="password" name="client_pass" placeholder="Password..." class="contact-subject form-control" id="client_pass" required>
			                        </div>                                    
                                        <div class="form-group">
                                        <label class="" for="confirm_password">Confirm Password:</label>
                                        <input type="password" name="confirm_password" placeholder="Confirm Password..." class="contact-subject form-control" id="confirm_password" required><span id='message'></span>
                                    </div>
                                    <br/>
			                        <input type="submit" name="submitRegister1" id="submitRegister1" class="btn btn-primary btn-lg" value="Next">
                                    <input type="reset" name="resetRegister1" id="resetRegister1" class="btn btn-info btn-lg">
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

<script language="javascript">
    

  $('#confirm_password').on('keyup', function () {
    if ($(this).val() == $('#client_pass').val()) {
        $('#message').html('matching').css('color', 'yellow');
    } else $('#message').html('not matching').css('color', 'red');
});

</script>