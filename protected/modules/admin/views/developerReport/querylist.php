
<div class="row m0">
    <div class="col-xs-12">        
        <div class="panel panel-default">
            <div class="panel-body">
				<h3>Query Log List</h3>
				<?php
				$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'querylog-form', 'enableClientValidation' => true,
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
				// @var $form TbActiveForm
				?>
				<div class="row">

					<div class="col-xs-12 col-sm-4" style="">
						<div class="form-group">
							<label class="control-label">Date Range</label>
							<?php
							$daterang		 = "Select Date Range";
							$qlg_created1	 = ($model->qlg_created1 == '') ? '' : $model->qlg_created1;
							$qlg_created2	 = ($model->qlg_created2 == '') ? '' : $model->qlg_created2;
							if ($qlg_created1 != '' && $qlg_created2 != '')
							{
								$daterang = date('F d, Y', strtotime($qlg_created1)) . " - " . date('F d, Y', strtotime($qlg_created2));
							}
							?>
							<div id="qlgCreatedDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
								<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
								<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
							</div>
							<?= $form->hiddenField($model, 'qlg_created1'); ?>
							<?= $form->hiddenField($model, 'qlg_created2'); ?>

						</div>
					</div>



					<div class="col-xs-12 col-sm-4">
						<div class="form-group"> 
							<label>Admin </label>
							<?php
							//$leadArr = Admins::model()->getAdminList();
							$leadArr = QueryLog::model()->getAllQueryLogAdmin();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'qlg_admin_id',
								'val'			 => $model->qlg_admin_id,
								'data'			 => $leadArr,
								'htmlOptions'	 => array('style'			 => 'width:100%',
									'placeholder'	 => 'Select  Admin',
									'style'			 => 'width: 100%')
								));
							?>
						</div>
					</div>


				</div>
				<div class="row">
					<div class="col-md-6" >
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?></div>
					
					<div class="col-md-6 text-right" >
						<a href="<?= Yii::app()->createUrl('admpnl/developerReport/query') ?>"><div class="btn btn-primary"><i class="fa fa-plus"></i> Add Query </div></a>&nbsp;
					</div></div>
				</div>
			<?php $this->endWidget(); ?>

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
                                              <div class='col-xs-12 pt5'>{summary}</div><div class='col-xs-12  pr0'>{pager}</div>
                                              </div></div>
                                                    <div class='panel-body table-responsive table-bordered'>{items}</div>
                                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
					'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
					'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
					//    'ajaxType' => 'POST',
					'columns'			 => array(
						array('name'	 => 'qlg_id', 'value'	 => function($data) {
								return $data['qlg_id'];
							}, 'sortable'			 => true,
							//'headerHtmlOptions'	 => array('class' => 'col-sm-1'),
							'header'			 => 'ID'),
						array('name'	 => 'qlg_query', 'value'	 => function($data) {
								return $data['qlg_query'];
							}, 'sortable'			 => false,
							//'headerHtmlOptions'	 => array('class' => 'col-xs-4'),
							'header'			 => 'Query'),
						array('name'	 => 'qlg_desc', 'value'	 => function($data) {
								return $data['qlg_desc'];
							}, 'sortable'								 => false,
							//'headerHtmlOptions'						 => array('class' => 'col-xs-3'),
							'header'								 => 'Desc'),
						array('name'	 => 'qlg_rows_effected', 'value'	 => function($data) {
								return $data['qlg_rows_effected'];
							}, 'sortable'			 => true,
							//'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Rows Affected'),
						array('name'	 => 'qlg_admin_id', 'value'	 => function($data) {
								return $data['adm_fname'] . " " . $data['adm_lname'];
							}, 'sortable'			 => true,
							//'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Executed By'),
						array('name'	 => 'qlg_created', 'value'	 => function($data) {
								echo date("d-m-Y H:i:s", strtotime($data['qlg_created']));
							}, 'sortable'			 => true, //'headerHtmlOptions'	 => array('class' => 'col-xs-1'),
							'header'			 => 'Created Date'),
				)));
			}
			?> 

		</div>  
	</div>  
</div>
</div>
<script type="text/javascript">
    $(document).ready(function ()
    {
        var start = '<?= date('d/m/Y'); ?>';
        var end = '<?= date('d/m/Y'); ?>';
        $('#qlgCreatedDate').daterangepicker(
                {
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    },
                    "showDropdowns": true,
                    "alwaysShowCalendars": true,
                    startDate: start,
                    endDate: end,
                    maxDate: moment(),
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Previous 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Previous 15 Days': [moment().subtract(15, 'days'), moment()]
                    }
                }, function (start1, end1) {
            $('#QueryLog_qlg_created1').val(start1.format('YYYY-MM-DD'));
            $('#QueryLog_qlg_created2').val(end1.format('YYYY-MM-DD'));
            $('#qlgCreatedDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#qlgCreatedDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#qlgCreatedDate span').html('Select Date Range');
            $('#QueryLog_qlg_created1').val('');
            $('#QueryLog_qlg_created2').val('');
        });
    })
</script>
