<div class="row">
    <div class="col-xs-12">
		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'zone-form', 'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error'
			),
			// Please note: When you enable ajax validation, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// See class documentation of CActiveForm for details on this,
			// you need to use the performAjaxValidation()-method described there.
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array(
				'class' => '',
			),
		));
		/* @var $form TbActiveForm */
		?>
        <div class="well pb20">
			<? $cls	 = "col-xs-6 col-sm-4 col-md-3 col-lg-2"; ?>
            <div class="<?= $cls ?>"> 
				<?= $form->textFieldGroup($model, 'search_text', array('label' => '', 'widgetOptions' => ['htmlOptions' => []])) ?>
            </div>
            <div class="">
                <button class="btn btn-primary" type="submit" style="width: 185px;"  name="zoneSearch">Search</button>
            </div>
        </div>
		<?php $this->endWidget(); ?>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name' => 'zon_name', 'value' => $data['zon_name'], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Zone Name'),
					array('name'	 => 'cty_names', 'value'	 => function($data) {
							echo $data['cty_names'];
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Cities List'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{edit}{addrule}{log}{manage}',
						'buttons'			 => array(
							'edit'			 => array(
								'url'		 => 'Yii::app()->createUrl("admin/zone/add", array("zon_id" => $data["zon_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\zone\edit_booking.png',
								'label'		 => '<i class="fa fa-edit"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Edit'),
							),
							'addrule'		 => array(
								'url'		 => 'Yii::app()->createUrl("admin/pricerule/areapricerule", array(\'aprtypeid\' => $data[zon_id], \'aprtype\' => 1))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\show_details.png',
								'label'		 => '<i class="fa fa-type"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Add Type'),
							),
							'log'			 => array(
								'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {

                                                        var box = bootbox.dialog({
                                                            message: data,
                                                            title: \'Zone Log\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
								'url'		 => 'Yii::app()->createUrl("admin/zone/showlog", array("zoneid" => $data["zon_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\rate_list\show_log.png',
								'label'		 => '<i class="fa fa-list"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Show Log'),
							),
							'manage'		 => array(
								'url'		 => 'Yii::app()->createUrl("admin/zone/manageServiceZone", array("zon_id" => $data["zon_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\zone\service_zone.png',
								'label'		 => '<i class="fa fa-task"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Manage Service Zone'),
							),
							'htmlOptions'	 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>