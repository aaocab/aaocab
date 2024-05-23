<div class="row">
    <div class="col-xs-12">
		<?php  if(Yii::app()->user->hasFlash('success')){	?>
                                   <div class="alert alert-block alert-success">
                                     <?php echo Yii::app()->user->getFlash('success'); ?>
                                   </div>
		            <?php } ?>
		<?php
		$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
			'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
			'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
			'openOnFocus'		 => true, 'preload'			 => false,
			'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
			'addPrecedence'		 => false,];
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
				<?= $form->textFieldGroup($model, 'search', array('label' => 'Search', 'htmlOptions' => array('placeholder' => 'search by name or email adresss or phone'))) ?>
            </div>		
			<div class="col-xs-12 col-md-4 mt20 pt5 mb10 text-center">
			<button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>		
			</div>
        </div>
		<?php $this->endWidget(); ?>
    </div>
    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$params = array_filter($_REQUEST);
			$dataProvider->getPagination()->params = $params;
			$dataProvider->getSort()->params = $params;
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'id' => 'contactlist',
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(	
					array('name' => 'usr_name','value' => function($data) {	echo $data[usr_name];},'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'First Name'),
					array('name' => 'usr_lname','value' => function($data) {	echo $data[usr_lname];},'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Last Name'),
					array('name' => 'usr_email','value' =>function($data) {echo $data[usr_email];},'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Email'),
					array('name' => 'usr_mobile', 'value' =>function($data) {echo $data[usr_mobile];}, 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Mobile'),
					array('name' => 'Vendors','value' =>function($data) {if($data[Vendors]!=NULL){
						$userVendors= explode("_",$data['Vendors']);
						echo CHtml::link($userVendors[0], Yii::app()->createUrl("admin/vendor/unlinksocialaccount", ["vnd_id" => $userVendors[1],'from'=>'users']), ["class" => "action_box", "onclick" => "viewDetailVendor(this);  return false;"]);
					}} , 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Vendor Code'),	
				    array('name' => 'Drivers','value' =>function($data) { if($data[Drivers]!=NULL){
						$userDrivers= explode("_",$data[Drivers]);
						echo CHtml::link($userDrivers[0], Yii::app()->createUrl("admin/driver/unlinksocialaccount", ["drv_id" => $userDrivers[1],'from'=>'users']), ["class" => "", "onclick" => "viewDetailDriver(this); return false;"]);
					   }} , 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Driver Code'),		
					array('name' => 'profile_cache', 'value' =>function($data) {
							$dataprofiledata= explode('"email";', $data['profile_cache']);
							$dataprofiledata=explode(';', $dataprofiledata[1]);
							$dataprofiledata=explode(':"', $dataprofiledata[0]);
							echo "1. ".trim($dataprofiledata[1],'"')." ( <b>".$data['provider']." </b>)";	
						},'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Socail Email'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{unlink}',
						'buttons'			 => array(
							   'unlink'		 => array(
							    'click'		 => 'function(e){
                                            var href = $(this).attr("href");
											bootbox.confirm({
                                            message: "Do you want to unlink your social account?",
											title:"Unlink Social Account",
                                            buttons: {
													confirm: {
													label: "Yes",
													className: "btn-success"
													},
													cancel: {
													label: "No",
													className: "btn-danger"
													}
													},
                                                    callback: function (result) {
													if(result){
													  window.location.replace(href);
													}
                                               }
                                             });
                                            return false;
                                         }',
							'url'		 => 'Yii::app()->createUrl("admin/user/unlinksocialaccount", array(\'user_id\' => $data[user_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\merge.png',
						'label'		 => '<i class="fa fa-credit-card"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example2','class' => 'btn btn-xs ignoreMergeView p0',  'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs merge p0', 'title' => 'Unlink Social Account'),
					    ),
					))
			)));
		}
		?>
    </div>
</div>
<script>
    function viewDetailVendor(obj) {
	  var href = $(obj).attr("href");
		  	bootbox.confirm({
            message: "Do you want to unlink your vendor social account?",
			title:"Unlink Social Account",
                buttons: {
						confirm: {
								label: "Yes",
								className: "btn-success"
								},
						cancel: {
								label: "No",
								className: "btn-danger"
								}
					},
                callback: function (result) {
					if(result){
							  window.location.replace(href);
							}
                }
            });
      return false;
  }
    function viewDetailDriver(obj) {
        var href = $(obj).attr("href");
			bootbox.confirm({
            message: "Do you want to unlink your driver social account?",
			title:"Unlink Social Account",
                buttons: {
						confirm: {
								label: "Yes",
								className: "btn-success"
								},
						cancel: {
								label: "No",
								className: "btn-danger"
								}
					},
                callback: function (result) {
					if(result){
							  window.location.replace(href);
							}
                }
            });
        return false;
    }
</script>