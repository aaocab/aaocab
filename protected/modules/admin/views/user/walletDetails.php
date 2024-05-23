<div class="panel panel-default panel-border">

	<div class="panel panel-body">
		<div class="row" style="margin-top: 10px">
			<div class="col-xs-6  text-center h4"><b>Wallet Balance :</b> ₹<?php echo $walletBalance | 0; ?> 
			</div>
			<div class="col-xs-6  text-center h4"><b>Locked Balance :</b> ₹<?php echo $lockedBalance | 0; ?> 
			</div>
		</div>
		<div class="row" style="margin-top: 10px">
			<div class="col-sm-10 col-sm-offset-1">
				<?php
				if (!empty($dataProvider))
				{
					/* @var $dataProvider2 TbGridView */
					$params1								 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params1;
					$dataProvider->getSort()->params		 = $params1;
					$this->widget('booster.widgets.TbGridView', array(
						'responsiveTable'	 => true,
						'dataProvider'		 => $dataProvider,
						'pager'				 => ['maxButtonCount' => 5, 'class' => 'booster.widgets.TbPager'],
						'id'				 => 'walletListGrid',
						'template'			 => "<div class='panel-heading'><div class='row m0'>
									<div class='col-xs-12 col-sm-4 pr0'>{summary}</div>
									<div class='col-xs-12 col-sm-4 pr0'>{pager}</div>
									</div></div>
									<div class='panel-body table-responsive'>{items}</div><div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary table-bordered compact'),
						'columns'			 => array(
							array('name' => 'created', 'value' => 'date("d/m/Y H:i:s",strtotime($data["created"]))', 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-4 text-center'), 'header' => 'Date'),
							array('name' => 'adt_amount', 'value' => '(-1*$data["adt_amount"])', 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-2 text-center'), 'header' => 'Amount'),
							array('name' => 'act_remarks', 'value' => $data['act_remarks'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array('class' => 'col-xs-6 text-center'), 'header' => 'Description'),
					)));
				}
				?>
			</div>
		</div>
	</div>
</div>
