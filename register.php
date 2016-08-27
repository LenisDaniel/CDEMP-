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
                            <div class="description">
                            	<p>
	                            	Please select the type of package you want.
                            	</p>
                            	<br/>
                            </div>
                        </div>
                    </div>
                    <div class="row">
					  <div class="col-md-4 col-md-offset-1" >
					  <h2><strong>Membership</strong></h2>
					  <p style="opacity: 0.8">If you decide Okie Dokie membership at home, you pay a comfortable monthly payment of $00.00 automatically debited from your account.</p>
					  <button type="button" class="btn btn-primary btn-lg btn-block" onclick="window.location.href='register_step1.php?member=1'">I Want My Membership!</button>
					  <br/>
					  </div>
					  <div class="col-md-4 col-md-offset-2">
					  <h2><strong>Single Payment</strong></h2>
					  <p style="opacity: 0.8">Choose the simple payment if you need our services only sporadically, example, less than 5 times every 6 months.</p>
					  <button type="button" class="btn btn-primary btn-lg btn-block" onclick="window.location.href='register_step1.php?member=2'">One Payment This Time</button>
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
        

    </body>

</html>