<div class="row">
    <div class="col-xs-12">
        <div class="panel panel-default"> 

			<?php
			if (!empty($dataProvider))
			{
				$params									 = array_filter($_REQUEST);
				$dataProvider->getPagination()->params	 = $params;
				$dataProvider->getSort()->params		 = $params;
				$this->widget('booster.widgets.TbGridView', array(
					'responsiveTable'	 => true,
					'dataProvider'		 => $dataProvider,
					'template'			 => "<div class='panel-heading'><div class='row m0'>
                                    <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
									<div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'><div class='table-responsive'>{items}</div></div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
					'itemsCssClass'		 => 'table-striped table-bordered dataTable mb0',
					'htmlOptions'		 => array('class' => 'panel panel-primary compact'),
					'columns'			 => array(
						array('name'	 => 'vnd_name',
							'value'	 => function ($data) {

								echo CHtml::link($data['vnd_name'], Yii::app()->createUrl("admin/vendor/view", ["code" => $data['vnd_code']]), ["class" => "viewVendor", "onclick" => "", 'target' => '_blank']);
							},
							'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Vendor'),
						array('name' => 'vnd_phone', 'value' => '$data[vnd_phone]', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Phone #'),
						array('name' => 'vrs_last_logged_in', 'value' => 'DateTimeFormat::DateTimeToLocale($data[vrs_last_logged_in])', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Last logged in'),
						array('name' => 'vnp_gnow_modify_time', 'value' => 'DateTimeFormat::DateTimeToLocale($data[vnp_gnow_modify_time])', 'sortable' => true, 'headerHtmlOptions' => array('class' => ''), 'header' => 'Disabled on'),
				)));
			}
			?> 
		</div> 
	</div>  
</div>  
 


