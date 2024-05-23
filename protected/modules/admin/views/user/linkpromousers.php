<?php
$pageno		 = Yii::app()->request->getParam('page');
$promoModel	 = Promos::model()->findByPk($promoId);
?>
<?php
$datefrom	 = $promoUserModel->pru_valid_from != '' ? $promoUserModel->pru_valid_from : date('Y-m-d H:i:s');
$dateTo		 = $promoUserModel->pru_valid_upto != '' ? $promoUserModel->pru_valid_upto : date('Y-m-d H:i:s', strtotime('+1 year 6am'));
?>
<div class="row" style="text-align: center;">
	<label style="font-size: 18px;font-weight: bold;">Promo Code:- <?= $promoModel->prm_code; ?></label>
</div>
<div class="row">
    <div class="col-sm-offset-1 col-md-offset-2 col-md-8 col-sm-10 col-xs-12">
        <div class="panel panel-white">
			<?php
			$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'searchform', 'enableClientValidation' => true,
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
			<input type="hidden" value="<?= $promoId ?>" id="promoId" name="promoid">
			<input type="hidden" value="<?= Yii::app()->request->baseUrl ?>" id="baseUrl">
            <div class="panel-body">
                <div class="row mt10" >
                    <div class="col-xs-6 col-sm-4 form-group text-center">
						<?=
						$form->textFieldGroup($model, 'search_name', array('label'			 => '',
							'htmlOptions'	 => array('placeholder' => 'Name'),
							'widgetOptions'	 => ['htmlOptions' => ['placeholder' => 'Name']]))
						?>
                    </div>
                    <div class="col-xs-6 col-sm-3 form-group text-center">
						<?=
						$form->textFieldGroup($model, 'search_phone', array('label'			 => '',
							'htmlOptions'	 => array('placeholder' => 'Phone'),
							'widgetOptions'	 => ['htmlOptions' => ['placeholder' => 'Phone']]))
						?>
                    </div>
                    <div class="col-xs-6 col-sm-3 form-group text-center">
						<?=
						$form->textFieldGroup($model, 'search_email', array('label'			 => '',
							'htmlOptions'	 => array('placeholder' => 'Email'),
							'widgetOptions'	 => ['htmlOptions' => ['placeholder' => 'Email']]))
						?>
                    </div>
                    <div class="col-xs-6 col-sm-2 form-group">
                        <button class="btn btn-info full-width" type="submit"  name="Search">Search</button>
                    </div>
                </div>
            </div>
			<?php $this->endWidget(); ?>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-md-12 col-lg-12" style="float: none; margin: auto">
		<div class="panel">
			<div class="panel panel-body">
				<?php
				$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'rate-form', 'enableClientValidation' => true,
					'clientOptions'			 => array(
						'validateOnSubmit'	 => true,
						'errorCssClass'		 => 'has-error'
					),
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'htmlOptions'			 => array(
						'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
					),
				));
				/* @var $form TbActiveForm */
				?>
				<?= $form->hiddenField($promoUserModel, 'pru_ref_id', array('value' => $refId)); ?>
				<?= $form->hiddenField($promoUserModel, 'pru_promo_id', array('value' => $promoId)); ?>
				<input type="hidden" value="<?= $pruId ?>" name="pruId">
				<div class="row mb15 hide">
					<div class="col-xs-12 col-md-6">
						<div class="form-group">
							<label class="control-label">Type</label>
							<?php
							$userTypeArr = PromoUsers::$userCategoty;
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $promoUserModel,
								'attribute'		 => 'pru_ref_type',
								'val'			 => $promoUserModel->pru_ref_type,
								'data'			 => $userTypeArr,
								//'asDropDownList' => FALSE,
								//'options'		 => array('data' => new CJavaScriptExpression($weekDaysArr)),
								'htmlOptions'	 => array('style'			 => 'width:100%', 'multiple'		 => '',
									'placeholder'	 => 'Select Type')
							));
							?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-3">
						<?= $form->numberFieldGroup($promoUserModel, 'pru_use_max', array('label' => 'Use Maximum', 'widgetOptions' => array('htmlOptions' => ['placeholder' => 'Use max']))) ?>
					</div>
					<div class="col-xs-12 col-md-3" style="padding: 20px;">
						<?= $form->radioButtonListGroup($promoUserModel, 'pru_auto_apply', array('label' => 'Auto Apply', 'widgetOptions' => array('htmlOptions' => [], 'data' => [0 => 'No', 1 => 'Yes']), 'inline' => true)) ?>
					</div>

					<div class="col-xs-12 col-md-3"><label>Offer Valid From</label>
						<div class="row ">
							<div class="col-xs-12 col-sm-7 pr5">
								<?=
								$form->datePickerGroup($promoUserModel, 'pru_valid_from_date', array('label'			 => '',
									'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($datefrom)))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>
							</div>
						</div>
					</div>

					<div class="col-xs-12 col-md-3"><label>Offer Valid Upto</label>
						<div class="row">
							<div class="col-xs-12 col-sm-7 pr5">
								<?=
								$form->datePickerGroup($promoUserModel, 'pru_valid_upto_date', array('label'			 => '',
									'widgetOptions'	 => array('options' => array('autoclose' => true, 'startDate' => date(), 'format' => 'dd/mm/yyyy'), 'htmlOptions' => array('required' => true, 'value' => date('d/m/Y', strtotime($dateTo)))), 'prepend'		 => '<i class="fa fa-calendar"></i>'));
								?>
							</div>
						</div>
					</div>
				</div>
				<?php $this->endWidget(); ?>
			</div>
		</div>
	</div>
</div>

<?php
if (!empty($dataProvider))
{
	$this->widget('booster.widgets.TbGridView', array(
		'responsiveTable'	 => true,
		'dataProvider'		 => $dataProvider,
		'id'				 => 'userListGrid',
		'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered mb0',
		'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
		//    'ajaxType' => 'POST',
		'columns'			 => array(
			array('name'	 => 'usr_profile_pic', 'type'	 => 'html', 'value'	 => function ($data)
				{
					$path = $data["usr_profile_pic_path"];
					echo CHtml::image(($path == '') ? "/images/noimg.gif" : $path, $data["usr_name"], ['style' => 'width: 50px']);
				}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'User Photo'),
			array('name' => 'usr_name', 'value' => '$data["ctt_first_name"]." ".$data["ctt_last_name"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Name'),
			array('name'	 => 'usr_mobile',
				'value'	 => function ($data)
				{
					if ($data["phn_phone_no"] != '')
					{
						echo '+' . $data["phn_phone_country_code"] . $data["phn_phone_no"];
					}
				},
				'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Phone'),
			array('name' => 'usr_email', 'value' => '$data["eml_email_address"]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Email'),
			array('name' => 'usr_city', 'value' => '$data["usr_city"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'City'),
			array('name' => 'usr_mobile_verify', 'value' => '($data["phn_is_verified"] == 1)?"Yes":"No"', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Phone Verified'),
			array('name' => 'usr_email_verify', 'value' => '($data["eml_is_verified"] == 1)?"Yes":"No"', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Email Verified'),
			array('name'	 => 'usr_created_at',
				'value'	 => function ($data)
				{
					echo DateTimeFormat::DateTimeToLocale($data["usr_created_at"]);
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Signup Date'),
			array('name' => 'usr_mark_customer_count', 'value' => '$data["usr_mark_customer_count"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Remark Bad'),
			array('name'	 => 'usr_acct_verify', 'value'	 => function($data)
				{
					echo ($data['usr_acct_verify'] == 1) ? 'Verified' : 'Not Verified';
				}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Account Verify'),
			array(
				'header'			 => 'Action',
				'class'				 => 'CButtonColumn',
				'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
				'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
				'template'			 => '{activate}{deactivate}',
				'buttons'			 => array(
					'add'			 => array(
						'click'		 => 'function(e){
                                    try
                                        {
											var promoId = $("#promoId").val();
                                            $href = $(this).attr("href")+"&promoId="+promoId;
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                bootbox.dialog({ 
                                                message: data, 
                                                className:"bootbox-sm",
                                                title:"Link User",
                                                size: "mediam",
                                                callback: function(){   }
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/promos/linkPromoUsers", array("userId"=>$data[user_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\add.png',
						'visible'	 => '($data["pru_id"]>0)?false:true',
						'label'		 => '<i class="fa fa-thumbs-down"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs promoAdd p0', 'title' => 'Add User'),
					),
					'edit'			 => array(
						'click'		 => 'function(e){
                                    try
                                        {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                bootbox.dialog({ 
                                                message: data, 
                                                className:"bootbox-sm",
                                                title:"Edit User",
                                                size: "mediam",
                                                callback: function(){   }
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/promos/linkPromoUsers", array("userId"=>$data[user_id],"pruId"=>$data[pru_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\promo_list\edit_booking.png',
						'visible'	 => '($data["pru_id"]>0)?true:false',
						'label'		 => '<i class="fa fa-thumbs-down"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs promoEdit p0', 'title' => 'Edit User'),
					),
					'deactivate'	 => array(
						'click'		 => 'function(e){                                                        
                                    try
                                    {
                                        $href = $(this).attr("href");
                                        jQuery.ajax({type:"GET",url:$href,success:function(data)
                                        {
											data=JSON.parse(data);
                                            if(data.success)
											{
												alert("User Deactivate Successfully");
												window.location.reload(true);
											}
											else
											{
												alert("Some error occurred");
											}
                                        }}); 
                                    }
                                    catch(e)
                                    { 
                                        alert(e); 
                                    }
                                    return false;

                                }',
						'url'		 => 'Yii::app()->createUrl("admin/promos/deletePromoUsers", array("pruId"=>$data[pru_id],"promoId"=>$data[pru_promo_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\active.png',
						'visible'	 => '($data[pru_active] == 1 && $data[activePromo] == 1)',
						'label'		 => '<i class="fa fa-toggle-on"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'pruActive', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs pruDeactive p0', 'title' => 'Active')
					),
					'activate'		 => array(
						'click'		 => 'function(e){                                                        
                                    try
                                    {
										var self = $(this);
										var baseUrl = $("#baseUrl").val();
                                        $href = $(this).attr("href");
										$maxUse = $("#PromoUsers_pru_use_max").val();
										if($("#PromoUsers_pru_auto_apply_0").is(":checked") == true)
										{
											$autoApply =$("#PromoUsers_pru_auto_apply_0").val();
										}
										else
										{
											$autoApply =$("#PromoUsers_pru_auto_apply_1").val();
										}
										$validFrom = $("#PromoUsers_pru_valid_from_date").val();
										$validUpto = $("#PromoUsers_pru_valid_upto_date").val();
										$promoId   = $("#promoId").val();
                                        jQuery.ajax({type:"GET",url:$href,data:{"maxUse":$maxUse,"autoApply":$autoApply,"validFrom":$validFrom,"validUpto":$validUpto,"promoId":$promoId},
										success:function(data)
                                        {
                                            data=JSON.parse(data);
                                            if(data.success)
											{
											
												alert("User Activate Successfully");
												self.find("img").attr("src",baseUrl+"/\images/\icon/\active.png");
											}
											else
											{
												alert("Some error occurred");
											}
                                        }}); 
                                    }
                                    catch(e)
                                    { 
                                        alert(e); 
                                    }
                                    return false;

                                }',
						'url'		 => 'Yii::app()->createUrl("admin/promos/linkPromoUsers", array("userId"=>$data[user_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
						'visible'	 => '($data[pru_active] == 0 || $data[activePromo] == 0)',
						'label'		 => '<i class="fa fa-toggle-on"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'pruActive', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs pruActive p0', 'title' => 'Deactivate')
					),
					'htmlOptions'	 => array('class' => 'center'),
				))
	)));
}
?>

