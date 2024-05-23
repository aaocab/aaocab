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
</style>
<div id="content" class=" mt20" style="width: 100%!important">
    <div class="row mb50">
        <h2 style="text-align: center"></h2>


		<?php
		$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'booking-form',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
			),
			// Please note: When you enable ajax validation, make sure the corresponding
			// controller action is handling ajax validation correctly.
			// See class documentation of CActiveForm for details on this,
			// you need to use the performAjaxValidation()-method described there.
			'enableAjaxValidation'	 => false,
			'errorMessageCssClass'	 => 'help-block',
			'htmlOptions'			 => array('class' => '',),
		));
		/* @var $form TbActiveForm */
		?>
		<div class="col-xs-12 col-lg-12">
			<div class="row">



				<div class="col-xs-12 col-sm-3 col-md-3">
					<div class="form-group">
						<label class="control-label">Manager</label>
						<?php
						$leadArr = Admins::model()->getAdminList();
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'adp_team_leader_id',
							'val'			 => $model->adp_team_leader_id,
							'data'			 => $leadArr,
							'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
								'placeholder'	 => 'Manager')
						));
						?>
					</div>
				</div>

				<div class="col-xs-12 col-sm-3 col-md-3">
					<div class="form-group">
						<label class="control-label">Team</label>
						<?php
						$teamarr = Teams::getList();
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $model,
							'attribute'		 => 'teamId',
							'val'			 => $model->teamId,
							'data'			 => $teamarr,
							'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
								'placeholder'	 => 'Team')
						));
						?>
					</div>
				</div>

				<div class="col-xs-12 col-sm-2 col-md-2">
					<label class="control-label">Show Only Current Gozens</label>
					<div class="form-group">
						<input class="form-control" type="checkbox" value='<?php echo $model->isActive; ?>' id="isActive"  onclick="changeStatus('<?php echo $model->isActive; ?>')"  name="Admins[isActive]" <?php
						if ($model->isActive > 0)
						{
							echo 'checked="checked"';
						}
						?> >
					</div>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">  
					<div class="form-group">
						<br>&nbsp;&nbsp;&nbsp;&nbsp;
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width')); ?>
					</div>
				</div>






			</div>
		</div>

		<?php $this->endWidget(); ?>

        <div id="userView1">
            <div class=" col-xs-12 ">
                <div class="projects">
                    <a class="btn btn-primary mb10" href="<?= Yii::app()->createUrl('admin/admin/add') ?>" style="text-decoration: none;margin-left: 20px;">Add new</a>
                    <div class="panel panel-default">
                        <div class="panel-body" >
							<?php
							if (!empty($dataProvider))
							{
								$params									 = array_filter($_REQUEST);
								$dataProvider->getPagination()->params	 = $params;
								$dataProvider->getSort()->params		 = $params;
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'admin-grid',
									'responsiveTable'	 => true,
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
										array('name' => 'adm_fname', 'filter' => CHtml::activeTextField($model, 'adm_fname', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('adm_fname'))), 'value' => '$data["adm_fname"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('adm_fname')),
										array('name' => 'adm_lname', 'filter' => CHtml::activeTextField($model, 'adm_lname', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('adm_lname'))), 'value' => '$data["adm_lname"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('adm_lname')),
										array('name' => 'adm_user', 'filter' => CHtml::activeTextField($model, 'adm_user', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('adm_user'))), 'value' => '$data["adm_user"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('adm_user')),
										array('name' => 'adm_email', 'filter' => CHtml::activeTextField($model, 'adm_email', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('adm_email'))), 'value' => '$data["adm_email"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('adm_email')),
										array('name' => 'adm_phone', 'filter' => CHtml::activeTextField($model, 'adm_phone', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('adm_phone'))), 'value' => '$data["adm_phone"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('adm_phone')),
										array('name' => 'adp_emp_code', 'filter' => CHtml::activeTextField($model, 'adp_emp_code', array('class' => 'form-control', 'placeholder' => 'Search by Employee Code')), 'value' => '$data->adp_emp_code', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => "Employee Code"),
										array('name' => 'tea_name', 'filter' => CHtml::activeTextField($model, 'tea_name', array('class' => 'form-control', 'placeholder' => 'Search by Team')), 'value' => '$data->tea_name', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => "Team"),
										array('name' => 'dpt_name', 'filter' => CHtml::activeTextField($model, 'dpt_name', array('class' => 'form-control', 'placeholder' => 'Search by Department')), 'value' => '$data->dpt_name', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => "Department"),
										array('name' => 'cat_name', 'filter' => CHtml::activeTextField($model, 'cat_name', array('class' => 'form-control', 'placeholder' => 'Search by Category')), 'value' => '$data->cat_name', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => "Category"),
										array('name'	 => 'adp_team_leader_id', 'filter' => false, 'value'	 =>
											function($data) {
												if ($data->adp_team_leader_id > 0)
												{
													echo Admins::model()->findById($data->adp_team_leader_id)->getName();
												}
											},
											'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => "Team Leader"),
										array('name'				 => 'des_name', 'filter'			 => false, 'value'				 => '$data->des_name',
											'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => "Designation"),
										array('name'	 => 'adp_hiring_date', 'filter' => false, 'value'	 =>
											function($data) {
												if ($data->adp_hiring_date != null)
												{
													echo date("d/M/Y", strtotime($data->adp_hiring_date));
												}
											},
											'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => "Hiring Date"),
										array('name'	 => 'adm_active', 'filter' => CHtml::activeCheckBoxList($model, 'adm_active', array('1' => 'On', '2' => 'Off')), 'value'	 => function($data) {
												if ($data->adm_active == 1)
												{
													echo "On";
												}
												else
												{
													echo "Off";
												}
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('adm_active')),
										array('name' => 'adm_created_at', 'filter' => FALSE, 'value' => 'date("d/M/Y h:i A", strtotime($data["adm_created_at"]))', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('adm_created_at')),
										array(
											'header'			 => 'Action',
											'class'				 => 'CButtonColumn',
											'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center'),
											'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
											'template'			 => '{edit}{delete}{deactivate}{activate}{log}{dailer}',
											'buttons'			 => array(
												'edit'			 => array(
													'url'		 => 'Yii::app()->createUrl("admin/admin/add", array("admid" => $data["adm_id"]))',
													'imageUrl'	 => false,
													'label'		 => '<i class="fa fa-edit"></i>',
													'options'	 => array('style' => 'margin-right: 4px', 'class' => 'btn btn-xs btn-info conEdit', 'title' => 'Edit'),
												),
												'delete'		 => array(
													'click'		 => 'function(){
                                                        var con = confirm("Are you sure you want to delete this admin?");
                                                        return con;
                                                    }',
													'url'		 => 'Yii::app()->createUrl("admin/admin/del", array("admid" => $data["adm_id"]))',
													'imageUrl'	 => false,
													'label'		 => '<i class="fa fa-remove"></i>',
													'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 4px', 'class' => 'btn btn-xs btn-danger conDelete', 'title' => 'Delete'),
												),
												'deactivate'	 => array(
													'click'		 => 'function(){
                                                        var con = confirm("Are you sure you want to deactivate this admin?"); 
                                                        if(con){
                                                            $href = $(this).attr(\'href\');
                                                            $.ajax({
                                                                url: $href,
                                                                dataType: "json",
                                                                success: function(result){
                                                                    if(result.success){
                                                                        $(\'#admin-grid\').yiiGridView(\'update\');
                                                                    } else {
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
													'url'		 => 'Yii::app()->createUrl("admpnl/admin/changestatus", array("adm_id" => $data->adm_id,"adm_active"=>1))',
													'imageUrl'	 => false,
													'visible'	 => '$data->adm_active == 1',
													'label'		 => '<i class="fa fa-toggle-on"></i>',
													'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 4px;', 'class' => 'btn btn-xs btn-success conDisable', 'title' => 'Deactivate')
												),
												'activate'		 => array(
													'click'		 => 'function(){
                                                        var con = confirm("Are you sure you want to activate this admin?"); 
                                                        if(con){
                                                            $href = $(this).attr(\'href\');
                                                            $.ajax({
                                                                url: $href,
                                                                dataType: "json",
                                                                success: function(result){
                                                                    if(result.success){
                                                                        $(\'#admin-grid\').yiiGridView(\'update\');
                                                                    } else {
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
													'url'		 => 'Yii::app()->createUrl("admpnl/admin/changestatus", array("adm_id" => $data->adm_id,"adm_active"=>2))',
													'imageUrl'	 => false,
													'visible'	 => '$data->adm_active == 2',
													'label'		 => '<i class="fa fa-toggle-off"></i>',
													'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'margin-right: 4px;', 'class' => 'btn btn-xs btn-primary conEnable', 'title' => 'Activate'),
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
                                                        title: \'Admin Log\',
                                                        onEscape: function () {
                                                            // user pressed escape
                                                        }
                                                        });
                                                    }
                                                    });
                                                    return false;
                                                    }',
													'url'		 => 'Yii::app()->createUrl("admin/admin/showlog", array("admid" => $data->adm_id))',
													'imageUrl'	 => false,
													'label'		 => '<i class="fa fa-list"></i>',
													'options'	 => array('class' => 'btn btn-primary btn-xs conshowlog', 'title' => 'Show Log'),
												),
												'dailer'		 => array(
													'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {
                                                        var box = bootbox.dialog({

                                                        message: data,
                                                        onEscape: function () {
                                                            // user pressed escape
                                                        }
                                                        });
                                                    }
                                                    });
                                                    return false;
                                                    }',
													'url'		 => 'Yii::app()->createUrl("admin/admin/dailer", array("admid" => $data->adm_id))',
													'imageUrl'	 => false,
													'label'		 => '<i class="fa fa-phone"></i>',
													'options'	 => array('class' => 'btn btn-success btn-xs', 'style' => 'margin-left: 4px;', 'title' => 'Dailer Info'),
												),
												'htmlOptions'	 => array('class' => 'center', 'style' => 'margin-left: 4px;'),
											))
								)));
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var front_end_height = parseInt($(window).outerHeight(true));
        var footer_height = parseInt($("footer").outerHeight(true));
        var header_height = parseInt($("header").outerHeight(true));
    });
	 function changeStatus(type)
        {
            if (type == 1)
            {
               $("#isActive").val("0");
                $('#uniform-isActive span').removeClass('checked');
                $("#isActive").prop('checked', false);
            } else
            {
                $("#isActive").val("1");
                $('#uniform-isActive span').addClass('checked');
                $("#isActive").prop('checked', true);
            }
        }
</script>

