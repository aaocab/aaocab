<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="panel panel-white"><div class="panel-body">
        <div class="row"> 

            <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-4 col-md-2 text-center mt20 p5">   
                <input class="btn btn-primary full-width" name="yt0" id="CreateEvent" type="button" value="Create Calendar Event">
            </div> 

            <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-4 col-md-2 text-center mt20 p5"> 
                <a  class="btn btn-primary full-width" href="/aaohome/CalendarEvent/MapYearEventDate" target="_blank">View event Calendar</a> 
            </div>
			<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-4 col-md-2 text-center mt20 p5"> 
                <a  class="btn btn-primary full-width" href="/aaohome/CalendarEvent/90DCalendar" target="_blank">View 90 Day Calendar</a> 
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12 col-md-12 col-lg-6 new-booking-list">

                <div class="col-xs-12 col-sm-6 col-md-6" >
                    <div class="form-group">
                        <label class="control-label">Event:</label>
						<?php
						$this->widget('booster.widgets.TbSelect2', array(
							'model'			 => $models,
							'attribute'		 => 'hde_id',
							'val'			 => $models->hde_id,
							'data'			 => HolidayEvents::getHoliday($type			 = 1),
							'htmlOptions'	 => array('class'			 => 'p0',
								'style'			 => 'width: 100%', 'placeholder'	 => 'Event name')
						));
						?>
                    </div></div>

            </div>
            <div class="col-xs-12 col-md-12 col-lg-6 new-booking-list">
				<?php
				$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'agent-type-form', 'enableClientValidation' => FALSE,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error'
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class'			 => 'form-horizontal', 'enctype'		 => 'multipart/form-data', 'autocomplete'	 => "off",
					),
				));
				/* @var $form TbActiveForm */

				if ($models->allregion == null)
				{
					$models->allregion = 0;
				}
				?>
                <div class="row">
					<?= $form->radioButtonListGroup($models, 'allregion', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => [0 => "Effect All Regions", -1 => "Effect No Regions", 1 => "Effect Only Selection"]), 'inline' => true)) ?>
                </div>


                <div class="row hideAll"  style="<?php echo $models->allregion == 0 ? 'display:none' : '' ?>">
                    <div class="col-xs-12 col-sm-6 col-md-6" >
                        <div class="form-group">
                            <label class="control-label">Region:</label>
							<?php
							$regionList	 = VehicleTypes::model()->getJSON(Vendors::model()->getRegionList());
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $models,
								'attribute'		 => 'region',
								'val'			 => $models->region,
								'data'			 => Vendors::model()->getRegionList(),
								'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
									'style'			 => 'width: 100%', 'placeholder'	 => 'Select Region')
							));
							?>
                        </div>
                    </div>
                </div>
                <div class="row" >
                    <div class="col-xs-12 col-sm-6 col-md-6" >

                        <div class="row hideAll" style="<?php echo $models->allregion == 0 ? 'display:none' : '' ?>">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>Source MZone:</label>
									<?php
									$dataMzone	 = Zones::model()->getMZoneArr();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $models,
										'attribute'		 => 'source_mzone',
										'val'			 => $model->source_mzone,
										'data'			 => $dataMzone,
										'htmlOptions'	 => array('style' => 'width:100%', 'multiple' => 'multiple', 'placeholder' => 'MZone')
									));
									?>
                                </div>
                            </div>
                        </div>


                        <div class="row hideAll" style="<?php echo $models->allregion == 0 ? 'display:none' : '' ?>">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>Source Zone:</label>
									<?php
									$datazone	 = Zones::model()->getZoneArrByFromBooking();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $models,
										'attribute'		 => 'source_zone',
										'val'			 => $model->source_zone,
										'data'			 => $datazone,
										'htmlOptions'	 => array('style' => 'width:100%', 'multiple' => 'multiple', 'placeholder' => 'Zone')
									));
									?>
                                </div>
                            </div>
                        </div>
                        <div class="row hideAll" style="<?php echo $models->allregion == 0 ? 'display:none' : '' ?>">
                            <div class="col-xs-12 col-sm-6 col-md-6" >
                                <div class="form-group">
                                    <label class="control-label">Source State </label>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $models,
										'attribute'		 => 'source_state',
										'val'			 => $model->source_state,
										//'asDropDownList' => FALSE,
										'data'			 => States::model()->getStateList1(),
										//'options' => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
										'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
											'style'			 => 'width: 100%', 'placeholder'	 => 'Select State')
									));
									?>
                                </div>
                            </div>
                        </div>
                        <div class="row hideAll" style="<?php echo $models->allregion == 0 ? 'display:none' : '' ?>">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Source City</label>
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $models,
										'attribute'			 => 'source_city',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "City",
										'fullWidth'			 => false,
										'options'			 => array('allowClear' => true),
										'htmlOptions'		 => array('width'		 => '100%', 'multiple'	 => 'multiple', 'id'		 => 'source_city'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$models->source_city}');
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


                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6" >
                        <div class="row hideAll" style="<?php echo $models->allregion == 0 ? 'display:none' : '' ?>">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>Destination MZone:</label>
									<?php
									$dataMzone	 = Zones::model()->getMZoneArr();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $models,
										'attribute'		 => 'destination_mzone',
										'val'			 => $model->destination_mzone,
										'data'			 => $dataMzone,
										'htmlOptions'	 => array('style' => 'width:100%', 'multiple' => 'multiple', 'placeholder' => 'MZone')
									));
									?>
                                </div>
                            </div>
                        </div>
                        <div class="row hideAll" style="<?php echo $models->allregion == 0 ? 'display:none' : '' ?>">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label>Destination Zone:</label>
									<?php
									$datazone	 = Zones::model()->getZoneArrByFromBooking();
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $models,
										'attribute'		 => 'destination_zone',
										'val'			 => $model->destination_zone,
										'data'			 => $datazone,
										'htmlOptions'	 => array('style' => 'width:100%', 'multiple' => 'multiple', 'placeholder' => 'Zone')
									));
									?>
                                </div>
                            </div>
                        </div>
                        <div class="row hideAll" style="<?php echo $models->allregion == 0 ? 'display:none' : '' ?>">
                            <div class="col-xs-12 col-sm-6 col-md-6" >
                                <div class="form-group">
                                    <label class="control-label">Destination State:</label>
									<?php
									$this->widget('booster.widgets.TbSelect2', array(
										'model'			 => $models,
										'attribute'		 => 'destination_state',
										'val'			 => $model->destination_state,
										//'asDropDownList' => FALSE,
										'data'			 => States::model()->getStateList1(),
										//'options' => array('data' => new CJavaScriptExpression($regionList), 'allowClear' => true),
										'htmlOptions'	 => array('class'			 => 'p0', 'multiple'		 => 'multiple',
											'style'			 => 'width: 100%', 'placeholder'	 => 'Select State')
									));
									?>
                                </div>
                            </div>
                        </div>
                        <div class="row hideAll" style="<?php echo $models->allregion == 0 ? 'display:none' : '' ?>">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="form-group">
                                    <label class="control-label">Destination City:</label>
									<?php
									$this->widget('ext.yii-selectize.YiiSelectize', array(
										'model'				 => $models,
										'attribute'			 => 'destination_city',
										'useWithBootstrap'	 => true,
										"placeholder"		 => "City",
										'fullWidth'			 => false,
										'options'			 => array('allowClear' => true),
										'htmlOptions'		 => array('width'		 => '100%', 'multiple'	 => 'multiple', 'id'		 => 'destination_city'
										),
										'defaultOptions'	 => $selectizeOptions + array(
									'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$models->destination_city}');
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


                                </div>
                            </div>
                        </div>
						<?php
						echo $form->hiddenField($models, 'hde_recurrs_rule');
						?>
                    </div>
                </div>

				<div class="row" >
					<div class="col-xs-12 col-sm-12 col-md-12">
						<div class="row hideAll" style="<?php echo $models->allregion == 0 ? 'display:none' : '' ?>">
							<div class="col-xs-12 col-sm-12 col-md-12">
								<div class="form-group">
									<label class="control-label recurring_lable">Recurring:No Rule;</label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									<button id="ApplyRecurringRule" type="button" class="btn btn-primary mb0" data-toggle="modal" data-target="#myModal"> Apply Recurring rule</button>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-6 pl20 pr20">  <?php echo $form->textFieldGroup($models, 'margin', array('label' => 'Margin')) ?></div>
				</div>
                <div class="row" >

                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-3 col-sm-3 col-md-3 text-center mt20 p5">   
                        <input class="btn btn-primary full-width" name="yt0" id="Modify" type="button" value="Modify">
                    </div>

                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-3 col-sm-3 col-md-3 text-center mt20 p5">   
                        <input class="btn btn-primary full-width" name="yt0" id="SaveChanges" type="button" value="Save Changes">
                    </div>

                    <div class="col-xs-offset-3 col-sm-offset-0 col-xs-3 col-sm-3 col-md-3 text-center mt20 p5">  
                        <input class="btn btn-primary full-width" name="yt0" id="Cancel" type="button" value="Cancel">
                    </div>

                </div>

				<?php $this->endWidget(); ?>
            </div>
        </div>
    </div>
</div>

</div>



<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Recurring rule</h4>
			</div>
			<div class="modal-body">
				<div>

					<!-- Nav tabs -->
					<ul class="nav nav-tabs" role="tablist">
						<li class="tablist active" tab="Daily" role="presentation"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Daily</a></li>
						<li class="tablist" tab="Weekly" role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Weekly</a></li>
						<li  style="display:none"  class="tablist" tab="Monthly" role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab" >Monthly</a></li>
						<li  style="display:none"  class="tablist" tab="Yearly" role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">Yearly</a></li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div role="tabpanel" class="tab-pane active" id="home">
							<div class="row">
								<div class="col-xs-12">
									<div class="form-inline mb10">
										<label for="repeatdays" class=""><b>Repeat every</b></label>
										<input type="text" class="form-control" name="repeatdays" id="repeatdays" value="1" style="width:70px; border-radius:5px;"> <b>  days</b>
									</div>
									<p>The event will be repeated every day or every x days, depending on the value</p>

								</div>

								<div class="col-xs-12 mt20">
									<p><b>Start condition</b></p>
									<div class="panel panel-default panel-border">
										<div class="panel-body">											
											<div class="mb20">
												<label class="checkbox-inline pl0">													
													start from specific date <input type="text" name="conditionStartDayDate"  value="<?php echo date('m/d/Y'); ?>" id="conditionStartDayDate" class="form-control" style="width: 50%; display: inline-block;"><br>

												</label>
											</div>


										</div>
									</div>
								</div>

								<div class="col-xs-12 mt20">
									<p><b>Stop condition</b></p>
									<div class="panel panel-default panel-border">
										<div class="panel-body">
											<div class="mb20">
												<label>
													<input type="radio" name="conditionDays" id="optionsRadios_day1" value="never" checked>
													Never stop<br>
													<span class="color-gray pl20">The event will repeat indefinitely</span>
												</label>
											</div>
											<div class="mb20">
												<label class="checkbox-inline pl0">
													<input type="radio" name="conditionDays" id="optionsRadios_day2" value="specific">
													Run until a specific date <input type="text" name="conditionDayDate"  value="<?php echo date('m/d/Y'); ?>" id="datepickerDay" class="form-control" style="width: 50%; display: inline-block;"><br>
													<span class="color-gray pl20">The event will run until it reaches a specific date</span>
												</label>
											</div>
											<div class="mb20">
												<label>
													<input type="radio" name="conditionDays" id="optionsRadios_day3" value="number">
													Run until it reaches <input type="text" value="1" name="conditionDaysTxt" id="conditionDaysTxt" class="form-control" style="width: 20%; display: inline-block;"> occurrences<br>
													<span class="color-gray pl20">The event will repeat until it reaches a certain amount of occurrences</span>
												</label>
											</div>

										</div>
									</div>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="profile">
							<div class="row">
								<div class="col-xs-12">
									<div class="form-inline mb10">
										<label for="repeatweek" class=""><b>Repeat every</b></label>
										<input type="text" class="form-control" name="repeatweek" id="repeatweek" value="1" style="width:70px; border-radius:5px;"> <b> weeks</b>
									</div>
									<p class="mb0">The event will be repeated every day or every x days, depending on the value</p>
									<label class="checkbox-inline pl0">
										<input type="checkbox" id="week_0" class="pl0 checkBoxWeek" value="SUN" aria-label="..."> Sun
									</label>
									<label class="checkbox-inline ml0">
										<input type="checkbox" id="week_1" class="checkBoxWeek" value="MON" aria-label="..."> Mon
									</label>
									<label class="checkbox-inline ml0">
										<input type="checkbox" id="week_2" class="checkBoxWeek" value="TUE" aria-label="..."> Tue
									</label>
									<label class="checkbox-inline ml0">
										<input type="checkbox" id="week_3" class="checkBoxWeek" value="WED" aria-label="..."> Wed
									</label>
									<label class="checkbox-inline ml0">
										<input type="checkbox" id="week_4" class="checkBoxWeek" value="THU" aria-label="..."> Thu
									</label>
									<label class="checkbox-inline ml0">
										<input type="checkbox" id="week_5" class="checkBoxWeek" value="FRI" aria-label="..."> Fri
									</label>
									<label class="checkbox-inline ml0">
										<input type="checkbox" id="week_6" class="checkBoxWeek" value="SAT" aria-label="..."> Sat
									</label>

								</div>
								<div class="col-xs-12 mt20">
									<p><b>Start condition</b></p>
									<div class="panel panel-default panel-border">
										<div class="panel-body">											
											<div class="mb20">
												<label class="checkbox-inline pl0">													
													start from specific date <input type="text" name="conditionStartWeekDate"  value="<?php echo date('m/d/Y'); ?>" id="conditionStartWeekDate" class="form-control" style="width: 50%; display: inline-block;"><br>

												</label>
											</div>


										</div>
									</div>
								</div>

								<div class="col-xs-12 mt20">
									<p><b>Stop condition</b></p>
									<div class="panel panel-default panel-border">
										<div class="panel-body">
											<div class="mb20">
												<label>
													<input type="radio" name="conditionWeek" id="optionsRadios_week1" value="never" checked>
													Never stop<br>
													<span class="color-gray pl20">The event will repeat indefinitely</span>
												</label>
											</div>
											<div class="mb20">
												<label class="checkbox-inline pl0">
													<input type="radio" name="conditionWeek" id="optionsRadios_week2" value="specific">
													Run until a specific date <input type="text" name="conditionWeekDate" value="<?php echo date('m/d/Y'); ?>" id="datepickerWeek" class="form-control" style="width: 50%; display: inline-block;"><br>
													<span class="color-gray pl20">The event will run until it reaches a specific date</span>
												</label>
											</div>
											<div class="mb20">
												<label>
													<input type="radio" name="conditionWeek" id="optionsRadios_week3" value="number">
													Run until it reaches <input type="text" value="1" id="conditionWeekTxt" name="conditionWeekTxt" class="form-control" style="width: 20%; display: inline-block;"> occurrences<br>
													<span class="color-gray pl20">The event will repeat until it reaches a certain amount of occurrences</span>
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="messages">
							<div class="row">
								<div class="col-xs-12">
									<div class="form-inline mb10">
										<label for="repeatmonth" class=""><b>Repeat every</b></label>
										<input type="text" name="repeatmonth" id="repeatmonth"  value="1" class="form-control" style="width:70px; border-radius:5px;"> <span class="font-13"><b> months on the</b></span>
									</div>
									<div class="panel panel-default panel-border">
										<div class="panel-body">
											<div class="mb20 row">
												<div class="col-xs-2 inline"><input type="radio" name="Month" id="Month_1" value="Month_1" class="ml10 n" checked></div>
												<div class="col-xs-5 pl10 inline">
													<select class="form-control" name="selectMonth1_1" id="selectMonth1_1">
														<option value="1">First</option>
														<option value="2">Second</option>
														<option value="3">Third</option>
														<option value="4">Fourth</option>
														<option value="5">Fifth</option>
														<option value="6">Sixth</option>
														<option value="7">Seventh</option>
														<option value="8">Eighth</option>
														<option value="9">Ninth</option>
														<option value="10">Tenth</option>
														<option value="11">Eleventh</option>
														<option value="12">Twelfth</option>
														<option value="13">Thirteenth</option>
														<option value="14">Fourteenth</option>
														<option value="15">Fifteenth</option>
														<option value="16">Sixteenth</option>
														<option value="17">Seventeenth</option>
														<option value="18">Eighteenth</option>
														<option value="19">Nineteenth</option>
														<option value="20">Twentieth</option>
														<option value="21">Twenty-first</option>
														<option value="22">Twenty-second</option>
														<option value="23">Twenty-third</option>
														<option value="24">Twenty-fourth</option>
														<option value="25">Twenty-fifth</option>
														<option value="26">Twenty-sixth</option>
														<option value="27">Twenty-seventh</option>
														<option value="28">Twenty-eighth</option>
														<option value="29">Twenty-ninth</option>
														<option value="30">Thirtieth</option>
														<option value="31">Thirty-first</option>

													</select>
												</div>
											</div>
											<div class="mb20 row">
												<div class="col-xs-2 inline"><input type="radio" name="Month" id="Month_2" value="Month_2" class="ml10 n"></div>
												<div class="col-xs-10 pl10 inline">
													<div class="row">
														<div class="col-xs-6">
															<select class="form-control" name="selectMonth2_1" id="selectMonth2_1" >
																<option value="1" selected="">First</option>
																<option value="2">Second</option>
																<option value="3">Third</option>
																<option value="4">Fourth</option>
																<option value="-1">Last</option>
															</select>
														</div>
														<div class="col-xs-6">
															<select class="form-control" name="selectMonth2_2" id="selectMonth2_2">
																<option value="SUN" selected="">Sunday</option>
																<option value="MON">Monday</option>
																<option value="TUE">Tuesday</option>
																<option value="WED">Wednesday</option>
																<option value="THU">Thursday</option>
																<option value="FRI">Friday</option>
																<option value="SAT">Saturday</option>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<p>The event will be repeated every day or every x days, depending on the value</p>

								</div>
								<div class="col-xs-12 mt20">
									<p><b>Start condition</b></p>
									<div class="panel panel-default panel-border">
										<div class="panel-body">											
											<div class="mb20">
												<label class="checkbox-inline pl0">													
													start from specific date <input type="text" name="conditionStartMonthDate"  value="<?php echo date('m/d/Y'); ?>" id="conditionStartMonthDate" class="form-control" style="width: 50%; display: inline-block;"><br>

												</label>
											</div>


										</div>
									</div>
								</div>
								<div class="col-xs-12 mt20">
									<p><b>Stop condition</b></p>
									<div class="panel panel-default panel-border">
										<div class="panel-body">
											<div class="mb20">
												<label>
													<input type="radio" name="conditionMonth" id="optionsRadios_month1" value="never" checked>
													Never stop<br>
													<span class="color-gray pl20">The event will repeat indefinitely</span>
												</label>
											</div>
											<div class="mb20">
												<label class="checkbox-inline pl0">
													<input type="radio" name="conditionMonth" id="optionsRadios_month2" value="specific">
													Run until a specific date <input type="text" name="conditionMonthDate" value="<?php echo date('m/d/Y'); ?>" id="datepickerMonth" class="form-control" style="width: 50%; display: inline-block;"><br>
													<span class="color-gray pl20">The event will run until it reaches a specific date</span>
												</label>
											</div>
											<div class="mb20">
												<label>
													<input type="radio" name="conditionMonth" id="optionsRadios_month3" value="number">
													Run until it reaches <input type="text" value="1" id="conditionMonthTxt" name="conditionMonthTxt" class="form-control" style="width: 20%; display: inline-block;"> occurrences<br>
													<span class="color-gray pl20">The event will repeat until it reaches a certain amount of occurrences</span>
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane" id="settings">
							<div class="row">
								<div class="col-xs-12">
									<div class="form-inline mb10">
										<label for="repeatyear" class=""><b>Repeat every</b></label>
										<input type="text"  name="repeatyear" id="repeatyear" value="1" class="form-control" style="width:70px; border-radius:5px;"> <b> year on the</b>
									</div>
									<div class="panel panel-default panel-border">
										<div class="panel-body">
											<div class="mb20 row">
												<div class="col-xs-2 inline"><input type="radio" name="Year" id="Year_1" value="Year_1" checked></div>
												<div class="col-xs-10 pl10 inline">
													<div class="row">
														<div class="col-xs-5">
															<select class="form-control" name="selectYear1_1" id="selectYear1_1">
																<option value="1">First</option>
																<option value="2">Second</option>
																<option value="3">Third</option>
																<option value="4">Fourth</option>
																<option value="5">Fifth</option>
																<option value="6">Sixth</option>
																<option value="7">Seventh</option>
																<option value="8">Eighth</option>
																<option value="9">Ninth</option>
																<option value="10">Tenth</option>
																<option value="11">Eleventh</option>
																<option value="12">Twelfth</option>
																<option value="13">Thirteenth</option>
																<option value="14">Fourteenth</option>
																<option value="15">Fifteenth</option>
																<option value="16">Sixteenth</option>
																<option value="17">Seventeenth</option>
																<option value="18">Eighteenth</option>
																<option value="19">Nineteenth</option>
																<option value="20">Twentieth</option>
																<option value="21">Twenty-first</option>
																<option value="22">Twenty-second</option>
																<option value="23">Twenty-third</option>
																<option value="24">Twenty-fourth</option>
																<option value="25">Twenty-fifth</option>
																<option value="26">Twenty-sixth</option>
																<option value="27">Twenty-seventh</option>
																<option value="28">Twenty-eighth</option>
																<option value="29">Twenty-ninth</option>
																<option value="30">Thirtieth</option>
																<option value="31">Thirty-first</option>

															</select>
														</div>
														<div class="col-xs-2 p0 pt10 text-center">of</div>
														<div class="col-xs-5">
															<select class="form-control" name="selectYear1_2" id="selectYear1_2">
																<option value="1" selected="">January</option>
																<option value="2">February</option>
																<option value="3">March</option>
																<option value="4">April</option>
																<option value="5">May</option>
																<option value="6">June</option>
																<option value="7">July</option>
																<option value="8">August</option>
																<option value="9">September</option>
																<option value="10">October</option>
																<option value="11">November</option>
																<option value="12">December</option>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="mb20 row">
												<div class="col-xs-2 inline"><input type="radio" name="Year" id="Year_2" value="Year_2"></div>
												<div class="col-xs-10 pl10">
													<div class="row">
														<div class="col-xs-4">
															<select class="form-control" name="selectYear2_1" id="selectYear2_1" >
																<option value="1" selected="">First</option>
																<option value="2">Second</option>
																<option value="3">Third</option>
																<option value="4">Fourth</option>
																<option value="-1">Last</option>
															</select>
														</div>
														<div class="col-xs-3">
															<select class="form-control" name="selectYear2_2" id="selectYear2_2">
																<option value="SUN" selected="">Sunday</option>
																<option value="MON">Monday</option>
																<option value="TUE">Tuesday</option>
																<option value="WED">Wednesday</option>
																<option value="THU">Thursday</option>
																<option value="FRI">Friday</option>
																<option value="SAT">Saturday</option>
															</select>
														</div>
														<div class="col-xs-1 p0 pt10 text-center">
															of
														</div>
														<div class="col-xs-4">
															<select class="form-control" name="selectYear2_3" id="selectYear2_3">
																<option value="1" selected="">January</option>
																<option value="2">February</option>
																<option value="3">March</option>
																<option value="4">April</option>
																<option value="5">May</option>
																<option value="6">June</option>
																<option value="7">July</option>
																<option value="8">August</option>
																<option value="9">September</option>
																<option value="10">October</option>
																<option value="11">November</option>
																<option value="12">December</option>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<p>The event will be repeated every day or every x days, depending on the value</p>

								</div>

								<div class="col-xs-12 mt20">
									<p><b>Start condition</b></p>
									<div class="panel panel-default panel-border">
										<div class="panel-body">											
											<div class="mb20">
												<label class="checkbox-inline pl0">													
													start from specific date <input type="text" name="conditionStartYearDate"  value="<?php echo date('m/d/Y'); ?>" id="conditionStartYearDate" class="form-control" style="width: 50%; display: inline-block;"><br>

												</label>
											</div>


										</div>
									</div>
								</div>

								<div class="col-xs-12 mt20">
									<p><b>Stop condition</b></p>
									<div class="panel panel-default panel-border">
										<div class="panel-body">
											<div class="mb20">
												<label>
													<input type="radio" name="conditionYear" id="optionsRadios_year1" value="option1" checked>
													Never stop<br>
													<span class="color-gray pl20">The event will repeat indefinitely</span>
												</label>
											</div>
											<div class="mb20">
												<label class="checkbox-inline pl0">
													<input type="radio" name="conditionYear" id="optionsRadios_year2" value="specific">
													Run until a specific date <input type="text" name="conditionYearDate" value="<?php echo date('m/d/Y'); ?>" id="datepickerYear" class="form-control" style="width: 50%; display: inline-block;"><br>
													<span class="color-gray pl20">The event will run until it reaches a specific date</span>
												</label>
											</div>
											<div class="mb20">
												<label>
													<input type="radio" name="conditionYear" id="optionsRadios_year3" value="number">
													Run until it reaches <input type="text" name="conditionYearTxt" id="conditionYearTxt" value="1" class="form-control" style="width: 20%; display: inline-block;"> occurrences<br>
													<span class="color-gray pl20">The event will repeat until it reaches a certain amount of occurrences</span>
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button type="button" class="btn btn-primary SaveRule">Save changes</button>
			</div>
		</div>
	</div>
</div>


<script>
    $(function () {
        $("#conditionStartMonthDate,#conditionStartYearDate,#conditionStartWeekDate,#conditionStartDayDate,#datepickerDay,#datepickerWeek,#datepickerMonth,#datepickerYear").datepicker();
    });
    $("#ytHolidayEvents_allregion").val(0);
    $("#HolidayEvents_hde_id").click(function () {
        eventId = $("#HolidayEvents_hde_id").val();
        if (eventId.length > 0)
        {
            $(".hideAll").hide();
            $("#HolidayEvents_region").val("");
            $("#HolidayEvents_region").trigger("change");

            $("#HolidayEvents_source_mzone").val("");
            $("#HolidayEvents_source_mzone").trigger("change");
            $("#HolidayEvents_destination_mzone").val("");
            $("#HolidayEvents_destination_mzone").trigger("change");

            $("#HolidayEvents_source_zone").val("");
            $("#HolidayEvents_source_zone").trigger("change");
            $("#HolidayEvents_destination_zone").val("");
            $("#HolidayEvents_destination_zone").trigger("change");

            $("#HolidayEvents_source_state").val("");
            $("#HolidayEvents_source_state").trigger("change");
            $("#HolidayEvents_destination_state").val("");
            $("#HolidayEvents_destination_state").trigger("change");

            var $select = $("#source_city").selectize();
            var selectize = $select[0].selectize;
            selectize.clear();

            var $selectdestination = $("#destination_city").selectize();
            var selectizedestination = $selectdestination[0].selectize;
            selectizedestination.clear();

            $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/CalendarEvent/eventDetails')) ?>",
                "data": {"eventId": eventId},
                "success": function (data) {
                    if (data.success)
                    {
                        if (data.recurrs_rule != null)
                        {
                            $("#HolidayEvents_hde_recurrs_rule").val(data.recurrs_rule);
                            recurringRule = $.parseJSON(data.recurrs_rule);
                            switch (recurringRule['repeat'])
                            {
                                case "Daily":

                                    txt = 'Recurring ' + recurringRule['repeat'] + ";";
                                    txt += " Repeat every " + recurringRule['interval'] + " day(s);";
                                    txt += " Start date from " + recurringRule['start'] + ";";
                                    if (recurringRule['until'])
                                    {
                                        txt += " Run until " + recurringRule['until'] + ";"
                                    } else if (recurringRule['count'])
                                    {
                                        txt += " Run until it reaches " + recurringRule['count'] + " occurences;"
                                    } else
                                    {
                                        txt += " Never stop;";
                                    }
                                    $(".recurring_lable").text("");
                                    $(".recurring_lable").text(txt);

                                    $('.nav-tabs li.active').removeClass('active');
                                    $('.nav-tabs li').eq(0).addClass('active');

                                    $('#home,#profile,#messages,#settings').removeClass('active');
                                    $('#home').addClass('active');


                                    $("#repeatdays").val(recurringRule['interval']);
                                    $("#conditionStartDayDate").val(recurringRule['start']);
                                    $("input[name='conditionDays']").removeAttr('checked');
                                    $("input[name='conditionDays']").parent().removeClass('checked');
                                    if (recurringRule['until'])
                                    {
                                        $("#optionsRadios_day2").parent().addClass('checked');
                                        $("#optionsRadios_day2").attr('checked', true);
                                        $("#datepickerDay").val(recurringRule['until']);
                                    } else if (recurringRule['count'])
                                    {
                                        $("#optionsRadios_day3").parent().addClass('checked');
                                        $("#optionsRadios_day3").attr('checked', true);
                                        $("#conditionDaysTxt").val(recurringRule['count']);
                                    } else
                                    {
                                        $("#optionsRadios_day1").parent().addClass('checked');
                                        $("#optionsRadios_day1").attr('checked', true);
                                    }
                                    $("#week_0,#week_1,#week_2,#week_3,#week_4,#week_5,#week_6").parent().removeClass('checked');
                                    $("#week_0,#week_1,#week_2,#week_3,#week_4,#week_5,#week_6").prop('checked', false);
                                    break;

                                case "Weekly":
                                    txt = 'Recurring ' + recurringRule['repeat'] + ";";
                                    txt += " Repeat every " + recurringRule['interval'] + " week(s) ";
                                    if (recurringRule['weekDays'])
                                    {
                                        txt += "  on " + recurringRule['weekDays'] + ";"
                                    }
                                    txt += " Start date " + recurringRule['start'] + ";";
                                    if (recurringRule['until'])
                                    {
                                        txt += " Run until: " + recurringRule['until'] + ";"
                                    } else if (recurringRule['count'])
                                    {
                                        txt += " Run until it reaches " + recurringRule['count'] + " occurences;"
                                    } else
                                    {
                                        txt += " Never stop;";
                                    }
                                    $(".recurring_lable").text("");
                                    $(".recurring_lable").text(txt);

                                    $('.nav-tabs li.active').removeClass('active');
                                    $('.nav-tabs li').eq(1).addClass('active');

                                    $('#home,#profile,#messages,#settings').removeClass('active');
                                    $('#profile').addClass('active');

                                    $("#repeatweek").val(recurringRule['interval']);
                                    $("#conditionStartWeekDate").val(recurringRule['start']);
                                    $("input[name='conditionWeek']").removeAttr('checked');
                                    $("input[name='conditionWeek']").parent().removeClass('checked');
                                    if (recurringRule['until'])
                                    {
                                        $("#optionsRadios_week2").parent().addClass('checked');
                                        $("#datepickerWeek").val(recurringRule['until']);
                                        $("#optionsRadios_week2").attr('checked', 'checked');
                                    } else if (recurringRule['count'])
                                    {
                                        $("#optionsRadios_week3").parent().addClass('checked');
                                        $("#optionsRadios_week3").attr('checked', true);
                                        $("#conditionWeekTxt").val(recurringRule['count']);
                                    } else
                                    {
                                        $("#optionsRadios_week1").parent().addClass('checked');
                                        $("#optionsRadios_week1").attr('checked', true);
                                    }

                                    $("input[name='checkBoxWeek']").removeAttr('checked');
                                    $("input[name='checkBoxWeek']").parent().removeClass('checked');
                                    weekDays = recurringRule['weekDays'].split(",");
                                    $('.checkBoxWeek').each(function (i, obj) {
                                        weekValue = $(obj).val();
                                        weekId = $(obj).attr('id');
                                        if ($.inArray(weekValue, weekDays) != -1)
                                        {
                                            console.log(weekValue, weekId, weekDays, $.inArray(weekValue, weekDays));
                                            $("#" + weekId).parent().addClass('checked');
                                            $("#" + weekId).prop('checked', true);
                                        }
                                    });
                                    break;

                                case "Monthly":
                                    $('.nav-tabs li.active').removeClass('active');
                                    $('.nav-tabs li').eq(2).addClass('active');
                                    $('#home,#profile,#messages,#settings').removeClass('active');
                                    $('#messages').addClass('active');

                                    txt = 'Recurring ' + recurringRule['repeat'] + ";";
                                    txt += " Repeat every  " + recurringRule['interval'] + " month(s) ";

                                    if (recurringRule['day'])
                                    {
                                        txt += " on the  " + recurringRule['day'] + " days;"
                                    } else
                                    {
                                        txt += " on the " + recurringRule['pos'] + " " + recurringRule['weekDays'] + ";"
                                    }
                                    txt += " Start date: " + recurringRule['start'] + ";"
                                    if (recurringRule['until'])
                                    {
                                        txt += " Run until: " + recurringRule['until'] + ";"
                                    } else if (recurringRule['count'])
                                    {
                                        txt += " Run until it reaches " + recurringRule['count'] + " occurences;"
                                    } else
                                    {
                                        txt += " Never stop;";
                                    }
                                    $(".recurring_lable").text("");
                                    $(".recurring_lable").text(txt);

                                    $("#repeatmonth").val(recurringRule['interval']);
                                    $("#conditionStartMonthDate").val(recurringRule['start']);
                                    $("input[name='conditionMonth']").removeAttr('checked');
                                    $("input[name='conditionMonth']").parent().removeClass('checked');
                                    if (recurringRule['until'])
                                    {
                                        $("#optionsRadios_month2").parent().addClass('checked');
                                        $("#optionsRadios_month2").attr('checked', true);
                                        $("#datepickerMonth").val(recurringRule['until']);
                                    } else if (recurringRule['count'])
                                    {
                                        $("#optionsRadios_month3").parent().addClass('checked');
                                        $("#optionsRadios_month3").attr('checked', true);
                                        $("#conditionMonthTxt").val(recurringRule['count']);
                                    } else
                                    {
                                        $("#optionsRadios_month1").parent().addClass('checked');
                                        $("#optionsRadios_month1").attr('checked', true);
                                    }
                                    $("input[name='Month']").removeAttr('checked');
                                    $("input[name='Month']").parent().removeClass('checked');
                                    if (recurringRule['day'])
                                    {
                                        $("#Month_1").parent().addClass('checked');
                                        $("#Month_1").prop('checked', true);
                                        $("#Month_2").prop('checked', false);
                                        $('select[name="selectMonth1_1"]').val(recurringRule['day']);
                                    } else
                                    {
                                        $("#Month_2").parent().addClass('checked');
                                        $("#Month_2").prop('checked', true);
                                        $("#Month_1").prop('checked', false);
                                        $('select[name="selectMonth2_1"]').val(recurringRule['pos']);
                                        $('select[name="selectMonth2_2"]').val(recurringRule['weekDays']);
                                    }
                                    $("#week_0,#week_1,#week_2,#week_3,#week_4,#week_5,#week_6").parent().removeClass('checked');
                                    $("#week_0,#week_1,#week_2,#week_3,#week_4,#week_5,#week_6").prop('checked', false);
                                    break;

                                case "Yearly":
                                    $('.nav-tabs li.active').removeClass('active');
                                    $('.nav-tabs li').eq(3).addClass('active');
                                    $('#home,#profile,#messages,#settings').removeClass('active');
                                    $('#settings').addClass('active');
                                    txt = 'Recurring  ' + recurringRule['repeat'] + ";";
                                    txt += " Repeat every " + recurringRule['interval'] + " year(s) ";
                                    if (recurringRule['day'])
                                    {
                                        txt += " on the  " + recurringRule['day'] + " of month " + recurringRule['month'] + ";"
                                    } else
                                    {
                                        txt += " on the  " + recurringRule['pos'] + " " + recurringRule['weekDays'] + " of month " + recurringRule['month'] + ";"
                                    }
                                    txt += " Start date " + recurringRule['start'] + ";";
                                    if (recurringRule['until'])
                                    {
                                        txt += " Run until: " + recurringRule['until'] + ";"
                                    } else if (recurringRule['count'])
                                    {
                                        txt += " Run until it reaches " + recurringRule['count'] + " occurences;"
                                    } else
                                    {
                                        txt += " Never stop; ";
                                    }
                                    $(".recurring_lable").text("");
                                    $(".recurring_lable").text(txt);


                                    $("#repeatyear").val(recurringRule['interval']);
                                    $("#conditionStartYearDate").val(recurringRule['start']);
                                    $("input[name='conditionYear']").removeAttr('checked');
                                    $("input[name='conditionYear']").parent().removeClass('checked');
                                    if (recurringRule['until'])
                                    {
                                        $("#optionsRadios_year2").parent().addClass('checked');
                                        $("#optionsRadios_year2").attr('checked', true);
                                        $("#datepickerYear").val(recurringRule['until']);
                                    } else if (recurringRule['count'])
                                    {
                                        $("#optionsRadios_year3").parent().addClass('checked');
                                        $("#optionsRadios_year3").attr('checked', true);
                                        $("#conditionYearTxt").val(recurringRule['count']);
                                    } else
                                    {
                                        $("#optionsRadios_year1").parent().addClass('checked');
                                        $("#optionsRadios_year1").attr('checked', true);
                                    }

                                    $("input[name='Year']").removeAttr('checked');
                                    $("input[name='Year']").parent().removeClass('checked');
                                    if (recurringRule['day'])
                                    {
                                        $("#Year_1").parent().addClass('checked');
                                        $("#Year_1").prop('checked', true);
                                        $("#Year_2").prop('checked', false);
                                        $('select[name="selectYear1_1"]').val(recurringRule['day']);
                                        $('select[name="selectYear1_2"]').val(recurringRule['month']);
                                    } else
                                    {
                                        $("#Year_2").parent().addClass('checked');
                                        $("#Year_2").prop('checked', true);
                                        $("#Year_1").prop('checked', false);
                                        $('select[name="selectYear2_1"]').val(recurringRule['pos']);
                                        $('select[name="selectYear2_2"]').val(recurringRule['weekDays']);
                                        $('select[name="selectYear2_3"]').val(recurringRule['month']);
                                    }
                                    $("#week_0,#week_1,#week_2,#week_3,#week_4,#week_5,#week_6").parent().removeClass('checked');
                                    $("#week_0,#week_1,#week_2,#week_3,#week_4,#week_5,#week_6").prop('checked', false);
                                    break;
                                default:
                                    break;
                            }
                        }
                        for (var obj in data.details)
                        {
                            if (data.details[obj].etg_affects_region_type == -1)
                            {
                                // no  region will be affected
                                $(".hideAll").hide();
                                $("#ytHolidayEvents_allregion").val(-1);
                                $('#uniform-HolidayEvents_allregion_0 span').removeClass('checked');
                                $('#uniform-HolidayEvents_allregion_1 span').addClass('checked');
                                $('#uniform-HolidayEvents_allregion_2 span').removeClass('checked');

                                $("#HolidayEvents_region").val("");
                                $("#HolidayEvents_region").trigger("change");

                                $("#HolidayEvents_source_mzone").val("");
                                $("#HolidayEvents_source_mzone").trigger("change");
                                $("#HolidayEvents_destination_mzone").val("");
                                $("#HolidayEvents_destination_mzone").trigger("change");

                                $("#HolidayEvents_source_zone").val("");
                                $("#HolidayEvents_source_zone").trigger("change");
                                $("#HolidayEvents_destination_zone").val("");
                                $("#HolidayEvents_destination_zone").trigger("change");

                                $("#HolidayEvents_source_state").val("");
                                $("#HolidayEvents_source_state").trigger("change");
                                $("#HolidayEvents_destination_state").val("");
                                $("#HolidayEvents_destination_state").trigger("change");

                                var $select_source = $("#source_city").selectize();
                                var selectize_source = $select_source[0].selectize;
                                selectize_source.clear();

                                var $select_destination = $("#destination_city").selectize();
                                var selectize_destination = $select_destination[0].selectize;
                                selectize_destination.clear();

                            } else if (data.details[obj].etg_affects_region_type == 0)
                            {
                                // all region
                                $(".hideAll").hide();
                                $("#ytHolidayEvents_allregion").val(0);
                                $('#uniform-HolidayEvents_allregion_0 span').addClass('checked');
                                $('#uniform-HolidayEvents_allregion_1 span').removeClass('checked');
                                $('#uniform-HolidayEvents_allregion_2 span').removeClass('checked');

                                $("#HolidayEvents_region").val("");
                                $("#HolidayEvents_region").trigger("change");

                                $("#HolidayEvents_source_mzone").val("");
                                $("#HolidayEvents_source_mzone").trigger("change");
                                $("#HolidayEvents_destination_mzone").val("");
                                $("#HolidayEvents_destination_mzone").trigger("change");

                                $("#HolidayEvents_source_zone").val("");
                                $("#HolidayEvents_source_zone").trigger("change");
                                $("#HolidayEvents_destination_zone").val("");
                                $("#HolidayEvents_destination_zone").trigger("change");

                                $("#HolidayEvents_source_state").val("");
                                $("#HolidayEvents_source_state").trigger("change");
                                $("#HolidayEvents_destination_state").val("");
                                $("#HolidayEvents_destination_state").trigger("change");

                                var $select_source = $("#source_city").selectize();
                                var selectize_source = $select_source[0].selectize;
                                selectize_source.clear();

                                var $select_destination = $("#destination_city").selectize();
                                var selectize_destination = $select_destination[0].selectize;
                                selectize_destination.clear();
                            } else if (data.details[obj].etg_affects_region_type == 1)
                            {
                                // region
                                $(".hideAll").show();
                                $("#ytHolidayEvents_allregion").val(1);
                                $('#uniform-HolidayEvents_allregion_0 span').removeClass('checked');
                                $('#uniform-HolidayEvents_allregion_1 span').removeClass('checked');
                                $('#uniform-HolidayEvents_allregion_2 span').addClass('checked');

                                $("#agent-type-form select").attr("disabled", "disabled");
                                region = data.details[obj].etg_region_id;
                                regionArr = region != null ? region.split(",") : [];
                                $("#HolidayEvents_region").val(regionArr);
                                $("#HolidayEvents_region").trigger("change");

                                mzone_source = data.details[obj].etg_source_mzone_id;
                                mzoneSourceArr = mzone_source != null ? mzone_source.split(",") : [];
                                $("#HolidayEvents_source_mzone").val(mzoneSourceArr);
                                $("#HolidayEvents_source_mzone").trigger("change");

                                mzone_destination = data.details[obj].etg_destination_mzone_id;
                                mzoneDestinationArr = mzone_destination != null ? mzone_destination.split(",") : [];
                                $("#HolidayEvents_destination_mzone").val(mzoneDestinationArr);
                                $("#HolidayEvents_destination_mzone").trigger("change");

                                zone_source = data.details[obj].etg_source_zone_id;
                                zoneSourceArr = zone_source != null ? zone_source.split(",") : [];
                                $("#HolidayEvents_source_zone").val(zoneSourceArr);
                                $("#HolidayEvents_source_zone").trigger("change");

                                zone_destination = data.details[obj].etg_destination_zone_id;
                                zoneDestinationArr = zone_destination != null ? zone_destination.split(",") : [];
                                $("#HolidayEvents_destination_zone").val(zoneDestinationArr);
                                $("#HolidayEvents_destination_zone").trigger("change");

                                state_source = data.details[obj].etg_source_state_id;
                                stateSourceArr = state_source != null ? state_source.split(",") : [];
                                $("#HolidayEvents_source_state").val(stateSourceArr);
                                $("#HolidayEvents_source_state").trigger("change");

                                state_destination = data.details[obj].etg_destination_state_id;
                                stateDestinationArr = state_destination != null ? state_destination.split(",") : [];
                                $("#HolidayEvents_destination_state").val(stateDestinationArr);
                                $("#HolidayEvents_destination_state").trigger("change");

                                var $selectSource = $("#source_city").selectize();
                                var selectizeSource = $selectSource[0].selectize;
                                for (var city in data.citySourceDetails)
                                {
                                    selectizeSource.addOption({id: data.citySourceDetails[city].id, text: data.citySourceDetails[city].text});
                                    selectizeSource.addItem(data.citySourceDetails[city].id);
                                }
                                selectizeSource.disable();

                                var $selectdestination = $("#destination_city").selectize();
                                var selectizedestination = $selectdestination[0].selectize;
                                for (var city in data.cityDestinationDetails)
                                {
                                    selectizedestination.addOption({id: data.cityDestinationDetails[city].id, text: data.cityDestinationDetails[city].text});
                                    selectizedestination.addItem(data.cityDestinationDetails[city].id);
                                }
                                selectizedestination.disable();
                            }
                            $("#HolidayEvents_margin").val(data.details[obj].etg_margin);
                        }

                    } else
                    {
                        $(".hideAll").hide();
                        $("#ytHolidayEvents_allregion").val(0);
                        $('#uniform-HolidayEvents_allregion_0 span').addClass('checked');
                        $('#uniform-HolidayEvents_allregion_1 span').removeClass('checked');
                        $('#uniform-HolidayEvents_allregion_2 span').removeClass('checked');
                        $("#agent-type-form select").removeAttr("disabled");

                        var $select = $("#source_city").selectize();
                        var selectize = $select[0].selectize;
                        selectize.enable();

                        var $selectdestination = $("#destination_city").selectize();
                        var selectizedestination = $selectdestination[0].selectize;
                        selectizedestination.enable();
                    }
                }
            });
        } else
        {
            bootbox.alert("Please select an event");
        }
    });
    $("#SaveChanges").click(function () {

        if ($("#agent-type-form select").is('[disabled=disabled]'))
        {
            bootbox.alert("please click on modify button to enable dropdown")
        } else
        {
            eventId = $("#HolidayEvents_hde_id").val();
            allRegionType = $("#ytHolidayEvents_allregion").val();

            region = $("#HolidayEvents_region").val();

            source_mzone = $("#HolidayEvents_source_mzone").val();
            destination_mzone = $("#HolidayEvents_destination_mzone").val();

            source_zone = $("#HolidayEvents_source_zone").val();
            destination_zone = $("#HolidayEvents_destination_zone").val();

            source_state = $("#HolidayEvents_source_state").val();
            destination_state = $("#HolidayEvents_destination_state").val();

            source_city = $("#source_city").val();
            destination_city = $("#destination_city").val();
            margin = $("#HolidayEvents_margin").val();
            hde_recurrs = $("#HolidayEvents_hde_recurrs_rule").val();

            if (eventId.length > 0)
            {
                $.ajax({
                    "type": "POST",
                    "dataType": "json",
                    "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/CalendarEvent/AddUpdateEvent')) ?>",
                    "data": {
                        'allRegionType': allRegionType,
                        "eventId": eventId,
                        "region": region,
                        "source_mzone": source_mzone,
                        "destination_mzone": destination_mzone,
                        "source_zone": source_zone,
                        "destination_zone": destination_zone,
                        "source_state": source_state,
                        'destination_state': destination_state,
                        'source_city': source_city,
                        'destination_city': destination_city,
                        'hde_recurrs': hde_recurrs,
                        'margin': margin,
                        "YII_CSRF_TOKEN": "<?= Yii::app()->request->csrfToken ?>",
                        'type': 'addupdate_Event'
                    },
                    "success": function (data) {
                        if (data.success)
                        {
                            location.reload();
                        } else
                        {
                            bootbox.alert(data.message);
                        }
                    }
                });
            } else if (eventId.length == 0)
            {
                bootbox.alert("Please select an event");
            } else if (allRegionType == 1)
            {
                bootbox.alert("Please select enter value from at least one dropdown");
            }
        }
    });
    $('input:radio[name="HolidayEvents[allregion]"]').on('click change', function (e) {
        if (e.currentTarget.value == 1)
        {
            $("#ytHolidayEvents_allregion").val(1);
            $(".hideAll").show();
        } else if (e.currentTarget.value == -1)
        {
            $("#ytHolidayEvents_allregion").val(-1);
            $(".hideAll").hide();
        } else
        {
            $("#ytHolidayEvents_allregion").val(0);
            $(".hideAll").hide();
        }
    });
    $("#Modify").click(function () {
        $("#agent-type-form select").removeAttr("disabled");
        var $select_source = $("#source_city").selectize();
        var selectize_source = $select_source[0].selectize;
        selectize_source.enable();

        var $select_destination = $("#destination_city").selectize();
        var selectize_destination = $select_destination[0].selectize;
        selectize_destination.enable();

    });
    $("#Cancel").click(function () {

        $("#agent-type-form select").removeAttr("disabled");
        $(".hideAll").hide();
        $("#ytHolidayEvents_allregion").val(0);
        $('#uniform-HolidayEvents_allregion_0 span').addClass('checked');
        $('#uniform-HolidayEvents_allregion_1 span').removeClass('checked');
        $('#uniform-HolidayEvents_allregion_2 span').removeClass('checked');

        $("#HolidayEvents_region").val("");
        $("#HolidayEvents_region").trigger("change");

        $("#HolidayEvents_source_mzone").val("");
        $("#HolidayEvents_source_mzone").trigger("change");
        $("#HolidayEvents_destination_mzone").val("");
        $("#HolidayEvents_destination_mzone").trigger("change");

        $("#HolidayEvents_source_zone").val("");
        $("#HolidayEvents_source_zone").trigger("change");
        $("#HolidayEvents_destination_zone").val("");
        $("#HolidayEvents_destination_zone").trigger("change");

        $("#HolidayEvents_source_state").val("");
        $("#HolidayEvents_source_state").trigger("change");
        $("#HolidayEvents_destination_state").val("");
        $("#HolidayEvents_destination_state").trigger("change");

        var $select_source = $("#source_city").selectize();
        var selectize_source = $select_source[0].selectize;
        selectize_source.clear();

        var $select_destination = $("#destination_city").selectize();
        var selectize_destination = $select_destination[0].selectize;
        selectize_destination.clear();
    });
    $("#CreateEvent").click(function () {
        bootbox.confirm('<b>Make sure that event is not already existing in the event master list</b><br/><br/><form id="infos"><div class="form-group"><label for="holidayName">Event Name:</label><input type="text" class="form-control" id="holidayName" placeholder="event name"></div><div class="form-group"><label for="holidayDescription">Event Description:</label><textarea class="form-control" id="holidayDescription" rows="3" placeholder="event description"></textarea></div><div class="form-group"><label for="previousHaloDays">Previous halo days:</label><input type="text" class="form-control" id="previousHaloDays" placeholder="Previous halo days"></div><div class="form-group"><label for="nextHaloDays">Next halo days:</label><input type="text" class="form-control" id="nextHaloDays" placeholder="Next Halo Days"></div></form>', function (result) {
            if (result)
            {
                holidayName = $("#holidayName").val();
                holidayDescription = $("#holidayDescription").val();
                previousHaloDays = $("#previousHaloDays").val();
                nextHaloDays = $("#nextHaloDays").val();
                if (holidayName == "")
                {
                    bootbox.alert("Event name cannot be blank");
                    return false;
                } else if ((previousHaloDays != null && previousHaloDays < 0) || (nextHaloDays != null && nextHaloDays < 0))
                {
                    bootbox.alert("Halo days cannot be -ve");
                    return false;
                } else
                {
                    $.ajax({
                        "type": "GET",
                        "dataType": "json",
                        "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/CalendarEvent/create')) ?>",
                        "data": {"holidayName": holidayName, "previousHaloDays": previousHaloDays, "nextHaloDays": nextHaloDays, "holidayDescription": holidayDescription, 'type': 'createEvent'},
                        "success": function (data) {
                            if (data.success)
                            {
                                location.reload();
                            } else
                            {
                                bootbox.alert(data.message);
                            }
                        }
                    });
                }


            }

        });
    });
    function populateSource(obj, cityId)
    {
        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null)
            {
                xhr = $.ajax({url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function () {
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
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false, error: function () {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    }

    $(document).on('click', '.SaveRule', function () {
        tab = $(".tablist.active").attr("tab");
        jsonRecuringRule = {};
        switch (tab)
        {
            case "Daily":
                conditionDays = ($("input[name='conditionDays']:checked").val());
                jsonRecuringRule['repeat'] = "Daily";
                jsonRecuringRule['interval'] = $("#repeatdays").val();
                jsonRecuringRule['start'] = $("#conditionStartDayDate").val();
                switch (conditionDays)
                {
                    case "specific":
                        jsonRecuringRule['until'] = $("#datepickerDay").val();
                        break;
                    case "number":
                        jsonRecuringRule['count'] = $("#conditionDaysTxt").val();
                        break;
                    default:
                        break;

                }
                break;

            case "Weekly":
                jsonRecuringRule['repeat'] = "Weekly";
                jsonRecuringRule['interval'] = $("#repeatweek").val();
                jsonRecuringRule['start'] = $("#conditionStartWeekDate").val();
                conditionWeek = $("input[name='conditionWeek']:checked").val();
                weekStr = "";
                weekStr += $('#week_0').is(':checked') ? "SUN," : "";
                weekStr += $('#week_1').is(':checked') ? "MON," : "";
                weekStr += $('#week_2').is(':checked') ? "TUE," : "";
                weekStr += $('#week_3').is(':checked') ? "WED," : "";
                weekStr += $('#week_4').is(':checked') ? "THU," : "";
                weekStr += $('#week_5').is(':checked') ? "FRI," : "";
                weekStr += $('#week_6').is(':checked') ? "SAT," : "";
                if (weekStr != null)
                {
                    jsonRecuringRule['weekDays'] = weekStr.replace(/,$/, '');
                }
                switch (conditionWeek)
                {
                    case "specific":
                        jsonRecuringRule['until'] = $("#datepickerWeek").val();
                        break;
                    case "number":
                        jsonRecuringRule['count'] = $("#conditionWeekTxt").val();
                        break;
                    default:
                        break;

                }
                break;

            case "Monthly":
                jsonRecuringRule['repeat'] = "Monthly";
                jsonRecuringRule['interval'] = $("#repeatmonth").val();
                jsonRecuringRule['start'] = $("#conditionStartMonthDate").val();
                conditionMonth = ($("input[name='conditionMonth']:checked").val());
                switch (conditionMonth)
                {
                    case "specific":
                        jsonRecuringRule['until'] = $("#datepickerMonth").val();
                        break;
                    case "number":
                        jsonRecuringRule['count'] = $("#conditionMonthTxt").val();
                        break;
                    default:
                        break;

                }
                Month = ($("input[name='Month']:checked").val());
                switch (Month)
                {
                    case "Month_1":
                        jsonRecuringRule['day'] = $('#selectMonth1_1').find(":selected").val();
                        break;
                    case "Month_2":
                        jsonRecuringRule['pos'] = $('#selectMonth2_1').find(":selected").val();
                        jsonRecuringRule['weekDays'] = $('#selectMonth2_2').find(":selected").val();
                        break;
                    default:
                        break;
                }
                break;

            case "Yearly":
                jsonRecuringRule['repeat'] = "Yearly";
                jsonRecuringRule['interval'] = $("#repeatyear").val();
                jsonRecuringRule['start'] = $("#conditionStartYearDate").val();
                conditionYear = ($("input[name='conditionYear']:checked").val());
                switch (conditionYear)
                {
                    case "specific":
                        jsonRecuringRule['until'] = $("#datepickerYear").val();
                        break;
                    case "number":
                        jsonRecuringRule['count'] = $("#conditionYearTxt").val();
                        break;
                    default:
                        break;
                }
                Year = ($("input[name='Year']:checked").val());
                switch (Year)
                {
                    case "Year_1":
                        jsonRecuringRule['day'] = $('#selectYear1_1').find(":selected").val();
                        jsonRecuringRule['month'] = $('#selectYear1_2').find(":selected").val();
                        break;
                    case "Year_2":
                        jsonRecuringRule['pos'] = $('#selectYear2_1').find(":selected").val();
                        jsonRecuringRule['weekDays'] = $('#selectYear2_2').find(":selected").val();
                        jsonRecuringRule['month'] = $('#selectYear2_3').find(":selected").val();
                        break;
                    default:
                        break;
                }
                break;

            default:
                break;
        }
        $("#HolidayEvents_hde_recurrs_rule").val(JSON.stringify(jsonRecuringRule));
        $('#myModal').modal('hide');
        switch (jsonRecuringRule['repeat'])
        {
            case "Daily":
                txt = 'Recurring ' + jsonRecuringRule['repeat'] + ";";
                txt += " Repeat every " + jsonRecuringRule['interval'] + " day(s);";
                txt += " Start date from " + jsonRecuringRule['start'] + ";";
                if (jsonRecuringRule['until'])
                {
                    txt += " Run until " + jsonRecuringRule['until'] + ";"
                } else if (jsonRecuringRule['count'])
                {
                    txt += " Run until it reaches " + jsonRecuringRule['count'] + " occurences;"
                } else
                {
                    txt += " Never stop;";
                }
                $(".recurring_lable").text("");
                $(".recurring_lable").text(txt);
                break;

            case "Weekly":
                txt = 'Recurring ' + jsonRecuringRule['repeat'] + ";";
                txt += " Repeat every " + jsonRecuringRule['interval'] + " week(s) ";
                if (jsonRecuringRule['weekDays'])
                {
                    txt += "  on " + jsonRecuringRule['weekDays'] + ";"
                }
                txt += " Start date " + jsonRecuringRule['start'] + ";";
                if (jsonRecuringRule['until'])
                {
                    txt += " Run until: " + jsonRecuringRule['until'] + ";"
                } else if (jsonRecuringRule['count'])
                {
                    txt += " Run until it reaches " + jsonRecuringRule['count'] + " occurences;"
                } else
                {
                    txt += " Never stop;";
                }
                $(".recurring_lable").text("");
                $(".recurring_lable").text(txt);
                break;

            case "Monthly":
                txt = 'Recurring ' + jsonRecuringRule['repeat'] + ";";
                txt += " Repeat every  " + jsonRecuringRule['interval'] + " month(s) ";

                if (jsonRecuringRule['day'])
                {
                    txt += " on the  " + jsonRecuringRule['day'] + " days;"
                } else
                {
                    txt += " on the " + jsonRecuringRule['pos'] + " " + jsonRecuringRule['weekDays'] + ";"
                }
                txt += " Start date: " + jsonRecuringRule['start'] + ";"
                if (jsonRecuringRule['until'])
                {
                    txt += " Run until: " + jsonRecuringRule['until'] + ";"
                } else if (jsonRecuringRule['count'])
                {
                    txt += " Run until it reaches " + jsonRecuringRule['count'] + " occurences;"
                } else
                {
                    txt += " Never stop;";
                }
                $(".recurring_lable").text("");
                $(".recurring_lable").text(txt);
                break;

            case "Yearly":
                txt = 'Recurring  ' + jsonRecuringRule['repeat'] + ";";
                txt += " Repeat every " + jsonRecuringRule['interval'] + " year(s) ";
                if (jsonRecuringRule['day'])
                {
                    txt += " on the  " + jsonRecuringRule['day'] + " of month " + jsonRecuringRule['month'] + ";"
                } else
                {
                    txt += " on the  " + jsonRecuringRule['pos'] + " " + jsonRecuringRule['weekDays'] + " of month " + jsonRecuringRule['month'] + ";"
                }
                txt += " Start date " + jsonRecuringRule['start'] + ";";
                if (jsonRecuringRule['until'])
                {
                    txt += " Run until: " + jsonRecuringRule['until'] + ";"
                } else if (jsonRecuringRule['count'])
                {
                    txt += " Run until it reaches " + jsonRecuringRule['count'] + " occurences;"
                } else
                {
                    txt += " Never stop; ";
                }
                $(".recurring_lable").text("");
                $(".recurring_lable").text(txt);
                break;
            default:
                break;
        }
    });

</script>