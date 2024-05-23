<body>
        <div class="container">
            <div class="row">
                <div class="oops-main mt50">
                    <div class="oops-img"><img src="<?=  Yii::app()->getBaseUrl()?>/images/oops.png" alt=""></div>
                    <div class="oops-text col-xs-12 col-sm-6 col-md-6">
                        <h4>Whoops, our bad...</h4>
                        <div class="p0 pl15" style="max-height: 100px; overflow: auto">
                            <? if (YII_DEBUG) { 
                $purifier = new CHtmlPurifier();
                $trace = $purifier->purify($trace);
                ?>
                <?= nl2br($trace) ?>
            <?
            } else {
                echo "<h4>$message</h4>";
            }
            ?></div>
						<div class="p0 pl15 pt10 text-right"><a class="btn btn-info" href="<?=  Yii::app()->getHomeUrl()?>">Go back</a> to the Home page.</div>
                </div>
            </div>
				 <h4 class="text-center">Call us (+91) 90518-77-000</h4>
        </div>

</body>
