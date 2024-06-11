<?php
$this->beginContent('//layouts/head');
if ($this->layout == 'column1')
{
	$style = "background-color: inherit";
}
$fixedTop	 = ($this->fixedTop) ? "navbar-fixed-top" : "";
$bgBanner	 = ($this->fixedTop) ? "bg-banner" : "";
?>
<body>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="http://www.googletagmanager.com/ns.html?id=GTM-T73295"
                      height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->


    <div class="container-fluid smain-bg">
        <header class="header">
            <div class="container">
                <div class="row inner-top hidden-xs">
                    <div class="col-sm-6 col-md-6 socials-menu">
                        <a href="http://www.facebook.com/aaocab" target="_blank" class="wow fadeInUp animated" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInUp;" data-wow-delay="0.5s" title="Facebook"><i class="fa fa-facebook" data-toggle="tooltip" data-placement="left" title="Facebook"></i></a>
                        <a href="https://twitter.com/aaocab" target="_blank" class="wow bounceIn animated" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInUp;" data-wow-delay="0.7s" title="Twitter"><i class="fa fa-twitter" data-toggle="tooltip" data-placement="left" title="Twitter"></i></a>
                        <a href="https://plus.google.com/b/113163564383201478409/+aaocab" target="_blank" class="wow bounceIn animated" style="visibility: visible; animation-delay: 0.6s; animation-name: fadeInUp;" data-wow-delay="0.9s" title="Google+"><i class="fa fa-google-plus" data-toggle="tooltip" data-placement="left" title="Google Plus"></i></a>
                    </div>
                   
                </div>
                <div class="row">
					<?= $this->renderPartial("/index/toprow"); ?>
                </div>
            </div>
            <div class="row inner-top-mune">
                <div class="container hidden-xs">
                    <h1><?= $this->pageTitle ?></h1>
                </div>
            </div>
        </header>
		<?php
		$time		 = Filter::getExecutionTime();

		$GLOBALS['time97']	 = $time;
		?>
        <div class="container mt20">
            <div class="row">
                <div class="col-xs-12">
					<?= $content ?>
                </div>
            </div>
        </div>
		<?php
		$time				 = Filter::getExecutionTime();

		$GLOBALS['time98']	 = $time;
		?>
		<?= $this->renderPartial("/index/footer"); ?>
    </div>
</body>
<?php
$time				 = Filter::getExecutionTime();

$GLOBALS['time99'] = $time;
?>
<!--
<?php
//print_r($GLOBALS['time1'] ."==1\n");
//print_r($GLOBALS['time2'] ."==2\n");
//print_r($GLOBALS['time3'] ."==3\n");
//print_r($GLOBALS['time4'] ."==4\n");
//print_r($GLOBALS['time5'] ."==5\n");
//print_r($GLOBALS['time6'] ."==6\n");
//print_r($GLOBALS['time7'] ."==7\n");
//print_r($GLOBALS['time8'] ."==8\n");
//print_r($GLOBALS['time9'] ."==9\n");
?>
-->
<?php $this->endContent(); ?>
<script>
    $('.helpline').click(function () {
        openhelpline();
    });
    function openhelpline() {

        var href2 = "<?= Yii::app()->createUrl('scq/helpline') ?>";
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                bootbox.dialog({
                    message: data,

                    size: 'small',
                    className: "smallwidth",

                });
            }
        });
        return false;

    }
</script>