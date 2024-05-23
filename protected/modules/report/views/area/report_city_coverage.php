<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
</style>
<div class="row" >

    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">
				<?php
				$checkContactAccess	 = Yii::app()->user->checkAccess("bookingContactAccess");
				$checkExportAccess	 = false;
				if ($roles['rpt_export_roles'] != null)
				{
					$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
				}
				if ($checkExportAccess)
				{
					?>
					<?= CHtml::beginForm(Yii::app()->createUrl('report/area/citycoverage'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>

					<div class="row">
						<div class="col-xs-12">
							<div class="col-xs-12 col-sm-4 col-md-3">
								<input type="hidden" id="export1" name="export1" value="true"/>
								<input type="hidden" id="export_vnd_region" name="export_vnd_region" value="<?= $model->vnd_region ?>">
								<input type="hidden" id="export_vnd_zone" name="export_vnd_zone" value="<?= $model->vnd_zone ?>">
								<input type="hidden" id="export_vnd_cty" name="export_vnd_cty" value="<?= $model->vnd_cty ?>">
								<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>

							</div>
						</div>
					</div>
					<?= CHtml::endForm() ?>
					<?php
				}
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'cityCoverageList', 'enableClientValidation' => true,
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
                    <div class="col-xs-12">
                        <div class="col-xs-12 col-sm-4 col-md-3"> 
                            <div class="form-group">
                                <label class="control-label">Search By Region</label>
								<?php
								$regionList	 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
								$this->widget('booster.widgets.TbSelect2', array(
									'model'			 => $model,
									'attribute'		 => 'vnd_region',
									'val'			 => $model->vnd_region,
									'asDropDownList' => FALSE,
									'options'		 => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
									'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Region')
								));
								?>
                            </div>
                        </div>
                        <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20" style="padding: 4px;">
                            <button class="btn btn-primary full-width" type="submit"  name="bookingSearch">Search</button>
                        </div>
                    </div>

                    <div class="col-xs-12">
                        <div class="row table table-bordered">
							<?php
							if (!empty($dataProvider))
							{
								$params									 = array_filter($_REQUEST);
								$dataProvider->getPagination()->params	 = $params;
								$dataProvider->getSort()->params		 = $params;
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'vendorListGrid',
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider,
									'template'			 => "<div class='panel-heading'><div class='row m0'>
							<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body table-responsive'>{items}</div>
							<div class='panel-footer'>
							<div class='row'><div class='col-xs-12 col-sm-6 p5'>{summary}</div>
							<div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
									'htmlOptions'		 => array('class' => 'panel panel-primary compact'),
									//       'ajaxType' => 'POST',
									'columns'			 => array(
										array('name'	 => 'cty_name', 'value'	 => function ($data) {
												echo $data['cty_name'];
											}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'City'),
										array('name' => 'home_zone_name', 'value' => $data['home_zone_name'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-left'), 'htmlOptions' => array('class' => 'text-left'), 'header' => 'ZONE'),
										array('name' => 'region', 'value' => $data['region'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-left'), 'htmlOptions' => array('class' => 'text-left'), 'header' => 'Region'),
										array('name' => 'opt_homezone', 'value' => $data['opt_homezone'], 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Homezone Operators(Active)'),
										array('name' => 'opt_homezone_freeze', 'value' => $data['opt_homezone'], 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Homezone Operators(Frozen)'),
										array('name' => 'opt_homezone_inactive', 'value' => $data['opt_homezone_inactive'], 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Homezone Operators(Inactive)'),
										array('name' => 'opt_servingzone', 'value' => $data['opt_servingzone'], 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Serving Zone Operators(Active)'),
										array('name' => 'opt_servingzone_freeze', 'value' => $data['opt_servingzone_freeze'], 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Serving Zone Operators(Frozen)'),
										array('name' => 'opt_servingzone_inactive', 'value' => $data['opt_servingzone_inactive'], 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Serving Zone Operators(Inactive)'),
								)));
							}
							?> 
						</div>
					</div>  
				</div>
				<?php $this->endWidget(); ?>


			</div>  
		</div>  
	</div>
</div>
<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
<script>
    function refreshVendorGrid()
    {
        $('#vendorListGrid').yiiGridView('update');
    }

    function viewRating(vndId) {
        //var href2 = $(obj).attr("href");
        $href2 = $adminUrl + "/rating/listbyvendor";
        $vendorId = vndId;
        $.ajax({
            "url": $href2,
            "type": "GET",
            "dataType": "html",
            data: {"vendor_id": $vendorId},
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: '',
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
