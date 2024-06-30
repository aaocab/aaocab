<style type="text/css">
    .cityinput > .selectize-control>.selectize-input{
        width:100% !important;
    }
</style>
<div class="row">
	<div class="panel" >
		<div class="panel-body">


			<div class="col-xs-12">
				<?php
				if (!empty($dataProvider))
				{
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbExtendedGridView', array(
						'responsiveTable'	 => true,
						'fixedHeader'		 => true,
						'headerOffset'		 => 110,
						'id'				 => 'trip-grid',
						'dataProvider'		 => $dataProvider,
						'template'			 => "<div class='panel-heading'><div class='row m0'>
                                    <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
						'itemsCssClass'		 => 'table items table-striped table-bordered mb0',
						'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
						'columns'			 => array(
							array('name'	 => 'bkg_id', 'value'	 => function ($data) {
									echo CHtml::link($data['bkg_id'], Yii::app()->createUrl("aaohome/booking/view", ["id" => $data['bkg_id']]), ['target' => '_blank']);
								}, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Booking Id'),
							array('name' => 'bkg_pickup_date', 'value' => $data['bkg_pickup_date'], 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'sortable' => true, 'header' => 'Pickup Date'),
							array('name' => 'assigned_mode', 'value' => $data['assigned_mode'], 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'sortable' => true, 'header' => 'Assign Mode'),
							array('name' => 'bvr_assigned_at', 'value' => $data["bvr_assigned_at"], 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Assigned At'),
							array('name' => 'gozen', 'value' => $data["gozen"], 'htmlOptions' => array('class' => 'text-center'), 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'header' => 'Assigned By'),
							array('name'	 => 'bkg_gozo_amount', 'value'	 =>
								function ($data) {
									echo Filter::moneyFormatter($data['bkg_gozo_amount']);
								}, 'htmlOptions'		 => array('class' => 'text-center'), 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Profit/Loss(Amt)'),
							array('name'	 => 'bkg_gozo_amount', 'value'	 =>
								function ($data) {
									echo $data['bkg_gozo_amount'] >= 0 ? "YES" : "NO";
								}, 'htmlOptions'		 => array('class' => 'text-center'), 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'header'			 => 'Is Profit')
					)));
				}
				?>
			</div>
		</div>
	</div>
</div>
