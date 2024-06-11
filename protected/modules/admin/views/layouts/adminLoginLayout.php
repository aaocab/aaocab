
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
                            <div class="text-center mb10"><img src="<?= Yii::app()->request->baseUrl; ?>/images/logo2_outstation.png?v1.0" alt="GozoCab"/></div>
                            <div class="login-box panel panel-white">
                                <div class="panel-heading pt0"><h2>Administrator Login</h2></div>
                                <div class="panel-body">
									<?php echo $content; ?>

                                </div>            <div class="panel-footer" id="footer">
									<div class="innheaderr">
										Copyright &copy; Gozo Cabs. All Rights Reserved.
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