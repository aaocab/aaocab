<style>
	.widget-menu .navbar{
		position: relative!important;
		background: none!important;
		z-index: 1!important;
		padding-bottom: 10px;
	}
	.widget-menu .nav>li>a{
		margin: 0 5px;
	}
	.widget-menu .nav>li>a:hover{
		color: #000!important;
	}
</style>
<?php
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];

$time = Filter::getExecutionTime();

echo "<!--" . $time * 1000 . "-->";
$source = Yii::app()->request->getParam('source');
if ($source != '')
{
	$sourceName		 = '[' . Admins::model()->getSourceById($source) . ']';
	$this->pageTitle = 'Booking History ' . $sourceName;
}
else
{
	$this->pageTitle = 'Booking History';
}

Logger::profile('Render View Start');

//$api				 = Yii::app()->params['googleBrowserApiKey'];
$api				 = Config::getGoogleApiKey('browserapikey');
Yii::app()->clientScript->registerScriptFile('https://maps.googleapis.com/maps/api/js?key=' . $api . '&libraries=places&', CClientScript::POS_HEAD);
$autoAddressJSVer	 = Yii::app()->params['autoAddressJSVer'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/autoAddress.js?v=$autoAddressJSVer");

$pageno			 = Yii::app()->request->getParam('page');
//$cityList = CHtml::listData(Cities::model()->findAll('cty_active = :act', array(':act' => '1')), 'cty_id', 'cty_name');
$bookingStatus	 = Booking::model()->getActiveBookingStatus();
//$datacity = Cities::model()->getCityByFromBooking();
$datazone		 = Zones::model()->getZoneArrByFromBooking();
//$fromCityArr	 = Cities::model()->getCityArrByFromBooking();
//$toCityArr = Cities::model()->getCityArrByToBooking();
$flagSource		 = Booking::model()->getFlagSouce();
$flagInfo		 = VehicleTypes::model()->getJSON($flagSource);
$time			 = Filter::getExecutionTime();

$GLOBALS['time'][5]		 = $time;
$tab					 = ($tab == "") ? "2" : $tab;
${'tabactive' . $tab}	 = 'active ';
?>
<div class="row <?= $formHide ?>">
	<div class="col-xs-12 widget-menu">
		<?php
		$form					 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
			'action' => Yii::app()->createUrl('admin/booking/list'),
		));
		/* @var $form TbActiveForm */
		?>
		<nav class="navbar mb20">
			<div class="container-fluid">
				<ul class="nav navbar-nav pull-right">
					<li class="active"><a class="btn btn-success  btn-sm" style="background-color: blue;" id="delegatedAssignmentId" href="<?= Yii::app()->createUrl('admpnl/booking/list?source=224') ?>" title="Delegated assignment"><span style="font-size: 24px; font-weight: bold;"> <?= $assignment['delegation']; ?></span> in Delegated to Operation manager</a></li>
					<li><a class="btn btn-danger  btn-sm "  id="DemSupMisfire" href="<?= Yii::app()->createUrl('admpnl/booking/list?source=228&tab=2') ?>" title="Demand Supply Misfired"><span style="font-size: 24px; font-weight: bold;"> <?= $demsupmisfireCount; ?> </span> At Risk Bookings</a></li>
					<li><a class="btn btn-success  btn-sm" style="background-color: orange;" id="manualAssignmentId" href="<?= Yii::app()->createUrl('admpnl/booking/list?source=225') ?>" title="Manual assignment"><span style="font-size: 24px; font-weight: bold;"> <?= $assignment['manual']; ?></span> in Manual assignment</a></li>
					<li><a class="btn btn-success  btn-sm" style="background-color: red;" id="criticalAssignmentId" href="<?= Yii::app()->createUrl('admpnl/booking/list?source=226') ?>" title="Critical assignment"><span style="font-size: 24px; font-weight: bold;"> <?= $assignment['critical']; ?> </span> in Critical assignment</a></li>
					<!--					<li class="dropdown">
											<a href="#" class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" id="bkg_assignment" title="Auto Assign Pickup">Auto-allocate<span class="caret"></span></a>
											<ul class="dropdown-menu">
												<li><a href="javascript:void(0)" onclick="autoAssignment(0)">ANY</a></li>
												<li><a href="javascript:void(0)" onclick="autoAssignment(1)">North</a></li>
												<li><a href="javascript:void(0)" onclick="autoAssignment(2)">West</a></li>
												<li><a href="javascript:void(0)" onclick="autoAssignment(3)">Central</a></li>
												<li><a href="javascript:void(0)" onclick="autoAssignment(4)">South</a></li>
												<li><a href="javascript:void(0)" onclick="autoAssignment(5)">East</a></li>
												<li><a href="javascript:void(0)" onclick="autoAssignment(6)">North East</a></li>
											</ul>
										</li>-->
										<!--<li><a href="<? //= Yii::app()->createUrl("admin/booking/AutoAssignUnverifiedFollowup")   ?>" onclick="return autoAssign(this);" class="btn btn-sm btn-animate-side btn-info">Auto Assign<br>Payment Followup</a></li> -->
				</ul>
			</div>
		</nav>


        <div class="row">
            <div class="col-xs-12 col-lg-12">
                <div class="row">
					<div class="col-xs-12 col-sm-2 col-md-2">
						<?= $form->textFieldGroup($model, 'trip_id', array('label' => 'Booking Id/ Trip Id/ Agent Bkg Id', 'htmlOptions' => array('placeholder' => 'Search By Booking Id/Trip Id/Agent Bkg Id'))) ?>
                    </div>
					<div class="col-xs-12 col-sm-2 col-md-2">
						<?= $form->textFieldGroup($model, 'bkg_user_email1', array('label' => 'Email', 'htmlOptions' => array('placeholder' => 'search by email'))) ?>
                    </div>
					<div class="col-xs-12 col-sm-2 col-md-2">
						<?= $form->textFieldGroup($model, 'bkg_contact_no1', array('label' => 'Phone', 'htmlOptions' => array('placeholder' => 'search by phone'))) ?>
                    </div>
					<div class="col-xs-12 col-sm-2 col-md-2">
						<?= $form->textFieldGroup($model, 'bkg_name', array('label' => 'Name', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Name', 'class' => 'nameFilterMask')))) ?>
                    </div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<?= $form->textFieldGroup($model, 'search', array('label' => 'Others(Pickup/Drop Address,Instruction to Driver/Vendor)', 'htmlOptions' => array('placeholder' => 'search by  other information'))) ?>
                    </div>
                </div>
			</div>

			<div class="col-xs-12 col-lg-12">
                <div class="row">
					<div class="col-xs-12 col-sm-4 col-md-4" style="">
                        <div class="form-group">
							<?php
							$daterang				 = "Select Booking Date Range";
							$createdate1			 = ($model->bkg_create_date1 == '') ? '' : $model->bkg_create_date1;
							$createdate2			 = ($model->bkg_create_date2 == '') ? '' : $model->bkg_create_date2;
							if ($createdate1 != '' && $createdate2 != '')
							{
								$daterang = date('F d, Y', strtotime($createdate1)) . " - " . date('F d, Y', strtotime($createdate2));
							}
							?>
                            <label  class="control-label">Booking Date</label>
                            <div id="bkgCreateDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?php
							echo $form->hiddenField($model, 'bkg_create_date1');
							echo $form->hiddenField($model, 'bkg_create_date2');
							?>
                        </div>
                    </div>

					<div class="col-xs-12 col-sm-4 col-md-4" style="">
                        <div class="form-group">
                            <label class="control-label">Pickup Date</label>
							<?php
							$daterang			 = "Select Pickup Date Range";
							$bkg_pickup_date1	 = ($model->bkg_pickup_date1 == '') ? '' : $model->bkg_pickup_date1;
							$bkg_pickup_date2	 = ($model->bkg_pickup_date2 == '') ? '' : $model->bkg_pickup_date2;
							if ($bkg_pickup_date1 != '' && $bkg_pickup_date2 != '')
							{
								$daterang = date('F d, Y', strtotime($bkg_pickup_date1)) . " - " . date('F d, Y', strtotime($bkg_pickup_date2));
							}
							?>
                            <div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
                                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                                <span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
                            </div>
							<?= $form->hiddenField($model, 'bkg_pickup_date1'); ?>
							<?= $form->hiddenField($model, 'bkg_pickup_date2'); ?>

                        </div>
					</div>

					<div class="col-xs-12 col-sm-4 col-md-4" >
						<div class="form-group">
							<label class="control-label">Region </label>
							<?php
							$regionList			 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_region',
								'val'			 => $model->bkg_region,
								//'asDropDownList' => FALSE,
								'data'			 => Vendors::model()->getRegionList(),
								//'options' => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
								'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
									'style'			 => 'width: 100%', 'placeholder'	 => 'Select Region')
							));
							?>
						</div></div>

                </div>
			</div>

			<div class="col-xs-12 col-lg-12">
                <div class="row">
					<div class="col-xs-12 col-sm-4 col-md-4 form-group ">
						<div class="form-group">
							<label class="control-label">Vendor</label>
							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'bcb_vendor_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Vendor",
								'fullWidth'			 => false,
								'options'			 => array('allowClear' => true),
								'htmlOptions'		 => array('width' => '100%',
								//  'id' => 'from_city_id1'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                  populateVendor(this, '{$model->bcb_vendor_id}');
                                                }",
							'load'			 => "js:function(query, callback){
                        loadVendor(query, callback);
                        }",
							'render'		 => "js:{
                            option: function(item, escape){
                            return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                            },
                            option_create: function(data, escape){
                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                            }
                        }", 'allowClear'	 => true
								),
							));
							?></div>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4 form-group ">
						<div class="form-group">
							<label class="control-label">Driver </label>
							<?php
							$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
								'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
								'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
								'openOnFocus'		 => true, 'preload'			 => false,
								'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
								'addPrecedence'		 => false,];
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'bcb_driver_id',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Driver",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%'),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
																  populateDriver(this, '{$model->bcb_driver_id}');
											}",
							'load'			 => "js:function(query, callback){
																loadDriver(query, callback);
											}",
							'render'		 => "js:{
																	option: function(item, escape){
																		return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
																	},
																	option_create: function(data, escape){
																		return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
																	}
																}",
								),
							));
							?> </div>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<div class="form-group">
							<label class="control-label">Channel Partner</label>
							<?php
							$dataagents			 = Agents::model()->getAgentsFromBooking();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_agent_id',
								'val'			 => $model->bkg_agent_id,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataagents), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Partner name')
							));
							?>
						</div> 
					</div>


                </div>
			</div>

			<div class="col-xs-12 col-lg-12">
                <div class="row">
					<div class="col-xs-12 col-sm-2 col-md-2" >
						<div class="form-group">
							<label class="control-label">State </label>
							<?php
							//$regionList			 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_state',
								'val'			 => $model->bkg_state,
								//'asDropDownList' => FALSE,
								'data'			 => States::model()->getStateList1(),
								//'options' => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
								'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
									'style'			 => 'width: 100%', 'placeholder'	 => 'Select State')
							));
							?>
						</div></div>
					<div class="col-xs-12 col-sm-2 col-md-2">
						<div class="form-group">
							<label class="control-label">From City</label>


							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'fromcity',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Source City",
								'fullWidth'			 => false,
								'options'			 => array('allowClear' => true),
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'Booking_fromcity1'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$model->fromcity}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadSource(query, callback);
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
							<span class="has-error"><? echo $form->error($model, 'fromcity'); ?></span>

						</div>
					</div>
					<div class="col-xs-12 col-sm-2 col-md-2">
						<div class="form-group">
							<label class="control-label">To City</label>


							<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'tocity',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select Destination City",
								'fullWidth'			 => false,
								'options'			 => array('allowClear' => true),
								'htmlOptions'		 => array('width'	 => '100%',
									'id'	 => 'Booking_tocity1'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$model->tocity}');
                                                }",
							'load'			 => "js:function(query, callback){
                                            loadSource(query, callback);
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
							<span class="has-error"><? echo $form->error($model, 'tocity'); ?></span>
						</div> 
					</div>

					<div class="col-xs-12 col-sm-2 col-md-2">
						<div class="form-group">
							<label>Source Zone</label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'sourcezone',
								'val'			 => $model->sourcezone,
								'data'			 => $datazone,
								//'asDropDownList' => FALSE,
								//'options' => array('data' => new CJavaScriptExpression($datazone), 'allowClear' => true,),
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Source Zone')
							));
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-2 col-md-2">
						<div class="form-group">
							<label class="control-label">Destination Zone</label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'destinationzone',
								'val'			 => $model->destinationzone,
								'data'			 => $datazone,
								//  'asDropDownList' => FALSE,
								// 'options' => array('data' => new CJavaScriptExpression($datazone), 'allowClear' => true,),
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Destination Zone')
							));
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-2 col-md-2">
						<div class="form-group">
							<label class="control-label">Source</label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_flag_source',
								'val'			 => $model->bkg_flag_source,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($flagInfo), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Source')
							));
							?></div>
					</div>

                </div>
			</div>

			<div class="col-xs-12 col-lg-12">
                <div class="row">
					<div class="col-xs-12 col-sm-2 col-md-2">
						<div class="form-group">
							<label class="control-label">Service Tier</label>
							<?php
							$returnType			 = "filter";
							$serviceClassList	 = ServiceClass::model()->getList($returnType);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_service_class',
								'val'			 => $model->bkg_service_class,
								'data'			 => $serviceClassList,
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Select Service Class')
							));
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-2 col-md-2">
						<div class="form-group">
							<label class="control-label">Cab Type</label>
							<?php
							$returnType			 = "listCategory";
							$vehicleList		 = SvcClassVhcCat::getVctSvcList($returnType);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkg_vehicle_type_id',
								'val'			 => $model->bkg_vehicle_type_id,
								'data'			 => $vehicleList,
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => 'multiple',
									'placeholder'	 => 'Select Car Type')
							));
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-2 col-md-2">
						<div class="form-group">
							<label class="control-label">Booking Type</label>
							<?php
							$bookingTypesArr	 = $model->booking_type;
							unset($bookingTypesArr[2]);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'bkgtypes',
								'val'			 => $model->bkgtypes,
								'data'			 => $bookingTypesArr,
								//'asDropDownList' => FALSE,
								//'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true,),
								'htmlOptions'	 => array('style'			 => 'width:100%', 
									'multiple'		 => 'multiple',
									'placeholder'	 => 'Booking Type')
							));
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-2 col-md-2">
						<div class="form-group">
							<label class="control-label">Tags</label>
							<?php
							$SubgroupArray2		 = Tags::getListByType(Tags::TYPE_BOOKING);
							$this->widget('booster.widgets.TbSelect2', array(
								//'name'			 => 'bkg_tags',
								'attribute'		 => 'search_tags',
								'model'			 => $model,
								'val'			 => $model->search_tags,
								'data'			 => $SubgroupArray2, 
								'htmlOptions'	 => array(
									'multiple'		 => 'multiple',
									'placeholder'	 => 'Add tags keywords ',
									'style'			 => 'width:100%'
								),
							));
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-2 col-md-2">
						<div class="form-group">
							<label class="control-label">User Category</label>
							<?php
							$arrUserCat		 = UserCategoryMaster::catDropdownList();
							$this->widget('booster.widgets.TbSelect2', array(
								'attribute'		 => 'userCategories',
								'model'			 => $model,
								'val'			 => $model->userCategories,
								'data'			 => $arrUserCat, 
								'htmlOptions'	 => array(
									'multiple'		 => 'multiple',
									'placeholder'	 => 'User Categories',
									'style'			 => 'width:100%'
								),
							));
							?>
						</div>
					</div>
                </div>
			</div>
			
			<div class="col-xs-12 col-lg-12">
                <div class="row">
					<div class="col-xs-12 col-sm-2 col-md-2">
						<?= $form->checkboxListGroup($model, 'b2cbookings', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'B2C bookings only'), 'htmlOptions' => []))) ?>
                    </div>
					<div class="col-xs-12 col-sm-4 col-md-2">
						<?= $form->checkboxListGroup($model, 'b2b0bookings', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Non MMT Partners only'), 'htmlOptions' => []))) ?>
                    </div>
					<div class="col-xs-12 col-sm-2 col-md-2">
						<?= $form->checkboxListGroup($model, 'bkg_is_corporate', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'MMT corporate booking'), 'htmlOptions' => []))) ?>
                    </div>
					<div class="col-xs-12 col-sm-4 col-md-4">
						<?= $form->checkboxListGroup($model, 'incB2Btfrbookings', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Include MMT TFR booking'), 'htmlOptions' => []))) ?>
                    </div>
				</div>
			</div>

            <div class="col-xs-12 text-center pb10">  
                <button class="btn btn-primary mt5" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>
			</div>

		</div>


		<?php $this->endWidget(); ?>
    </div>
</div>					
<?php
Logger::profile('Render Form End');
$time				 = Filter::getExecutionTime();

$GLOBALS['time'][6]	 = $time;
$GLOBALS['time'][7]	 = [];
?>
<div class="row">
    <div class="col-xs-12" id="bkgList">
        <ul class="nav nav-tabs <?= $formHide ?>" id="myTab">
			<?php
			$i					 = 0;
//  $bgcolor = ['warning', 'success', 'info', 'danger'];
			$bgcolor			 = 'default';

			if (!empty($dataProvider[$tab]['data']))
			{
				$params = $dataProvider[$tab]['data']->getPagination()->params;
			}
			foreach ($leadStatus as $bid => $bval)
			{
				$label = '';
				unset($params['Booking_page']);

				$params['tab']	 = $bid;
				$tabUrl			 = "data-url=\"" . Yii::app()->createUrl('admin/booking/list', $params) . '"';
				?>
				<li class='<?= ${"tabactive" . $bid} ?> '><a data-toggle="tabajax"  <?= $tabUrl ?> class="bg-white" href="#sec<?= $bid ?>"><?= $bval ?> <span id="bkgCount<?= $bid ?>" class="font-bold" style="font-size: 1.2em">(<?= $statusCount[$bid] | 0 ?>)</span></a></li>
				<?php
				$i				 = ($i == 3) ? 0 : $i + 1;

				$time = Filter::getExecutionTime();

				$GLOBALS['time'][7][$bid] = $time;
			}
			$GLOBALS['time'][8] = [];
			?>
		</ul>
		<div class="tab-content p0">
<?php
foreach ($leadStatus as $bid => $bval)
{
	$tabUrl = "";
	?>
				<div id="<?= 'sec' . $bid ?>" tabid="<?= $bid ?>" class="tab-pane <?= ${'tabactive' . $bid} ?>">
				<?php
				if (isset($dataProvider[$bid]))
				{
					$this->renderPartial("grid", ['status' => $bid, 'provider' => $dataProvider[$bid]]);
				}
				$time = Filter::getExecutionTime();

				$GLOBALS['time'][8][$bid]	 = $time;
				?>
				</div>
					<?
					$time						 = Filter::getExecutionTime();
					echo "<!-- time: " . $time / 1000 . "-->";
				}

				Logger::profile('Render List Done');
				?>
		</div>
	</div>
</div>
<?php ?>


<script>
	$(document).ready(function ()
	{
		$('#myTab a[data-toggle="tabajax"]').click(function (e)
		{
			e.preventDefault();

			var url = $(this).attr("data-url");
			var href = this.hash;
			var pane = $(this);
			if ($tabCache.indexOf($(href).attr('id')) > -1)
			{
				pane.tab('show');
				return;
			}
			// ajax load from data-url
			$(href).load(url, function (result)
			{
				pane.tab('show');
				addTabCache($(this).attr('tabid'));
			});
		});

		var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
		var end = '<?= date('d/m/Y'); ?>';
//var $select = $("#Booking_fromcity").selectize();
//var selectize = $select[0].selectize;
//var yourDefaultIds = [30711];
//selectize.setValue(yourDefaultIds);




		$('#bkgCreateDate').daterangepicker(
				{
					locale: {
						format: 'DD/MM/YYYY',
						cancelLabel: 'Clear'
					},
					"showDropdowns": true,
					"alwaysShowCalendars": true,
					startDate: start,
					endDate: end,
					ranges: {
						'Today': [moment(), moment()],
						'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Last 7 Days': [moment().subtract(6, 'days'), moment()],
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
					}
				}, function (start1, end1) {
			$('#Booking_bkg_create_date1').val(start1.format('YYYY-MM-DD'));
			$('#Booking_bkg_create_date2').val(end1.format('YYYY-MM-DD'));
			$('#bkgCreateDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#bkgCreateDate').on('cancel.daterangepicker', function (ev, picker) {
			$('#bkgCreateDate span').html('Select Booking Date Range');
			$('#Booking_bkg_create_date1').val('');
			$('#Booking_bkg_create_date2').val('');
		});


		$('#bkgPickupDate').daterangepicker(
				{
					locale: {
						format: 'DD/MM/YYYY',
						cancelLabel: 'Clear'
					},
					"showDropdowns": true,
					"alwaysShowCalendars": true,
					startDate: start,
					endDate: end,
					ranges: {
						'Today': [moment(), moment()],
						'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
						'Next 7 Days': [moment(), moment().add(6, 'days')],
						'Next 15 Days': [moment(), moment().add(15, 'days')],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
					}
				}, function (start1, end1) {
			$('#Booking_bkg_pickup_date1').val(start1.format('YYYY-MM-DD'));
			$('#Booking_bkg_pickup_date2').val(end1.format('YYYY-MM-DD'));
			$('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
		});
		$('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker) {
			$('#bkgPickupDate span').html('Select Pickup Date Range');
			$('#Booking_bkg_pickup_date1').val('');
			$('#Booking_bkg_pickup_date2').val('');
		});

	});

	var checkCounter = 0;
	var checked = [];
	function setMarkComplete() {
		checked = [];
		$('#bookingTab5 input[name="booking_id5[]"]').each(function (i) {
			if (this.checked) {
				checked.push(this.value);
			}
		});
		if (checked.length == 0) {
			bootbox.alert("Please select a booking for mark complete.");
			return false;
		}
		if (checked.length > 0) {
			var j = 0;
			var checked1 = [];
			while (j < 10 && checkCounter < checked.length) {
				checked1.push(checked[checkCounter]);
				j++;
				checkCounter++;
			}
			markCompleteAjax(checked1);
		}

	}


	function markCompleteAjax(checkedIds) {
		ajaxindicatorstart("Processing " + checkCounter.toString() + " of " + checked.length.toString() + "");
		var href = '<?= Yii::app()->createUrl("admin/booking/setcompletebooking"); ?>';
		$.ajax({
			'type': 'GET',
			'url': href,
			'dataType': 'json',
			global: false,
			data: {"bkIds": checkedIds.toString()},
			success: function (data) {
				if (data.success) {
					if (checkCounter >= checked.length)
					{
						ajaxindicatorstop();
						checkCounter = 0;
						updateGrid(5);
						removeTabCache(6);
					} else
					{
						setMarkComplete();
					}
				} else {
					ajaxindicatorstop();
					checkCounter = 0;
					alert("Sorry error occured");
				}
			},
			error: function (xhr, status, error) {
				ajaxindicatorstop();
				checkCounter = 0;
				alert(xhr.error);
			}
		});

	}

	function autoAssignment(zoneId)
	{
		$href = "<?= Yii::app()->createUrl('admin/booking/autoAssignment') ?>";
		jQuery.ajax({type: 'GET',
			url: $href,
			'data': {'zoneId': zoneId},
			'dataType': 'json',
			success: function (data)
			{
				if (data.success)
				{
					bootbox.alert(data.BookingName + " is assigned for your vendor assignment."),
							location.href = data.url;

				}

			}
		});
	}


	function autoAssign(obj)
	{
		$href = $(obj).attr("href");
		$.ajax({
			url: $href,
			type: 'GET',
			"dataType": "json",
			success: function (result) {
				window.location = result.url;
			},
			error: function (xhr, status, error) {
				alert('Sorry error occured');
			}
		});
		return false;
	}



	function populateSource(obj, cityId)
	{

		obj.load(function (callback)
		{
			var obj = this;
			if ($sourceList == null)
			{
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
					dataType: 'json',
					success: function (results)
					{
						$sourceList = results;
						obj.enable();
						callback($sourceList);
						obj.setValue(cityId);
					},
					error: function ()
					{
						callback();
					}
				});
			} else
			{
				obj.enable();
				callback($sourceList);
				obj.setValue(cityId);
			}
		});
	}
	function loadSource(query, callback)
	{
		//	if (!query.length) return callback();
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
			global: false,
			error: function ()
			{
				callback();
			},
			success: function (res)
			{
				callback(res);
			}
		});
	}
	function populateDriver(obj, drvId) {
		obj.load(function (callback) {
			var obj = this;
			if ($sourceList == null) {
				xhr = $.ajax({
					url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/alldriverbyquery', ['onlyActive' => 0, 'drv' => ''])) ?>' + drvId,
					dataType: 'json',
					data: {},
					success: function (results) {
						$sourceList = results;
						obj.enable();
						callback($sourceList);
						obj.setValue(drvId);
					},
					error: function () {
						callback();
					}
				});
			} else {
				obj.enable();
				callback($sourceList);
				obj.setValue(drvId);
			}
		});
	}
	function loadDriver(query, callback) {
		$.ajax({
			url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/alldriverbyquery')) ?>?onlyActive=0&q=' + encodeURIComponent(query),
			type: 'GET',
			dataType: 'json',
			global: false,
			error: function () {
				callback();
			},
			success: function (res) {
				callback(res);
			}
		});
	}



</script>
<?php
$version = Yii::app()->params['customJsVersion'] . '1';
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);

Yii::app()->getClientScript()->registerScript("refreshCGrid", "refreshGrid = function(){

$.fn.yiiGridView.update('bookingTab6');
$('#bookingTab5').yiiGridView('update');
};
");
$time = Filter::getExecutionTime();

$GLOBALS['time'][9] = $time;
?>