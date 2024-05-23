<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
    <!--<![endif]-->
    <!-- BEGIN HEAD -->
    <head>

        <meta charset="utf-8" />
        <title>Gozo Agent Portal</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <!-- BEGIN GLOBAL MANDATORY STYLES -->
         <?php 
         Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/moment.min.js', CClientScript::POS_HEAD);
         Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/daterangepicker.js', CClientScript::POS_HEAD);

         ?>
        <link rel="stylesheet" href="<?= Yii::app()->getBaseUrl(true) ?>/css/font-awesome/css/font-awesome.css">
        <link href="<?= Yii::app()->getBaseUrl(true) ?>/css/hover.css?v=1" rel="stylesheet" media="all">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css" />
        <link href="/assets/mtnc/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/mtnc/global/plugins/simple-line-icons/simple-line-icons.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/mtnc/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/mtnc/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css" />
        <!-- END GLOBAL MANDATORY STYLES -->
        <!-- BEGIN PAGE LEVEL PLUGINS -->
        <link href="/assets/mtnc/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/mtnc/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- END PAGE LEVEL PLUGINS -->
        <!-- BEGIN THEME GLOBAL STYLES -->
        <link href="/assets/mtnc/global/css/components-md.min.css" rel="stylesheet" id="style_components" type="text/css" />
        <link href="/assets/mtnc/global/css/plugins-md.min.css" rel="stylesheet" type="text/css" />
        <!-- END THEME GLOBAL STYLES -->
        <!-- BEGIN PAGE LEVEL STYLES -->
        <link href="/assets/mtnc/layouts/layout3/css/layout.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/mtnc/layouts/layout3/css/themes/default.min.css" rel="stylesheet" type="text/css" id="style_color" />
        <link href="/assets/mtnc/layouts/layout3/css/custom.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/mtnc/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/daterangepicker.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="<?= Yii::app()->getBaseUrl(true) ?>/css/agtStyle.css?v=0.5">

        <!-- END PAGE LEVEL STYLES -->
        <!-- BEGIN THEME LAYOUT STYLES -->
        <!-- END THEME LAYOUT STYLES -->
        <link rel="shortcut icon" href="/favicon.ico" /> 
<!--<script type="text/javascript" src="/res/app-assets/js/jquery.min.js"></script>
<script src="/res/app-assets/js/bootstrap-noconflict.js" type="text/javascript"></script>
<script src="/res/app-assets/js/bootstrap.min.js" type="text/javascript"></script>-->
    </head>
    <!-- END HEAD -->
<!-- The Modal -->
	

    <?= $content; ?>


</html>