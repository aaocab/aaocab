<?
$version		 = Yii::app()->params['siteJSVersion'];
$api			 = Yii::app()->params['googleBrowserApiKey'];
$this->layout	 = 'column_booking';
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
//$bType = Booking::model()->getBookingType(0, 'Trip');
$disabled		 = '';
$bkgtype		 = $model->bkg_booking_type;
$checked		 = "checked='checked'";
$active			 = "active";
$checked1		 = $checked2		 = $checked3		 = $active1		 = $active2		 = $active3		 = '';
if ($bkgtype == 1)
{
	$checked1	 = $checked;
	$active1	 = $active;
}
else
{
	${"checked$bkgtype"} = $checked;
	${"active$bkgtype"}	 = $active;
}
$autoAddressJSVer = Yii::app()->params['autoAddressJSVer'];
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
//	'disabled';
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . "/js/gozo/autoAddress.js?v=$autoAddressJSVer");
?>
<div class="">
    <div class="row">
        <div class="col-sm-12 col-lg-12 media-view">
            <div class="booking_panel">
                <ul class="nav nav-tabs arrow_box hidden-xs font-sm not-active" id="myTab">
                    <li class="ltab active tabcolor_1" id="l1">
                        <a data-toggle="tab" href="#menu1"><span id="btype">Select Trip</span></a></li>
                    <li class="ltab <?= $disabled ?> tabcolor_2" id="l2">
                        <a data-toggle="tab" href="#menu2"><span id="bdate">Select Dates of Travel</span></a></li>
                    <li class="ltab <?= $disabled ?> tabcolor_3" id="l3">
                        <a data-toggle="tab" href="#menu3"><span id="bcabs">Select Service Type</span></a></li>
                    <li class="ltab <?= $disabled ?> tabcolor_4" id="l4">
                        <a data-toggle="tab" href="#menu4"><span id="binfo">Booking Details</span></a></li>
                    <li class="ltab <?= $disabled ?> tabcolor_5" id="l5">
                        <a data-toggle="tab" href="#menu5"><span id="bpay">Review &amp; Pay</span></a></li>
                </ul>
                <ul class="nav nav-tabs arrow_box hidden-sm hidden-lg hidden-md ml15 mr15 mt20 n" id="myTab">
                    <li class="ltab active tabcolor_1" id="l11">
                        <a data-toggle="tab" href="#menu1"><span id="btype"><i class="fa fa-angle-double-right fa-2x"></i></span></a></li>
                    <li class="ltab <?= $disabled ?> tabcolor_2" id="l21">
                        <a data-toggle="tab" href="#menu2"><span id="bdate"><i class="fa fa-calendar fa-2x"></i></span></a></li>
                    <li class="ltab <?= $disabled ?> tabcolor_3" id="l31">
                        <a data-toggle="tab" href="#menu3"><span id="bcabs"><i class="fa fa-car fa-2x"></i></span></a></li>
                    <li class="ltab <?= $disabled ?> tabcolor_4" id="l41">
                        <a data-toggle="tab" href="#menu4"><span id="binfo"><i class="fa fa-th-list fa-2x"></i></span></a></li>
                    <li class="ltab <?= $disabled ?> tabcolor_5" id="l51">
                        <a data-toggle="tab" href="#menu5"><span id="bpay"><i class="fa fa-check fa-2x"></i></span></a></li>
                </ul>
                <div class="tab-content">
                    <div id="menu1" class="tab-pane fade in active">
						<?php
						$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
							'id'					 => 'bookingTrip',
							'enableClientValidation' => true,
							'clientOptions'			 => array(
								'validateOnSubmit'	 => true,
								'errorCssClass'		 => 'has-error',
								'afterValidate'		 => 'js:function(form,data,hasError){
                                    if(!hasError){
                                        $.ajax({
                                            "type":"POST",
                                            "dataType":"html",
                                            "url":"' . CHtml::normalizeUrl(Yii::app()->request->url) . '",
                                            "data":form.serialize(),
                                            "success":function(data1){
                                                var data = "";
                                                var isJSON = false;
                                                try {
                                                    data = JSON.parse(data1);
                                                    isJSON = true;
                                                } catch (e) {

                                                }
                                                if(!isJSON){
                                                    openTab(data1,2);
                                                    trackPage(\'' . CHtml::normalizeUrl(Yii::app()->createUrl('booking/route')) . '\');
                                                    disableTab(2);
                                                }
                                                else{
                                                    settings=form.data(\'settings\');
                                                    data2 = data1.error;
                                                    $.each (settings.attributes, function (i) {
                                                        $.fn.yiiactiveform.updateInput (settings.attributes[i], data2, form);
                                                    });
                                                    $.fn.yiiactiveform.updateSummary(form, data2);
                                                }
                                            },
                                        });
                                    }
                                }'
							),
							'enableAjaxValidation'	 => false,
							'errorMessageCssClass'	 => 'help-block',
							'htmlOptions'			 => array(
								'class'			 => 'form-horizontal',
								'autocomplete'	 => 'off',
							),
						));
						/* @var $form TbActiveForm */
						?>
						<?= $form->errorSummary($model); ?>
						<?= CHtml::errorSummary($model); ?>
                        <input type="hidden" id="step" name="step" value="0">
						<?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id11', 'class' => 'clsBkgID']); ?>
						<?= $form->hiddenField($model, 'hash', ['id' => 'hash11', 'class' => 'clsHash']); ?>
                        <h3 class="text-center"> 
                            <div class="row">
                                <div class="col-xs-12 col-sm-10 col-md-8 col-lg-6 float-none marginauto mt20 hidden-xs">
                                    <div class="row trip_btn" data-toggle="buttons">
                                        <div class="col-xs-6 p5">
                                            <div class="button raised clickable <?= $active1 ?>">
                                                <input type="checkbox" class="toggle newtriptype" name="BookingTemp[bkg_booking_type]" onclick="submitTriptype(1)" value="1" id="BookingTemp_bkg_booking_type_0" > <div class="anim"></div><span>One way Trip</span>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 p5">
                                            <div class="button raised clickable <?= $active2 ?>">
                                                <input type="checkbox" class="toggle newtriptype" name="BookingTemp[bkg_booking_type]" onclick="submitTriptype(2)" value="2" id="BookingTemp_bkg_booking_type_1" > <div class="anim"></div><span>Round Trip</span>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 p5">
                                            <div class="button raised clickable <?= $active3 ?>">
                                                <input type="checkbox" class="toggle newtriptype" name="BookingTemp[bkg_booking_type]" id="BookingTemp_bkg_booking_type_2" onclick="submitTriptype(3)" value="3"> <div class="anim"></div><span>Multi city Trip</span>
                                            </div>
                                        </div>
                                        <div class="col-xs-6 p5">
                                            <div class="button raised clickable <?= $active4 ?>">
                                                <input type="checkbox" class="toggle newtriptype" name="BookingTemp[bkg_booking_type]" id="BookingTemp_bkg_booking_type_3" onclick="submitTriptype(4)" value="4"> <div class="anim"></div><span>Airport Transfer</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-11 hidden-lg hidden-md hidden-sm float-none marginauto">
                                    <div class="row trip_btn" data-toggle="buttons">
                                        <div class="col-xs-12 col-sm-6 p5">
                                            <div class="button raised clickable <?= $active1 ?>">
                                                <input type="checkbox" class="toggle newtriptype" name="BookingTemp[bkg_booking_type]" onclick="submitTriptype(1)" value="1" id="BookingTemp_bkg_booking_type_0" > <div class="anim"></div><span>One way Trip</span>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 p5">
                                            <div class="button raised clickable <?= $active2 ?>">
                                                <input type="checkbox" class="toggle newtriptype" name="BookingTemp[bkg_booking_type]" onclick="submitTriptype(2)" value="2" id="BookingTemp_bkg_booking_type_1" > <div class="anim"></div><span>Round Trip</span>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 p5">
                                            <div class="button raised clickable <?= $active3 ?>">
                                                <input type="checkbox" class="toggle newtriptype" name="BookingTemp[bkg_booking_type]" id="BookingTemp_bkg_booking_type_2" onclick="submitTriptype(3)" value="3"> <div class="anim"></div><span>Multi city Trip</span>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 p5">
                                            <div class="button raised clickable <?= $active4 ?>">
                                                <input type="checkbox" class="toggle newtriptype" name="BookingTemp[bkg_booking_type]" id="BookingTemp_bkg_booking_type_3" onclick="submitTriptype(4)" value="4"> <div class="anim"></div><span>Airport Transfer</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<?
//=
//   $form->radioButtonListGroup($model, 'bkg_booking_type', ['label' => '', 'widgetOptions' => array('data' => $bType,'htmlOption'=>['class'=>'btn-group btn-group-justified',]), 'inline' => true]);
							?></h3>


						<?php $this->endWidget(); ?>
                    </div>
                    <div id="menu2" class="tab-pane fade">
						<?php
						if ($_REQUEST['step'] === '0')
						{
							$this->renderPartial('route', ['model' => $model], FALSE, false);
						}
						?>
                    </div>
                    <div id="menu3" class="tab-pane fade">
						<?php
						if (isset($_GET['id']) && $_GET['id'] != '')
						{
							$this->renderPartial('match_flexxi', ['model' => $model], FALSE, false);
						}
						?>
                    </div>
                    <div id="menu4" class="tab-pane fade">
                    </div>
                    <div id="menu5" class="tab-pane fade">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
//$this->renderPartial('popupform', ['model' => $model]);
?>
<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?key=<?= $api ?>&libraries=places&"></script>
<script type="text/javascript">
													$(document).ready(function () {
														//     fakewaffle.responsiveTabs(['xs', 'sm']);
													
														disableTab(1);
<?php
if ($_REQUEST['step'] === '0')
{
	
	?>
															disableTab(2);
															$('.nav-tabs a[href="#menu2"]').tab("show");
	<?
}
?>
<?php if (isset($_GET['id']) && $_GET['id'] != '')
{
	?>
															$('#menu1').removeClass('in');
															$('#menu1').removeClass('active');
															$('#menu3').addClass('in');
															$('#menu3').addClass('active');
															$("#myTab.nav-tabs #l1 a").css('pointer-events', 'none');
															$("#myTab.nav-tabs #l1").removeClass('active');
															$("#myTab.nav-tabs #l2 a").css('pointer-events', 'none');
															//$("#myTab.nav-tabs #l4").addClass('disabled');
															//$("#myTab.nav-tabs #l5").addClass('disabled');
															$("#myTab.nav-tabs #l3").removeClass('disabled');
															$("#myTab.nav-tabs #l3").addClass('active');


<? } ?>
													});
													var rtrip = false;
													function openTab(html, tab) {
														$menu = 'menu' + tab;
														$('.nav-tabs a[href="#menu' + tab + '"]').tab("show");
														$('#collapse-myTab a[href="#collapse-menu' + tab + '"]').click();
														$("#" + $menu).html(html);
														$('.tab-pane').removeClass('active in');
														$('#' + $menu).addClass('active in');
														$('.ltab').removeClass('active');
														$('#l' + tab).addClass('active');
														$menu1 = 'menu' + tab;
														$('.nav-tabs a[href="#menu' + tab + '1' + '"]').tab("show");
														$('#collapse-myTab a[href="#collapse-menu' + tab + '1' + '"]').click();
														$("#" + $menu1).html(html);
														$('#' + $menu1).addClass('active in');
														$('#l' + tab + '1').addClass('active');
													}
													$(document).on('show.bs.tab', 'a[data-toggle="tab"]', function (e) {
														var $target = $(e.target);
														if ($target.parent().hasClass('disabled')) {
															return false;
														}
													});
													function refreshType(tp) {
														oFormObject = document.forms['bookingform'];
														oFormObject.elements["BookingTemp[bkg_booking_type]"].value = tp;
													}

													function disableTab(tabNo) {
														
														for (i = 1; i <= 5; i++)
														{
															if (i <= tabNo)
															{
																console.log($("#myTab.nav-tabs #l" + i + "1"));
																$("#myTab.nav-tabs #l" + i + "1").removeClass('disabled');
																$("#myTab.nav-tabs #l" + i + "").removeClass('disabled');
															}
															else {
																$("#myTab.nav-tabs #l" + i + "1").addClass('disabled');
																$("#myTab.nav-tabs #l" + i + "").addClass('disabled');
															}
														}
														if ($('#BookingTemp_bkg_booking_type').val() == 5) {
															$("#myTab.nav-tabs #l1").addClass('disabled');
														}
													}
													$(".nav .disabled>a").on("click", function (e) {
														e.preventDefault();
														return false;
													});
													function refreshRDetails(bdata) {

														jQuery.ajax({type: 'GET', url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/route1', ['view' => 'rtview'])) ?>',
															data: {"bdata": bdata},
															success: function (data)
															{
																$('#rt1').html(data);
															}
														});
													}
													function refreshMultiDetails(bdata)
													{
														jQuery.ajax({type: 'GET', url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/route2', ['view' => 'rtview'])) ?>',
															data: {"bdata": bdata},
															success: function (data)
															{
																$('#rt1').html(data);
															}
														});
													}
													function refreshAdditionalDetails(bdata) {

														jQuery.ajax({type: 'GET', url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/additionaldetail', ['view' => 'conview'])) ?>',
															data: {"bdata": bdata},
															success: function (data)
															{
																$('#ad1').html(data);
																$('#BookingTemp_bkg_user_name').focus();
															}
														});
													}
													function refreshLasttab(fdata) {
														jQuery.ajax({type: 'GET', url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('booking/finalbook', ['view' => 'booksummary'])) ?>',
															data: {"bdata": fdata},
															success: function (data)
															{
																$('#ls').html(data);
																$('#link').focus();
															}
														});
													}
													function submitTriptype(trip) {
														$('#bookingTrip').submit();
													}


													$("#bkg_booking_type_0_1").click(function () {
														s_oneway();

													});
													$("#bkg_booking_type_1_1").click(function () {
														s_roundway();
													});
													$("#bkg_booking_type_2_1").click(function () {
														s_multiway();
													});
													$("#bkg_booking_type_3_1").click(function () {
														s_airportway();
													});
													function s_oneway() {
														$("#bkg_booking_type_0_1_1").css('color', 'white');
														$("#bkg_booking_type_1_1_1").css('color', 'black');
														$("#bkg_booking_type_2_1_1").css('color', 'black');
														$("#bkg_booking_type_3_1_1").css('color', 'black');
													}

													function s_roundway() {
														$("#bkg_booking_type_1_1_1").css('color', 'white');
														$("#bkg_booking_type_0_1_1").css('color', 'black');
														$("#bkg_booking_type_2_1_1").css('color', 'black');
														$("#bkg_booking_type_3_1_1").css('color', 'black');
													}

													function s_multiway() {
														$("#bkg_booking_type_2_1_1").css('color', 'white');
														$("#bkg_booking_type_1_1_1").css('color', 'black');
														$("#bkg_booking_type_0_1_1").css('color', 'black');
														$("#bkg_booking_type_3_1_1").css('color', 'black');
													}

													function s_airportway() {
														$("#bkg_booking_type_3_1_1").css('color', 'white');
														$("#bkg_booking_type_1_1_1").css('color', 'black');
														$("#bkg_booking_type_2_1_1").css('color', 'black');
														$("#bkg_booking_type_0_1_1").css('color', 'black');
													}
													
													
</script>
<?
if ($btyp == 1)
{
	?>
	<script type="text/javascript">
		$("#t1").show();
		//		openTab('One way Trip', 1, 2);
		//		disableTab(2);
	</script>
	<?
}
if ($btyp == 2 || $btyp == 3)
{
	?>
	<script type="text/javascript">

		$tshow = (<?= $btyp ?> == 2) ? "Round Trip" : "Multi way Trip";
		$("#t1").hide();
		$("#t2").show();
		openTab($tshow,<?= $btyp ?>, 2);
		disableTab(2);
	</script>
	<?
}
?>
<script>
	$('#myTab li').click(function (e) {
		if (e.currentTarget.id == 'l3' || e.currentTarget.id == 'l2')
		{
			$('#BookingTemp_bkg_flexxi_type').val(0);
		}
	});
</script>