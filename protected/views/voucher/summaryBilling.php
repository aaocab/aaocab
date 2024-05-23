<?php

?>
<div class="col-xs-12">
	<div class="main_time pb0 border-greenline mb20">
		<div class="row book-summary">
			<div class="col-xs-12 col-sm-12 col-md-12">

				<div class="row pt5 pb5 clsBilling" >
					<div class=" col-xs-3 m0 sum-height">Full name: </div>
					<div class=" col-xs-9 m0 text-left"><span  class=""><?= $model->vor_bill_fullname; ?></span></div>
				</div>

				<div class="row pt5 pb5 clsBilling" >
					<div class=" col-xs-3 m0 sum-height">Email : </div>
					<div class=" col-xs-9 m0 text-left"><span  class=""><?= $model->vor_bill_email; ?></span></div>
				</div>

				<div class="row pt5 pb5 clsBilling" >
					<div class=" col-xs-3 m0 sum-height">Phone : </div>
					<div class=" col-xs-9 m0 text-left"><span  class=""><?= $model->vor_bill_contact; ?></span></div>
				</div>

				<div class="row pt5 pb5 clsBilling" >
					<div class=" col-xs-3 m0 sum-height">State : </div>
					<div class=" col-xs-9 m0 text-left"><span  class=""><?= $model->vor_bill_state; ?></span></div>
				</div>

				<div class="row pt5 pb5 clsBilling" >
					<div class=" col-xs-3 m0 sum-height">City : </div>
					<div class=" col-xs-9 m0 text-left"><span  class=""><?= $model->vor_bill_city; ?></span></div>
				</div>				

				<div class="row pt5 pb5 clsBilling" >
					<div class=" col-xs-3 m0 sum-height">Postal Code : </div>
					<div class=" col-xs-9 m0 text-left"><span  class=""><?= $model->vor_bill_postalcode; ?></span></div>
				</div>

				<div class="row pt5 pb5 clsBilling" >
					<div class=" col-xs-3 m0 sum-height">Total Cost : </div>
					<div class=" col-xs-9 m0 text-left"><i class='fa fa-inr'></i><span  class=""><?= $model->vor_total_price; ?></span></div>
				</div>
			</div>
		</div>
	</div>

