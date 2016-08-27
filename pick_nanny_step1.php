<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bootstrap Contact Form Template</title>

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
					<a class="navbar-brand" href="display_page.php?tpl=appointments">Bootstrap Contact Form Template</a>
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
                            <h1><strong>Okie Dokie at Home</strong> Nanny Appointments</h1>
                            <div class="description">
                            	<p>
	                            	Let's find a nanny that will be the perfect match for your kids.
                            	</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                        	<div class="form-top">
                        		<div class="form-top-left">
                        			<h3>Make an Appointment</h3>
                            		<p>Step 1</p>
                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-heart"></i>
                        		</div>
                            </div>
                            <div class="form-bottom contact-form">
			                    <form role="form" action="pick_nanny_step2.php" method="post">
			                    	<div class="form-group">
			                    		<label class="" for="client_name">Your Full Name:</label>
			                        	<input type="text" name="client_name" placeholder="Your Full Name..." class="contact-email form-control" id="client_name">
			                        </div>
			                        <div class="form-group">
			                        	<label class="" for="app_date">Appointment Date:</label>
			                        	<input type="date" name="app_date" placeholder="Appointment Date..." class="contact-subject form-control" id="app_date">
			                        </div>
									<div class="form-group">
			                        	<label class="" for="app_time">When should we arrive:</label>
			                        	<input type="time" name="app_time" placeholder="Appointment Time..." class="contact-subject form-control" id="app_time_to">
			                        </div>
										<div class="form-group">
			                        	<label class="" for="app_time_to">You'll need a nanny until:</label>
			                        	<input type="time" name="app_time_to" placeholder="Appointment Time..." class="contact-subject form-control" id="app_time">
			                        </div>
			                        <button type="submit" class="btn" onclick="window.location.href='pick_nanny_step2.php'">Next >></button>
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