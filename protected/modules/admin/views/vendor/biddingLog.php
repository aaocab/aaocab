<div class="row">
	<div class="col-xs-12">
		<div class="row mb20">
			<div class="col-xs-12 widget-tab-box3 widget-tab-box5">
				<?php
				if ($mycall == 1)
				{
					$this->renderPartial('../vendor/bidlog', ["dataProvider" => $bidLog], false, false);
				}
				else
				{
					$this->renderPartial("bidlog", ["dataProvider" => $bidLog], false, false);
				}
				?>
			</div> 
		</div>
		<div class="row" style="display: flex; flex-wrap: wrap; ">
		</div>
	</div>
</div>