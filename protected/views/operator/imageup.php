<form id="imageup" >
	<div class="row">
		<div class="col-sm-12">

		 
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<input type="file" accept="image/*" capture="camera">
				</div>

			</div>

			<div class=" mt20" style="text-align: center">
				<?php
				echo CHtml::Button("Upload", array('class' => 'btn btn-primary', 'onclick' => "submitselfie()"));
				?>
			</div>
		</div>
	</div> 
</form> 