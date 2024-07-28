
<!DOCTYPE html>
<html>
    <head>

        <!-- Title -->
        <title>aaocab | Login - Sign in</title>

        <meta content="width=device-width, initial-scale=1" name="viewport"/>
        <meta charset="UTF-8">
        <meta name="keywords" content="admin,dashboard" />

        <!-- Styles -->
        <link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700' rel='stylesheet' type='text/css'>
        <link href="/assets/plugins/pace-master/themes/blue/pace-theme-flash.css" rel="stylesheet"/>
        <link href="/assets/plugins/uniform/css/uniform.default.min.css" rel="stylesheet"/>
        <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/plugins/fontawesome/css/font-awesome.css" rel="stylesheet" type="text/css"/>


        <!-- Theme Styles -->
        <link href="/assets/css/modern.min.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/css/custom.css" rel="stylesheet" type="text/css"/>



        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body class="page-login login-alt">
        <main class="page-content">
            <div class="page-inner">
                <div id="main-wrapper">
					<div class="row">
                        <div class="col-md-4 center">
                            <div class="text-center mb10">
                                <h2 class="panel-heading">Aaocab</h2>
                                <img src="<?php echo  Yii::app()->request->baseUrl; ?>/images/logo2_outstation.jpg?v1.0" height="100" width="100" alt="aaocab"/>
                            
                            </div>
                            <div class="login-box panel panel-white">
                                <div class="panel-heading pt0"><h3>Enter my home</h3></div>
                                <div class="panel-body">
									<?php echo $content; ?>

                                </div>            <div class="panel-footer" id="footer">
									<div class="innheaderr">
										Copyright &copy; Aao Cabs. All Rights Reserved.
									</div>
								</div></div></div></div>
				</div>
			</div><!-- Main Wrapper -->
		</div><!-- Page Inner -->
	</main><!-- Page Content -->


	<!-- Javascripts -->
	<script src="/assets/plugins/jquery/jquery-2.1.4.min.js"></script>
	<script src="/assets/plugins/jquery-ui/jquery-ui.min.js"></script>
	<script src="/assets/plugins/pace-master/pace.min.js"></script>
	<script src="/assets/plugins/jquery-blockui/jquery.blockui.js"></script>
	<script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
	<script src="/assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>

</body>
</html>