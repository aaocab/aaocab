
<?php
if (!empty($vendorModels))
{
	$params									 = array_filter($_REQUEST);
	$vendorModels->getPagination()->params	 = $params;
	$vendorModels->getSort()->params		 = $params;
	$this->widget('booster.widgets.TbGridView', array(
		'responsiveTable'	 => true,
		'id'				 => 'reportlist',
		'dataProvider'		 => $vendorModels,
		'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered mb0',
		'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
		'columns'			 => array(
			array('name'	 => 'bkg_pickup_date', 'value'	 => function ($data)
				{
					echo ($data['bkg_pickup_date'] == NULL) ? 'NA' : date('d/m/Y', strtotime($data['bkg_pickup_date']));
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Pickup Date'),
			array('name'	 => 'ledgerNames', 'value'	 => function ($data)
				{
					echo ($data['ledgerNames'] == NULL) ? 'NA' : $data['ledgerNames'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Trip ID/Booking ID'),
			array('name'	 => 'bkg_advance_amount', 'value'	 => function ($data)
				{
					echo ($data['bkg_advance_amount'] == NULL ) ? 'NA' : trim($data['bkg_advance_amount']);
				}, 'sortable'								 => true, 'headerHtmlOptions'						 => array(), 'header'								 => 'Advanced Collected'),
			array('name'	 => 'act_date', 'value'	 => function ($data)
				{
					echo date('d/m/Y', strtotime($data['act_date']));
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Transaction Date'),
			array('name'	 => 'act_created', 'value'	 => function ($data)
				{
					echo date('d/m/Y', strtotime($data['act_created']));
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Created Date'),
			array('name'	 => 'booking_info', 'value'	 => function ($data)
				{
					$fromCity = ($data['from_city'] == NULL) ? 'NA' : trim($data['from_city']);
					echo ($data['ledgerNames'] == NULL) ? 'NA' : $fromCity;
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Booking Info'),
			array('name'	 => 'entityType', 'value'	 => function ($data)
				{
					echo (round($data['ven_trans_amount']) > 0) ? "Gozo Receiver" : "Gozo Paid";
					echo ($data['entityType'] == NULL) ? ' ' : '- ' . $data['entityType'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Entity Type'),
			array('name'	 => 'ven_trans_amount', 'value'	 => function ($data)
				{
					echo round($data['ven_trans_amount']);
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Amount (<i class="fa fa-inr"></i>)</b><br>(+=credit to gozo,<br>-=credit to vendor)'),
			array('name'	 => 'ven_trans_remarks', 'value'	 => function ($data)
				{
					echo trim($data['ven_trans_remarks']);
					if (isset($data['bank_charge']) && $data['bank_charge'] != '')
					{
						echo ",Bank charge deducted(" . $data['bank_charge'] . ")";
					}
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Notes'),
			array('name'	 => 'adm_name', 'value'	 => function ($data)
				{
					echo "<b>" . trim($data['adm_name']) . "</b>";
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Who'),
			array('name'	 => 'runningBalance', 'value'	 => function ($data)
				{
					echo number_format((float) $data['runningBalance'], 2, '.', '');
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => '<b>Running Balance</b>'),
	)));
}
?>
	 