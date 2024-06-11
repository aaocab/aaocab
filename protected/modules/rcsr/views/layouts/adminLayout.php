<?
Yii::app()->clientScript->registerPackage('jquery');
Yii::app()->clientScript->registerPackage('jqueryui');
Yii::app()->clientScript->registerPackage('style');
$adminModel = Admins::model()->findByPk(Yii::app()->user->getId());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>aaocab : Admin Panel</title>
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/adminStyle.css"></link>                 

		<link rel="icon" type="image/png"  href="/images/favicon/favicon.png"/>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/validation.js"></script>

        <script src="<?php echo Yii::app()->request->baseUrl; ?>/assets/plugins/form-daterangepicker/moment.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400italic,700' rel='stylesheet' type='text/css'></link>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <div class="innheaderr">
                    <a href="<?php echo Yii::app()->createUrl('admin/index/dashboard'); ?>">
                        <img style="width: 300px" src="<?php echo Yii::app()->request->baseUrl; ?>/images/gozo-logo.png" />
                    </a>
                    <div class="afterLogin">
                        <p>Welcome, <span><?php echo $adminModel->adm_fname; ?></span> | <a href="<?php echo Yii::app()->createUrl('admin/index/logout') ?>" style="color: #ff0000">Sign Out</a></p>
                        <div class="clr"></div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12"><?php echo $content; ?></div>
            <div class="footer" id="footer" style="bottom: 0; position: absolute">
                <div class="innheaderr">
                    Copyright &copy; Gozo Cabs. All Rights Reserved.
                </div>
            </div>
        </div>
    </body>   
</html>