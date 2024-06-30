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
	.nowrap{
		white-space: nowrap;
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
<div id="content" class="  " style="width: 100%!important;overflow: auto;">
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


            <div class="row1">
				<?php
				if (!$hideMycall)
				{
					?>
					<div class="col-xs-12">

						<?php
						if (Yii::app()->user->hasFlash('notice'))
						{
							?>
							<div class="alert alert-block alert-danger">
								<?php echo Yii::app()->user->getFlash('notice'); ?>
							</div>
						<?php } ?>

						<?php
						if (Yii::app()->user->hasFlash('success'))
						{
							?>
							<div class="alert alert-block alert-success">
								<?php echo Yii::app()->user->getFlash('success'); ?>
							</div>
						<?php } ?>
						<div class="row">
							<div class="col-xs-12 col-sm-3 col-md-2"> 
								<div class="form-group">
									<label class="control-label" style="margin-left:5px;">Search By Home Zone</label>
									<?php
									$zoneListJson	 = Zones::model()->getJSON();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model->vendorPrefs,
										'attribute'		 => 'vnp_home_zone',
										'val'			 => $model->vendorPrefs->vnp_home_zone,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($zoneListJson), 'allowClear' => true),
										'htmlOptions'	 => array('style' => 'width:100%;margin-left:5px;', 'placeholder' => 'Zone List')
									));
									?>
								</div>
							</div>
							<div class="col-xs-12 col-sm-3 col-md-2"> 
								<div class="form-group cityinput">
									<label class="control-label">Search By Home City</label>
									<?php
//                            $datacity = Cities::model()->getCityByBooking1();
//                            $this->widget('booster.widgets.TbSelect2', array(
//                                'model' => $model,
//                                'attribute' => 'vnd_cty',
//                                'val' => $model->vnd_cty,
//                                'asDropDownList' => FALSE,
//                                'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true),
//                                'htmlOptions' => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'City List')
//                            ));
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $model,
										'attribute'			 => 'vnd_city',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "Select City",
										'fullWidth'			 => false,
										'htmlOptions'		 => array('width' => '100%',
										//  'id' => 'from_city_id1'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                  populateSourceCity(this, '{$model->vnd_city}');
                                                }",
									'load'			 => "js:function(query, callback){
                        loadSourceCity(query, callback);
                        }",
									'render'		 => "js:{
                            option: function(item, escape){
                            return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                            },
                            option_create: function(data, escape){
                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                            }
                        }",
										),
									));
									?>
								</div>
							</div>

							<div class="col-xs-12 col-sm-3 col-md-2"> 
								<div class="form-group">
									<label class="control-label" style="margin-left:5px;">Search By Served Zones</label>
									<?php
									$zoneListJson	 = Zones::model()->getJSON();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model->vendorPrefs,
										'attribute'		 => 'vnp_accepted_zone',
										'val'			 => $model->vendorPrefs->vnp_accepted_zone,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($zoneListJson), 'allowClear' => true),
										'htmlOptions'	 => array('style' => 'width:100%;margin-left:5px;', 'placeholder' => 'Zone List')
									));
									?>
								</div>
							</div>

							<div class="col-xs-12 col-sm-3 col-md-2"> 
								<div class="form-group">
									<label class="control-label" style="margin-left:5px;">Rating</label>
									<?php
									$rateList		 = Vendors::model()->getRating();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model->vendorStats,
										'attribute'		 => 'vrs_vnd_overall_rating',
										'val'			 => $model->vendorStats->vrs_vnd_overall_rating,
										'asDropDownList' => FALSE,
										'options'		 => array(
											'data'		 => new CJavaScriptExpression(VehicleTypes::model()->getJSON($rateList)),
											'allowClear' => true
										),
										'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Rating')
									));
									?>
								</div>
							</div>


							<div class="col-xs-12 col-sm-3 col-md-2"> 
								<div class="form-group">
									<label class="control-label" style="margin-left:5px;">Tier</label>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'vnd_rel_tier',
										'val'			 => $model->vnd_rel_tier,
										'asDropDownList' => FALSE,
										'options'		 => array(
											'data'		 => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Vendors::getTierList())),
											'allowClear' => true
										),
										'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Tier')
									));
									?>
								</div>
							</div>

							<div class="col-xs-12 col-sm-3 col-md-2"> 
								<div class="form-group">
									<label class="control-label" style="margin-left:5px;">Status</label>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'vnd_status',
										'val'			 => $model->vnd_status,
										'asDropDownList' => FALSE,
										'options'		 => array(
											'data'		 => new CJavaScriptExpression(VehicleTypes::model()->getJSON(Vendors::model()->getStatusList())),
											'allowClear' => true
										),
										'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Status')
									));
									?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-3 col-md-2"> 
								<div class="form-group">
									<label class="control-label" style="margin-left:5px;">Operating Services</label>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'bkgtypes',
										'data'			 => Booking::model()->bkgtype,
										'htmlOptions'	 => array('class'			 => 'p0', 'style'			 => 'width:100%;margin-left:5px;', 'multiple'		 => 'multiple',
											'placeholder'	 => 'Operating Services')
									));
									?>
								</div>
							</div>
							<div class="col-xs-12 col-sm-3 col-md-2"> 
								<div class="form-group">
									<label class="control-label" style="margin-left:5px;">Search By Service Class</label>

									<?php
									$classes		 = ServiceClass::model()->getList('array');
									$serviceTierArr	 = ServiceClass::model()->getJSON($classes);
									unset($serviceTierArr[4]);
									$this->widget('booster.widgets.TbSelect2', array(
										'attribute'		 => 'vnd_service_class',
										'model'			 => $model,
										'data'			 => $serviceTierArr,
										'value'			 => $model->vnd_service_class,
										'htmlOptions'	 => array(
											'placeholder'	 => 'Select Service Class',
											'width'			 => '100%',
											'style'			 => 'width:100%',
										),
									));
									?>

								</div>
							</div>
							<div class="col-xs-12 col-sm-3 col-md-2"> 
								<div class="form-group">
									<label class="control-label" style="margin-left:5px;">Search By Vehicle Category</label>

									<?php
									$categoryList	 = VehicleCategory::getCat();
									$categoryListArr = array_intersect_key($categoryList, array_flip(['1', '2', '3']));
									$this->widget('booster.widgets.TbSelect2', array(
										'attribute'		 => 'vnd_vehicle_category',
										'model'			 => $model,
										'data'			 => $categoryListArr,
										'value'			 => $model->vnd_vehicle_category,
										'htmlOptions'	 => array(
											'placeholder'	 => 'Select Vehicle Category',
											'width'			 => '100%',
											'style'			 => 'width:100%',
										),
									));
									?>

								</div>
							</div>
							<div class="col-xs-12 col-sm-3 col-md-2"> 
								<div class="form-group">
									<label class="control-label" style="margin-left:5px;">Search By Car Model</label>
									<?php
									$vtypeList		 = VehicleTypes::model()->getVehicleTypeList1();
									$vtypeListJson	 = VehicleTypes::model()->getJSON($vtypeList);

									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $vhtModel,
										'attribute'		 => 'vht_id',
										'val'			 => $vhtModel->vht_id,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($vtypeListJson), 'allowClear' => true),
										'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vehicle Model')
									));
									?>
								</div>
							</div>

							<div class="col-xs-12 col-sm-4 col-md-2"> 
								<div class="form-group">
									<label class="control-label">Security Deposit Status </label>
									<?php
									$filters = [
										1	 => 'Deposit Paid',
										2	 => 'Deposit not Paid'
									];
									$dataPay = Vendors::model()->getJSON($filters);
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'vnd_security_paid',
										'val'			 => $model->vnd_security_paid,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
										'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%;margin-left:5px;', 'placeholder' => 'Select Filter')
									));
									?>

								</div>

							</div>

							<div class="col-xs-12 col-sm-3 col-md-2">
								<div class="form-group">
									<input class="form-control" type="checkbox" id="searchdlmismatch" name="searchdlmismatch" <?php
									if ($qry['searchdlmismatch'] > 0 || $_POST['searchdlmismatch'])
									{
										echo 'checked="checked"';
									}
									?> >&nbsp;DL Mismatched
								</div>

								<div class="form-group">
									<input class="form-control" type="checkbox" id="searchpanmismatch" name="searchpanmismatch" <?php
									if ($qry['searchpanmismatch'] > 0 || $_POST['searchpanmismatch'])
									{
										echo 'checked="checked"';
									}
									?> >&nbsp;PAN Mismatched
								</div>
							</div>
							<!--                     <div class="col-xs-12 col-sm-3 col-md-2">
													<div class="form-group">
														<input class="form-control" type="checkbox" id="searchvndpaymentlock" name="searchvndpaymentlock" <?php
							if ($qry['searchvndpaymentlock'] > 0 || $_REQUEST['source'] == 232 || $_POST['searchvndpaymentlock'])
							{
								echo 'checked="checked"';
							}
							?> >&nbsp;Vendor Payment Lock
													</div>
												</div>-->
							<?php
							if ($model->vnd_source == 232)
							{
								?>
								<div class="col-xs-12 col-sm-4 col-md-4">
									<div class="form-group">
										<label class="control-label">Channel Partner</label>
										<?php
										$dataagents = Agents::model()->getAgentsFromBooking();
										$this->widget('booster.widgets.TbSelect2', array(
											'model'			 => $bkgModel,
											'attribute'		 => 'bkg_agent_id',
											'val'			 => $model->vnd_bkg_agent_id,
											'asDropDownList' => FALSE,
											'options'		 => array('data' => new CJavaScriptExpression($dataagents), 'allowClear' => true),
											'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Partner name')
										));
										?>
									</div> 
								</div>
							<?php } ?>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-4 col-md-2"> 
								<div class="form-group">
									<label class="control-label">Platform </label>
									<?php
									$filters = [
										1	 => 'All',
										2	 => 'Local Rides'
									];
									$dataPay = Vendors::model()->getJSON($filters);
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $model,
										'attribute'		 => 'vnd_platform',
										'val'			 => $model->vnd_platform,
										'asDropDownList' => FALSE,
										'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
										'htmlOptions'	 => array('class' => 'p0', 'style' => 'width:100%;margin-left:5px;', 'placeholder' => 'Select Platform')
									));
									?>

								</div>

							</div>
							<div class="col-xs-12 col-sm-3 col-md-2 pt15">
								<?php echo $form->checkboxGroup($model, 'vnd_registered_platform', ['label' => 'Show DCO registered only', 'widgetOptions' => ['htmlOptions' => ['value' => 1]]]);
								?>
							</div>
							<div class="col-xs-12 col-sm-3 col-md-2 pt15">
								<?php echo $form->checkboxGroup($model, 'vnd_cat_type', ['label' => 'Show DCO only', 'widgetOptions' => ['htmlOptions' => ['value' => 1]]]);
								?>
							</div>

							<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20" style="padding: 4px;">
								<button class="btn btn-primary full-width" type="submit"  name="bookingSearch">Search</button>
							</div>
						</div>
					</div>

					<div class="col-xs-12">
						<a class="btn btn-primary mb10" href="<?= Yii::app()->createUrl('admin/vendor/add') ?>" style="text-decoration: none;margin-left: 20px;">Add new</a>

						<?php
					}
					else
					{
						?>
						<div class="col-xs-12">
						<?php }
						?>
						<div class="panel panel-default">
							<div class="panel-body " >
								<?php
								/* @var $dataProvider CActiveDataProvider */
								if (!empty($dataProvider))
								{
									$GLOBALS['cityData']					 = Cities::getCityName();
									$params									 = array_filter($_REQUEST);
									$dataProvider->getPagination()->pageSize = 30;
									$dataProvider->getPagination()->params	 = $params;
									$dataProvider->getSort()->params		 = $params;
									$this->widget('booster.widgets.TbGridView', array(
										'id'				 => 'vendorListGrid',
										'responsiveTable'	 => true,
										'selectableRows'	 => 2,
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
//											array('class'	 => 'CCheckBoxColumn', "value"	 => function ($data)
//												{
//													return $data['vnd_id'];
//												}, 'id' => 'vnd_checked[]'),
											array('name'	 => 'vnd_name',
												'filter' => CHtml::activeTextField($model, 'vnd_name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vnd_name'))),
												// 'value' => '$data["vnd_name"]', 
												'value'	 => function ($data) {
													$vndName = $data["vnd_name"];
													if ($data["ctt_name"] != "")
													{
														$vndName = $data['cntVendors'] . $data["ctt_name"];
													}
													else if ($data["ctt_business_name"] != "")
													{
														$vndName = $data["ctt_business_name"];
													}
													$icon				 = '<img src="/images/icon/eye.png"  style="cursor:pointer ;height:16px; width:16px;" title="Value">';
													echo CHtml::link($vndName, Yii::app()->createUrl("admin/vendor/view", ["id" => $data['vnd_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']);
													//echo CHtml::link($icon, Yii::app()->createUrl("admin/vendor/profile", ["id" => $data['vnd_id']]), ["class" => "viewBooking", "onclick" => "", 'target' => '_blank']);
													echo ($data['vnd_code'] != '') ? "<br><span class='nowrap'>( " . $data['vnd_code'] . " )</span>" : '';
													if ($data["ctt_is_name_dl_matched"] == 2)
													{
														echo ' <span class="label label-danger ">DL Mismatch</span><br><br>';
													}
													if ($data["ctt_is_name_pan_matched"] == 2)
													{
														echo ' <span class="label label-danger ">Pan Mismatch</span><br>';
													}
													if ($data["cntMergedVnd"] > 0)
													{
														$mergedList = explode(',', trim($data['codeMergedVnd']));
														//$mergedShow	 = '';
														echo "</br><span style='font-size:0.9em'><br>Merged:  ";
														foreach ($mergedList as $val)
														{
															echo '<br>' . CHtml::link(trim($val), Yii::app()->createUrl("admin/vendor/view", ["code" => trim($val)]), ["class" => "", "onclick" => "", 'target' => '_blank']);
														}
														echo "</span>";
													}
												},
												'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('vnd_name')),
											array('name'	 => 'vnd_owner', 'filter' => CHtml::activeTextField($model, 'vnd_owner', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vnd_owner'))),
												'value'	 => function ($data) {
													// if($data['ctt_user_type'] == '1'){
													// $contact = Contact::model()->findByPk($data["ctt_owner_id"]);  
													echo $data['ctt_first_name'] . '&nbsp;' . $data['ctt_last_name'] . "<br>";
													//echo $data["ctt_owner_id"] . "<br>";
													// }
													if ($data['vnd_type'] == 0)
													{
														echo ' <span class="label label-primary">Dedicated</span>';
													}
													if ($data['vnd_type'] == 1)
													{
														echo ' <span class="label label-primary">Meterdown</span>';
													}
													if ($data['vnd_type'] == 2)
													{
														echo ' <span class="label label-primary">Floating</span>';
													}
													if ($data['vnd_type'] == 3)
													{
														echo ' <span class="label label-primary">Exclusive</span>';
													}

													if ($data['vnp_is_attached'] == 1)
													{
														echo ' <span class="label label-info ">Attached</span>';
													}
													if ($data['vnp_is_freeze'] == 1)
													{
														echo ' <span class="label label-warning ">Frozen</span>';
													}
													if ($data['vnp_is_freeze'] == 2)
													{
														echo ' <span class="label label-warning ">Adminstrative Frozen</span>';
													}
													if ($data['vnd_active'] == 2)
													{
														echo ' <span class="label label-danger">Blocked</span>';
													}

													if ($data['vnd_cat_type'] == 1 || $data['vnd_is_dco'] == 1)
													{
														echo ' <span class="label label-info ">DCO</span>';
													}

													if ($data['vnp_oneway'] == 1)
													{
														echo ' <span class="label label-success">One Way</span>';
													}
													if ($data['vnp_round_trip'] == 1)
													{
														echo ' <span class="label label-success">Return</span><br>';
													}
													if ($data['vnd_agreement_lnk'] != '' && $data['vnd_agreement_lnk'])
													{
														$adate = ($data["vnd_agreement_dt"] != "" AND date("Y-m-d", strtotime($data["vnd_agreement_dt"])) != "1970-01-01") ? date("d/M/Y", strtotime($data["vnd_agreement_dt"])) : "";
														echo ' <span class="label label-primary">Agreement on ' . $adate . '</span><br>';
													}
													if ($data['vnp_vhc_boost_count'] > 0)
													{
														echo '<span class="label label-success ">Boost Enabled</span><br>';
													}
													if ($data['bcb_lock_vendor_payment'] > 0)
													{
														echo '<span class="label label-danger"> Vendor Payment Lock </span><br>';
													}
												},
												'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('vnd_owner')),
											array('name' => 'vnd_company', 'filter' => CHtml::activeTextField($model, 'vnd_company', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vnd_company'))), 'value' => '$data["ctt_business_name"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('vnd_company')),
//                                        array('name' => 'vnd_type', 'filter' => false, 'value' => function($data) {
//                                                switch ($data->vnd_type) {
//                                                    case 0:
//                                                        echo "Dedicated";
//                                                        break;
//                                                    case 1:
//                                                        echo "Meterdown";
//                                                        break;
//                                                    case 2:
//                                                        echo "Floating";
//                                                        break;
//                                                    case 3:
//                                                        echo "Exclusive";
//                                                        break;
//                                                    default:
//                                                        break;
//                                                }
//                                            },
//                                            'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('vnd_type')),
											array('name'	 => 'phn_phone_no',
												'filter' => CHtml::activeTextField($model->vndContact->contactPhones, 'phn_phone_no', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('phn_phone_no'))),
												'value'	 => function ($data) {
													echo CHtml::link("Show Contact", Yii::app()->createUrl("admin/contact/view", ["ctt_id" => $data['cr_contact_id'], 'viewType' => 'vendor']), ["class" => "", "onclick" => "return viewContactVendor(this)"]);
												},
												'sortable'	 => true, 'header'	 => $model->vndContact->contactPhones->getAttributeLabel('phn_phone_no')),
//										
//										array('name' => 'vnd_alt_contact_number', 'filter' => CHtml::activeTextField($model, 'vnd_alt_contact_number', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vnd_alt_contact_number'))), 'value' => '$data["vnd_alt_contact_number"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('vnd_alt_contact_number')),
											array('name'	 => 'eml_email_address',
												'filter' => CHtml::activeTextField($model->vndContact->contactEmails, 'eml_email_address', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('eml_email_address'))),
												'value'	 => function ($data) {
													if (trim($data['eml_email_address']) != '')
													{
														echo ContactEmail::getEmailFromString($data['eml_email_address']);
													}
												},
												'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'htmlOptions'		 => array('style' => 'word-break: break-all;min-width:75px'), 'header'			 => $model->vndContact->contactEmails->getAttributeLabel('eml_email_address')),
											// array('name' => 'vnd_booking_type', 'filter' => false, 'value' => '$data["vnd_booking_type"] == 1 ? "Yes" : "No"', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Operates one-way (Y/N)'),
											array('name' => 'ctt_address', 'filter' => CHtml::activeTextField($model->vndContact, 'ctt_address', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->vndContact->getAttributeLabel('ctt_address'))), 'value' => '$data["ctt_address"]', 'sortable' => FALSE, 'headerHtmlOptions' => array(), 'header' => $model->vndContact->getAttributeLabel('ctt_address')),
											array('name'	 => 'ctt_city', 'filter' => CHtml::activeDropDownList($model->vndContact, 'ctt_city', $vendorCity, array('class' => 'form-control', 'placeholder' => 'Search By City')),
												'value'	 => function ($data) {
													echo $GLOBALS['cityData'][$data["ctt_city"]];
												}, 'sortable'			 => FALSE, 'headerHtmlOptions'	 => array(), 'header'			 => $model->vndContact->getAttributeLabel('ctt_city')),
											array('name'	 => 'vnd_active', 'filter' => FALSE, 'value'	 => function ($data) {
													if ($data['vnd_active'] == 1)
													{
														echo "Active";
													}
													else if ($data['vnd_active'] == 2)
													{
														echo "Blocked";
													}
												}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => $model->getAttributeLabel('vnd_active')),
//											array('name'	 => 'security', 'value'	 => function ($data) {
////													$relVendorList	 = \Vendors::getRelatedIds($data['vnd_id']);
////													$securityAmount	 = AccountTransactions::getSecurityAmount($relVendorList);
////													if ($securityAmount > 0)
////													{
////														echo '<i class="fa fa-inr"></i>' . $securityAmount . ' on ' . DateTimeFormat::DateToDatePicker($data["vrs_security_receive_date"]);
////													}
//													if ($data['vrs_security_amount'] > 0)
//													{
//														echo '<i class="fa fa-inr"></i>' . $data['vrs_security_amount'] . ' on ' . DateTimeFormat::DateToDatePicker($data["vrs_security_receive_date"]);
//													}
//												}, 'sortable'			 => false, 'filter'			 => FALSE, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Security Deposit'),
											array('name'	 => 'drivers_approved', 'value'	 => function ($data) {
													if ($data['vrs_count_driver'] > 0)
													{
														echo $data['vrs_approve_driver_count'] . '/' .
														(($data['vrs_pending_drivers'] > 0) ? CHtml::link($data['vrs_pending_drivers'], Yii::app()->createUrl('admin/driver/list', ['vnd' => $data['vnd_id'], 'approve' => 2, 'vndlist' => 1]), array('target' => '_blank')) : 0) . '/' .
														$data['vrs_rejected_drivers'] . '/' .
														(($data['vrs_count_driver'] > 0) ? CHtml::link($data['vrs_count_driver'], Yii::app()->createUrl('admin/driver/list', ['vnd' => $data['vnd_id'], 'vndlist' => 1]), array('target' => '_blank')) : 0);
													}
												}, 'sortable'			 => false, 'filter'			 => FALSE, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => '#Drivers<br>(approved/<br>pending approval/<br>rejected/all)'),
											array('name'	 => 'vehicles_approved', 'value'	 => function ($data) {
													if ($data['vrs_count_car'] > 0)
													{
														echo $data['vrs_approve_car_count'] . '/' .
														(($data['vrs_pending_cars'] > 0) ? CHtml::link($data['vrs_pending_cars'], Yii::app()->createUrl('admin/vehicle/list', ['vnd' => $data['vnd_id'], 'approve' => 2, 'vndlist' => 1]), array('target' => '_blank')) : 0) . '/' .
														$data['vrs_rejected_cars'] . '/' .
														(($data['vrs_count_car'] > 0) ? CHtml::link($data['vrs_count_car'], Yii::app()->createUrl('admin/vehicle/list', ['vnd' => $data['vnd_id'], 'vndlist' => 1]), array('target' => '_blank')) : 0)
														// $data['vehicles_all']
														;
													}
												}, 'sortable'			 => false, 'filter'			 => FALSE, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => '#Cars<br>(approved/<br>pending approval/<br>rejected/all)'),
											array('name'	 => 'cab_type_cnt', 'value'	 => function ($data) {

													echo (($data['vrs_car_reg_compact_cnt'] > 0) ? $data['vrs_car_reg_compact_cnt'] : 0 ) . '/' .
													(($data['vrs_car_reg_sedan_cnt'] > 0) ? $data['vrs_car_reg_sedan_cnt'] : 0 ) . '/' .
													(($data['vrs_car_reg_suv_cnt'] > 0) ? $data['vrs_car_reg_suv_cnt'] : 0 );
												}, 'sortable'			 => false, 'filter'			 => FALSE, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => '#Cab Types<br>(Compact/<br>Sedan/SUV)'),
											array('name'	 => 'last_lock_date', 'filter' => FALSE, 'value'	 => function ($data) {
													if ($data['last_lock_date'] != null && $data['last_lock_date'] != '-')
													{
														echo date("d/M/Y h:i A", strtotime($data["last_lock_date"]));
													}
													else
													{
														echo "-";
													}
												},
												'visible'			 => ($model->vnd_source == 232 ? true : false),
												'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Last Lock Amount Date'),
											array('name' => 'vnd_create_date', 'filter' => FALSE, 'value' => 'date("d/M/Y h:i A", strtotime($data["vnd_create_date"]))', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('vnd_create_date')),
											array('name'	 => 'vrs_last_logged_in', 'value'	 => function ($data) {
													if ($data['vrs_last_logged_in'] != '')
													{
														$lastLogin = date("d/M/Y h:i A", strtotime($data["vrs_last_logged_in"]));
													}
													else
													{
														$lastLogin = "-";
													}
													echo $lastLogin;
												}, 'sortable'			 => false
												, 'filter'			 => FALSE
												, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center')
												, 'htmlOptions'		 => array('class' => 'text-center')
												, 'header'			 => 'Last Login'),
											array('name'	 => 'vnd_rel_tier', 'value'	 => function ($data) {
													$tier = '';
													if ($data['vnd_rel_tier'] == 0)
													{
														$tier .= "Silver";
													}
													else if ($data['vnd_rel_tier'] > 0)
													{
														$tier .= "Gold";
													}

													$rating	 = " / ";
													$rating	 .= ($data['vrs_vnd_overall_rating'] > 0) ? $data['vrs_vnd_overall_rating'] : '0';
													echo $tier . $rating;
												}, 'sortable'			 => false
												, 'filter'			 => FALSE
												, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center')
												, 'htmlOptions'		 => array('class' => 'text-center')
												, 'header'			 => '#(Tier - Rating)'),
//                                        array('name' => 'vnd_agreement_file_link', 'filter' => false,
//                                            'value' => function($data) {
//                                                if ($data['vnd_agreement_file_link'] != '' && $data['vnd_agreement_file_link']) {
//                                                    echo "Yes";
//                                                    //echo CHtml::link("Yes", Yii::app()->createUrl($data['vnd_agreement_file_link']));
//                                                } else {
//                                                    echo No;
//                                                }
//                                            },
//                                            //'value' => '$data["vnd_agreement"]==1?"Yes":"No"',
//                                            'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Agreement'),
											//  array('name' => 'vnd_agreement_date', 'filter' => FALSE, 'value' => '($data["vnd_agreement_date"]!="" AND date("Y-m-d",strtotime($data["vnd_agreement_date"]))!="1970-01-01")? date("d/M/Y", strtotime($data["vnd_agreement_date"])):""', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Agreement date'),
//										array('name' => 'vnd_incorporation_year', 'filter' => FALSE, 'value' => '$data["vnd_incorporation_year"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Year of incorporation'),
											array(
												'header'			 => 'Action',
												'class'				 => 'CButtonColumn',
												'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
												'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
												'template'			 => '{showaccount}{docview}{vehicleverify}{edit}{log}{addremark}<br>{markedbadlist}{resetmarkedbad}{vendorassign}{vendorunassign}{active}{inactive}{admfreeze}{admunfreeze}{cod_active}{cod_inactive}{agreement}{duplicateuser}{linkuser}', //{delete}
												'buttons'			 => array(
													'showaccount'	 => array(
														'url'		 => 'Yii::app()->createUrl("admin/vendor/vendoraccount", array("vnd_id" => $data["vnd_id"]))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\gozocoins.png',
														'label'		 => '<i class="fa fa-check"></i>',
														'options'	 => array('style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'accdetails btn btn-xs p0', 'title' => 'Vendor Account'),
													),
													'docview'		 => array(
														'url'		 => 'Yii::app()->createUrl("admin/document/view", array(\'ctt_id\' => $data[cr_contact_id],\'viewType\' =>"vendor"))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\uploads.png',
														'label'		 => '<i class="fa fa-email"></i>',
														'options'	 => array('target' => '_blank', 'style' => '', 'class' => 'btn btn-xs p0', 'title' => 'Document Upload'),
													),
													'vehicleverify'	 => array(
														'click'		 => 'function(){
                                                        $href = $(this).attr(\'href\');
                                                        $.ajax({
                                                        url: $href,
                                                        dataType: "json",
                                                        success: function(result){
                                                        if(result.success){
                                                        bootbox.alert("Email sent successfully<br>URL: <br>"+result.url);
                                                                            return false;
                                                                   }else{
                                                                           alert(\'Sorry error occured\');
                                                                            return false;
                                                                   }

                                                           },
                                                           error: function(xhr, status, error){
                                                                   alert(\'Sorry error occured\');
                                                                    return false;
                                                           }
                                                       });
                                                       return false;
                                                    }',
														'url'		 => 'Yii::app()->createUrl("admin/vendor/showverifylink", array("vndid" => $data["vnd_id"]))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\verify_vehicle_link.png',
														'label'		 => '<i class="fa fa-check"></i>',
														'options'	 => array('style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'verify btn btn-xs p0', 'title' => 'Verify Vehicle Link'),
													),
													'edit'			 => array(
														'url'		 => 'Yii::app()->createUrl("admin/vendor/add", array("agtid" => $data[vnd_id]))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\edit_booking.png',
														'label'		 => '<i class="fa fa-edit"></i>',
														'options'	 => array('style' => '', 'class' => 'btn btn-xs conEdit p0', 'title' => 'Edit Model'),
													),
													'linkuser'		 => array(
														'click'		 => 'function(){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "html",
                                                                        success: function(data){
                                                                               var linkuserbootbox1 = bootbox.dialog({ 
                                                                                   message: data,  
                                                                                   title:"Link User",
                                                                                   size: "large",
                                                                                   callback: function(){   }
                                                                               });
                                                                                linkuserbootbox1.on("hidden.bs.modal", function () { $(this).data("bs.modal", null); });
                                                                        },
                                                                        error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                        }
                                                                    });
                                                            
                                                                    return false;
                                                    }',
														'url'		 => 'Yii::app()->createUrl("aaohome/vendor/linkuser", array("vndId" => $data[vnd_id]))',
														'label'		 => '<i class="fa fa-users"></i>',
														'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'padding: 4px ;margin-left: 4px', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs linkUser', 'title' => 'Link User')
													),
//												'delete'			 => array(
//													'click'		 => 'function(){
//                                                        var con = confirm("Are you sure you want to delete this model?");
//                                                        return con;
//                                                    }',
//													'url'		 => 'Yii::app()->createUrl("admin/vendor/del", array("agtid" => $data[vnd_id]))',
//													'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\customer_cancel.png',
//													'label'		 => '<i class="fa fa-remove"></i>',
//													'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conDelete p0', 'title' => 'Delete Model'),
//												),
//													'delete'		 => array(
//														'click'		 => 'function(e){
//                                                         var con = confirm("Are you sure you want to delete this model?"); 
//                                                          if(con){
//                                                        try
//                                                            {
//                                                            $href = $(this).attr("href");
//                                                            jQuery.ajax({type:"GET",url:$href,success:function(data)
//                                                            {
//															
//                                                                bootbox.dialog({ 
//                                                                message: data, 
//                                                                className:"bootbox-sm",
//                                                                title:"Delete Vendor",
//                                                                success: function(result){
//                                                                if(result.success){
//                                                                   
//																	
//                                                                    }else{
//                                                                        alert(\'Sorry error occured\');
//                                                                    }
//                                                                },
//                                                                error: function(xhr, status, error){
//                                                                    alert(\'Sorry error occured\');
//                                                                }
//                                                            });
//                                                            }}); 
//                                                            }
//                                                            catch(e)
//                                                            { alert(e); }
//                                                            }
//                                                            return false;
//                                                            
//                                                     }',
//														'url'		 => 'Yii::app()->createUrl("admin/vendor/del", array("vndid" => $data[vnd_id]))',
//														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\customer_cancel.png',
//														'label'		 => '<i class="fa fa-remove"></i>',
//														'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conDelete p0', 'title' => 'Delete Model'),
//													),
													'addremark'		 => array(
														'click'		 => 'function(e){                                                        
                                                        try
                                                            {
                                                            $href = $(this).attr("href");
                                                            jQuery.ajax({type:"GET",url:$href,success:function(data)
                                                            {
                                                                bootbox.dialog({ 
                                                                message: data, 
                                                                className:"bootbox-sm",
                                                                title:"Add Remark",
                                                                success: function(result){
                                                                if(result.success){
                                                                
                                                                    }else{
                                                                        alert(\'Sorry error occured\');
                                                                    }
                                                                },
                                                                error: function(xhr, status, error){
                                                                    alert(\'Sorry error occured\');
                                                                }
                                                            });
                                                            }}); 
                                                            }
                                                            catch(e)
                                                            { alert(e); }
                                                        return false;
                                                            
                                                    }',
														'url'		 => 'Yii::app()->createUrl("aaohome/vendor/addremark", array("vnd_id" => $data[vnd_id]))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\add_remarks.png',
														//'visible'	 => '($data[vnd_active] == 1 && Yii::app()->user->checkAccess("vendorList"))',
														'label'		 => '<i class="fa fa-toggle-on"></i>',
														'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'remark', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs addremark p0', 'title' => 'Add Remark')
													),
													'active'		 => array(
														'click'		 => 'function(e){
                                                         var con = confirm("Are you sure you want to block this vendor?"); 
                                                          if(con){
                                                        try
                                                            {
                                                            $href = $(this).attr("href");
                                                            jQuery.ajax({type:"GET",url:$href,success:function(data)
                                                            {
                                                                bootbox.dialog({ 
                                                                message: data, 
                                                                className:"bootbox-sm",
                                                                title:"Block Vendor",
                                                                success: function(result){
                                                                if(result.success){
                                                                       
                                                                    }else{
                                                                        alert(\'Sorry error occured\');
                                                                    }
                                                                },
                                                                error: function(xhr, status, error){
                                                                    alert(\'Sorry error occured\');
                                                                }
                                                            });
                                                            }}); 
                                                            }
                                                            catch(e)
                                                            { alert(e); }
                                                            }
                                                            return false;
                                                            
                                                     }',
														'url'		 => 'Yii::app()->createUrl("aaohome/vendor/block", array("vnd_id" => $data[vnd_id]))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\vendor_unblock.png',
														'visible'	 => '($data[vnd_active] == 1 && Yii::app()->user->checkAccess("vendorChangestatus"))',
														'label'		 => '<i class="fa fa-toggle-on"></i>',
														'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example1', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs conEnable11 p0', 'title' => 'Block')
													),
													'inactive'		 => array(
														'click'		 => 'function(){
                                                    var con = confirm("Are you sure you want to unblock this vendor?"); 
                                                        if(con){
                                                            $href = $(this).attr(\'href\');
                                                            $.ajax({
                                                                url: $href,
                                                                dataType: "json",
                                                                success: function(result){
                                                                    if(result.success){
                                                                        refreshVendorGrid();
                                                                    }else{
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
														'url'		 => 'Yii::app()->createUrl("aaohome/vendor/changestatus", array("vnd_id" => $data[vnd_id],"vnd_active"=>2))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\vendor_block.png',
														'visible'	 => '($data[vnd_active] == 2 && Yii::app()->user->checkAccess("vendorChangestatus"))',
														'label'		 => '<i class="fa fa-toggle-off"></i>',
														'options'	 => array('data-toggle'	 => 'ajaxModal',
															'id'			 => 'example',
															'style'			 => '',
															'rel'			 => 'popover',
															'data-placement' => 'left',
															'class'			 => 'btn btn-xs conInactive p0',
															'title'			 => 'Unblock Vendor'),
													),
													'vendorassign'	 => array(
														'click'		 => 'function(e){                                                        
                                                        try
                                                        {
                                                            $href = $(this).attr("href");
                                                            jQuery.ajax({type:"GET",url:$href,success:function(data)
                                                            {
                                                                bootbox.dialog({ 
                                                                message: data, 
                                                                className:"bootbox-sm",
                                                                title:"UnFreeze Vendor",
                                                                success: function(result){
                                                                    if(result.success)
                                                                    {
                                                                       
                                                                    }else
                                                                    {
                                                                        alert(\'Sorry error occured\');
                                                                    }
                                                                },
                                                                error: function(xhr, status, error){
                                                                    alert(\'Sorry error occured\');
                                                                }
                                                            });
                                                            }}); 
                                                        }
                                                        catch(e)
                                                        { 
                                                            alert(e); 
                                                        }
                                                        return false;
                                                            
                                                    }',
														'url'		 => 'Yii::app()->createUrl("aaohome/vendor/freeze", array("vnd_id" => $data[vnd_id],"vnd_is_freeze"=>1))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
														'visible'	 => 'false',
														'label'		 => '<i class="fa fa-toggle-on"></i>',
														'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'admFreeze', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs inactive p0', 'title' => 'Unfreeze Vendor')
													),
													'vendorunassign' => array(
														'click'		 => 'function(e){                                                        
                                                        try
                                                        {
                                                            $href = $(this).attr("href");
                                                            jQuery.ajax({type:"GET",url:$href,success:function(data)
                                                            {
                                                                bootbox.dialog({ 
                                                                message: data, 
                                                                className:"bootbox-sm",
                                                                title:"Freeze Vendor",
                                                                success: function(result){
                                                                    if(result.success)
                                                                    {
                                                                       
                                                                    }else
                                                                    {
                                                                        alert(\'Sorry error occured\');
                                                                    }
                                                                },
                                                                error: function(xhr, status, error){
                                                                    alert(\'Sorry error occured\');
                                                                }
                                                            });
                                                            }}); 
                                                        }
                                                        catch(e)
                                                        { 
                                                            alert(e); 
                                                        }
                                                        return false;
                                                            
                                                    }',
														'url'		 => 'Yii::app()->createUrl("aaohome/vendor/freeze", array("vnd_id" => $data[vnd_id],"vnp_is_freeze"=>0))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\active.png',
														'visible'	 => false,
														'label'		 => '<i class="fa fa-toggle-on"></i>',
														'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'admFreeze', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs active p0', 'title' => 'Freeze Vendor')
													),
													//AdministrativeFreeze                                                
													'admunfreeze'	 => array
														(
														'click'		 => 'function(){
                                                        var con = confirm("Are you sure you want to admin unfreeze this vendor?"); 
                                                        if(con){
                                                        try
                                                            {
                                                            $href = $(this).attr("href");
                                                            jQuery.ajax({type:"GET",url:$href,success:function(data)
                                                            {
                                                                bootbox.dialog({ 
                                                                message: data, 
                                                                className:"bootbox-sm",
                                                                title:"Unfreeze Vendor",
                                                                success: function(result){
                                                                if(result.success){
                                                                       
                                                                    }else{
                                                                        alert(\'Sorry error occured\');
                                                                    }
                                                                },
                                                                error: function(xhr, status, error){
                                                                    alert(\'Sorry error occured\');
                                                                }
                                                            });
                                                            }}); 
                                                            }
                                                            catch(e)
                                                            { alert(e); }
                                                            }
                                                            return false;
                                                            
                                                     }',
														'url'		 => 'Yii::app()->createUrl("aaohome/vendor/administrativefreeze", array("vnd_id" => $data[vnd_id],"vnp_is_freeze"=>2))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\unfreeze.png',
														'visible'	 => '(($data[vnp_is_freeze] == 1 || $data[vnp_is_freeze] == 2) && Yii::app()->user->checkAccess("vendorChangestatus"))',
														'label'		 => '<i class="fa fa-toggle-on"></i>',
														'options'	 => array('data-toggle'	 => 'ajaxModal',
															'id'			 => 'unfreeze2',
															'style'			 => '',
															'rel'			 => 'popover',
															'data-placement' => 'left',
															'class'			 => 'btn btn-xs admunfreeze p0',
															'title'			 => 'Administrative Unfreeze')
													),
													'admfreeze'		 => array(
														'click'		 => 'function(){
                                                            var con = confirm("Are you sure you want to freeze this vendor?"); 
                                                              if(con){
                                                        try
                                                            {
                                                            $href = $(this).attr("href");
                                                            jQuery.ajax({type:"GET",url:$href,success:function(data)
                                                            {
                                                                bootbox.dialog({ 
                                                                message: data, 
                                                                className:"bootbox-sm",
                                                                title:"Freeze Vendor",
                                                                success: function(result){
                                                                if(result.success){
                                                                       
                                                                    }else{
                                                                        alert(\'Sorry error occured\');
                                                                    }
                                                                },
                                                                error: function(xhr, status, error){
                                                                    alert(\'Sorry error occured\');
                                                                }
                                                            });
                                                            }}); 
                                                            }
                                                            catch(e)
                                                            { alert(e); }
                                                            }
                                                            return false;
                                                            
                                                     }',
														'url'		 => 'Yii::app()->createUrl("aaohome/vendor/administrativefreeze", array("vnd_id" => $data[vnd_id],"vnp_is_freeze"=>0))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\freeze.png',
														'visible'	 => '($data[vnp_is_freeze] == 0 && Yii::app()->user->checkAccess("vendorChangestatus"))',
														'label'		 => '<i class="fa fa-toggle-off"></i>',
														'options'	 => array('data-toggle'	 => 'ajaxModal',
															'id'			 => 'freeze2',
															'style'			 => '',
															'rel'			 => 'popover',
															'data-placement' => 'left',
															'class'			 => 'btn btn-xs admfreeze p0',
															'title'			 => 'Administrative Freeze'),
													),
													'cod_active'	 => array(
														'click'		 => 'function(e){
                                                        var con = confirm("Are you sure you want to freeze COD for this vendor?"); 
                                                        if(con){
                                                            $href = $(this).attr(\'href\');
                                                            $.ajax({
                                                                url: $href,
                                                                dataType: "json",
                                                                className:"bootbox-sm",
                                                                title:"Inactive COD",
                                                                success: function(result){
                                                                    if(result.success){
                                                                        refreshVendorGrid();
                                                                    }else{
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
														'url'		 => 'Yii::app()->createUrl("aaohome/vendor/changecod", array("vnd_id" => $data[vnd_id],"vnd_cod"=>0))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\cod_freeze.png',
														'visible'	 => '($data[vnp_cod_freeze] == 0 && Yii::app()->user->checkAccess("vendorChangestatus"))',
														'label'		 => '<i class="fa fa-toggle-on"></i>',
														'options'	 => array('data-toggle'	 => 'ajaxModal',
															'id'			 => 'codActive',
															'style'			 => '',
															'rel'			 => 'popover',
															'data-placement' => 'left',
															'class'			 => 'btn btn-xs cod_active p0',
															'title'			 => 'Freeze COD')
													),
													'cod_inactive'	 => array
														(
														'click'		 => 'function(){
                                                     var con = confirm("Are you sure you want to unfreeze COD for this vendor?"); 
                                                        if(con){
                                                            $href = $(this).attr(\'href\');
                                                            $.ajax({
                                                                url: $href,
                                                                dataType: "json",
                                                                className:"bootbox-sm",
                                                                title:"Active COD",
                                                                success: function(result){
                                                                    if(result.success){
                                                                        refreshVendorGrid();
                                                                    }else{
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
														'url'		 => 'Yii::app()->createUrl("aaohome/vendor/changecod", array("vnd_id" => $data[vnd_id],"vnd_cod"=>1))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\cod_unfreeze.png',
														'visible'	 => '($data[vnp_cod_freeze] == 1 && Yii::app()->user->checkAccess("vendorChangestatus"))',
														'label'		 => '<i class="fa fa-toggle-off"></i>',
														'options'	 => array('data-toggle'	 => 'ajaxModal',
															'id'			 => 'codInactive',
															'style'			 => '',
															'rel'			 => 'popover',
															'data-placement' => 'left',
															'class'			 => 'btn btn-xs cod_inactive p0',
															'title'			 => 'Unfreeze COD'),
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
                                                            title: \'Vendor Log\',
                                                            size: \'large\',
                                                            onEscape: function () {

                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
														'url'		 => 'Yii::app()->createUrl("admin/vendor/showlog", array("vndid" => $data[vnd_id]))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\show_log.png',
														'label'		 => '<i class="fa fa-list"></i>',
														'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Show Log'),
													),
													'markedbadlist'	 => array(
														'click'		 => 'function(e){
                                                        try
                                                            {
                                                            $href = $(this).attr("href");
                                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                                            {
                                                                bootbox.dialog({ 
                                                                message: data, 
                                                                className:"bootbox-lg",
                                                                title:"Mark Bad Vendor",
                                                                size: "large",
                                                                callback: function(){   }
                                                            });
                                                            }}); 
                                                            }
                                                            catch(e)
                                                            { alert(e); }
                                                            return false;
                                                         }',
														'url'		 => 'Yii::app()->createUrl("admin/vendor/markedbadlist", array("vnd_id"=>$data[vnd_id]))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\bad_vendor.png',
														'label'		 => '<i class="fa fa-credit-card"></i>',
														'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example2', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs markBad p0', 'title' => 'Marked Bad Vendor'),
													),
													'resetmarkedbad' => array(
														'click'		 => 'function(){
                                                        $href = $(this).attr(\'href\');
                                                        jQuery.ajax({type: \'GET\',
                                                        url: $href,
                                                        success: function (data){
                                                            bootbox.dialog({
                                                                message: data,
                                                                title: \'Reset Bad Count For Vendor\',
                                                                onEscape: function () {
                                                                    // user pressed escape
                                                                }
                                                            });
                                                        }
                                                    });
                                                    return false;
                                                    }',
														'url'		 => 'Yii::app()->createUrl("admin/vendor/resetmarkedbad", array("refId" =>$data[vnd_id]))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\reset_marked_bad_vendor.png',
														'visible'	 => '($data[vrs_mark_vend_count]>0)',
														'label'		 => '<i class="fa fa-refresh"></i>',
														'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs resetBad p0', 'title' => 'Reset Marked Bad Vendor'),
													),
													'merge'			 => array(
														'click'		 => 'function(e){
                                                        try {
															$href = $(this).attr("href");
															jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)  {
																			var mergebox=bootbox.dialog({ 
																			message: data, 
																			className:"bootbox-lg",
																			title:"Merge Vendors",
																			size: "large",
																			callback: function(){   
																			},
																			onEscape: function(){                                                
																			   $(mergebox).modal("hide");
																			},
																			});
                                                            }}); 
                                                        }
                                                    catch(e)
                                                       { alert(e); }
                                                       return false;
                                                    }',
														'url'		 => 'Yii::app()->createUrl("admin/vendor/merge", array(\'ctt_id\' => $data[vnd_contact_id]))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\merge.png',
														'label'		 => '<i class="fa fa-credit-card"></i>',
														'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example2', 'class' => 'btn btn-xs ignoreMergeView p0', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs merge p0', 'title' => 'Merge Contact'),
													),
													'agreement'		 => array(
														'click'		 => 'function(e){
                                                        try {
															$href = $(this).attr("href");
															jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)  {
																			var agreement=bootbox.dialog({ 
																			message: data, 
																			className:"bootbox-lg",
																			title:"Approve Agreement",
																			size: "large",
																			callback: function(){   
																			},
																			onEscape: function(){                                                
																			   $(agreement).modal("hide");
																			},
																			});
                                                            }}); 
                                                        }
                                                    catch(e)
                                                       { alert(e); }
                                                       return false;
                                                    }',
														'url'		 => 'Yii::app()->createUrl("admin/vendor/agreementShowdoc", array(\'ctt_id\' => $data[vnd_contact_id],\'vnd_id\' => $data[vnd_id]))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\copy_booking.png',
														'label'		 => '<i class="fa fa-check"></i>',
														'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example2', 'class' => 'btn btn-xs ignoreAgreement p0', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs agreement p0', 'title' => 'Approve Agreement'),
													),
													'duplicateuser'	 => array(
														'visible'	 => '($data[vnp_multi_link]==0) ? false : true',
														'click'		 => 'function(){
                                                    $href = $(this).attr(\'href\');
                                                    jQuery.ajax({type: \'GET\',
                                                    url: $href,
                                                    success: function (data)
                                                    {
                                                        var box = bootbox.dialog({
                                                            message: data,
                                                            title: \'Vendor List with same User\',
                                                            size: \'large\',
                                                            onEscape: function () {
												
                                                                // user pressed escape
                                                            }
                                                        });
                                                    }
                                                });
                                                    return false;
                                                    }',
														'url'		 => 'Yii::app()->createUrl("admin/vendor/showduplicateuser", array("vndid" => $data[vnd_id],"userid" => $data[vnd_user_id]))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\view.gif',
														'label'		 => '<i class="fa fa-list"></i>',
														'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs duplicateuser p0', 'title' => 'Show Vendor List with same User'),
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
				<?php
				$this->endWidget();

				if (!$hideMycall)
				{
					?>



					<div class=" col-xs-12  ">
						<div class="projects">
							<!--		<h2>Users</h2>-->


							<div class="row">
								<form name="messageForm" method="get" action="">
									<div class="row">
										<textarea rows="4" cols="80" id="messageText" placeholder="Enter your message here." style="text-decoration:none; margin-left:50px;"></textarea>
									</div>
								</form>
							</div>
							<br>
							<div class="row">
								<div class="col-xs-12">
									<div class="col-md-3">
										<div class="form-group"><label><b>Send message via: </b></label></div>
									</div>
									<div class="col-md-3">
										<div class="form-group"><label><input type="checkbox" id="chk_sms" checked/> <b>SMS</b></label></div>
									</div>
									<div class="col-md-3">
										<div class="form-group"><label><input type="checkbox" id="chk_email" checked/> <b>E-Mail</b></label></div>
									</div>
									<div class="col-md-3">
										<div class="form-group"><label><input type="checkbox" id="chk_app" checked/> <b>Push Notification on App</b></label></div>
									</div>
								</div>
							</div>    

							<a href="#" class="btn btn-primary mb10" onclick="BroadcastMessage();" style="text-decoration: none;margin-left: 20px;">Send Message</a>

						</div>
					</div>
				<?php } ?>
			</div>


		</div>


	</div>

	<script>
		$(document).ready(function () {

			var front_end_height = parseInt($(window).outerHeight(true));
			var footer_height = parseInt($("footer").outerHeight(true));
			var header_height = parseInt($("header").outerHeight(true));
			// var ch = (front_end_height - (header_height + footer_height + 23));
			//console.log("wH: "+front_end_height+" HH : "+header_height+" FH: "+footer_height+"CH :"+ch);
			// $("#content").attr("style", "height:" + ch + "px;");


		});

		function refreshVendorGrid() {
			$('#vendorListGrid').yiiGridView('update');
		}

		function confirmDelete() {
			if (confirm("Do you really want to delete this driver?")) {
				return true;
			} else {
				return false;
			}
		}
		function edit(obj) {
			var $drvid = $(obj).attr('drv_id');
			var href2 = '<?= Yii::app()->createUrl("admin/driver/add"); ?>';
			$.ajax({
				"url": href2,
				"type": "GET",
				"dataType": "json",
				"data": {"drvid": $drvid},
				"success": function (data) {
					alert(data);
				}
			});
		}

		function viewContactVendor(obj) {
			var href2 = $(obj).attr("href");
			$.ajax({
				"url": href2,
				"type": "GET",
				"dataType": "html",
				"success": function (data) {
					var box = bootbox.dialog({
						message: data,
						title: 'Vendor Contact',
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

		function viewDetail(obj) {
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




		function showimage(url) {
			bootbox.alert("<img src='/uploadedFiles/" + url + "' width='100%'>", function () {
				console.log("It was awesome!");
			});
		}
		function showimage1(url) {
			bootbox.alert("<img src='/uploadedFiles/" + url + "' width='100%'>", function () {
				console.log("It was awesome!");
			});
		}
		function showimage2(url) {
			bootbox.alert("<img src='/uploadedFiles/" + url + "' width='100%'>", function () {
				console.log("It was awesome!");
			});
		}
		function BroadcastMessage() {
			// var keys = $('#vendorListGrid').yiiGridView('getSelection');
			var keys = $('input[type="checkbox"][name="vnd_checked\\[\\]"]:checked').map(function () {
				return this.value;
			}).get();

			var numrows = keys.length;
			var messageText = document.getElementById("messageText").value;
			var sms = document.getElementById("chk_sms").checked;
			var email = document.getElementById("chk_email").checked;
			var app = document.getElementById("chk_app").checked;

			function smscheck() {
				if (sms == true)
					return "<b>SMS</b><br>";
				else
					return "";
			}
			function emailcheck() {
				if (email == true)
					return "<b>E-Mail</b><br>";
				else
					return "";
			}
			function appcheck() {
				if (app == true)
					return "<b>Push Notification on App</b><br>";
				else
					return"";
			}

			if (keys == '') {
				bootbox.alert("Please select atleast one Vendor.");
			} else {

				bootbox.confirm("Do you want to send the message:<b><br> " + messageText
						+ "</b><br>Using the following methods: " + "<br>" + smscheck() + emailcheck() + appcheck() + "To <b>" + numrows + "</b>  selected vendors."
						, function (confirmed) {
							if (confirmed) {
								window.location.href = '<?php echo Yii::app()->createUrl('admin/vendor/BroadcastMessage'); ?>?vnd_id=' + keys.join() + '&&message=' + messageText + '&&sms=' + sms + '&&email=' + email + '&&app=' + app;
							}
						});
			}
		}

	</script>