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
				

				<div class="row" id="bookingsDiv" style="margin-top: 10px;">  
					<div class="col-xs-12 col-md-9">
						<div class="row">
							<div class="col-xs-12 col-sm-6">       
								<table class="table table-bordered">
									<thead>
										<tr style="color: blue;background: whitesmoke">
											<th colspan="8" class="text-center"><u>MMT TOP ROUTES</u></th>
										</tr>
										<tr style="color: black;background: whitesmoke">
											<th class="text-center"><u>ROUTE</u></th>
											<th class="text-center"><u>SEARCH COUNT</u></th>
											<th class="text-center"><u>HOLD COUNT</u></th>
											<th class="text-center"><u>CONFIRM COUNT</u></th>
										</tr>
									</thead>
									<tbody id="booking_row">                         
										<?
										foreach ($model as $data)
										{
											?>
											<tr>
												<td class="text-left"><?= $data['rut_name'] ?></td>
												<td class="text-center"><?= $data['search_count'] ?></td>
												<td class="text-center"><?= $data['hold_count'] ?></td>
												<td class="text-center"><?= $data['create_count'] ?></td>
											</tr>
											<?
										}
										?>
									</tbody>
								</table>
								<!--            <div class="col-xs-12  text-right">
								<?php
								//  $this->widget('CLinkPager', array('pages' => $usersList->pagination));
								?>
											</div>-->
							</div>

							<div class="col-xs-12 col-sm-6">       
								<table class="table table-bordered">
									<thead>
										<tr style="color: blue;background: whitesmoke">
											<th colspan="8" class="text-center"><u>MMT TOP ROUTES (Not Converted)</u></th>
										</tr>
										<tr style="color: black;background: whitesmoke">
											<th class="text-center"><u>ROUTE</u></th>
											<th class="text-center"><u>SEARCH COUNT</u></th>
											<th class="text-center"><u>HOLD COUNT</u></th>
											<th class="text-center"><u>CONFIRM COUNT</u></th>
										</tr>
									</thead>
									<tbody id="booking_row">                         
										<?
										foreach ($model1 as $data)
										{
											?>
											<tr>
												<td class="text-left"><?= $data['rut_name'] ?></td>
												<td class="text-center"><?= $data['search_count'] ?></td>
												<td class="text-center"><?= $data['hold_count'] ?></td>
												<td class="text-center"><?= $data['create_count'] ?></td>
											</tr>
											<?
										}
										?>
									</tbody>
								</table>
								<!--            <div class="col-xs-12  text-right">
								<?php
								//  $this->widget('CLinkPager', array('pages' => $usersList->pagination));
								?>
											</div>-->
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-md-3">
						<div class="row">
							<div class="col-xs-7 col-md-12">       
								<table class="table table-bordered">
									<thead>
										<tr style="color: blue;background: whitesmoke">
											<th colspan="8" class="text-center"><u>MMT ROUTES NOT SUPPORTED</u></th>
										</tr>
										<tr style="color: black;background: whitesmoke">
											<th class="text-center"><u>ROUTE</u></th>
											<th class="text-center"><u>COUNT</u></th>
										</tr>
									</thead>
									<tbody id="booking_row">                         
										<?
										foreach ($modelRtNotFound as $data)
										{
											?>
											<tr>
												<td class="text-left"><?= $data['rutname'] ?></td>
												<td class="text-center"><?= $data['tot'] ?></td>
											</tr>
											<?
										}
										?>
									</tbody>
								</table>
								<!--            <div class="col-xs-12  text-right">
								<?php
								//  $this->widget('CLinkPager', array('pages' => $usersList->pagination));
								?>
											</div>-->
							</div>
							<div class="col-xs-5 col-md-12">


								<table class="table table-bordered">
									<thead>
										<tr style="color: blue;background: whitesmoke">
											<th colspan="8" class="text-center"><u>MMT CITIES NOT FOUND</u></th>
										</tr>
										<tr style="color: black;background: whitesmoke">
											<th class="text-center"><u>ROUTE</u></th>
											<th class="text-center"><u>COUNT</u></th>
										</tr>
									</thead>
									<tbody id="booking_row">                         
										<?
										foreach ($modelCtNotFound as $data)
										{
											?>
											<tr>
												<td class="text-left"><?= $data['citynotfound'] ?></td>
												<td class="text-center"><?= $data['totalcount'] ?></td>
											</tr>
											<?
										}
										?>
									</tbody>
								</table>
								<!--            <div class="col-xs-12  text-right">
								<?php
								//   $this->widget('CLinkPager', array('pages' => $usersList->pagination));
								?>
											</div>-->
							</div>


						</div>
					</div>
				</div>
			<? } ?>
        </div></div></div>