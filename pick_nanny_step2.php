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
		<link rel="stylesheet" href="assets1/css/image-picker.css">

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
                            		<p>Step 2</p>
                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-heart"></i>
                        		</div>
                            </div>
                            <div class="form-bottom contact-form">
			                    <form role="form" action="assets1/contact.php" method="post">
			                    	<div class="form-group">
			                    		<label class="" for="client_name">Choose a Nanny:</label>
			                        	<select name="client_name" class="contact-email form-control" id="client_name">
											<option  data-img-src='data:image/jpeg;base64,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' value='41'>Joan</option>
											<option  data-img-src='https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcT-GBpspnrBLUzIfxcRh6eqlqkauvee_4yc8owrDXjfmqcn9FBmng' value='42'>Laura</option>
											<option  data-img-src='https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQc364GqLLnhfFllhF91hAGVCyJ-MO3JZuA6DN3uZtbQi4KWCgv' value='43'>Carmen</option>
											<option  data-img-src='https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTKRZDHwrK1AifV6OkaAeXj3mOt8KVn3g0uK6vbkDPuJU2GiKKsdA' value='44'>María</option>
										</select>
			                        </div>
			         
			                        <button type="submit" class="btn">Next >></button>
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
		<script src="assets1/js/image-picker.js"></script>
		<script>
			$("select").imagepicker({
				show_label:true
			});
		</script>
        
        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->

    </body>

</html>