 
<?php
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div id="content" class=" mt20" style="width: 100%!important;overflow: auto;">

	<div class="row1">

		<div class="col-xs-12">
			<div class="panel panel-default">
				<div class="panel-body" >
					<?php
					/* @var $dataProvider CActiveDataProvider */
					if (!empty($dataProvider))
					{
						$GLOBALS['cityData']					 = Cities::getCityName();
						$params									 = array_filter($_REQUEST);
						$dataProvider->getPagination()->pageSize = 30;
						$dataProvider->getPagination()->params	 = $params;
						$dataProvider->getSort()->params		 = $params;
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'payListGrid',
							'responsiveTable'	 => true,
							'selectableRows'	 => 2,
							'filter'			 => $model,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
            </div></div>
            <div class='panel-body'>{items}</div>
            <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table table-striped table-bordered mb0',
							'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
							'columns'			 => array(
								array('name'				 => 'payeename',
									'filter'			 => CHtml::activeTextField($model, 'payeename', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('payeename'))),
									'value'				 => $data["payeename"],
									'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('payeename')),
								array('name'				 => 'payeetype', 'value'				 => ' $data["payeetype"]', 'sortable'			 => false, 'filter'			 => FALSE,
									'headerHtmlOptions'	 => array('class' => '  text-center'),
									'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Payee Type'),
								array('name'				 => 'onb_amount', 'value'				 => ' $data["onb_amount"]', 'sortable'			 => false, 'filter'			 => FALSE,
									'headerHtmlOptions'	 => array('class' => '  text-center'),
									'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Amount <br>in <i class="fa fa-inr"></i>'),
								array('name'				 => 'onb_remarks', 'value'				 => ' $data["onb_remarks"]', 'sortable'			 => false, 'filter'			 => FALSE,
									'headerHtmlOptions'	 => array('class' => ' text-center'),
									'htmlOptions'		 => array('class' => 'text-Left'), 'header'			 => 'Remarks'),
								array('name'				 => 'onb_created_on', 'value'				 => ' DateTimeFormat::DateTimeToLocale($data["onb_created_on"])', 'sortable'			 => false, 'filter'			 => FALSE,
									'headerHtmlOptions'	 => array('class' => ' text-center'),
									'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Transaction Time'),
								array('name'	 => 'onb_status',
									'value'	 => function($data)
									{
										$list = OnlineBanking::statusList;
										echo $list[$data['onb_status']];
									},
									'sortable'			 => false, 'filter'			 => FALSE,
									'headerHtmlOptions'	 => array('class' => ' text-center'),
									'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Payment Status'),
					array('name'				 => 'onb_response_message', 
'value'				 => '$data["onb_response_message"]', 'sortable'			 => false, 'filter'			 => FALSE,
									'headerHtmlOptions'	 => array('class' => ' text-center'),
									'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Response Message'),
								
	)));
					}
					?>
				</div>
			</div>
		</div>
	</div>





</div>
