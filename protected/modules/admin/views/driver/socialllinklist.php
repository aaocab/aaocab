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
			<div class="col-xs-12 col-sm-4 col-md-4"> 
				<?= $form->textFieldGroup($model, 'search' ,array('label' => 'Search:', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Search By Driver Code,Driver Name,Email,Phone')))) ?>
            </div>
			<div class="col-xs-12 col-sm-4 col-md-4"> 
				<?= $form->textFieldGroup($model, 'email',  array('label' => 'Search By Social Email:', 'widgetOptions' => array('htmlOptions' => array('placeholder' => 'Search By Social Email')))) ?>
            </div>	
			
			<div class="col-xs-12 col-md-4 mt20 pt5 mb10 text-center">
			<button class="btn btn-primary" type="submit" style="width: 185px;"  name="Search">Search</button>		
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
					
					
					array('name' => 'drv_code','value' => function($data) {	echo $data[drv_code];},'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Driver Code'),
					array('name' => 'drv_name','value' => function($data) {	echo $data[drv_name];},'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Driver Name'),
					array('name' => 'email','value' =>function($data) {echo $data[eml_email_address];},'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Email'),
					array('name' => 'mobile','value' => '$data[phn_phone_no]', 'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Mobile'),
					array('name' => 'profile_cache', 'value' =>function($data) {
						if($_REQUEST['Users']['email']!=NULL  ||  $_REQUEST['Users']['search']!=NULL){
							 if($data[drv_user_id]!=NULL){
								 $datas=Users::model()->getProfileCacheByUserId($data[drv_user_id]);
								if(count($datas)>0){
									$socialemail="";
									$k=1;
									for($i=0;$i<count($datas);$i++){
										$dataprofiledata=explode('"email";', $datas[$i]['profile_cache']);
										$dataprofiledata=explode(';', $dataprofiledata[1]);
										$dataprofiledata=explode(':"', $dataprofiledata[0]);
										$socialemail.=" $k. ".trim($dataprofiledata[1],'"')." ( <b>".$datas[$i]['provider']." </b>)"."<br>";
										$k++;
									}
									echo trim($socialemail);
								}
							}
						}
						else{
								$dataprofiledata= explode('"email";', $data['profile_cache']);
								$dataprofiledata=explode(';', $dataprofiledata[1]);
								$dataprofiledata=explode(':"', $dataprofiledata[0]);
								echo "1. ".trim($dataprofiledata[1],'"')." ( <b>".$data['provider']." </b>)"."<br>";	
						}
					}, 
						'sortable' => false, 'headerHtmlOptions' => array(), 'header' => 'Socail Email'),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{unlink}',
						'buttons'			 => array(
							   'unlink'		 => array(
								'visible'=>'$data[drv_user_id]==NULL ? false : true',
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
							'url'		 => 'Yii::app()->createUrl("admin/driver/unlinksocialaccount", array(\'drvid\' => $data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\merge.png',
						'label'		 => '<i class="fa fa-credit-card"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example2','class' => 'btn btn-xs ignoreMergeView p0',  'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs merge p0', 'title' => 'Unlink Social Account'),
					    ),
					))
			)));
		}
		else{
			echo '<div class="col-xs-12"><div class="table-responsive panel panel-primary compact" id="contactlist"><div class="panel-heading"><div class="row m0"><div class="col-xs-12 col-sm-6 pt5"></div><div class="col-xs-12 col-sm-6 pr0"></div></div></div><div class="panel-body"><table class="table table-striped table-bordered mb0 table"><thead><tr><th id="contactlist_c0">Vendor Code</th><th id="contactlist_c1">Vendor Name</th><th id="contactlist_c2">Email</th><th id="contactlist_c3">Mobile</th><th id="contactlist_c4">Socail Email</th><th class="col-xs-1 text-center" style="min-width: 100px;" id="contactlist_c5">Action</th></tr></thead><tbody><tr><td colspan="6" class="empty"><span class="empty">No results found.</span></td></tr></tbody></table></div><div class="panel-footer"><div class="row m0"><div class="col-xs-12 col-sm-6 p5"></div><div class="col-xs-12 col-sm-6 pr0"></div></div></div><div class="keys" style="display:none" title="/aaohome/driver/sociallist"></div></div></div>';	
		}
		?>
    </div>
</div>