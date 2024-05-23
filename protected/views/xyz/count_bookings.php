<div class="container-fluid p0"><div class="panel panel-white"><div class="panel-body">
			<?
			if ($error == 1)
			{
				?>
				<div class="row mt20" id="passwordDiv">
					<form name="cbkg" method="POST" action="<?= Yii::app()->request->url ?>">
						<div class="col-xs-offset-4 col-xs-4">   
							<div class="form-group row text-center">
								<input class="form-control" type="password" id="psw" name="psw" value="" placeholder="Password" required/>
							</div>
							<div class="Submit-button row text-center">
								<button type="submit" class="btn btn-primary">SUBMIT</button>
							</div>
						</div>
					</form>
				</div>
			<? } ?>
			<?
			if ($error == 2)
			{
				?>
				<div class="row mt20" id="wrongPassword" style="">
					<div class="col-xs-offset-4 col-xs-4">
						<h3>Wrong Password</h3>
						<img src="http://static.commentcamarche.net/es.ccm.net/pictures/Ud6krzOUaQiVrbx4IWkuzUrMD8vWr4qbG1wMtmWKQ94r7Doi6fybXXnACJoLFtKR-lol.png">
					</div>
				</div>
			<? } ?>
			<?
			if ($error == 0)
			{
				$desc = [
					0	 => "TODAY'S COUNT BY BOOKING DATE",
					1	 => "TODAY'S COUNT BY PICKUP DATE",
					2	 => "YESTERDAY COUNT BY BOOKING DATE",
					3	 => "YESTERDAY COUNT BY PICKUP DATE"
				];
				?>
				<div class="row">  
					<?php
					foreach ($countResults as $key => $result)
					{
						?>
						<div class="col-xs-12 col-md-6"  id="routewiseDiv" style="margin-top: 10px;">       
							<table class="table table-bordered">
								<thead>
									<tr style="color: blue;background: whitesmoke">
										<th colspan="6" class="text-center"><u><?= $desc[$key] ?></u></th>
									</tr>
									<tr style="color: black;background: whitesmoke">
										<th class="text-center"><u>BOOKING TYPE</u></th>
										<th class="text-center"><u>COUNT</u></th>
										<th class="text-center"><u>ADVANCE COUNT</u></th>
										<th class="text-center"><u>CANCELLED COUNT</u></th>
										<th class="text-center"><u>TOTAL AMOUNT</u></th>
										<th class="text-center"><u>GOZO AMOUNT</u></th>
									</tr>
								</thead>
								<tbody id="count_booking_row">
									<?php
									$bkgCount	 = 0;
									$canCount	 = 0;
									$bkgAmount	 = 0;
									$gozoAmount	 = 0;
									$advcanCount = 0;
									foreach ($result as $row)
									{
										$bkgCount		 += $row['ry_booking_count'];
										$canCount		 += $row['ry_cancelled_booking_count'];
										$bkgAmount		 += $row['ry_booking_amount'];
										$advcanCount	 += $row['ry_adv_booking_count'];
										$gozoAmount		 += $row['ry_gozo_amount'];
										$canGozoAmount	 += $row['ry_cancelled_gozo_amount'];
										?>
										<tr>
											<td class=""><?= ($row['seq'] == '0000') ? $row['name'] : 'ALL' ?></td>
											<td class="text-center"><?= $row['ry_booking_count'] ?></td>
											<td class="text-center"><?= number_format($row['ry_adv_booking_count']) ?></td>
											<td class="text-center"><?= $row['ry_cancelled_booking_count'] ?></td>
											<td class="text-center"><?= number_format($row['ry_booking_amount']) ?></td>
											<td class="text-center"><?= number_format($row['ry_gozo_amount']) ?></td>
										</tr>
										<?php
									}
									?><tr>
										<td class=""><?= 'ALL'; ?></td>
										<td class="text-center"><?= $bkgCount ?></td>
										<td class="text-center"><?= number_format($advcanCount) ?></td>
										<td class="text-center"><?= $canCount ?></td>
										<td class="text-center"><?= number_format($bkgAmount) ?></td>
										<td class="text-center"><?= number_format($gozoAmount) ?></td>
									</tr>
								</tbody>
							</table>
						</div>
						<?php
						if ($key == 1)
						{
							echo "</div><div class='row'/>";
						}
					}
					?>


				</div>

				<p>See top routes <a href="<?= CHtml::normalizeUrl(Yii::app()->createUrl('/xyz/mrptTopRoutes')) ?>" target="_blank">click here</a></p>
			<? } ?>
        </div></div></div>