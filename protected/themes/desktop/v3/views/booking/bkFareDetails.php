<div class="modal fade" id="bkFareDetailsModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header pl20 p5">
				<h5 class="modal-title" id="bkFareModelHeader">Fare details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<img src="/images/bx-x.svg" alt="img" width="18" height="18">
				</button>
			</div>
			<div class="modal-body" id="bkFareDetailsModelBody">
				<?=$this->renderPartial("fare", ["model"=>$model]);?>
			</div>
		</div>
	</div>
</div>