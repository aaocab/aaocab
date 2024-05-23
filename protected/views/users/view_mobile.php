<?php
 $this->renderPartial('../index/head_mobile');
?>
<div>
	<?
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/js/jquery.mask.min.js');
    $cityList  = ['' => 'Select City'] + Cities::model()->getAllCityList();
    ?>
	<?
    $form      = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id'                     => 'view-form', 'enableClientValidation' => true,
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
            'class' => 'form-horizontal',
        ),
    ));
    /* @var $form TbActiveForm */
    ?>
	<div class="row">
        <div class="">
            <?php echo CHtml::errorSummary($model); ?>
        </div>
        <div class="col-xs-12 text-center">
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success" style="padding: 10px">
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
	<div class="page-content header-clear-large">
		<div class="content p0">
			<div class="content-padding box-text-1 box-text-3 mb10 ">
				<div class="line-f3 uppercase"><h3 class="mb0">My Profile</h3></div>
				<div class="line-f3">
					<div class="line-s3 p0 pt5">
						<div class="input-simple-1 has-icon input-green bottom-20"><em>First Name</em>
						<?= $form->textFieldGroup($model, 'usr_name', array('label' => '', 'class' => 'form-control border-radius')) ?>
						</div>
					</div>
					<div class="line-t3 p0 pt5">
						<div class="input-simple-1 has-icon input-green bottom-20"><em>Last Name</em>
							<?= $form->textFieldGroup($model, 'usr_lname', array('label' => '', 'class' => 'form-control border-radius')) ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="page-content">
		<div class="content p0">
			<div class="content-padding box-text-1 box-text-3 mb10 ">
				<div class="line-f3 uppercase"><h3 class="mb0">Contacts Details</h3></div>
				<div class="line-f3">
					<div class="input-simple-1 has-icon input-green bottom-10"><em>Email ID</em><i class="fa fa-user"></i>
						<?= $form->textFieldGroup($model, 'usr_email', array('class' => 'form-control border-radius', 'label' => '', 'widgetOptions' => array('htmlOptions' => array('readOnly' => true)))) ?>
					</div>
				</div>
				<div class="line-f3">
					<div class="select-box select-box-1 mt30 mb20">
						<strong>Required Field</strong>
						<em>Gender</em>
						 <?= $form->dropDownListGroup($model, 'usr_gender', array('label' => '', 'widgetOptions' => array('data' => array('' => 'Select Gender', '1' => 'Male', '2' => 'Female')))) ?>
					</div>
				</div>
				<div class="line-f3">
					<div class="input-simple-1 has-icon input-blue bottom-30"><strong>Required Field</strong><em>Phone Number (incl. country code)</em><i class="fa fa-phone"></i>
						<div class="from-left bottom-30">
						<?php
							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'            => $model,
								'attribute'        => 'usr_country_code',
								'useWithBootstrap' => true,
								"placeholder"      => "Code",
								'fullWidth'        => true,
								'htmlOptions'      => array('width' => '50%', ''
										),
								'defaultOptions'   => array(
									'create'             => false,
									'persist'            => true,
									'selectOnTab'        => true,
									'createOnBlur'       => true,
									'dropdownParent'     => 'body',
									'optgroupValueField' => 'id',
									'optgroupLabelField' => 'pcode',
									'optgroupField'      => 'pcode',
									'openOnFocus'        => true,
									'labelField'         => 'pcode',
									'valueField'         => 'pcode',
									'searchField'        => 'name',
									//   'sortField' => 'js:[{field:"order",direction:"asc"}]',
									'closeAfterSelect'   => true,
									'addPrecedence'      => false,
									'onInitialize'       => "js:function(){
						this.load(function(callback){
						var obj=this;                                
						 xhr=$.ajax({
				 url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('index/country')) . "',
				 dataType:'json',                  
				 success:function(results){
					 obj.enable();
					 callback(results.data);
					  $('#Users_usr_country_code')[0].selectize.setValue({$model->usr_country_code});
				 },                    
				 error:function(){
					 callback();
				 }});
						});

					   }",
									'render'             => "js:{
					 option: function(item, escape){  
					   return '<div><span class=\"\">' + escape(item.name) +'</span></div>';

				},
			option_create: function(data, escape){
			  $('#countrycode').val(data.pcode);

			 return '<div>' +'<span class=\"\">' + escape(data.pcode) + '</span></div>';
								  }
				}",
								),
							));
					 ?>
						</div>
						<div class="from-right bottom-30">
							<br>
							<?= $form->textFieldGroup($model, 'usr_mobile', array('label' => '', 'widgetOptions' => array('htmlOptions' => ['placeholder' => "Mobile No.(will be used for verification)"]))) ?>
						</div>
					</div>
				</div>
				<div class="line-f3">
					<div class="input-simple-1 has-icon input-green bottom-20"><em>Address Line1</em><i class="fas fa-map-marker-alt"></i>
						 <?= $form->textFieldGroup($model, 'usr_address1', array('label' => '', 'class' => 'form-control border-radius')) ?>
				</div>
				<div class="line-f3">
					<div class="input-simple-1 has-icon input-green bottom-20"><em>Address Line2</em><i class="fas fa-map-marker-alt"></i>
						<?= $form->textFieldGroup($model, 'usr_address2', array('label' => '', 'class' => 'form-control border-radius')) ?>
					</div>
				</div>
				<div class="line-f3">
					<div class="input-simple-1 has-icon input-green bottom-20"><em>Nearby Landmark</em><i class="fas fa-map-marker-alt"></i>
						<?= $form->textFieldGroup($model, 'usr_address3', array('label' => '', 'class' => 'form-control border-radius')) ?>  
					</div>
				</div>
				<div class="line-f3">
					<div class="input-simple-1 has-icon input-green bottom-20"><em>Zip Code</em>
						 <?= $form->textFieldGroup($model, 'usr_zip', array('label' => '', 'class' => 'border-radius')) ?>
					</div>
				</div>
				<div class="line-f3">
				<div class="select-box select-box-1 mt30 mb20">
						<em>Country</em>
						<?php
                        $criteria        = new CDbCriteria();
                        $criteria->order = 'country_order DESC';
                        $countryList     = CHtml::listData(Countries::model()->findAll($criteria), 'id', 'country_name');
                        $this->widget('ext.yii-selectize.YiiSelectize', array(
                            'model'            => $model,
                            'attribute'        => 'usr_country',
                            'data'             => $countryList,
                            'useWithBootstrap' => true,
                            'placeholder'      => 'Country',
                            'htmlOptions'      => array('onchange' => "changeCountry(this)"),
                            'defaultOptions'   => array(
                                'create'           => false,
                                'persist'          => false,
                                'createOnBlur'     => true,
                                'closeAfterSelect' => true,
                                'addPrecedence'    => true,
                            ),
                        ));
                        ?>
					</div>
				</div>
				<div class="line-f3">
					<div class="input-simple-1 has-icon input-green bottom-20"><em>State</em>
						 <?php
                        $this->widget('ext.yii-selectize.YiiSelectize', array(
                            'model'            => $model,
                            'attribute'        => 'usr_state',
                            'useWithBootstrap' => true,
                            "placeholder"      => "State",
                            'fullWidth'        => false,
                            'htmlOptions'      => array(
                            ),
                            'defaultOptions'   => array(
                                'create'             => false,
                                'persist'            => false,
                                'selectOnTab'        => true,
                                'createOnBlur'       => true,
                                'dropdownParent'     => 'body',
                                'optgroupValueField' => 'id',
                                'optgroupLabelField' => 'id',
                                'optgroupField'      => 'id',
                                'openOnFocus'        => true,
                                'labelField'         => 'text',
                                'valueField'         => 'id',
                                'searchField'        => 'text',
                                'closeAfterSelect'   => true,
                                'addPrecedence'      => false,
                                'onInitialize'       => "js:function(){
                            this.load(function(callback){
                            var obj=this;    
                            
                             xhr=$.ajax({
                     url:'" . CHtml::normalizeUrl(Yii::app()->createUrl('users/countrytostate', ['countryid' => $model->usr_country])) . "',
                     dataType:'json',                  
                     success:function(results){
                         obj.enable();
                         callback(results);
                          $('#Users_usr_state')[0].selectize.setValue({$model->usr_state});
                     },                    
                     error:function(){
                         callback();
                         }});
                     });
                     }",
                                'render'             => "js:{
                                    option: function(item, escape){
                         return '<div><span class=\"\">' + escape(item.text) +'</span></div>';
                           },
				option_create: function(data, escape){
                $('#countryname').val(escape(data.id));
                return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                }
                }",
                            ),
                        ));
                        ?>
					</div>
				</div>
				<div class="line-f3">
					<div class="input-simple-1 has-icon input-green bottom-20"><em>City</em>
						<?= $form->textFieldGroup($model, 'usr_city', array('label' => '')) ?>
					</div>
				</div>
				<div class="line-f3 text-center mb20">
					
					<button type="submit" class="uppercase btn-orange shadow-medium"  name="sub" value="Submit">Save</button>
				</div>
			</div>
		</div>
	</div>
	<?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $state = '<?= $model->usr_state ?>';
        $country = '<?= $model->usr_country ?>';
        if ($country == 99)
        {
            $('#statetextdiv').hide();
            $('#statediv').show();
        } else {
            $('#statetextdiv').show();
            $('#statediv').hide();
        }


        changestate($('#countryname').val());




        $(window).on('beforeunload', function () {
            $(window).scrollTop(0);
        });
        $('#<?= CHtml::activeId($model, 'usr_mobile') ?>').mask("9999999999");
        $('#<?= CHtml::activeId($model, 'usr_zip') ?>').mask("999999");
        //        $('#<?= CHtml::activeId($model, 'usr_alternative_phone') ?>').mask("(999) 999-9999");
        //        $("#mobileVerify").bind('click', mobileVerifyHandler);
        //        $("#emvrify").bind('click', emalVrifyHandler);
        //        $("#<?= CHtml::activeId($model, 'email') ?>").bind('change', emailVerifyHandler);



        $("#view-form").submit(function (event) {



        });

        $("#Users_usr_state1").change(function () {

            var stid = $("#Users_usr_state").val();
            var href2 = '<?= Yii::app()->createUrl("users/cityfromstate"); ?>';
            $.ajax({
                "url": href2,
                "type": "GET",
                "dataType": "json",
                "data": {"id": stid},
                "success": function (data1) {

                    $data2 = data1;
                    var placeholder = $('#<?= CHtml::activeId($model, "usr_city") ?>').attr('placeholder');
                    $('#<?= CHtml::activeId($model, "usr_city") ?>').select2({data: $data2, placeholder: placeholder});
                }
            });
        });
    });
    function changestate(selectizeControl)
    {

//        var href2 = '<?= Yii::app()->createUrl("users/countrytostate"); ?>';
//        $.ajax({
//            "url": href2,
//            "type": "GET",
//            "dataType": "json",
//            "data": {"countryid": selectizeControl},
//            "success": function(data1) {
//
//
//                $data2 = data1;
//                var placeholder = $('#<?= CHtml::activeId($model, "usr_state") ?>').attr('placeholder');
//                $('#<?= CHtml::activeId($model, "usr_state") ?>').select2({data: $data2, placeholder: placeholder});
//            }
//        });
    }

    function changeCountry(obj)
    {
        var selectize = $('#Users_usr_state')[0].selectize;
        var country = obj.value;
        if (country != 99)
        {
            $('#statetextdiv').show();
            $('#statediv').hide();
        } else {

            $('#statetextdiv').hide();
            $('#statediv').show();

            var href2 = '<?= Yii::app()->createUrl("users/countrytostate"); ?>';
            $.ajax({
                "url": href2,
                "type": "GET",
                "dataType": "json",
                "data": {"countryid": country},
                "success": function (data1) {
                    //$data2 = data1;

                    // var placeholder = $('#<?= CHtml::activeId($model, "usr_state") ?>').attr('placeholder');
                    // $('#<?= CHtml::activeId($model, "usr_state") ?>').selectize({data: $data2, placeholder: placeholder});

                    selectize.clearOptions();
                    selectize.addOption(data1);
                    selectize.refreshOptions(false);
                    //$('select').selectize(options);
                }
            });
        }
    }
</script>
