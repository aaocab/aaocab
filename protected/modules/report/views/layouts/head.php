<?php
/* @var $this Controller */

Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/enquire.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.cookie.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.nicescroll.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/moment.min.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/daterangepicker.js', CClientScript::POS_HEAD);

Yii::app()->clientScript->registerPackage('style');
$adminModel = Admins::model()->findByPk(Yii::app()->user->getId());
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="WRP">
        <meta name="author" content="GozoCabs">
		<link href="/assets/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
        <link rel="icon" type="image/png"  href="/images/favicon/favicon1.ico"/>
        <link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.ico"/>
        <link href='<?= (Yii::app()->request->getIsSecureConnection() ? "https" : "http") ?>://fonts.googleapis.com/css?family=Ubuntu:300,400,500,700' rel='stylesheet' type='text/css'/>	
        <link href="/assets/css/custom.css?v=4.0" rel="stylesheet" type="text/css"/>
        <link href="/assets/css/admin.css" rel="stylesheet" type="text/css"/>
        <link href="/assets/css/daterangepicker.css" rel="stylesheet" type="text/css"/>
        <script src="/assets/js/admin.js"></script>
        <script src="/assets/plugins/jquery-counterup/jquery.counterup.min.js"></script>
        <link href="/assets/toastr/toastr.min.css" rel="stylesheet" type="text/css"/>
        <script src="/assets/toastr/toastr.min.js"></script>
		<style>
			@font-face {
				font-family: 'FontAwesome';
				src: url('/fonts/fontawesome-webfont.eot?v=4.5.0');
				src: url('/fonts/fontawesome-webfont.eot?#iefix&v=4.5.0') format('embedded-opentype'), url('/fonts/fontawesome-webfont.woff2?v=4.5.0') format('woff2'), url('/fonts/fontawesome-webfont.woff?v=4.5.0') format('woff'), url('/fonts/fontawesome-webfont.ttf?v=4.5.0') format('truetype'), url('/fonts/fontawesome-webfont.svg?v=4.5.0#fontawesomeregular') format('svg');
				font-weight: normal;
				font-style: normal;
			}
		</style>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries. Placeholdr.js enables the placeholder attribute -->
        <!--[if lt IE 9]>
            <script type="text/javascript" src="<?= (Yii::app()->request->getIsSecureConnection() ? "https" : "http") ?>://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <!-- <? if (YII_DEBUG) print_r($_SERVER); ?> -->
        <link rel='stylesheet' type='text/css' href='<?php echo Yii::app()->request->baseUrl; ?>/assets/plugins/form-toggle/toggles.css' />
        <script type="text/javascript">
            var $baseUrl = "<?= Yii::app()->getBaseUrl(true) ?>";
            var $adminUrl = "<?= Yii::app()->createAbsoluteUrl('admin') ?>";
            function ajaxindicatorstart(text)
            {
                if (jQuery('body').find('#resultLoading').attr('id') != 'resultLoading')
                {
                    jQuery('body').append('<div id="resultLoading" style="display:none"><div><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/ajax-loader.gif"><div>' + text + '</div></div><div class="bg"></div></div>');
                }

                jQuery('#resultLoading').css({
                    'width': '100%',
                    'height': '100%',
                    'position': 'fixed',
                    'z-index': '10000000',
                    'top': '0',
                    'left': '0',
                    'right': '0',
                    'bottom': '0',
                    'margin': 'auto'
                });

                jQuery('#resultLoading .bg').css({
                    'background': '#ddd',
                    'opacity': '0.6',
                    'width': '100%',
                    'height': '100%',
                    'position': 'absolute',
                    'top': '0'
                });

                jQuery('#resultLoading>div:first').css({
                    'width': '250px',
                    'height': '75px',
                    'text-align': 'center',
                    'position': 'fixed',
                    'top': '0',
                    'left': '0',
                    'right': '0',
                    'bottom': '0',
                    'margin': 'auto',
                    'font-size': '16px',
                    'z-index': '10',
                    'color': '#111'

                });

                jQuery('#resultLoading .bg').height('100%');
                jQuery('#resultLoading').fadeIn(100);
                jQuery('body').css('cursor', 'wait');
            }

            function ajaxindicatorstop()
            {
                jQuery('#resultLoading .bg').height('100%');
                jQuery('#resultLoading').fadeOut(100);
                jQuery('body').css('cursor', 'default');
            }


            jQuery(document).ajaxStart(function ()
            {
                //show ajax indicator
                ajaxindicatorstart('loading data.. please wait..');
            }).ajaxStop(function ()
            {
                //hide ajax indicator
                ajaxindicatorstop();
            });
            jQuery(window).on('load', function ()
            {
                // will first fade out the loading animation
                jQuery("#status").fadeOut();
                // will fade out the whole DIV that covers the website.
                jQuery("#preloader").delay(100).fadeOut("slow");
            });

            toastr.options = {
                "closeButton": false,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": function ()
                {
                    window.location.href = '<?= Yii::app()->createUrl("admin/lead/report"); ?>';
                },
                "timeOut": "0",
                "extendedTimeOut": "0",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "tapToDismiss": true,
                "hideMethod": "fadeOut"
            };
        </script>
        <style>

            .toolbar.navbar-nav {
                margin-top: 5px
            }
            header.navbar {
                height: 50px;
            }
            footer{
                height: 32px;
                bottom:0;
                position: fixed;
            }
            #preloader  {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #fefefe;
                z-index: 99;
                height: 100%;
            }

            #status  {
                width: 200px;
                height: 200px;
                position: absolute;
                left: 50%;
                top: 50%;
                background-image: url(/images/ajax-loader.gif);
                background-repeat: no-repeat;
                background-position: center;
                margin: -100px 0 0 -100px;
            }
            .horizontal-bar .accordion-menu>li>a, .small-sidebar.page-horizontal-bar .horizontal-bar .accordion-menu>li>a {
                padding: 8px 13px!important;
            }
            .horizontal-bar .accordion-menu>li>ul {
                top: 56px;
            }
            .menu.accordion-menu a span.menu-icon {
                margin-bottom: 6px;
            }
            body:not(.small-sidebar) .horizontal-bar .menu.accordion-menu>li>a>.menu-icon {
                font-size: 15px;
            }
            @media (max-width: 1200px){
                .horizontal-bar .accordion-menu ul {
                    top: 35px!important;
                }
            }
        </style>
    </head>
    <body class="page-header-fixed page-horizontal-bar  page-sidebar-fixed">
		<?= $content ?>

    </body></html>