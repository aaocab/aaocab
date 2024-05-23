<div class="row">
	<div class="col-xs-12">
		<div class="row mb20">
			<div class="col-xs-12 widget-tab-box3 widget-tab-box5">
				<?php
				if ($mycall == 1)
				{
					$this->renderPartial("../driver/pastTripDetails", ["dataProvider" => $pastData], false, false);
				}
				else
				{
					$this->renderPartial("pastTripDetails", ["dataProvider" => $pastData], false, false);
				}
				?>
			</div>
		</div>	
	</div>
</div>