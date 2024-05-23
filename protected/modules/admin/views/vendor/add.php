<?php
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/jquery.mask.min.js');
Yii::app()->clientScript->registerCssFile(ASSETS_URL . '/plugins/form-select2/select2.css');

$modelContact = $uvrcontact['uvr_id'];
if ($model->vnd_id != '' || $model->vnd_uvr_id != "")
{
    $email = ContactEmail::model()->getContactEmailById($model->vnd_contact_id);
    $phone = ContactPhone::model()->getContactPhoneById($model->vnd_contact_id);
}
$stateList        = array("" => "Select state") + CHtml::listData(States::model()->findAll('stt_active = :act AND stt_country_id = :con order by stt_name', array(':act' => '1', ':con' => '99')), 'stt_id', 'stt_name');
$selectizeOptions = ['create'             => false, 'persist'            => true, 'selectOnTab'        => true,
    'createOnBlur'       => true, 'dropdownParent'     => 'body',
    'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'      => 'id',
    'openOnFocus'        => true, 'preload'            => false,
    'labelField'         => 'text', 'valueField'         => 'id', 'searchField'        => 'text', 'closeAfterSelect'   => true,
    'addPrecedence'      => false,];
?>
<style type="text/css">

    .select2-container-multi .select2-choices {
        min-height: 50px;
    }
    .new-booking-list .form-horizontal .form-group{
        margin-left: 0;
        margin-right: 0;
    }
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type="number"] {
        -moz-appearance: textfield;
    }
    label.error {
        margin-top: 0;
    }
    .form-group{ display: inline-block;}
</style>
<div class="row">
    <div class="col-lg-10 col-md-8 col-sm-10 pb10 new-booking-list" style="float: none; margin: auto">
        <div style="text-align:center;" class="col-xs-12">

            <?php
            $display = "";
            if ($model->vnd_active == 0)
            {
                $display = "pointer-events:none;";
                echo "<h2 style='color:#ff0000;'>Vendor is deleted.</h2>";
            }
            if ($status == "emlext")
            {
                echo "<span style='color:#ff0000;'>This email address is already registered. Please try again using a new email address.</span>";
            }
            elseif ($status == "added")
            {
                echo "<span style='color:#00aa00;'>Driver added successfully.</span>";
            }
            ?>


        </div>

        <?php
        if ($message != '')
        {
            echo '<h2>' . $message . '</h2>';
        }
        else
        {

            $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
                'id'                     => 'vendors-register-form', 'enableClientValidation' => true,
                'clientOptions'          => array(
                    'validateOnSubmit' => true,
                    'errorCssClass'    => 'has-error'
                ),
                // Please note: When you enable ajax validation, make sure the corresponding
                // controller action is handling ajax validation correctly.
                // See class documentation of CActiveForm for details on this,
                // you need to use the performAjaxValidation()-method described there.
                'enableAjaxValidation'   => false,
                'errorMessageCssClass'   => 'help-block',
                'htmlOptions'            => array(
                    'class'   => 'form-horizontal', 'enctype' => 'multipart/form-data'
                ),
            ));
            /* @var $form TbActiveForm */
            ?>
            <?php
            echo $form->hiddenField($model, 'vnd_contact_id');
            echo $form->hiddenField($model, 'vnd_contact_name');
            echo $form->hiddenField($model, 'vnd_active');
            ?>
            <div class="row" style="<?php echo $display; ?>">           
                <div class="col-xs-12 col-md-12 col-lg-6 new-booking-list">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default panel-border">
                                <div class="panel-body">
                                    <?php echo CHtml::errorSummary($model); ?>
                                    <h3 class="pb10 mt0">Personal Information</h3>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                            <label>Name </label>
                                            <?php
                                            echo $form->textFieldGroup($model, 'vnd_name', array('label' => '', 'widgetOptions' => array('htmlOptions' => array('placeholder' => '', 'readOnly' => 'readOnly'))));
                                            ?>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <span id ="contactReport"></span>
                                        <div class="col-xs-12">
                                            <label>Vendor Type</label>
                                            <?php
                                            if ($model->vnd_uvr_id != "")
                                            {
                                                $model->vnd_cat_type = 2;
                                            }
                                            ?>
                                            <?=
                                            $form->radioButtonListGroup($model, 'vnd_cat_type', array(
                                                'label'         => '', 'widgetOptions' => array(
                                                    'data' => array('1' => 'DCO', '2' => 'Vendor'),
                                                ), 'inline'        => true)
                                            );
                                            ?>
                                        </div>
                                    </div>

                                    <div class="row hide" id="contactSelectDetails">
                                        <div class="col-xs-12 contact_div_details"> <label>Contact Info</label></div>
                                        <div class="col-xs-12 col-sm-6 contact_div_details hide" style="background-color: lightgray;" >

                                            <? //= $form->textFieldGroup($model, 'vnd_contact_name', array('label' => '','widgetOptions' => array('htmlOptions' => array('placeholder' => 'Contact Name','readonly' => 'readonly'))))  ?>
                                            <label id="contactDetails"></label>
                                        </div>
                                        <?php
                                        if ($isNew != 'Approve')
                                        {
                                            ?> 
                                            <div class="col-xs-4 col-sm-3 viewcontctsearch hide" style="<?= $contactViewSearch; ?>;">
                                                <label>&nbsp;</label>
                                                <div><button class="btn btn-info viewContact" type="button">View Contact</button></div>
                                            </div>

                                            <?php
                                            if ($model->vnd_id != "")
                                            {
                                                ?>

                                                <div class="col-xs-4 col-sm-3">
                                                    <label>&nbsp;</label>
                                                    <div>
                                                        <a class="btn btn-info modifyContact" target="_blank" href="<?= Yii::app()->createUrl('admin/contact/form', array('ctt_id' => $model->vndContact->ctt_id, 'type' => 3)) ?>" >Modify Contact</a></div>
                                                </div>
                                            <?php } ?>
                                            <div class="col-xs-4 col-sm-3 ">
                                                <label>&nbsp;</label>
                                                <div><button class="btn btn-info searchContact" type="button">Select Contact</button></div>
                                            </div>
                                            <div class="col-xs-4 col-sm-3 ">
                                                <label>&nbsp;</label>
                                                <div> 
                                                    <a class="btn btn-primary  weight400 font-bold addContact" title="Add Contact">Add Contact</a>
                                                </div>
                                            </div>
                                            <?
                                            }
                                            else
                                            {
                                            ?>
                                            <div class="col-xs-4 col-sm-3">
                                                <label>&nbsp;</label>
                                                <div>
                                                    <a class="btn btn-info modifyContact" target="_blank" href="<?= Yii::app()->createUrl('admin/contact/form', array('ctt_id' => $model->vnd_contact_id, 'type' => 3)) ?>" >Modify Contact</a></div>
                                            </div>
                                            <div class="col-xs-4 col-sm-3 ">
                                                <label>&nbsp;</label>
                                                <div><button class="btn btn-info searchContact" type="button">Select Contact</button></div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="panel panel-default panel-border">
                                <div class="panel-body">
                                    <h3 class="pb10 mt0">Inventory Information</h3>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 ">
                                            <div class="row ">
                                                <div class="col-xs-12">
                                                    <label>Sedan Count</label>
                                                    <?= $form->textFieldGroup($modelVendPref, 'vnp_sedan_count', array('label' => '')) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 ">

                                            <label>Compact Count</label>

                                            <?= $form->textFieldGroup($modelVendPref, 'vnp_compact_count', array('label' => '')) ?>

                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 ">

                                            <label>SUV Count</label>
                                            <?= $form->textFieldGroup($modelVendPref, 'vnp_suv_count', array('label' => '')) ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12">
                            <div class="panel panel-default panel-border">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 ">

                                            <label>Relationship Manager</label>
                                            <?php
                                            $adminList = Admins::model()->getJSON();
                                            $this->widget('booster.widgets.TbSelect2', array(
                                                'model'          => $model,
                                                'attribute'      => 'vnd_rm',
                                                'val'            => $model->vnd_rm,
                                                'asDropDownList' => FALSE,
                                                'options'        => array('data' => new CJavaScriptExpression($adminList), 'allowClear' => true),
                                                'htmlOptions'    => array('style' => 'width:100%', 'placeholder' => 'Select Relationship Manager')
                                            ));
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 col-lg-6">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="panel panel-default panel-border">
                                <div class="panel-body">
                                    <h3 class="pb10 mt0">Account Information</h3>


                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 ">                                        
                                            <?= $form->numberFieldGroup($modelVendStats, 'vrs_security_amount', array('widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'min' => 0]))) ?>
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <?
                                            if ($modelVendStats->vrs_security_receive_date)
                                            {
                                            $modelVendStats->vrs_security_receive_date1 = DateTimeFormat::DateToDatePicker($modelVendStats->vrs_security_receive_date);
                                            }
                                            ?>
                                            <div class="form-group">
                                                <?=
                                                $form->datePickerGroup($modelVendStats, 'vrs_security_receive_date1', array('label'         => 'Security Receive Date', 'widgetOptions' => array('options'     => array('autoclose' => true,
                                                            'endDate'   => '+0d', 'format'    => 'dd/mm/yyyy'), 'htmlOptions' => array(
                                                        )), 'groupOptions'  => ['class' => 'm0'], 'prepend'       => '<i class="fa fa-calendar"></i>'));
                                                ?>                            
                                            </div> 
                                        </div>
                                    </div>

									<div class="row"> 
											<div class="col-xs-12 col-sm-6 ">
												<?= $form->numberFieldGroup($modelVendPref, 'vnp_min_sd_req_amt', array('widgetOptions' => array('htmlOptions' => ['class' => 'form-control', 'min' => 0]))) ?>
											</div>
											<div class="col-xs-12 col-sm-6 "> </div>
										</div>
								
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 "> 
                                            <?php
                                            if ($modelVendStats->isNewRecord)
                                            {
                                                $modelVendStats->vrs_credit_limit = 500;
                                            }
                                            ?>
                                            <?= $form->numberFieldGroup($modelVendStats, 'vrs_credit_limit', array()) ?>
                                        </div>
                                        <div class="col-xs-12 col-sm-6">                                       
                                            <?= $form->numberFieldGroup($modelVendStats, 'vrs_credit_throttle_level', array('widgetOptions' => array('htmlOptions' => ['max' => 100]))) ?>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xs-12 col-sm-6 ">

                                            <label>Agreement date</label>
                                            <?php
                                            if ($model->vndAgreement->vag_soft_date)
                                            {
                                                $model->vnd_agreement_date1 = DateTimeFormat::DateTimeToDatePicker($model->vndAgreement->vag_soft_date);
                                            }
                                            echo $form->datePickerGroup($model, 'vnd_agreement_date1', array('label'         => '',
                                                'widgetOptions' => array('options' => array('autoclose' => true, 'format' => 'dd/mm/yyyy', 'autoclose' => true, 'endDate' => '+0d',))
                                            ));
                                            ?>
                                        </div>
                                        <div class="col-xs-12 col-sm-6">

                                            <label>Agreement file</label>
                                            <?= $form->fileFieldGroup($model, 'vnd_agreement_file_link', array('label' => '', 'widgetOptions' => array())); ?>

                                            <?php
                                            if ($model->vndAgreement->vag_soft_path != '')
                                            {
                                                $softPath     = VendorAgreement::getPathById($model->vndAgreement->vag_id, VendorAgreement::SOFT_PATH);
                                                ?><div class="row ">
                                                    <div class="col-xs-12 mb15">
                                                        <a href="<?= $softPath ?>" target="_blank"><?= $softPath; ?></a>
                                                    </div>
                                                </div>
                                                <? } ?>
                                            </div>
                                        </div>
                                        <div class="row mb10">
                                            <div class="col-xs-12 col-sm-6 ">
                                                <div class="row ">
                                                    <div class="col-xs-12">
                                                        <label>Home Zone (Select Home zone where vendor is located)</label>
                                                    </div>
                                                    <div class="col-xs-12 ">  <?php
                                        $zoneListJson = Zones::model()->getJSON();

                                        $this->widget('booster.widgets.TbSelect2', array(
                                            'model'          => $modelVendPref,
                                            'attribute'      => 'vnp_home_zone',
                                            'val'            => "{$modelVendPref->vnp_home_zone}",
                                            'asDropDownList' => FALSE,
                                            'options'        => array('data' => new CJavaScriptExpression($zoneListJson), 'allowClear' => true),
                                            'htmlOptions'    => array('style' => 'width:100%;', 'placeholder' => 'Home Zone')
                                        ));
                                                ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12 col-sm-6">
                                                <div class="row">
                                                    <div class="col-xs-12 "><label> One Way Zones (Accepted Zones)</label>
                                                    </div>
                                                    <div class="col-xs-12"> <?php
                                                $loc2           = Zones::model()->getZoneList();
                                                $SubgroupArray2 = CHtml::listData(Zones::model()->getZoneList(), 'zon_id', function ($loc2) {
                                                            return $loc2->zon_name;
                                                        });

//print_r($SubgroupArray2);
                                                $this->widget('booster.widgets.TbSelect2', array(
                                                    'name'        => 'vnp_accepted_zone',
                                                    'model'       => $modelVendPref,
                                                    'data'        => $SubgroupArray2,
                                                    'value'       => explode(',', $modelVendPref->vnp_accepted_zone),
                                                    'htmlOptions' => array(
                                                        'multiple'    => 'multiple',
                                                        'placeholder' => 'One Way Zones',
                                                        'width'       => '100%',
                                                        'style'       => 'width:100%',
                                                    ),
                                                ));
                                                ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb10">

                                            <div class="col-xs-12 col-sm-6">
                                                <div class="row">
                                                    <div class="col-xs-12 "><label>Excluded Cities</label></div>
                                                    <div class="col-xs-12">  <?php
                                                $this->widget('booster.widgets.TbSelect2', array(
                                                    'name'           => 'vnp_excluded_cities',
                                                    'model'          => $modelVendPref,
                                                    'asDropDownList' => FALSE,
                                                    'options'        => array('data' => new CJavaScriptExpression('[]'), 'multiple' => true),
                                                    // 'value' => explode(',', $modelVendPref->vnp_excluded_cities),
                                                    'htmlOptions'    => array(
                                                        'multiple'    => 'multiple',
                                                        'placeholder' => 'Excluded Cities',
                                                        'width'       => '100%',
                                                        'style'       => 'width:100%',
                                                    ),
                                                ));
                                                ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-xs-12 col-sm-6 ">
                                                <?php
                                                if ($modelVendPref->isNewRecord)
                                                {
                                                    $modelVendPref->vnp_is_attached  = 1;
                                                    $modelVendPref->vnp_booking_type = 2;
                                                }
                                                ?>

                                                <label> Is Attached(Y/N)</label> &nbsp;
                                                <?= $form->radioButtonListGroup($modelVendPref, 'vnp_is_attached', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Yes', 0 => 'No')), 'inline' => true), array(1 => 'checked')) ?>


                                            </div>
                                            <!--										<div class="col-xs-12 col-sm-6">
                                                                                                                                    <div class="row ">
                                                                                                                                            <div class="col-xs-12">
                                            
                                                                                                                                                    <label> Operates one-way(Y/N)</label> &nbsp;
                                                                                                                                                    <?//= $form->radioButtonListGroup($modelVendPref, 'vnp_booking_type', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Yes', 2 => 'No')), 'inline' => true)) ?>
                                            
                                                                                                                                            </div>
                                                                                                                                    </div>
                                                                                                                            </div>-->

                                        </div>
                                        <h3 class="pb10 mt0">Operating Services</h3>

                                        <div class="row">

                                            <div class="col-xs-12 col-sm-6 mt5 ">
                                                <?= $form->checkboxGroup($modelVendPref, 'vnp_oneway', array()) ?>
                                                 <b><?php
                                                 if($modelVendPref->vnp_oneway==-1)
                                                 {
                                                     echo " ( Not Approved )";
                                                 }
                                                 else if($modelVendPref->vnp_oneway==0)
                                                 {
                                                     echo " (Waiting For  Approval)";
                                                 }
                                                 else
                                                 {
                                                       echo " (Approved)";
                                                 }
                                                ?></b>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 mt5">
                                                <?= $form->checkboxGroup($modelVendPref, 'vnp_round_trip', array()) ?>
                                                 <b><?php
                                                 if($modelVendPref->vnp_round_trip==-1)
                                                 {
                                                     echo " ( Not Approved )";
                                                 }
                                                 else if($modelVendPref->vnp_round_trip==0)
                                                 {
                                                     echo " (Waiting For  Approval)";
                                                 }
                                                 else
                                                 {
                                                       echo " (Approved)";
                                                 }
                                                ?></b>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 mt5">
                                                <?= $form->checkboxGroup($modelVendPref, 'vnp_package', array()) ?>
                                                 <b><?php
                                                 if($modelVendPref->vnp_package==-1)
                                                 {
                                                     echo " (Not Approved)";
                                                 }
                                                 else if($modelVendPref->vnp_package==0)
                                                 {
                                                     echo " (Waiting For  Approval)";
                                                 }
                                                 else
                                                 {
                                                       echo " (Approved)";
                                                 }
                                                ?></b>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 mt5">
                                                <?= $form->checkboxGroup($modelVendPref, 'vnp_daily_rental', array()) ?>
                                                 <b><?php
                                                 if($modelVendPref->vnp_daily_rental==-1)
                                                 {
                                                     echo " (Not Approved)";
                                                 }
                                                 else if($modelVendPref->vnp_daily_rental==0)
                                                 {
                                                     echo " (Waiting For  Approval)";
                                                 }
                                                 else
                                                 {
                                                       echo " (Approved)";
                                                 }
                                                ?></b>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 mt5">
                                                <?= $form->checkboxGroup($modelVendPref, 'vnp_airport', array()) ?>
                                                 <b><?php
                                                 if($modelVendPref->vnp_airport==-1)
                                                 {
                                                     echo " (Not Approved)";
                                                 }
                                                 else if($modelVendPref->vnp_airport==0)
                                                 {
                                                     echo " (Waiting For  Approval)";
                                                 }
                                                 else
                                                 {
                                                       echo " (Approved)";
                                                 }
                                                ?></b>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 mt5">
                                                <?= $form->checkboxGroup($modelVendPref, 'vnp_tempo_traveller', array()) ?>
                                                 <b><?php
                                                 if($modelVendPref->vnp_tempo_traveller==-1)
                                                 {
                                                     echo " (Not Approved)";
                                                 }
                                                 else if($modelVendPref->vnp_tempo_traveller==0)
                                                 {
                                                     echo " (Waiting For  Approval)";
                                                 }
                                                 else
                                                 {
                                                       echo " (Approved)";
                                                 }
                                                ?></b>
                                            </div>
                                            <div class="col-xs-12 col-sm-6 mt5">
                                                <?= $form->checkboxGroup($modelVendPref, 'vnp_lastmin_booking', array()) ?> 
                                                <b><?php
                                                 if($modelVendPref->vnp_lastmin_booking==-1)
                                                 {
                                                     echo " (Not Approved)";
                                                 }
                                                 else if($modelVendPref->vnp_lastmin_booking==0)
                                                 {
                                                     echo " (Waiting For  Approval)";
                                                 }
                                                 else
                                                 {
                                                       echo " (Approved)";
                                                 }
                                                ?></b>
                                            </div>
                                        </div>

                                        <div class="row">                                       
                                            <div class="col-xs-12">
                                                <label>Notes</label><br>
                                                <?= $form->textAreaGroup($modelVendPref, 'vnp_notes', array('label' => '')) ?>
                                                <?= $form->hiddenField($model, 'login_type', array('value' => '2')); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="type" id="type" value="<?= $type; ?>" >
                    <div class="row">
                        <div class="col-xs-12 text-center pb10">
                            <?php
                            if ($isNew == 'Add')
                            {
                                ?>
                                <?php echo CHtml::submitButton($isNew, array('class' => 'btn  btn-primary')); ?>
                                <?php
                            }
                            else
                            {
                                ?>
                                <button type="button" class="btn  btn-primary" id="approve"><?= $isNew ?></button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php $this->endWidget(); ?>

                <?php
            }
            ?>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function ()
        {
            citylist();
    <?php
    if ($model->vnd_uvr_id != '' && $model->vnd_contact_id == '')
    {
        ?>
                var uvrid = '<?= trim($model->vnd_uvr_id, $character_mask = " \t\n\r\0\x0B") ?>';
                $href = '<?= Yii::app()->createUrl('admin/contact/form') ?>';

                jQuery.ajax({type: 'GET', url: $href, data: {"type": 3, "uvrid": uvrid},

                    success: function (data)
                    {
                        box = bootbox.dialog({
                            message: data,
                            title: 'Add Contact',
                            size: 'large',
                            onEscape: function ()
                            {
                                $('.bootbox.modal').modal('hide');
                            },
                        });
                    }});
    <?php } ?>

    <?php
    if ($model->vnd_cat_type == 2)
    {
        ?>
                $('#Vendors_vnd_cat_type_1').click();
                $('#Vendors_vnd_cat_type_1').attr('checked', 'checked');
                $('#Vendors_vnd_cat_type_1').parent().addClass('checked');
                $("#contactDetails").html('<?= trim($model->vnd_contact_name, $character_mask = " \t\n\r\0\x0B") . ' | ' . trim($email, $character_mask = " \t\n\r\0\x0B") . ' | ' . trim($phone, $character_mask = " \t\n\r\0\x0B") ?>');
                $('#Vendors_vnd_contact_id').val('<?= trim($model->vnd_contact_id, $character_mask = " \t\n\r\0\x0B") ?>');
                $('#Vendors_vnd_contact_name').val('<?= trim($model->vnd_contact_name, $character_mask = " \t\n\r\0\x0B") ?>');
                $(".contact_div_details").removeClass('hide');
                $(".viewcontctsearch").removeClass('hide');

        <?php
    }
    else if ($model->vnd_cat_type == 1)
    {
        ?>

                $('#Vendors_vnd_cat_type_0').click();
                $('#Vendors_vnd_cat_type_0').attr('checked', 'checked');
                $('#Vendors_vnd_cat_type_0').parent().addClass('checked');
                $("#contactDetails").html('<?= trim($model->vnd_contact_name, $character_mask = " \t\n\r\0\x0B") . ' | ' . trim($email, $character_mask = " \t\n\r\0\x0B") . ' | ' . trim($phone, $character_mask = " \t\n\r\0\x0B") ?>');
                $('#Vendors_vnd_contact_id').val('<?= trim($model->vnd_contact_id, $character_mask = " \t\n\r\0\x0B") ?>');
                $('#Vendors_vnd_contact_name').val('<?= trim($model->vnd_contact_name, $character_mask = " \t\n\r\0\x0B") ?>');
                $(".contact_div_details").removeClass('hide');
                $(".viewcontctsearch").removeClass('hide');
    <?php } ?>

    <?php
    if ($isNew == 'Approve')
    {
        ?>
                $("#Vendors_vnd_cat_type_0").attr('disabled', 'true');
                $("#Vendors_vnd_cat_type_1").attr('disabled', 'true');

    <?php } ?>

        });

        $('form').on('focus', 'input[type=number]', function (e)
        {
            $(this).on('mousewheel.disableScroll', function (e)
            {
                e.preventDefault();
            });
            $(this).on("keydown", function (event)
            {
                if (event.keyCode === 38 || event.keyCode === 40)
                {
                    event.preventDefault();
                }
            });
        });

        $('form').on('blur', 'input[type=number]', function (e)
        {
            $(this).off('mousewheel.disableScroll');
            $(this).off('keydown');
        });


        function checkDuplicateUser(obj, utype)
        {
            cttid = $(obj).val();
            var href = '<?= Yii::app()->createUrl("admin/vendor/checkuser"); ?>';
            $.ajax({
                "url": href,
                "type": "GET",
                "dataType": "json",
                "data": {cttid: cttid},
                "success": function (data)
                {
                    if (data == 1)
                    {
                        var conf = confirm('This Contact Address is alredy registered by vendor.')
                        if (conf)
                        {
                            if (utype == 'company')
                            {
                                $("#s2id_Contact_ctt_id").select2("val", "");
                            } else
                            {
                                $("#s2id_Contact_ctt_owner_id").select2("val", "");
                            }
                        }
                    }
                }
            });
        }

        $('#Vendors_vnd_phone').mask('9999999999');
        $('#vnp_home_zone').on("change", function ()
        {
            $('#vnp_excluded_cities').unbind("select2-focus").on("select2-focus", function ()
            {
                citylist();
            });
        });

        $('#vnp_accepted_zone').on("change", function ()
        {
            $('#vnp_excluded_cities').unbind("select2-focus").on("select2-focus", function ()
            {
                citylist();
            });
        });

        $excludedCities = [<?= $modelVendPref->vnp_excluded_cities ?>];
        $openOnFocus = false;

        $("#Vendors_vnd_cat_type_0,#Vendors_vnd_cat_type_1").click(function ()
        {
    <?php
    if ($model->vnd_id == "")
    {
        ?>
                $('#Vendors_vnd_contact_id').val('');
                $('#Vendors_vnd_contact_name').val('');
                $('#contactSelectDetails').removeClass('hide');
                $('.searchContact ').removeClass('hide');
                $('.contact_div_details').addClass('hide');
                $(".viewcontctsearch").addClass('hide');
        <?php
    }
    else
    {
        ?>
                $('#contactSelectDetails').removeClass('hide');
                $('.contact_div_details').removeClass('hide');
                $(".viewcontctsearch").hide();
                $(".addContact").hide();
    <?php } ?>
            if ($('#Vendors_vnd_cat_type_0').is(':checked'))
            {
                $('#ytVendors_vnd_cat_type').val($('#Vendors_vnd_cat_type_0').val());
            } else
            {
                $('#ytVendors_vnd_cat_type').val($('#Vendors_vnd_cat_type_1').val());
            }

        });

        $('#VendorPref_vnp_is_attached').change(function ()
        {
            if ($("#VendorPref_vnp_is_attached .checked input").val() == 1)
            {
                $('#VendorPref_vnp_booking_type input').prop('checked', true);
            }
        });

        function citylist()
        {
            //alert("fgdfg");
            var total = '0';
            var home = $('#vnp_home_zone').val();
            var accepted = $('#vnp_accepted_zone').val();
            if (home != null)
            {
                total = total + "," + home.toString();
            }
            if (accepted != null)
            {
                total = total + "," + accepted.toString();
            }
            //alert(total);
            var href = '<?= Yii::app()->createUrl("admin/vendor/zonecity"); ?>';
            $.ajax({
                "url": href,
                "type": "GET",
                "dataType": "json",
                "data": {zoneid: total},
                "success": function (data)
                {
                    $data2 = data;
                    $('#vnp_excluded_cities').select2('destroy');
                    $('#vnp_excluded_cities').select2({data: $data2, multiple: true});
                    $('#vnp_excluded_cities').unbind("select2-focus");
                    if ($openOnFocus)
                    {
                        $('#vnp_excluded_cities').select2("open");
                    } else
                    {
                        $('#vnp_excluded_cities').select2('val', $excludedCities);
                    }
                    $openOnFocus = true;
                }
            });
        }
        $sourceList = null;
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
                        data: {
                            // city: cityId
                        },
                        //  async: false,
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

        $('.viewContact').click(function ()
        {
            $href = '<?= Yii::app()->createUrl('admin/contact/form') ?>';
            var contid = $("#Vendors_vnd_contact_id").val();
            jQuery.ajax({type: 'GET',
                url: $href,
                data: {"ctt_id": contid, "type": 4, "userType": "Vendor"},
                success: function (data)
                {
                    box = bootbox.dialog({
                        message: data,
                        title: 'Contact View',
                        size: 'large',
                        onEscape: function ()
                        {

                            // user pressed escape
                        },
                    });
                }
            });
        });

        $('.searchContact').click(function ()
        {

            $href = '<?= Yii::app()->createUrl('admin/contact/list') ?>';
            var contype = $("input[name='Vendors[vnd_cat_type]']:checked").val();
            jQuery.ajax({type: 'GET',
                url: $href,
                data: {"ctype": contype, "vndtype": "asgncont", "userType": "Vendor"},
                success: function (data)
                {
                    box = bootbox.dialog({
                        message: data,
                        title: 'Contact List',
                        size: 'large',
                        onEscape: function ()
                        {
                            $('.bootbox.modal').modal('hide');
                        },
                    });
                }
            });
        });

        $('.addContact').click(function ()
        {
            $href = '<?= Yii::app()->createUrl('admin/contact/form') ?>';
            jQuery.ajax({type: 'GET', url: $href, data: {"type": 3},
                success: function (data)
                {
                    box = bootbox.dialog({
                        message: data,
                        title: 'Add Contact',
                        size: 'large',
                        onEscape: function ()
                        {
                            $('.bootbox.modal').modal('hide');
                        },
                    });
                }});
        });
        $('#approve').click(function ()
        {
            var agreement_approved = '<?= trim($model->vndAgreement->vag_approved, $character_mask = " \t\n\r\0\x0B") ?>';

        var agreement_approved = 1;// temporary modification due to resist unapprove

        if (agreement_approved == 0)
        {
            alert("Vendor Digital agreement not approved.");
        } else if (agreement_approved == 2)
        {
            alert("Vendor Digital Agreement Pending.");
        } else if (agreement_approved == 3)
        {
            alert("Vendor Digital Agreement Rejected.");
        } else
        {
            $('form').submit();
        }
    });
</script>
