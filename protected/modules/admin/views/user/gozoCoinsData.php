<div class="panel">									
	<div class="panel-body p0 pt20">										
		<?php
		$this->renderpartial("gozocoinsdetails", ["dataProvider" => $gozocoinsdetails, "dataProvider2" => $gozocoinsdetailspending, "totalGozoCoins" => $totalGozoCoins], false, false);
		?>
	</div>
</div>