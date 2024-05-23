<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<?php
/* @var $form TbActiveForm */
$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'partnerTypeForm', 'enableClientValidation' => true,
	'clientOptions'			 => array(
		'validateOnSubmit'	 => true,
		'errorCssClass'		 => 'has-error',
		'afterValidate'		 => 'js:function(form,data,hasError){
                if(!hasError){
					if(!admBooking.validatePartner())
					{
                        return false;                         
					}
                    $.ajax({
                    "type":"POST",
                    "dataType":"HTML",
                    async: false,
                    "url":"' . CHtml::normalizeUrl(Yii::app()->createUrl('admin/booking/partnerInfo')) . '",
                    "data":form.serialize(),
                    "beforeSend": function () {
                        ajaxindicatorstart("");
                    },
                    "complete": function () {                     
                        ajaxindicatorstop();
                    },
                    "success":function(data1){
						$("#bkErrors").addClass("hide");
						$(".btn-partner").removeClass("btn-info");
						$(".btn-partner").addClass("disabled");
						$("#partnerType").find("input").attr("disabled",true);
						$("#partnerType").find(".agent-focus,.selectize-control").addClass("disabled");
						$("#customerPhoneDetails").html(data1);
						$("#customerPhoneDetails").removeClass("hide");
						$(".btn-editPartner").removeClass("hide");
						$(document).scrollTop($("#customerPhoneDetails").offset().top);
                        },
                     error: function(xhr, status, error){
                      
                         }
                    });

                    }
                }'
	),
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// See class documentation of CActiveForm for details on this,
	// you need to use the performAjaxValidation()-method described there.
	'enableAjaxValidation'	 => false,
	'errorMessageCssClass'	 => 'help-block',
	'htmlOptions'			 => array(
		'onkeydown'	 => "return event.key != 'Enter';",
		'class'		 => '',
	),
		));
?>
<?= CHtml::hiddenField("jsonData_partner", $data, ['id'=>'jsonData_partner'])?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default panel-border">
			<span class="edit-block btn-editPartner hide"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span>
			<div class="row">
				<div class="col-md-8 col-md-offset-2">
					<h3 class="pl15">Channel Partner</h3>
					<div class="form-group panel-body pt0">
						<div class="row">
							<div class="panel-body p15 pt0 pb0">
								<?php
								$this->widget('ext.yii-selectize.YiiSelectize', array(
									'model'				 => $model,
									'attribute'			 => 'bkg_agent_id',
									'useWithBootstrap'	 => true,
									"placeholder"		 => "Select Agent",
									'fullWidth'			 => false,
									'htmlOptions'		 => array('style'	 => 'width:100%;',
										'id'	 => 'bkg_agent_id',
										'class'	 => 'agent-focus'
									),
									'defaultOptions'	 => $selectizeOptions + array(
								'onInitialize'	 => "js:function(){
							populateAgent(this, '{$model->bkg_agent_id}');
								}",
								'load'			 => "js:function(query, callback){
							loadAgent(query, callback);
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

						<input type="hidden" value="<?php echo UserInfo::getUserId(); ?>" id="adminid" name="adminid">
						<input type="hidden" value="<?php echo $model->arl_operating_managers; ?>" id="arl_operating_managers" name="arl_operating_managers"> 
						<input type="hidden" value="<?php echo $model->agt_approved_untill_date; ?>" id="agt_approved_untill_date" name="agt_approved_untill_date"> 

						<input type="hidden" value="" id="agt_type" name="agt_type">
						<input type="hidden" value="" id="agt_commission_value" name="agt_commission_value">
						<input type="hidden" value="" id="agt_commission" name="agt_commission">

						<div class="row hide mt10" id="booking_ref_code_div">
							<div class="col-xs-4">Agent Ref ID</div>
							<div class="col-xs-8">

								<?= $form->textFieldGroup($model, 'bkg_agent_ref_code', array('label' => "", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Agents reference ID for this booking')))); ?>          
							</div>
						</div>
						<div class="row hide" id="divpaidby">
							<div class="col-xs-4">To be paid by</div>
							<div class="col-xs-8 pl0">
								<label class="checkbox-inline pl0">
									<?
									if ($model->agentBkgAmountPay == '')
									{
										$model->agentBkgAmountPay = 2;
									}
									?>
									<?= $form->radioButtonListGroup($model, 'agentBkgAmountPay', array('label' => '', 'widgetOptions' => array('htmlOptions' => [], 'data' => [1 => 'Customer', 2 => 'Agent/Company']), 'inline' => true)) ?>
								</label>
							</div>
						</div>
						<!--           notification agent options-->
						<div class="row hide" id="agtnotification">
							<div class="col-sm-12 mt20">
								<div class="row" id="" >

									<h3 class="pl15 mt0 mb0">Partner preferences</h3>
									<div class="col-xs-12" style="display:none;" id="divpaidby2">
										<span class="mr15" id="divpref"></span>
									</div>
								</div>	
								<div class="row">
									<div class="col-xs-12"><h3 class="mt0 mb0">Send a booking copy to</h3></div>
									<div class="col-xs-12">
										<div class="row">
											<div class="col-xs-6 col-md-3">
												<?= $form->textFieldGroup($model, 'bkg_copybooking_name', array('label' => "Name", 'class' => "form-control", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Name')))) ?>
											</div>
											<div class="col-xs-6 col-md-3"> 
												<?= $form->textFieldGroup($model, 'bkg_copybooking_email', array('label' => "Email", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Email')))) ?>
											</div>
											<div class="col-xs-3 col-md-2">
												<div class="form-group ">
													<label>Country Code</label>
													<?php
													$this->widget('ext.yii-selectize.YiiSelectize', array(
														'model'				 => $model,
														'attribute'			 => 'bkg_copybooking_country',
														'useWithBootstrap'	 => true,
														"placeholder"		 => "Code",
														'fullWidth'			 => false,
														'htmlOptions'		 => array(
														),
														'defaultOptions'	 => array(
															'create'			 => false,
															'persist'			 => true,
															'selectOnTab'		 => true,
															'createOnBlur'		 => true,
															'dropdownParent'	 => 'body',
															'optgroupValueField' => 'id',
															'optgroupLabelField' => 'pcode',
															'optgroupField'		 => 'pcode',
															'openOnFocus'		 => true,
															'labelField'		 => 'pcode',
															'valueField'		 => 'pcode',
															'searchField'		 => 'name',
															//   'sortField' => 'js:[{field:"order",direction:"asc"}]',
															'closeAfterSelect'	 => true,
															'addPrecedence'		 => false,
															'onInitialize'		 => "js:function(){
																	this.load(function(callback){
																	var obj=this;                                
																	 xhr=$.ajax({
															 url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
															 dataType:'json',                  
															 success:function(results){
																 obj.enable();
																 callback(results.data);
																  obj.setValue('{$model->bkg_copybooking_country}');
															 },                    
															 error:function(){
																 callback();
															 }});
															});
															}",
															'render'			 => "js:{
																option: function(item, escape){                      
																return '<div><span class=\"\">' + escape(item.name) +'</span></div>';                          
																},
																option_create: function(data, escape){
																return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
																	}
																}",
														),
													));
													?>
												</div>  </div>
											<div class="col-xs-9 col-md-4"> 
												<?= $form->textFieldGroup($model, 'bkg_copybooking_phone', array('label' => "Phone", 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Enter Phone')))) ?>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
								</div>
							</div>
							<div class="col-xs-12 pt20 " id="divUpd">
							</div>
						</div>
						<!--            notification agent options-->
						<div class="row">
							<div class="col-xs-12 mt20 hide" id="corp_addt_details">
								<input type="checkbox" name="corp_addt_details[]" value="1"  checked>Driver & Car details required at least 12 hours before the pickup
								<br><br><input type="checkbox" name="corp_addt_details[]" value="2" checked>Corporate booking – car must be new and clean inside & outside
								<br><br><input type="checkbox" name="corp_addt_details[]" value="3" checked>Corporate company require duty slips for all parking or toll payments.
								<br><br><input type="checkbox" name="corp_addt_details[]" value="4" checked>Do not ask traveller for any cash. Contact Gozo for any issues.
							</div>
						</div>
						<div class="row">
							<div class="col-sm-12 text-center">
								<button type='button' class='btn btn-info btn-partner pl20 pr20'>Next</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
    </div>
</div> 
<?php $this->endWidget(); ?>
<script>
    function showPreference()
    {
        var agentId = $("#bkg_agent_id").val();
        var href = '<?= Yii::app()->createUrl("admin/booking/partnerPreference"); ?>';
        $.ajax({
            "url": href,
            "type": "GET",
            "dataType": "html",
            "data": {"agent_id": agentId},
            "success": function (data1)
            {
                //var yy = jQuery.parseJSON(data1);
                console.log(data1);


            }
        });
        return false;
    }

    $('#bkg_agent_id').change(function ()
    {
        var agtId = $("#bkg_agent_id").val();
        admBooking.getBookingPreferences(agtId);
        admBooking.onAgentSelected(agtId);
    });

    $(".btn-partner").click(function ()
    {
		var aom = $("#arl_operating_managers").val();
		var UserDate = $("#agt_approved_untill_date").val();
		var admid =$("#adminid").val();	
		var ToDate = new Date();
			if (new Date(UserDate).getTime() < ToDate.getTime()) 
			{
				alert("Approved Untill date expired! The Date must be Bigger to today date");
				return false;				
			}		
		if(aom != '')
		{
			if(aom.split(',').indexOf(admid) > -1) 
				{
				$("#partnerTypeForm").submit();
				}
				else
				{
				alert("You are not authorized to create booking for this channel partner.")
				}
		}
		else
		{	
			$("#partnerTypeForm").submit();
		}
	
    });

    $(".btn-editPartner").click(function ()
    {
        $('#customerPhoneDetails,#bookingType,#bookingRoute,#payment,#travellerInfo,#additionalInfo,#rePayment,#vendorIns').html('');
        $('#customerPhoneDetails,#bookingType,#bookingRoute,#payment,#travellerInfo,#additionalInfo,#rePayment,#vendorIns').addClass('hide');
        $(".btn-partner").addClass("btn-info");
        $(".btn-partner").removeClass("disabled");
        $("#partnerType").find("input").attr("disabled", false);
        $("#partnerType").find(".agent-focus,.selectize-control").removeClass("disabled");
        $(".btn-editPartner").addClass("hide");
    });

    $agtList = null;
    function populateAgent(obj, agtId)
    {
        obj.load(function (callback)
        {
            var obj = this;
            if ($agtList == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allpartnerbyquery', ['onlyActive' => 0, 'agt' => ''])) ?>' + agtId,
                    dataType: 'json',
                    data: {
                    },
                    success: function (results)
                    {
                        $agtList = results;
                        obj.enable();
                        callback($agtList);
                        obj.setValue(agtId);
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            }
            else
            {
                obj.enable();
                callback($agtList);
                obj.setValue(agtId);
            }
        });
    }

    function loadAgent(query, callback)
    {
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allpartnerbyquery')) ?>?onlyActive=0&q=' + encodeURIComponent(query),
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
</script>