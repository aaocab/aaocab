
<div class="container-fluid mt15 n">
	<div class="row">
		<div class="col-12 bg-black mb30 p0 text-center">
			<img src="/images/doubleback.jpg?v=0.7" alt="" class="img-fluid">
		</div>
	</div>
</div>

<div class="container mb20">
	<div class="row justify-center">
	<div class="col-12 col-xl-10 mt30 text-center"><a class="btn btn-lg btn-primary mb-2 text-uppercase" href="/" role="button">BOOK NOW</a></div>
	<div class="col-12 col-xl-10 lst-2">
<div class="card">
<div class="card-header pb0"><h4 class="card-title">Double Back</h4></div>
<div class="card-body">
		<?php
			  $url = Yii::app()->createUrl('booking/doubleBackOffer', []);
			  $this->renderPartial($url, false);
		?>
<p class="text-center mb30"><a class="btn btn-lg btn-primary text-uppercase" href="/" role="button">BOOK NOW</a></p>
</div>
</div>
		
	</div>
	</div>
</div>

