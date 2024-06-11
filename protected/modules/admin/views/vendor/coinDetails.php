<div class="panel-advancedoptions" >
    <div class="row">
		<div class="col-xs-4">
			<p class="mb0 color-gray">Total Coins Available</p>
			<p class="font-16"><b><?php 
			echo VendorCoins::totalCoin($vndIds); ?></b></p>
		</div>
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body p0">
                    <div class="">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'coins-grid',
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider,
									'template'			 => "<div class='panel-heading'>
                                        <div class='row m0 f-white'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'>
                                        <div class='row m0'>                                            
                                         </div>
                                     </div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name'	 => 'vnc_type', 'filter' => FALSE, 'value'	 => function ($data) {
												$coinType = ['1' => "Rating", '2' => "Driver On time", '3' => "GozoNow", '4' => "Penalty"];
												echo $coinType[$data['vnc_type']];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Type'),
										array('name'	 => 'vnc_value', 'filter' => FALSE, 'value'	 => function ($data) {
												echo $data['vnc_value'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Value'),
										array('name'	 => 'vnc_desc', 'filter' => FALSE, 'value'	 => function ($data) {
												echo $data['vnc_desc'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Description'),
										array('name'	 => 'vnc_ref_id', 'filter' => FALSE, 'value'	 => function ($data) {
												if ($data['vnc_ref_type'] == 1)
												{
													$bkId	 = $data['vnc_ref_id'];
													$bkCode	 = Booking::model()->getCodeById($data['vnc_ref_id']);
												}
												else if ($data['vnc_ref_type'] == 2)
												{
													$bkgCabModel = BookingCab::model()->findByPk($data['vnc_ref_id']);
													$arrBkgIds	 = explode(',', $bkgCabModel->bcb_bkg_id1);
													$bkgModel	 = Booking::model()->findByPk($arrBkgIds[0]);
													$bkCode		 = $bkgModel->bkg_booking_id;
													$bkId		 = $bkgModel->bkg_id;
												}
												echo CHtml::link($bkCode . '</b>', Yii::app()->createUrl("/admpnl/booking/view?id=" . $bkId), ["target" => "_blank"]);
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Reference'),
										array('name'	 => 'ntl_created_on', 'filter' => FALSE, 'value'	 => function ($data) {
												echo date("d/M/Y h:i A", strtotime($data['vnc_created_at']));
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Created At'),
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