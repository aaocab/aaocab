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

		
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'contact-form',
			'enableClientValidation' => true,
			'clientOptions'			 => array(
				'validateOnSubmit'	 => true,
				'errorCssClass'		 => 'has-error',
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
                <label>Search Type</label>
				<?= $form->radioButtonListGroup($model, 'searchtype', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Email', 2 => 'Phone')), 'inline' => true)) ?>
            </div>
			<div class="col-xs-12 col-sm-6 col-md-4"> 
				<?= $form->textFieldGroup($model, 'search', array('label' => 'Search', 'htmlOptions' => array('placeholder' => 'search by email or phone','width'=>'10'))) ?>
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
					<button class="btn btn-primary" type="button" style="width: 185px;"  id="search">Search</button>
				<?php } ?>			
			</div>
        </div>
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
						'value'		 => function ($data) {
							echo CHtml::link($data["ctt_id"], Yii::app()->createUrl("admin/contact/view", ["ctt_id" => $data['ctt_id']]), ["title" => "View Contact", 'target' => '_blank']);
                        }, 'sortable'  => false, 'headerHtmlOptions'  => array(), 'header' => 'Contact Id'),
					
					array('name'	 => 'email',
						'value'	 => function($data) {
							$emails = explode(',', trim($data['eml_email_address']));
							if($emails)
							{
								foreach ($emails as $val)
								{
									$strVerify = $strStatus = '';
									$arrVal = explode('|', $val);
									$email = $arrVal[0];
									$eVerify = (int)$arrVal[1];
									$eStatus = (int)$arrVal[2];
									
									if($email != '') 
									{
										$strVerify = ' <span><img src="/images/icon/unblock.png" style="cursor:pointer" title="UnVerified" width="26"></span>';
										if ($eVerify == 1)
										{
											$strVerify = ' <span><img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Verified" width="26"></span>';
										}
										
										$strStatus = ' <span class="label label-danger">I</span>';
										if ($eStatus == 1)
										{
											$strStatus = ' <span class="label label-success">A</span>';
										}
										
										echo $email . $strVerify . $strStatus . '<br/>';
									}
								}
							}
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Email'),
					array('name'	 => 'phone',
						'value'	 => function($data) {
							$phones = explode(',', trim($data['phn_phone_no']));
							if($phones)
							{
								foreach ($phones as $val)
								{
									$strVerify = $strStatus = '';
									$arrVal = explode('|', $val);
									$phone = $arrVal[0];
									
									$eStatus = (int)$arrVal[1];
									$eVerify = (int)$arrVal[2];
									
									if($phone != '')
									{
										$strVerify = ' <span><img src="/images/icon/unblock.png" style="cursor:pointer" title="UnVerified" width="26"></span>';
										if ($eVerify == 1)
										{
											$strVerify = ' <span><img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Verified" width="26"></span>';
										}
										
										$strStatus = ' <span class="label label-danger">I</span>';
										if ($eStatus == 1)
										{
											$strStatus = ' <span class="label label-success">A</span>';
										}
										
										echo $phone . $strVerify . $strStatus . '<br/>';
									}
								}
							}
						},
						'sortable'			 => true, 'headerHtmlOptions'	 => array('width'=>'13%'), 'header'			 => 'Phone'),
                    array('name' => 'vendor_profile',
						  'value' => function($data) {
								$codeArrVendor	= Filter::getCodeById($data['vendor_id'], "vendor");
								if($data['vendor_id'] != ''){
								    echo CHtml::link($codeArrVendor['code'], Yii::app()->createUrl("admin/vendor/view", ["id" => $data['vendor_id']]), ["title" => "Vendor Profile", 'target' => '_blank']);
								$isVendorActive = ($data['is_vnd_active']==1)?'Active':'Merged';
								echo ' <span class="label label-warning">'.$isVendorActive.'</span>';
								}
								
						 }, 
						  'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'V-Profile Id'),
                    array('name' => 'driver_profile',
						  'value' => function($data){ 
								$codeArrDriver = Filter::getCodeById($data['driver_id'], "driver");
								if($data['driver_id'] != ''){
								    echo CHtml::link($codeArrDriver['code'], Yii::app()->createUrl("admin/driver/view", ["id" => $data['driver_id']]), ["title" => "Driver Profile", 'target' => '_blank']);	
									$isDriverActive = ($data['is_drv_active']==1)?'Active':'Merged';
								echo ' <span class="label label-warning">'.$isDriverActive.'</span>';
								}
						}, 
						'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'D-Profile Id'),
					array(
							'name' => 'consumer_profile', 
							'value' => function($data) {
								if($data['consumer_id'] != '')
								{
									echo CHtml::link($data['consumer_id'], Yii::app()->createUrl("admin/user/view", ["id" => $data['consumer_id']]), ["title" => "User Profile", 'target' => '_blank']);	
								}
							},
							'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'C-Profile Id'
						),
					array('name' => 'license_no',
                          'value' => function($data) {
								echo $data[ctt_license_no].'<br/>';
						   }, 
                          'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'License No'),
					array('name' => 'voter_no',
						  'value' => function($data){
								echo $data[ctt_voter_no].'<br/>';
						   }, 
						  'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Voter No'),
					array('name' => 'aadhaar_no',
						  'value' => function($data){
						    echo $data[ctt_aadhaar_no].'<br/>';
						  }, 
						  'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Aadhaar No'),
					array('name' => 'pan_no',
						  'value' => function($data){
						    echo $data[ctt_pan_no].'<br/>';
						  }, 
						'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Pan No'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{merge}',
						'buttons'			 => array(
							'merge'		 => array(
								'click'		 => 'function(e){                                                        
								try
									{
									$href = $(this).attr("href");
									jQuery.ajax({type:"GET",url:$href,success:function(data)
									{
										bootbox.dialog({ 
										message: data, 
										className:"bootbox-sm",
										title:"Merge Contact",
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
								'url'		 => 'Yii::app()->createUrl("admpnl/contact/mergecode", array("ctt_id" => $data[ctt_id]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\merge.png',
								'visible'	 => '1',
								'label'		 => 'Merge Contact',
								'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'merge', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs p0', 'title' => 'Merge Contact')
							)
						),
					),
					
			)));
		}
		?>
    </div>
</div>
<script type="text/javascript">
	$('#search').click(function(){ 
		var ck_email = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;
		if($("input:radio[name='Contact[searchtype]']").is(":checked")){
			var searchType = $('input[name="Contact[searchtype]"]:checked').val();
		}else{
			alert('Please check at least one searchtype');
			return false;
		}
		var searchVal = $('#Contact_search').val();
		if(searchVal == '' && searchType == 1){
			alert('Please enter email address.');
			return false;
		}
		if(searchVal == '' && searchType == 2){
			alert('Please enter mobile number.');
			return false;
		}
		
		if ((searchType == 1) && (!ck_email.test(searchVal))) 
		{
            alert('Invalid email address');
			return false;
        }
		$('#contact-form').submit();
	});
</script>

