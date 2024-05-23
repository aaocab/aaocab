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
<?php
$vendorCity			 = (Cities::model()->getCityOnlyByBooking1());
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div id="content" class=" mt20" style="width: 100%!important;overflow: auto;">
    <div class="row mb50">
        <h2 style="text-align: center"></h2>

		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'vendor-list', 'enableClientValidation' => true,
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

        <div id="userView1">
			<div class="row">
                <div class="col-xs-12">
					<div class="col-xs-12 col-sm-3 col-md-2"> 
                        <div class="form-group">
                            <label class="control-label" style="margin-left:5px;">Search By Home Zone</label>
							<?php
							$zoneListJson		 = Zones::model()->getJSON();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $vnpmodel,
								'attribute'		 => 'vnp_home_zone',
								'val'			 => $vnpmodel->vnp_home_zone,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($zoneListJson), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%;margin-left:5px;', 'placeholder' => 'Zone List', 'id' => 'VendorPref_vnp_home_zone')
							));
							?>
                        </div>
                    </div>
					<div class="col-xs-12 col-sm-3 col-md-2">
						<div class="form-group">
                            <label class="control-label" style="margin-left:5px;">Search By Region</label>
							<?php
							$regionList			 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $vnpmodel,
								'attribute'		 => 'zonRegion',
								'val'			 => $vnpmodel->zonRegion,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Region', 'id' => 'VendorPref_zonRegion')
							));
							?>
                        </div>
					</div>
					<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20" style="padding: 4px;">
						<!--                    <button class="btn btn-primary full-width" type="submit"  name="bookingSearch">Search</button>-->
						<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width', 'name' => 'bookingSearch')); ?>
					</div>
					<?php $this->endWidget(); ?>
				</div>


				<?php
				$checkExportAccess	 = false;
				if ($roles['rpt_export_roles'] != null)
				{
					$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
				}
				if ($checkExportAccess)
				{
					?>
					<?= CHtml::beginForm(Yii::app()->createUrl('report/area/TierCount'), "post", ['id' => 'exporttier', 'style' => "margin-bottom: 10px;"]); ?>
					<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20" style="padding: 4px;">
						<input type="hidden" id="VendorPref" name="VendorPref" value="<?= $vnpmodel->vnp_home_zone ?>"/>
						<input type="hidden" id="export1" name="export1" value="true"/>
						<input type="hidden" name="vnp_home_zone_export" value="<?= $vnpmodel->vnp_home_zone; ?>">
						<input type="hidden" name="zonRegion_export" value="<?= $vnpmodel->zonRegion; ?>">
						<button class="btn btn-default" onclick="exportTierCount();" type="button" style="width: 185px;">Export Below Table</button>
					</div>
					<?php
					echo CHtml::endForm();
				}
				?>

			</div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-body" >
							<?php
							/* @var $dataProvider CActiveDataProvider */
							if (!empty($dataProvider))
							{
								$params									 = array_filter($_REQUEST);
								$dataProvider->getPagination()->pageSize = 30;
								$dataProvider->getPagination()->params	 = $params;
								$dataProvider->getSort()->params		 = $params;
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'vendorListGrid',
									'responsiveTable'	 => true,
									'selectableRows'	 => 2,
									'filter'			 => $vnpmodel,
									'dataProvider'		 => $dataProvider,
									'template'			 => "<div class='panel-heading'><div class='row m0'>
									<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
									</div></div>
									<div class='panel-body'>{items}</div>
									<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name'				 => 'zon_name',
											'value'				 => '$data["zon_name"]', 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Zone Name'),
										array('name'	 => 'value',
											'value'	 => function ($data) {
												echo ($data["cntValueVendors"] > 0) ? CHtml::link($data["cntValueVendors"], Yii::app()->createUrl("admin/vendor/vendorDetails", ["id" => $data['vnp_home_zone'], "tier" => '1']), ["class" => "", "onclick" => "return viewDetailVendor(this)"]) . " / " . CHtml::link($data["cntValueVehicles"], Yii::app()->createUrl("admin/vehicle/vehicleDetails", ["id" => $data['vnp_home_zone'], "tier" => '1']), ["class" => "", "onclick" => "return viewDetailVehicle(this)"]) : '0' . " / " . $data['cntValueVehicles'];
												//echo $data['cntValueVendors']  . " | " . $data['cntValueVehicles'] ;
											},
											'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Value (Vendor Count / Car Count)'),
										array('name'	 => 'valueplus',
											'value'	 => function ($data) {
												echo ($data["cntValuePlusVendors"] > 0) ? CHtml::link($data["cntValuePlusVendors"], Yii::app()->createUrl("admin/vendor/vendorDetails", ["id" => $data['vnp_home_zone'], "tier" => '2']), ["class" => "", "onclick" => "return viewDetailVendor(this)"]) . " / " . CHtml::link($data["cntValuePlusVehicles"], Yii::app()->createUrl("admin/vehicle/vehicleDetails", ["id" => $data['vnp_home_zone'], "tier" => '2']), ["class" => "", "onclick" => "return viewDetailVehicle(this)"]) : '0' . " / " . $data['cntValuePlusVehicles'];
												//echo $data['valueplustier']  . " | " . $data['vehicleValuePlus'] ;
											},
											'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Value+ (Vendor Count / Car Count)'),
										array('name'	 => 'plus',
											'value'	 => function ($data) {
												echo ($data["cntPlusVendors"] > 0) ? CHtml::link($data["cntPlusVendors"], Yii::app()->createUrl("admin/vendor/vendorDetails", ["id" => $data['vnp_home_zone'], "tier" => '3']), ["class" => "", "onclick" => "return viewDetailVendor(this)"]) . " / " . CHtml::link($data["cntPlusVehicles"], Yii::app()->createUrl("admin/vehicle/vehicleDetails", ["id" => $data['vnp_home_zone'], "tier" => '3']), ["class" => "", "onclick" => "return viewDetailVehicle(this)"]) : '0' . " / " . $data['cntPlusVehicles'];
												//echo $data['plustier']  . " | " . $data['vehiclePlus'] ;
											},
											'sortable'								 => false, 'headerHtmlOptions'						 => array(), 'header'								 => 'Premium (Vendor Count / Car Count)'),
										array('name'	 => 'select',
											'value'	 => function ($data) {
												echo ($data["cntSelectVendors"] > 0) ? CHtml::link($data["cntSelectVendors"], Yii::app()->createUrl("admin/vendor/vendorDetails", ["id" => $data['vnp_home_zone'], "tier" => '4']), ["class" => "", "onclick" => "return viewDetailVendor(this)"]) . " / " . CHtml::link($data["cntSelectVehicles"], Yii::app()->createUrl("admin/vehicle/vehicleDetails", ["id" => $data['vnp_home_zone'], "tier" => '4']), ["class" => "", "onclick" => "return viewDetailVehicle(this)"]) : '0' . " / " . $data['cntSelectVehicles'];
												//echo $data['selecttier']  . " | " . $data['vehicleSelect'] ;
											},
											'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Select (Vendor Count / Car Count)'),
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
<script>
    function viewDetailVendor(obj)
    {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Vendor Details',
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

    function viewDetailVehicle(obj)
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

    function exportTierCount()
    {
        $("input[name='vnp_home_zone_export']").val($('#VendorPref_vnp_home_zone').val());
        $("input[name='zonRegion_export']").val($('#VendorPref_zonRegion').val());
        $("#exporttier").submit();
    }
</script>
