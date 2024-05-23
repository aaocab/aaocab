<style>
    .bookin_header{
        background: #193651;
    }
    .stop-menu3{ margin-right: -15px; margin-top: 12px;}
    .stop-menu3 .navbar { min-height: 40px!important;}
    .stop-menu3 .navbar-nav li a{ font-size: 14px; text-align: right; font-weight: normal; color: #fff!important; padding: 5px 10px; -webkit-border-radius: 2px; -moz-border-radius: 2px; border-radius: 2px;}
    .stop-menu3 .navbar-nav li a:hover{ background: #315679; color: #fff;}
    .stop-menu3 .navbar-nav li a:focus{ color: #fff;}
    .stop-menu3 .dropdown-menu{ background: #193651;}
</style>

<?php
$this->beginContent('//layouts/head');
?>
<body>
    <div class="container-fluid">
        <div class="container ">
            <div class="row">
                <div class="col-xs-12">
					<?= $content ?>
                </div>
            </div>
        </div>		
    </div>
</body>
<?php $this->endContent(); ?>

