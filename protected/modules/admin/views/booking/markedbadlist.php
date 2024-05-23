<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<script>
    if ($.fn.yiiGridView != undefined && $.fn.yiiGridView.settings['bookingmarkedbadGrid'] != undefined) {
        $(document).off('change.yiiGridView keydown.yiiGridView', $.fn.yiiGridView.settings['bookingmarkedbadGrid'].filterSelector);
    }
</script>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'bookingmarkedbadGrid',
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider,
									'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name'	 => 'bkg_booking_id', 'filter' => false, 'value'	 => function($data) {
												echo $data['bkg_booking_id'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-4', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => $model->getAttributeLabel('Booking Id')),
										array('name'	 => 'bkg_from_city_id', 'filter' => false, 'value'	 => function($data) {
												echo $data['from_city_name'] . "-" . $data['to_city_name'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-4', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => $model->getAttributeLabel('Route')),
										array('name'	 => 'blg_remark_type', 'filter' => false, 'value'	 => function($data) {
												switch ($data['blg_remark_type'])
												{
													case '1':
														echo 'General';
														break;
													case '2':
														echo 'Car';
														break;
													case '3':
														echo 'Driver';
														break;
													case '4':
														echo 'Vendor';
														break;
													case '5':
														echo 'Customer';
														break;
												}
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-4', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => $model->getAttributeLabel('Remarks Type')),
										array('name'	 => 'blg_desc', 'filter' => false, 'value'	 => function($data) {
												echo $data['blg_desc'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-4', 'class' => 'text-center'), 'htmlOptions'		 => array('style' => 'text-align: center;'), 'header'			 => $model->getAttributeLabel('Remarks')),
										array('name' => 'bkg_pickup_date', 'filter' => FALSE, 'value' => 'date("d/M/Y h:i A", strtotime($data[bkg_pickup_date]))', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => $model->getAttributeLabel('Pickup Date')),
										array('name' => 'blg_created', 'filter' => FALSE, 'value' => 'date("d/M/Y h:i A", strtotime($data[blg_created]))', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => $model->getAttributeLabel('Booking Date'))
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