<div class="panel panel-default panel-border">

	<div class="panel panel-body">
		<h2>Payment Transaction</h2>

		<div class="row">
			<div class="col-sm-12">
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
						'template'			 => "<div class='panel-heading bg-primary text-white border border-primary'>
                                        <div class='row '>
                                        <div class='col-xs-12 col-sm-6   '>{summary}</div>
                                        <div class='col-xs-12 col-sm-6 text-right'>{pager}</div>
                                        </div></div>
                                        <div class='panel-body table-responsive'>{items}</div><div></div>",
						'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
						'htmlOptions'		 => array('class' => 'panel panel-primary table-bordered compact'),
						'columns'			 => array(
							array('name'	 => 'bkg_booking_id', 'filter' => FALSE, 'value'	 => function($data) {
												echo CHtml::link($data["bkg_booking_id"], Yii::app()->createUrl("admin/booking/view", ["id" => $data['bkg_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']) . "<br>";
											}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Booking Id'),
										
							array('name' => 'apg_code', 'value' => $data['apg_code'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array(), 'header' => 'Code'),
							array('name' => 'apg_trans_ref_id', 'value' => $data['apg_ref_no'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array(), 'header' => 'Trn.Ref No'),
							array('name' => 'apg_remarks', 'value' => $data['apg_remark'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array(), 'header' => 'Remark'),
							array('name' => 'apg_amount', 'value' => $data['apg_amount'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array(), 'header' => 'Amount'),
							array('name' => 'apg_date', 'value' =>  'date("d/m/Y h:iA",strtotime($data["apg_date"]))', 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array(), 'header' => 'Date'),
							array('name' => 'apg_ipaddress', 'value' => $data['apg_ipaddress'], 'sortable' => false, 'htmlOptions' => array('class' => 'text-left'), 'headerHtmlOptions' => array(), 'header' => 'IP Address'),
							
					)));
				}
/*
 * apg_code,apg_ref_no
apg_remark,apg_amount
apg_active,apg_date
 */
				?>
			</div>
		</div>
	</div>
</div>
