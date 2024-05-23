<div class="panel">

    <div class="panel panel-body">
		<div class="col-xs-12"> 
			<?php
			if (!empty($dataProvider))
			{
				$this->widget('booster.widgets.TbGridView', array(
					'responsiveTable'	 => true,
					'dataProvider'		 => $dataProvider,
					'id'				 => 'walletlist',
					'id'				 => 'driverListGrid',
					'template'			 => "<div class='panel-heading'><div class='row m0'>
            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
            </div></div>
            <div class='panel-body'>{items}</div>
            <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
					'itemsCssClass'		 => 'table table-striped table-bordered mb0',
					'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
					'columns'			 => array(
						array('name'				 => 'user_name', 'type'				 => 'raw', 'value'				 => '$data["user_name"]',
							'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'User'),
						array('name'				 => 'wallet_balance', 'type'				 => 'raw', 'value'				 => '$data["wallet_balance"]',
							'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'User'),
				)));
			}
			?>
		</div>
    </div>
</div>



