<? $maxSeatAllowed = 3; ?>
<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<?
			if (sizeof($result) > 0)
			{
				?>
				<div class="h4">List of Shuttle available for the route on <?= DateTimeFormat::DateTimeToDatePicker($result[0]['slt_pickup_datetime']) ?></div>
				<table class="table table-hover">					 
					<thead>
						<tr>
							<th class="col-xs-1">Pickup Time</th>
							<th class="text-center col-xs-1">Price per seat</th>
							<th class="text-center col-xs-2">Seat Capacity</th>
							<th class="text-center col-xs-2">Seat Available</th>
							<th class="text-center col-xs-2"> No. of Seats</th>
							<th class="col-xs-2"></th> 
						</tr>
					</thead>
					<tbody>
						<?
						foreach ($result as $data)
						{
							?>
							<tr>
								<td><?= DateTimeFormat::DateTimeToTimePicker($data['slt_pickup_datetime']) ?></td>
								<td class="text-center"><i class="fa fa-inr"></i><?= $data['slt_price_per_seat'] ?></td>
								<td class="text-center"><?= $data['slt_seat_availability'] ?></td>
								<td class="text-center"><?= $data['available_seat'] ?></td>
								<td class="text-center">
									<select class="form-control seat_count" name="slt_no_of_seat" sltval="<?= $data['slt_id'] ?>" id="slt_no_<?= $data['slt_id'] ?>" onchange="getval(this)">
										<option value="">Select seat count</option>
										<?
										for ($i = 1; $i <= $maxSeatAllowed && $i <= $data['available_seat']; $i++)
										{
											echo '<option value="' . $i . '">' . $i . '</option>';
										}
										?>
									</select>
								</td>
								<td class="text-center">
									<button type="button" id="btn_<?= $data['slt_id'] ?>" name="sltid" value="<?= $data['slt_id'] ?>" seat_count ="0" class="btn btn-primary  sltbtn disabled" onclick="validateForm1(this);">Book this shuttle</button>
								</td>
							</tr>
						<? } ?>
					</tbody>
				</table>
				<?
			}
			else
			{
				echo "No Shuttle is available for the current schedule";
			}
			?>
		</div>
	</div>
</div>
<script>


	function validateForm1(obj) {
//		ajaxindicatorstart("Please wait... ");		
		var shuttleid = $(obj).attr('value');
		var seat_count = $(obj).attr('seat_count');

		if (seat_count > 0) {

			$('#<?= CHtml::activeId($model, "bkg_shuttle_id") ?>').val(shuttleid);
			$('#<?= CHtml::activeId($model, "bkg_shuttle_seat_count") ?>').val(seat_count).change();
			$('#shuttlebookform').submit();
		}
	}
	function getval($obj) {
		var sid = $obj.id;
		var seat_count = $obj.value;
		var shuttleid = $($obj).attr('sltval');
		if (seat_count > 0) {
			$('.seat_count').prop('selectedIndex', '');
			$('#' + sid).prop('selectedIndex', seat_count);
			$('.sltbtn').addClass('disabled');
			$('.sltbtn').attr('seat_count', 0);
			$('#btn_' + shuttleid).removeClass('disabled');
			$('#btn_' + shuttleid).attr('seat_count', seat_count);

			$('#<?= CHtml::activeId($model, "bkg_shuttle_id") ?>').val(shuttleid);
			$('#<?= CHtml::activeId($model, "bkg_shuttle_seat_count") ?>').val(seat_count).change();
		}
	}
</script>