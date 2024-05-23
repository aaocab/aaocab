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
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'tripratinglist-grid' . $qry['booking_id'],
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
										array('name'	 => 'bkg_customer_overall', 'filter' => FALSE, 'value'	 => function($data)
											{
												echo $data['bkg_customer_overall'];
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Rating'),

										array('name'	 => 'bkg_customer_review', 'filter' => FALSE, 'value'	 => function($data)
											{
												echo $data['bkg_customer_review'];
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Comment'),
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
$('#tripratinglist-grid-<?= $qry['booking_id'] ?> .tScore .a1').click(function (e) {
        e.preventDefault();
        return showReturnDetails(this);
    });
</script>