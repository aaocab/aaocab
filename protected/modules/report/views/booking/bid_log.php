<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions" >
	<div class="panel-body p0">
		<div class="col-md-12 panel">
			<div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
				<?php
				if (!empty($dataProvider))
				{
					$this->widget('booster.widgets.TbGridView', array(
						'id'				 => 'agentlog-grid',
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'>
                                        <div class='row m0'>
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
							array('name'	 => 'vnd_name', 'filter' => FALSE, 'value'	 => function ($data) {
									echo $data['vnd_name'];
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Vendor'),
							array('name'	 => 'bvr_bid_amount', 'filter' => FALSE, 'value'	 => function ($data) {
									echo $data['bvr_bid_amount'];
								}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Bid Amount'),
							array('name'				 => 'bvr_created_at',
								'filter'			 => FALSE,
								'value'				 => 'date("d/M/Y h:i A", strtotime($data[bvr_created_at]))',
								'sortable'			 => false,
								'headerHtmlOptions'	 => array('class' => 'col-xs-2')
								, 'header'			 => 'Created')
					)));
				}
				?>
			</div>
		</div>
	</div>
</div>
