<?php
$pageno				 = filter_input(INPUT_GET, 'page');
?>
<div class="row">
	<div class="col-xs-12">
		<?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'sms-form', 'enableClientValidation' => true,
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
<!--        <div class="well pb20">
            
			<div class="col-xs-6 col-md-1"> 
				<?php //echo $form->checkboxGroup($model, 'cfg_active', array('label' => 'Active')) ?>
            </div>
            <div class="col-xs-3 text-center mb20">
                <button class="btn btn-primary" type="submit" style="width: 185px;">Search</button>
            </div>
        </div>-->


		<?php $this->endWidget(); ?>
    </div>
	<div class="col-xs-6 pb10">
		<a class="btn btn-primary mb10" onclick="addConfig(this)">Add new</a>
	</div>	
	<div class="col-xs-6 pb10 text-right">
		<a class="btn btn-primary mb10" href="<?= Yii::app()->createUrl('admpnl/cache/refreshConfig') ?>" role="button"><?= "Clean Cache" ?></a>
	</div>
    <div class="col-xs-12 text-center">
		<?php if (Yii::app()->user->hasFlash('success')): ?>
			<div class="alert alert-success" style="padding: 10px">
				<?php echo Yii::app()->user->getFlash('success'); ?>
			</div>
		<?php endif; ?>
    </div>
	<div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$dataProvider->getPagination()->params	 = array_filter($_GET + $_POST);
			$dataProvider->getSort()->params		 = array_filter($_GET + $_POST);

			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				 'filter' => $model,
				'dataProvider'		 => $dataProvider,
				'id'				 => 'ConfigListGrid',
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				//    'ajaxType' => 'POST',
				'columns'			 => array(
					array('name' => 'cfg_name',
						'filter' => CHtml::activeTextField($model, 'cfg_name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('cfg_name'))),
						'value'	 => '$data[cfg_name]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Name',
						'class' => 'booster.widgets.TbEditableColumn',
						),
					array('name' => 'cfg_value',
						'filter' => CHtml::activeTextField($model, 'cfg_value', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('cfg_value'))),
						'value'	 => '$data[cfg_value]', 'sortable' => true, 'headerHtmlOptions'	 => array(), 'header'	=> 'Value'),	
					array('name' => 'cfg_description',
						'filter' => CHtml::activeTextField($model, 'cfg_description', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('cfg_description'))),
						'value' => '$data[cfg_description]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Description'),	
					array('name' => 'cfg_modified_date',
 						'value'	 => function ($data) {
							if ($data['cfg_modified_date'] != '')
							{
								echo DateTimeFormat::DateTimeToLocale($data['cfg_modified_date']);
							}
						},
						'sortable' => true, 'filter' => false, 'headerHtmlOptions' => array(), 'header' => 'Modified Date'),
					array('name' => 'cfg_modified_by',
						'value' => function ($data) {
							if($data['cfg_modified_by'] != '')
							{
								echo $name = Admins::model()->getFullNameById($data['cfg_modified_by']);
							}
						}, 
						'sortable' => false, 'filter' => false, 'headerHtmlOptions' => array(), 'header' => 'Modified By'),
					array('name' => 'cfg_active',
						'value' => function ($data) {
							if($data['cfg_active'] == 1)
							{
								echo "Active";
							}
							else{
								echo "Inactive";
							}
						}, 
						'sortable' => false, 'filter' => CHtml::activeCheckBoxList($model, 'cfg_active', array('1' => 'Active')), 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'Status'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{edit}{active}{inactive}',
						'buttons'			 => array(
							'edit'			 => array(
													'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {
                                                        var box = bootbox.dialog({
                                                            message: data,
                                                            title: \'Config Edit\',
                                                            size: \'large\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
													'url'		 => 'Yii::app()->createUrl("admin/config/add", array("cfg_id" => $data[cfg_id]))',
													'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\edit_booking.png',
													'label'		 => '<i class="fa fa-list"></i>',
													'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Edit'),
												),
							'active'		 => array(
							'click'		 => 'function(e){
                                                            var con = confirm("Are you sure you want to deactivated this config?"); 
                                                            if(con){
                                                                $href = $(this).attr(\'href\');
                                                                $.ajax({
                                                                    url: $href,
                                                                    dataType: "json",
                                                                    className:"bootbox-sm",
                                                                    title:"Inactive Config",
                                                                    success: function(result)
                                                                    {
                                                                        if(result.success)
                                                                        {
                                                                            ConfigListGrid();
                                                                        }
                                                                        else
                                                                        {
                                                                            alert(\'Sorry error occured\');
                                                                        }
                                                                    },
                                                                    error: function(xhr, status, error){
                                                                        alert(\'Sorry error occured\');
                                                                    }
                                                                });
                                                            }
                                                            return false;    
                                                         }',
							'url'		 => 'Yii::app()->createUrl("admpnl/config/ChangeStatus", array("cfg_id" => $data[cfg_id],"cfg_active"=>1))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\active.png',
							'visible'	 => '($data[cfg_active]==1)',
							'label'		 => '<i class="fa fa-toggle-on"></i>',
							'options'	 => array('data-toggle'	 => 'ajaxModal',
								'id'			 => 'rtgActive',
								'style'			 => '',
								'rel'			 => 'popover',
								'data-placement' => 'left',
								'class'			 => 'btn btn-xs cfg_active p0',
								'title'			 => 'Deactivated')
						),
						'inactive'		 => array(
							'click'		 => 'function(){
                                                     var con = confirm("Are you sure you want to activated this config?"); 
                                                        if(con){
                                                            $href = $(this).attr(\'href\');
                                                            $.ajax({
                                                                url: $href,
                                                                dataType: "json",
                                                                className:"bootbox-sm",
                                                                title:"Active Rating",
                                                                success: function(result)
                                                                {
                                                                    if(result.success)
                                                                    {
                                                                        ConfigListGrid();
                                                                    }
                                                                    else
                                                                    {
                                                                        alert(\'Sorry error occured\');
                                                                    }
                                                                },
                                                                error: function(xhr, status, error){
                                                                    alert(\'Sorry error occured\');
                                                                }
                                                            });
                                                        }
                                                        return false;
                                                    }',
							'url'		 => 'Yii::app()->createUrl("admpnl/config/ChangeStatus", array("cfg_id" => $data[cfg_id],"cfg_active"=>0))',
							'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\inactive.png',
							'visible'	 => '($data[cfg_active]==0)',
							'label'		 => '<i class="fa fa-toggle-off"></i>',
							'options'	 => array('data-toggle'	 => 'ajaxModal',
								'id'			 => 'rtgInactive',
								'style'			 => '',
								'rel'			 => 'popover',
								'data-placement' => 'left',
								'class'			 => 'btn btn-xs rtg_inactive p0',
								'title'			 => 'Activated'),
						),
						))
			)));
		}
		?>


    </div>
</div>

<script>
    function ConfigListGrid()
    {
        $('#ConfigListGrid').yiiGridView('update');
    }
	function addConfig(obj)
	{
		var href = '<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/config/add')) ?>';
        jQuery.ajax({type: 'GET', url: href,
            success: function (data) {
                bootbox = bootbox.dialog({
                    message: data,
                    size: 'large',
                    title: 'Add Config',
                    onEscape: function () {
                        bootbox.hide();
                        bootbox.remove();
						location.reload(); 
                    },
                });
            }
        });
	}
</script>  
 
