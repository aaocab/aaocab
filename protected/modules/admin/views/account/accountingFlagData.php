<style>
    .search-form ul{
        list-style: none ;
        margin-bottom: 20px;
        vertical-align: bottom
    }
    .search-form ul li{
        padding: 0;
    }
    .table{
        margin-bottom: 5px;
    }
    .pagination {
        margin: 0;
    }
.modal{ overflow: auto;}
</style>

<?php
#Yii::app()->session['agt_type']	 = $model->agt_type;
$form							 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'driver-register-form', 'enableClientValidation' => FALSE,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error'
	),
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
	),
		));
/* @var $form TbActiveForm */
?>

<?php $this->endWidget(); ?>

<div class="row"> 
<!--   date("d/M/Y h:i A", strtotime($data["date"]))-->
    <div class=" col-xs-12 ">
        <a class="mb10" href="" style="text-decoration: none; "><p></p></a>
		<?php
	
		if (!empty($dataProvider))
		{

			$this->widget('booster.widgets.TbGridView', array(
				'id'				 => 'agent-grid',
				'responsiveTable'	 => true,
				'filter'			 => $model,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary'),
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
                   
					array('name' => 'date', 'filter' => FALSE, 'value' => '$data["date"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Date'),
                    array('name' => 'accFlgSetCnt', 'filter' => FALSE, 'value' => '$data["accFlgSetCnt"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'AccFlgSetCnt'),
                    array('name' => 'accFlgUnsetCnt', 'filter' => FALSE, 'value' => '$data["accFlgUnsetCnt"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' =>'AccFlgUnsetCnt'),
                   
					
			)));
		}
		?>
    </div>
</div>


