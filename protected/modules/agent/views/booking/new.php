
<?
$version = Yii::app()->params['siteJSVersion'];
$api = Yii::app()->params['googleBrowserApiKey'];

Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/gozo/promo.js?v=' . $version);
//$bType = Booking::model()->getBookingType(0, 'Trip');
$disabled = '';
$bkgtype = $model->bkg_booking_type;
$checked = "checked='checked'";
$active = "active";
$checked1 = $checked2 = $checked3 = $active1 = $active2 = $active3 = '';
if ($bkgtype == 1) {
    $checked1 = $checked;
    $active1 = $active;
} else {
    ${"checked$bkgtype"} = $checked;
    ${"active$bkgtype"} = $active;
}
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
//	'disabled';
?>
<style>
    .page-wrapper .page-wrapper-middle{ background: #fff;}
    .panel_listcom{ float: left!important; width: 100%; margin-top: 20px;}
    .panel_listcom>ul>li{ width: 20%; text-align: center;}
    .panel_listcom li a:hover{color: #ffffff!important;background-color: #36C6D3;}
    .panel_listcom li.active>a:hover{color: #36C6D3!important;background-color: #ffffff!important;}
    .panel_listcom li.disabled>a:hover{color: #aaaaaa!important;}
    .btn-default.active{color: #ffffff!important;background-color: #26A2AE!important;}
    .btn-default:focus{color: #ffffff!important;background-color: #36C6D3!important;}
    .btn-default:hover{color: #ffffff!important;background-color: #36C6D3!important;}   
    .nav>li>a {
        padding: 10px;
    }
    .nav_height{min-height:72px;}
    .rcorners2{
        text-align: center;
        color: #fff;
        background: #36C6D3;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: inline-block;
        padding-top: 5px;
        font-weight: bold;
        margin-right: 5px;
        margin-left: -10px
    }
    .iBkgTabs{
        color: #36C6D3!important;
    }
    .panel_listcom .nav-tabs > li.active > a, .nav-tabs > li.active > a:hover, .nav-tabs > li.active > a:focus{
        background: #ffffff!important;
    }
</style>
<div class="container-fluid p15 pt0 pb0">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel_listcom">
                <ul class="nav nav-tabs  hidden-xs font-sm" id="myTab">
                    <li class="ltab active" id="l1">
                        <a data-toggle="tab" href="#menu1" class=" nav_height"><span class="rcorners2">1</span><span id="btype">Select Trip</span></a>
                    </li>
                    <li class="ltab<?= $disabled ?> " id="l2">
                        <a data-toggle="tab" href="#menu2" class=" nav_height"><span class="rcorners2">2</span><span id="bdate">Select Dates of Travel</span></a></li>
                    <li class="ltab <?= $disabled ?> " id="l3">
                        <a data-toggle="tab" href="#menu3" class=" nav_height"><span class="rcorners2">3</span><span id="bcabs">Select Cab Type</span></a></li>
                    <li class="ltab <?= $disabled ?> " id="l4">
                        <a data-toggle="tab" href="#menu4" class=" nav_height"><span class="rcorners2">4</span><span id="binfo">Booking Details</span></a></li>
                    <li class="ltab <?= $disabled ?> " id="l5">
                        <a data-toggle="tab" href="#menu5" class=" nav_height"><span class="rcorners2">5</span><span id="bpay">Booking Confirmation</span></a></li>
                </ul>
                <ul class="nav nav-tabs  hidden-sm hidden-lg hidden-md" id="myTab">
                    <li class="ltab active " id="l11">
                        <a data-toggle="tab" href="#menu1"><span class="rcorners2">1</span><span id="btype"><i class = "fa fa-angle-double-right iBkgTabs"></i><i class="fa  fa-angle-double-right iBkgTabs"></i></span></a>
                    </li>
                    <li class="ltab <?= $disabled ?> " id="l21">
                        <a data-toggle="tab" href="#menu2"><span class="rcorners2">2</span><span id="bdate"><i class="fa fa-calendar iBkgTabs"></i></span></a></li>
                    <li class="ltab <?= $disabled ?> " id="l31">
                        <a data-toggle="tab" href="#menu3"><span class="rcorners2">3</span><span id="bcabs" style="font-size: 12px; line-height: 1.1em;"><i class="fa fa-car iBkgTabs"></i></span></a></li>
                    <li class="ltab <?= $disabled ?> " id="l41">
                        <a data-toggle="tab" href="#menu4"><span class="rcorners2">4</span><span id="binfo"><i class="fa fa-th-list iBkgTabs"></i></span></a></li>
                    <li class="ltab <?= $disabled ?> " id="l51">
                        <a data-toggle="tab" href="#menu5"><span class="rcorners2">5</span><span id="bpay"><i class="fa fa-check iBkgTabs"></i></span></a></li>
                </ul>
                <div class="tab-content">
                    <div id="menu1" class="tab-pane fade in active">
                        <?php
                        $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                            'id' => 'bookingTrip',
                            'enableClientValidation' => true,
                            'clientOptions' => array(
                                'validateOnSubmit' => true,
                                'errorCssClass' => 'has-error',
                                'afterValidate' => 'js:function(form,data,hasError){
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
                            'enableAjaxValidation' => false,
                            'errorMessageCssClass' => 'help-block',
                            'htmlOptions' => array(
                                'class' => 'form-horizontal',
                            ),
                        ));
                        /* @var $form TbActiveForm */
                        ?>
                        <?= $form->errorSummary($model); ?>
                        <?= CHtml::errorSummary($model); ?>
                        <input type="hidden" id="step" name="step" value="0">
                        <?= $form->hiddenField($model, 'bkg_id', ['id' => 'bkg_id1', 'class' => 'clsBkgID']); ?>
                        <?= $form->hiddenField($model, 'hash', ['id' => 'hash1', 'class' => 'clsHash']); ?>
                        <h3 class="text-center"> 
                            <div class="row">
                                <div class="col-xs-12 col-sm-8 col-sm-offset-2 mt30">
                                    <div class="btn-group btn-group-justified trip_btn111" data-toggle="buttons">
                                        <label class="btn btn-default <?= $active1 ?>">
                                            <input type="radio" name="BookingTemp[bkg_booking_type]" value="1" id="BookingTemp_bkg_booking_type_0" <?= $checked1 ?>> One way Trip
                                        </label>
                                        <label class="btn btn-default <?= $active2 ?>">
                                            <input type="radio" name="BookingTemp[bkg_booking_type]" value="2" id="BookingTemp_bkg_booking_type_1" <?= $checked2 ?>> Round Trip
                                        </label>
                                        <label class="btn btn-default <?= $active3 ?>">
                                            <input type="radio" name="BookingTemp[bkg_booking_type]" id="BookingTemp_bkg_booking_type_2" value="3" <?= $checked3 ?>> Multi city Trip
                                        </label>
                                        <label class="btn btn-default <?= $active4 ?>">
                                            <input type="radio" name="BookingTemp[bkg_booking_type]" id="BookingTemp_bkg_booking_type_2" value="4" <?= $checked4 ?>> Airport Transfer
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <?
                            //=
                            //   $form->radioButtonListGroup($model, 'bkg_booking_type', ['label' => '', 'widgetOptions' => array('data' => $bType,'htmlOption'=>['class'=>'btn-group btn-group-justified',]), 'inline' => true]);
                            ?></h3>

                        <div class="text-center mt50 mb50">
                            <?= CHtml::submitButton('NEXT', array('class' => 'btn btn-success btn-lg pl40 pr40')); ?>
                        </div>
                        <?php $this->endWidget(); ?>
                    </div>
                    <div id="menu2" class="tab-pane fade">
                        <?php
                        if ($_REQUEST['step'] === '0') {
                            $this->renderPartial('route', ['model' => $model], FALSE, false);
                        }
                        ?>
                    </div>
                    <div id="menu3" class="tab-pane fade"></div>
                    <div id="menu4" class="tab-pane fade"></div>
                    <div id="menu5" class="tab-pane fade"></div>
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
if ($_REQUEST['step'] === '0') {
    ?>
            disableTab(2);
            $('.nav-tabs a[href="#menu2"]').tab("show");
    <?
}
?>
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
                $("#myTab.nav-tabs #l" + i + "1").removeClass('disabled');
                $("#myTab.nav-tabs #l" + i + "").removeClass('disabled');
            } else {
                $("#myTab.nav-tabs #l" + i + "1").addClass('disabled');
                $("#myTab.nav-tabs #l" + i + "").addClass('disabled');
            }
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


</script>
<?
if ($btyp == 1) {
    ?>
    <script type="text/javascript">
        $("#t1").show();


        //		openTab('One way Trip', 1, 2);
        //		disableTab(2);
    </script>
    <?
}
if ($btyp == 2 || $btyp == 3) {
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