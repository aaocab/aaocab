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
		<?php if ($_REQUEST['flag'] == 1)
		{
			?>
			<div style="text-align: center; color: green;">Session destroyed successfully</div>
		<?php
		}
		else
		{
			?>
			<div style="text-align: center; color: red;"><?= $_REQUEST['error'] ?></div>
				<?php } ?>
        <h2 style="text-align: center"></h2>
        <div id="userView1">
            <div class="col-xs-12">
				<?php
				$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'log-form', 'enableClientValidation' => true,
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
                <div class="row">
					<div class="col-xs-12 col-sm-4 col-md-3"> 
						<div class="form-group">
							<label class="control-label">Admins</label>
							<?php
							$adminListJson	 = Admins::model()->getJSON();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'adm_log_user',
								'val'			 => $model->adm_log_user,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($adminListJson), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Admins')
							));
							?>
						</div> </div>
					<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">
						<button class="btn btn-primary full-width" type="submit"  name="adminSearch">Search</button>
					</div>
                </div>
				<?php $this->endWidget(); ?>
			</div>
            <div class=" col-xs-12 ">
				<?php
				if (!empty($dataProvider))
				{
					$params									 = array_filter($_REQUEST);
					$dataProvider->getPagination()->params	 = $params;
					$dataProvider->getSort()->params		 = $params;
					$this->widget('booster.widgets.TbGridView', array(
						'id'				 => 'adminlog-grid',
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
							array('name' => 'admUser.adm_fname', 'value' => '$data["admUser"]["adm_fname"] . " " . $data["admUser"]["adm_lname"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Admin Name'),
							array('name' => 'adm_log_ip', 'value' => '$data["adm_log_ip"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Admin Log IP'),
							array('name' => 'adm_log_session', 'value' => '$data["adm_log_session"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Session ID'),
							array('name' => 'adm_log_in_time', 'value' => '$data["adm_log_in_time"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Admin Log In Time'),
							array('name' => 'adm_log_out_time', 'value' => '$data["adm_log_out_time"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Admin Log Out Time'),
							array(
								'header'			 => 'Action',
								'class'				 => 'CButtonColumn',
								'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
								'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
								'template'			 => '{kill}',
								'buttons'			 => array(
									'kill'			 => array(
										'click'		 => 'function(){
                                                                                                    var con = confirm("Are you sure you want to destroy the session?");
                                                                                                    return con;
                                                                                                }',
										'url'		 => 'Yii::app()->createUrl("admin/admin/kill", array("adm_log_id" => $data["adm_log_id"]))',
										'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\customer_cancel.png',
										'label'		 => '<i class="fa fa-remove"></i>',
										'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJob p0', 'title' => 'Kill'),
									),
									'htmlOptions'	 => array('class' => 'center'),
								))
					)));
				}
				?>
            </div>
        </div>
    </div>
</div>
<script>
</script>



