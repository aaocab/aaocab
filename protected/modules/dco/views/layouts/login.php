<?php
$this->beginContent('/layouts/head');
?>

<?php
$app = Yii::app();
/* @var $app CWebApplication */
$app->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
?><link href="/assets/mtnc/pages/css/login-2.min.css" rel="stylesheet" type="text/css" />

<body class=" login">
    <!-- BEGIN LOGO -->
    <div class="logo">
        <a href="#">
            <img src="/images/logo2_new.png" style="height: 60px;" alt="Gozocabs" /> 
        </a>
    </div>
    <!-- END LOGO -->
    <!-- BEGIN LOGIN -->
   
    <div class="content">

        <?= $content ?>
    </div>
    <div class="copyright hide"> 2017 © Gozo Technologies Private Limited.</div>
    <script src="/assets/mtnc/global/plugins/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/mtnc/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/assets/mtnc/global/plugins/js.cookie.min.js" type="text/javascript"></script>
    <script src="/assets/mtnc/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script>
    <script src="/assets/mtnc/global/plugins/jquery.blockui.min.js" type="text/javascript"></script>
    <script src="/assets/mtnc/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script>
    <!-- END CORE PLUGINS -->
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="/assets/mtnc/global/plugins/jquery-validation/js/jquery.validate.min.js" type="text/javascript"></script>
    <script src="/assets/mtnc/global/plugins/jquery-validation/js/additional-methods.min.js" type="text/javascript"></script>
    <script src="/assets/mtnc/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
    <!-- BEGIN THEME GLOBAL SCRIPTS -->
    <script src="/assets/mtnc/global/scripts/app.min.js" type="text/javascript"></script>
    <!-- END THEME GLOBAL SCRIPTS -->
    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="/assets/mtnc/pages/scripts/login.min.js" type="text/javascript"></script>
    <!-- END PAGE LEVEL SCRIPTS -->
    <!-- BEGIN THEME LAYOUT SCRIPTS -->
    <!-- END THEME LAYOUT SCRIPTS -->
</body>
<?php $this->endContent(); ?>
