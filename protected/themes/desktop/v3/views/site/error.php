<body>
        <div class="container">
		<div class="container-fluid pl0 pr0 pt-4 pb-4" style="height: 100%;">
	
		<div class="row">
			<div class="col-12">
				<div class="row">
				<div class="col-12 mb-2"><img src="/images/gozo-white.svg" alt="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." title="aaocab:India's leader in inter-city taxi | Great service. Price guarantee. Awesome reviews." width="150"></div>
					<div class="col-12 col-xl-12">
					<p class="merriw color-blue h2">Oops... we looked but could not find that page on this server</p>

					</div>
					<div class="col-12 col-sm-6 col-xl-8">
					<p class="h5 color-blue">We did find some great deals on trips you could take this upcoming weekend.</p>
					<p class="h5 color-blue">Let's get that trip organized for you</p>
					<p class="mt-5 mb-2 hidden-xs"><img src="/images/bag-icon.png" alt=""></p>
					</div>
					<div class="col-12 col-sm-6 col-xl-4">
					<div class="error-style-1">
					<div class="error-style-2"><img src="/images/img-1.png" alt="" width="300"></div>
					<div class="error-style-3"><a href="/">Home</a></div>
					<div class="error-style-4"><a href="/faq">See our FAQs</a></div>
					<div class="error-style-5"><a href="/">Book a ride</a></div>
					<div class="error-style-6"><a href="/contactus">Contact us</a></div>
					</div>
					</div>
	            </div>
			</div>
		</div>
	
        
            <div class="row">
                <div class="oops-main mt50">
<!--                    <div class="oops-img"><img src="<?//=  Yii::app()->getBaseUrl()?>/images/oops.png" alt=""></div>-->
                    <div class="oops-text col-12 col-md-8 col-xl-8">
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
<!--						<div class="p0 pl15 pt10 text-right"><a class="btn btn-info" href="<?=  Yii::app()->getHomeUrl()?>">Go back</a> to the Home page.</div>-->
                </div>
            </div>
<!--				 <h4 class="text-center">Call us (+91) 90518-77-000</h4>-->
        </div>
	</div>	
</body>
