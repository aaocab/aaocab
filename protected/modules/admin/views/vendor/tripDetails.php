<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body p0">
                    <div class="">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							/*<div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 p5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                         </div>*/
							if (!empty($dataProvider))
							{
								$arr =array(5=>"Assigned",6=>"Completed",9=>"Cancelled");
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'tripdetails-grid' . $qry['booking_id'],
									'responsiveTable'	 => true,
									// 'filter' => FALSE,
									'dataProvider'		 => $dataProvider,
									'template'			 => "<div class='panel-heading'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'>
                                        
                                     </div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name'	 => 'bkg_booking_id', 'filter' => FALSE, 'value'	 => function($data)
											{
												echo CHtml::link($data["bkg_booking_id"], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']) . "<br>";
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Booking Id'),
										array('name'	 => 'bkg_booking_type', 'filter' => FALSE, 'value'	 => function($data)
											{
												if ($data['bkg_booking_type'] == 1)
												{
													echo "OneWay";
												}
												else
												{
													echo "Return";
												}
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Booking Type'),
										array('name'	 => 'bkg_from_city', 'filter' => FALSE, 'value'	 => function($data)
											{
												echo $data['bkg_from_city'];
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Form'),
										array('name'	 => 'bkg_to_city', 'filter' => FALSE, 'value'	 => function($data)
											{
												echo $data['bkg_to_city'];
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'To'),
										array('name'	 => 'bkg_pickup_date', 'filter' => FALSE, 'value'	 => 'date("d/M/Y h:i A", strtotime($data[bkg_pickup_date]))'
											, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Pickup Date'),
										array('name'	 => 'vhc_number', 'filter' => FALSE, 'value'	 => function($data)
											{
												echo CHtml::link($data["vhc_number"], Yii::app()->createUrl("admin/vehicle/view", ["code" => $data['vhc_code']]), ["class" => "", "onclick" => "", 'target' => '_blank']) . "<br>";
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Vehicle Number'),
										array('name'	 => 'bkg_customer_overall', 'filter' => FALSE, 'value'	 => function($data)
											{
												echo $data['bkg_customer_overall'];
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Rating'),
										array('name'	 => 'bkg_status', 'filter' => FALSE, 'value'	 => function($data)
											{
												
												if($data['bkg_status']==5)
												{
													
													echo 'assigned';
												}
												if($data['bkg_status']==6)
												{
													
													echo 'Completed';
												}
												if($data['bkg_status']==9)
												{
													
													echo 'Cancelled';
												}
												
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Status'),
										array('name'	 => 'bkg_customer_review', 'filter' => FALSE, 'value'	 => function($data)
											{
												echo $data['bkg_customer_review'];
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Comment'),

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