<div class="panel panel-default">
	<div class="panel-body p0" >
		<?php
		$ntlStatusArr	 = NotificationLog::model()->ntl_status_list;
		$offerSatatus	 = [1 => 'Accepted', 2 => 'Denied'];
		if ($errorMessage != '')
		{
			?>
			<div class="row">
				<div class="col-12 text-center  p10">
					<a class="btn btn-primary" href="<?php echo Yii::app()->createUrl('admin/booking/view', ['id' => $model->bkg_id]); ?>">Go to booking</a> 
				</div>
				<div class="col-12 text-center  p10">
					<?
					echo $errorMessage;
					?>
				</div>
			</div>
			<?
		}
		else
		{
			?>
			<div class="col-12 mb20">
				<a class="btn btn-primary" href="<?php echo Yii::app()->createUrl('admin/booking/view', ['id' => $model->bkg_id]); ?>">Go to booking</a>   
			</div>
			<?
			$this->widget('booster.widgets.TbGridView', array(
				'id'				 => 'requestVendorGrid',
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
							<div class='col-xs-12 col-sm-5 pt5'>{summary}</div><div class='col-xs-12 col-sm-7 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body'>{items}</div>
							<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-5 p5'>{summary}</div><div class='col-xs-12 col-sm-7 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				//'ajaxType' => 'POST',
				'columns'			 => array(
					array('name' => 'vnd_name', 'value' => '$data["vnd_name"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array(), 'header' => 'Name'),
					array('name' => 'ntl_ref_id', 'value' => '$data["ntl_ref_id"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array(), 'header' => 'Trip id'),
					array('name' => 'ntl_message', 'value' => '$data["ntl_message"]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array(), 'header' => 'Message'),
					array('name'	 => 'ntl_event_code', 'value'	 => function ($data) {
							echo ($data["ntl_event_code"] == 550) ? 'Trip notified' : 'Trip allocated';
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array(), 'header'			 => 'Type'),
					array('name'				 => 'ntl_created_on', 'value'				 => 'DateTimeFormat::DateTimeToLocale($data["ntl_created_on"])', 'sortable'			 => false,
						'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center  col-xs-1'), 'header'			 => 'Created'),
					array('name'	 => 'ntl_is_read', 'value'	 => function ($data) {
							$val = '';
							switch ($data["ntl_is_read"])
							{
								case 0:
									$val = 'Unread';
									break;
								case 1:
									$val = 'Read at' . "<br>" . DateTimeFormat::DateTimeToLocale($data["ntl_read_at"]);
									break;
								case 2:
									$val = 'Received at' . "<br>" . DateTimeFormat::DateTimeToLocale($data["ntl_read_at"]);
									break;
								default:
									break;
							}
							echo $val;
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center col-xs-1'), 'header'			 => 'Read Status'),
					array('name'	 => 'ntl_status', 'value'	 =>
						function ($data) use ($ntlStatusArr) {
							echo ($data["ntl_batch_id"] != '') ? 'Sent in batch' : $ntlStatusArr[$data["ntl_status"]];
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center col-xs-1'), 'header'			 => 'Delivery Status'),
					array('name'	 => 'bvr_bid_amount', 'value'	 =>
						function ($data) {
							echo ($data["bvr_bid_amount"] != '') ? $data["bvr_bid_amount"] : 'NA';
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center col-xs-1'), 'header'			 => 'Offer Amount'),
					array('name'	 => 'bvr_accepted', 'value'	 =>
						function ($data) use ($offerSatatus) {
							echo ($data["bvr_accepted"] > 0) ? $offerSatatus[$data["bvr_accepted"]] : 'NA';
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'text-center'), 'htmlOptions'		 => array('class' => 'text-center col-xs-1'), 'header'			 => 'Offer Status'),
			)));
		}
		?>
	</div>
</div>