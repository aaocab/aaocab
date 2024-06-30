<div class="row">
    <div class="col-xs-12">

		<?php
		if (Yii::app()->user->hasFlash('notice'))
		{
			?>
			<div class="alert alert-block alert-danger">
				<?php echo Yii::app()->user->getFlash('notice'); ?>
			</div>
			<?php
		}
		if (Yii::app()->user->hasFlash('success'))
		{
			?>
			<div class="alert alert-block alert-success">
				<?php echo Yii::app()->user->getFlash('success'); ?>
			</div>
			<?php
		}

		$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
			'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
			'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
			'openOnFocus'		 => true, 'preload'			 => false,
			'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
			'addPrecedence'		 => false,];
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'email-form',
			'enableClientValidation' => true,
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
        <div class="well pb20">
			<div class="col-xs-12 col-sm-6 col-md-4"> 
				<?= $form->textFieldGroup($model, 'search', array('label' => 'Search', 'htmlOptions' => array('placeholder' => 'search by booking id or other information'))) ?>
            </div>		

			<div class="col-xs-12 col-sm-2 col-md-2 " >
				<div class="form-group">
					<label class="control-label">Tags</label>
					<?php
					$SubgroupArray2		 = Tags::getListByType(Tags::TYPE_USER);
					$this->widget('booster.widgets.TbSelect2', array(
						//'name'			 => 'bkg_tags',
						'attribute'		 => 'strTags',
						'model'			 => $model,
						'val'			 => $model->strTags,
						'data'			 => $SubgroupArray2,
						'htmlOptions'	 => array(
							'multiple'		 => 'multiple',
							'placeholder'	 => 'Search tags keywords ',
							'style'			 => 'width:100%'
						),
					));
					?>
				</div>
			</div>
			<div class="col-xs-12 col-md-4 mt20 pt5 mb10 text-center">
				<?php
				if (Yii::app()->request->isAjaxRequest)
				{
					echo CHtml::Button("Search", array('class' => 'btn btn-primary search'));
				}
				else
				{
					?>
					<button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>
				<?php } ?>			
			</div>
        </div>
		<?php
		if (!Yii::app()->request->isAjaxRequest)
		{
			?>
			<a  class="btn btn-primary mb10" href="/aaohome/contact/form" style="text-decoration: none;margin-left: 20px;float:right">Add new</a>
		<?php } ?>
		<?php $this->endWidget(); ?>
    </div>
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$params									 = array_filter($_REQUEST);
			$dataProvider->getPagination()->params	 = $params;
			$dataProvider->getSort()->params		 = $params;
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'id'				 => 'contactlist',
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name'		 => 'ctt_profile_path', 'type'		 => 'html',
						'visible'	 => Yii::app()->request->getParam("vndtype") != null ? false : true,
						'value'		 => function ($data) {
							$path = "";
							if (substr_count($data['ctt_profile_path'], "attachments") > 0)
							{
								$path .= $data['ctt_profile_path'];
							}
							else
							{
								$path .= AttachmentProcessing::ImagePath($data['ctt_profile_path']);
							}
							echo CHtml::image(($path == '') ? "/images/noimg.gif" : $path, $data["ctt_first_name"], ['style' => 'width: 150px']);
						}, 'sortable'								 => false, 'headerHtmlOptions'						 => array(), 'header'								 => 'Contact Photo'),
					array('name'		 => 'user_type',
						'visible'	 => Yii::app()->request->getParam("vndtype") != null ? false : true,
						'value'		 => function ($data) {
							if ($data['ctt_user_type'] == 1)
							{
								echo "Individual";
							}
							else
							{
								echo "Business";
							}
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'User Type'),
					array('name'	 => 'contactperson',
						'value'	 => function ($data) {
							echo $data[contactperson];
							echo $type;
							if ($data['ctt_is_verified'] != 0 && $type == '')
							{
								echo ' <span><img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Contact Verified" width="26"></span>';
							}
							else
							{
								echo ' <span><img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="26"></span>';
							}
							$tagBtnList = '';
							if ($data['ctt_tags'] != '')
							{
								$tagBtnList	 = '<br>';
								$tagList	 = Tags::getListByids($data['ctt_tags']);
								foreach ($tagList as $tag)
								{
									if($tag['tag_color']!='')
									{
										$tagBtnList .= " <span title='" . $tag['tag_desc'] . "' class='badge badge-pill badge-primary m5 mr0 p5 pb10 pl10 pr10'  style='background:".$tag['tag_color']."'>" . $tag['tag_name'] . "</span>";
									}
									else
									{
										$tagBtnList .= " <span title='" . $tag['tag_desc'] . "' class='badge badge-pill badge-primary m5 mr0 p5 pb10 pl10 pr10'>" . $tag['tag_name'] . "</span>";
									}	
								}
							}
							echo $tagBtnList;
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Name/Company Name'),
					array('name'	 => 'phone',
						'value'	 => function ($data) {
							$phones		 = explode(',', $data[phn_phone_no]);
							$pVerifies	 = $data['phn_is_verified'] != '' ? explode(',', $data['phn_is_verified']) : '';
							foreach ($phones as $key => $phone)
							{
								echo $phone;
								if ($pVerifies != '')
								{
									if ($pVerifies[$key] == 1)
									{
										echo ' <span><img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Verified" width="26"></span><br>';
									}
									else
									{
										echo ' <span><img src="/images/icon/unblock.png" style="cursor:pointer" title="UnVerified" width="26"></span><br>';
									}
								}
							}
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Phone'),
					array('name'	 => 'email',
						'value'	 => function ($data) {
							$emails		 = explode(',', $data[eml_email_address]);
							$eVerifies	 = $data['eml_is_verified'] != '' ? explode(',', $data['eml_is_verified']) : '';
							foreach ($emails as $key => $email)
							{
								echo $email;
								if ($eVerifies != '')
								{
									if ($eVerifies[$key] == 1)
									{
										echo ' <span><img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Verified" width="26"></span><br>';
									}
									else
									{
										echo ' <span><img src="/images/icon/unblock.png" style="cursor:pointer" title="UnVerified" width="26"></span><br>';
									}
								}
							}
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Email'),
					array('name' => 'address', 'visible' => Yii::app()->request->getParam("vndtype") != null ? false : true, 'value' => '$data[ctt_address]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Address'),
					array('name' => 'license_no', 'visible' => Yii::app()->request->getParam("vndtype") != null ? false : true, 'value' => '$data[ctt_license_no]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'License No'),
					array('name' => 'voter_no', 'visible' => Yii::app()->request->getParam("vndtype") != null ? false : true, 'value' => '$data[ctt_voter_no]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Voter No'),
					array('name' => 'aadhaar_no', 'visible' => Yii::app()->request->getParam("vndtype") != null ? false : true, 'value' => '$data[ctt_aadhaar_no]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Aadhaar No'),
					array('name' => 'pan_no', 'visible' => Yii::app()->request->getParam("vndtype") != null ? false : true, 'value' => '$data[ctt_pan_no]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Pan No'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{edit}{docview}{view}{button}{approve}',
						'buttons'			 => array(
							'approve'	 => array(
								'visible'	 => '(Yii::app()->request->getParam("vndtype")!=null) ? false : true',
								'url'		 => 'Yii::app()->createUrl("admin/document/docsList", array("id" => $data[ctt_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\manually_verify.png',
								'label'		 => '<i class="fa fa-check"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJobEdit p0', 'title' => 'Approve Document'),
							),
							'edit'		 => array(
								'visible'	 => '(Yii::app()->request->getParam("vndtype")!=null) ? false : true',
								'url'		 => 'Yii::app()->createUrl("admin/contact/form", array(\'ctt_id\' => $data[ctt_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\city\edit_booking.png',
								'label'		 => '<i class="fa fa-edit"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJobEdit p0', 'title' => 'Edit'),
							),
							'docview'	 => array(
								'visible'	 => '(Yii::app()->request->getParam("vndtype")!=null) ? false : true',
								'url'		 => 'Yii::app()->createUrl("admin/document/view", array(\'ctt_id\' => $data[ctt_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\uploads.png',
								'label'		 => '<i class="fa fa-email"></i>',
								'options'	 => array('target' => '_blank', 'style' => '', 'class' => 'btn btn-xs ignoreJobDocument p0', 'title' => 'Document Upload'),
							),
							'view'		 => array(
								'visible'	 => '(Yii::app()->request->getParam("vndtype")!=null) ? false : true',
								'click'		 => 'function(){
                                    $href = $(this).attr(\'href\');
                                    jQuery.ajax({type: \'GET\',
                                    url: $href,
                                    success: function (data)
                                    {
                                        var box = bootbox.dialog({
                                            message: data,
                                            title: \' Contact Details: \',
                                            size: \'large\',
                                            onEscape: function () {

                                                // user pressed escape
                                            }
                                        });
                                    }
                                });
                                    return false;
                                    }',
								'url'		 => 'Yii::app()->createUrl("admin/contact/view", array(\'ctt_id\' => $data[ctt_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\show_log.png',
								'label'		 => '<i class="fas fa-eye"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs ignoreJobView p0', 'title' => 'View Contact'),
							),
							'button'	 => array(
								'visible'	 => '(Yii::app()->request->getParam("ctype")==null) ? false : true',
								'url'		 => function ($data) {
									return $data['ctt_id'] . "_" . $data['contactperson'] . "_" . Yii::app()->request->getParam('userType') . "_" . $data["phn_phone_no"] . "_" . $data["eml_email_address"] . "_" . $data["ctt_license_no"];
								},
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\approve.png',
								'label'		 => '<i class="fa fa-email"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs ignoreSelect p0', 'title' => 'Assign', 'onClick' => 'return sendContactId(this)'),
							),
							'htmlOptions'		 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>
<script type="text/javascript">
	var emailListArray = "";
	var contactArray = "";
	var phoneListArray = "";
	$(document).ready(function () {
		$(".search").click(function () {
			$.fn.yiiGridView.update('contactlist', {data: $('#email-form').serialize()});
		});
	});
	function sendContactId(obj)
	{

		//alert("puja_contact");

		var cnt_info = $(obj).attr('href');
		var contact = cnt_info.split('_');
		var cttid = contact[0];
		var type = contact[2];
		var phone = contact[3];
		var email = contact[4];
		var license = contact[5];
		var href = '<?= Yii::app()->createUrl("admin/contact/checkprofile"); ?>';
		$.ajax({
			"url": href,
			"type": "GET",
			"dataType": "json",
			"data": {cttid: cttid, phone: phone, email: email},
			"success": function (response)
			{
				console.log(response);
				let drvId = "";
				if (response.success)
				{
					let data = response.data;

					emailListArray = [];
					if (data.hasOwnProperty("email"))
					{
						emailListArray = data.email;
					}

					phoneListArray = [];
					if (data.hasOwnProperty("phone"))
					{
						phoneListArray = data.phone;
					}
					contactArray = [];
					if (emailListArray.length > 0 && phoneListArray.length == 0)
					{
						contactArray = emailListArray;
					}
					if (emailListArray.length && phoneListArray.length)
					{
						for (let index = 0; index < emailListArray.length; index++)
						{
							contactArray.push(emailListArray[index]);
						}

						for (let index = 0; index < phoneListArray.length; index++)
						{
							contactArray.push(phoneListArray[index]);
						}
					}
					if (phoneListArray.length > 0 && emailListArray.length == 0)
					{
						contactArray = phoneListArray;
					}

					for (let index = 0; index < contactArray.length; index++)
					{
						let vendorFlag = 0;
						let driverFlag = 0;
						drvId = contactArray[index].cr_is_driver;
						var vndId = "";
						if (window.location.href.indexOf("vendor") > -1)
						{
							vendorFlag = 1;
						} else
						{
							driverFlag = 1;
						}

						if (contactArray[index].eml_contact_id == cttid || contactArray[index].phn_contact_id == cttid)
						{
							//	debugger;
							if (vendorFlag && (contactArray[index].hasOwnProperty("cr_is_vendor")))
							{
								//alert(contactArray[index].eml_email_address);

								let exEmail = (contactArray[index].eml_email_address == undefined) ? "--" : contactArray[index].eml_email_address + "and ";
								let exPhone = (contactArray[index].phn_phone_no == undefined) ? "--" : contactArray[index].phn_phone_no;

								let alertString = "Already registered as a vendor. Cannot register again. Please use existing vendor account " + contactArray[index].vnd_code + " for " + contactArray[index].eml_email_address + " and " + contactArray[0].phn_phone_no;
								let alertString1 = "Existing vendor account " + contactArray[index].vnd_code + " for " + exEmail + exPhone;
								alert(alertString1);
								$("#contactReport").text(alertString1);
								bootbox.hideAll();
								return false;
								break;
							}

							if (driverFlag && (contactArray[index].hasOwnProperty("cr_is_driver")))
							{
								for (let i in contactArray[index].mapVendors)
								{
									vndId = contactArray[index].mapVendors[i].vnd_id;
									// && (contactArray[index].hasOwnProperty("eml_is_verified") || contactArray[index].hasOwnProperty("phn_is_verified"))
									if (($("#Drivers_drv_vendor_id1").val() === vndId))
									{
										let alertString = "Already mapped as a driver with this vendor. Please use existing driver account " + contactArray[index].drv_code;
										alert(alertString);
										//	alert("puja2");
										$("#contactDetails").text(alertString);
										bootbox.hideAll();
										return false;
										break;
									}
								}
							}
						}
					}
				}


				$('#Vendors_vnd_contact_id').val(contact[0]);
				$('#Drivers_drv_contact_id').val(contact[0]);
				$('#Drivers_drv_id').val(drvId);
				$('#Vendors_vnd_contact_name').val(contact[1]);
				$('#Drivers_drv_contact_name').val(contact[1]);
				$("#contactDetails").text(contact[1] + ' | ' + contact[3] + ' | ' + contact[4] + ' | License: ' + contact[5]);
				$(".contact_div_details").removeClass('hide');
				$(".viewcontctsearch").removeClass('hide');
			}
		});
		bootbox.hideAll();
		return false;
	}
</script>

