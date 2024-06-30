<div class="panel-advancedoptions" >
    <div class="row">
		<div class="col-xs-4">
			<p class="mb0 color-gray">Total Coins Available</p>
			<p class="font-16"><b><?php echo DriverCoins::totalCoin($drvId);?></b></p>
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
										array('name'	 => 'drc_type', 'filter' => FALSE, 'value'	 => function($data) {
												$coinType = ['1'=>"Rating",'2'=>"Penalty",'3'=>"GozoNow"];												
												echo $coinType[$data['drc_type']];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header' => 'Type'),
										array('name'	 => 'drc_value', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['drc_value'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'=> 'Value'),
										array('name'	 => 'drc_desc', 'filter' => FALSE, 'value'	 => function($data) {
												echo $data['drc_desc'];
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'=> 'Description'),
										array('name'	 => 'drc_ref_id', 'filter' => FALSE, 'value'	 => function($data) {												
													$bkCode = Booking::model()->getCodeById($data['drc_ref_id']);
													echo CHtml::link($bkCode.'</b>', Yii::app()->createUrl("/aaohome/booking/view?id=".$data['drc_ref_id']), ["target" => "_blank"]);																								
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'=> 'Reference'),
										array('name'	 => 'drc_created_at', 'filter' => FALSE, 'value'	 => function($data) {
												echo date("d/M/Y h:i A", strtotime($data['drc_created_at']));
											}, 'sortable'			 => false,
											'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'=> 'Created At'),										
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