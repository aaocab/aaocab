<div class="row" id="louList">
    <div class="col-xs-12 col-sm-12 col-md-12">
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'lou-form',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => '',
			),
		));
		/* @var $form TbActiveForm */
		?>
        <div class="well pb20">
            <div class="col-xs-12 col-sm-6 col-md-4"> 
				<?= $form->textFieldGroup($model, 'search', array('label' => 'Search:', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Search By Vehicle Code')))) ?>
            </div>
        </div>
		<div class="col-xs-4 col-md-2 mb15 text-center">
			<button class="btn btn-primary" type="submit" style="width: 150px;" name="Search">Search</button>		
        </div>
		<?php $this->endWidget(); ?>
    </div>

    <div class="col-xs-12">
		<?php

		$checkExportAccess = false;
		if ($roles['rpt_export_roles'] != null)
		{
			$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
		}
		if ($checkExportAccess)
		{
			?>
			<div class="row">
				<?= CHtml::beginForm(Yii::app()->createUrl('report/vehicle/lourequired'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>
				<input type="hidden" id="export" name="export" value="true"/>
				<input type="hidden" id="vhc_code" name="vhc_code" value="<?= $model->search ?>"/>
				<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
				<?= CHtml::endForm() ?>
			</div>
			<?php
		}



		if (!empty($dataProvider))
		{
			$params									 = array_filter($_REQUEST);
			$dataProvider->getPagination()->params	 = $params;
			$dataProvider->getSort()->params		 = $params;
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'id'				 => 'loulist',
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name'	 => 'vhc_code', 'value'	 => function($data) {
                            echo CHtml::link($data["vhc_code"], Yii::app()->createUrl("admin/vehicle/view", ["id" => $data['vhc_id']]), ["class" => "", "onclick" => "return viewDetail(this)"])."<br>";
						}, 'sortable'			 => false, 'headerHtmlOptions'=> array(), 'header' => 'Vehicle Code'),
                                
                    array('name'	 => 'vhc_number', 'value'	 => function($data) {
                            echo CHtml::link($data["vhc_number"], Yii::app()->createUrl("admin/vehicle/view", ["id" => $data['vhc_id']]), ["class" => "", "onclick" => "return viewDetail(this)"])."<br>";
						}, 'sortable'			 => false, 'headerHtmlOptions'=> array(), 'header' => 'Vehicle Code'),
                                
                   
					array('name'	 => 'vnd_code', 'value'	 => function($data) {
							echo $data['vnd_code'];
						}, 'sortable'			 => false, 'headerHtmlOptions'=> array(), 'header' => 'Vendor Code'),
					
			)));
		}
		
		?>
    </div>
</div>
<script>
    function viewDetail(obj)
    {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Vehicle Details',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
                if ($('body').hasClass("modal-open"))
                {
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }

            }
        });
        return false;
    }
</script>