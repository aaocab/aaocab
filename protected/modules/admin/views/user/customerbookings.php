<div class="panel-advancedoptions">
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body p0">
                    <div class="">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							$dataprovider = $tripdetails;
							if (!empty($tripdetails))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'tripdetails-grid',
									'responsiveTable'	 => true,
									// 'filter' => FALSE,
									'dataProvider'		 => $tripdetails,
									'template'			 => "<div class='panel-heading'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>
                                    <div class='panel-body'>{items}</div>
									",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name'	 => 'bkg_booking_id', 'filter' => FALSE, 'value'	 => function ($data) {
												echo CHtml::link($data["bkg_booking_id"], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']) . "<br>";
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Booking Id'),
										array('name'	 => 'bkg_booking_type', 'filter' => FALSE, 'value'	 => function ($data) {
												if ($data['bkg_booking_type'] == 1)
												{
													echo "OneWay";
												}
												else
												{
													echo "Return";
												}
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Booking Type'),
										array('name'	 => 'bkg_from_city', 'filter' => FALSE, 'value'	 => function ($data) {
												echo $data['bkg_from_city'];
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Form'),
										array('name'	 => 'bkg_to_city', 'filter' => FALSE, 'value'	 => function ($data) {
												echo $data['bkg_to_city'];
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'To'),
										array('name'				 => 'bkg_pickup_date', 'filter'			 => FALSE, 'value'				 => 'date("d/M/Y h:i A", strtotime($data[bkg_pickup_date]))'
											, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Pickup Date'),
										array('name'	 => 'vendor_name', 'filter' => FALSE, 'value'	 => function ($data) {
												echo CHtml::link($data["vendor_name"], Yii::app()->createUrl("admin/vendor/view", ["id" => $data['bcb_vendor_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']) . "<br>";
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Vendor Name'),
										array('name'	 => 'vhc_number', 'filter' => FALSE, 'value'	 => function ($data) {
												echo CHtml::link($data["vhc_number"], Yii::app()->createUrl("admin/vehicle/view", ["code" => $data['vhc_code']]), ["class" => "", "onclick" => "", 'target' => '_blank']) . "<br>";
												echo CHtml::link($data["driver_name"], Yii::app()->createUrl("admin/driver/view", ["id" => $data['bcb_driver_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']) . "<br>";
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Vehicle Number/<BR>Driver Name'),
										array('name'	 => 'bkg_status', 'filter' => FALSE, 'value'	 => function ($data) {
												$statusDetails	 = Booking::model()->getBookingStatus($data['bkg_status']);
												$iconColor		 = (in_array($data['bkg_status'], [9, 10])) ? 'label-danger' : 'label-success';
												echo "<strong class='label $iconColor'>$statusDetails</strong>";
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Status'),
									/* array('name'	 => 'bkg_customer_overall', 'filter' => FALSE, 'value'	 => function($data)
									  {
									  echo $data['bkg_customer_overall'];
									  }, 'sortable'			 => false,
									  'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Rating'),
									  array('name'	 => 'bkg_customer_review', 'filter' => FALSE, 'value'	 => function($data)
									  {
									  echo $data['bkg_customer_review'];
									  }, 'sortable'			 => false,
									  'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Comment'),
									 */
//										array('name'	 => 'Bid Accept Status', 'filter' => FALSE, 'value'	 => function($data)
//											{
//
//												if ($data['bvr_accepted'] == 1)
//												{
//													echo "Bid set";
//												}
//												else if ($data['bvr_assigned'] == 1)
//												{
//													echo "Bid accepted";
//												}
//												else if ($data['bvr_accepted'] == 2)
//												{
//													echo "Bid denied ";
//												}
//												else
//												{
//													echo "Bid lost ";
//												}
//											}, 'sortable'			 => false,
//											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Bid Status'),
//										array('name'				 => 'bvr_accepted_at',
//											'filter'			 => FALSE,
//											'value'				 => 'date("d/M/Y h:i A", strtotime($data[bvr_accepted_at]))',
//											'sortable'			 => false,
//											'headerHtmlOptions'	 => array('class' => 'col-xs-2')
//											, 'header'			 => 'Bid Accepted At'),
//										array('name'				 => 'bvr_created_at',
//											'filter'			 => FALSE,
//											'value'				 => 'date("d/M/Y h:i A", strtotime($data[bvr_created_at]))',
//											'sortable'			 => false,
//											'headerHtmlOptions'	 => array('class' => 'col-xs-2')
//											, 'header'			 => 'Bid Created At')
								)));
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	$('#tripdetails-grid-<?= $qry['booking_id'] ?> .tScore .a1').click(function (e) {
		e.preventDefault();
		return showReturnDetails(this);
	});
</script>