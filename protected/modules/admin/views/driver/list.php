<?php
$pageno				 = Yii::app()->request->getParam('page');
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
//$vendorListJson = Vendors::model()->getJSON1();
?>
<div class="row">
    <div class="  col-xs-12">
		<?php  if(Yii::app()->user->hasFlash('notice')){	?>
                                   <div class="alert alert-block alert-danger">
                                     <?php echo Yii::app()->user->getFlash('notice'); ?>
                                   </div>
		            <?php } ?>
					
					<?php  if(Yii::app()->user->hasFlash('success')){	?>
                                   <div class="alert alert-block alert-success">
                                     <?php echo Yii::app()->user->getFlash('success'); ?>
                                   </div>
		            <?php } ?>

		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'driverForm', 'enableClientValidation' => true,
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
		<div class="row mt10" >
			<div class="col-xs-4 col-sm-3">
				<?= $form->textFieldGroup($model, 'drv_name2', array('label' => '', 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Search By Name/Code/First Name/Last Name/Business Name']])) ?>
			</div>
			<div class="col-xs-4 col-sm-3">
				<?= $form->textFieldGroup($model, 'drv_phone2', array('label' => '', 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Search Phone']])) ?> 
			</div>
			<div class="col-xs-4 col-sm-3">
				<?= $form->textFieldGroup($model, 'drv_email2', array('label' => '', 'widgetOptions' => ['htmlOptions' => ['placeholder' => 'Search Email']])) ?> 
			</div>

			<div class="col-xs-4 col-sm-3 form-group">
				<input class="form-control" type="text" id="searchLicense" name="searchLicense" placeholder="Search License">
		</div>
		</div>
		<div class="row " >
			<div class="col-xs-6 col-sm-4 form-group ">
				<div class="cityinput">
					<?
					$this->widget('ext.yii-selectize.YiiSelectize', array(
						'model'				 => $model,
						'attribute'			 => 'drv_vendor_id',
						'useWithBootstrap'	 => true,
						"placeholder"		 => "Select Vendor",
						'fullWidth'			 => false,
						'options'			 => array('allowClear' => true),
						'htmlOptions'		 => array('width' => '100%',
						//  'id' => 'from_city_id1'
						),
						'defaultOptions'	 => $selectizeOptions + array(
					'onInitialize'	 => "js:function(){
                                  populateVendor(this, '{$model->drv_vendor_id}');
                                                }",
					'load'			 => "js:function(query, callback){
                        loadVendor(query, callback);
                        }",
					'render'		 => "js:{
                            option: function(item, escape){
                            return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                            },
                            option_create: function(data, escape){
                            return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                            }
                        }", 'allowClear'	 => true
						),
					));
					?></div>
			</div>
			<div class="col-xs-6 col-sm-4 form-group">
				<?php
				$arrJSON1			 = array();
				$arr1				 = ['0' => 'Not Verified', '1' => 'Approved', '2' => 'Pending Approval(Verified)', '3' => 'Rejected'];
				foreach ($arr1 as $key => $val)
				{
					$arrJSON1[] = array("id" => $key, "text" => $val);
				}
				$approvedriverlist = CJSON::encode($arrJSON1);

				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'drv_approved',
					'val'			 => $model->drv_approved,
					'asDropDownList' => FALSE,
					'options'		 => array('data' => new CJavaScriptExpression($approvedriverlist), 'allowClear' => true),
					'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'approved status')
				));
				?>
			</div>
			
            <div class="col-xs-6 col-sm-4">
			
				<?php
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $model,
					'attribute'		 => 'drv_trip_type',
					'val'			 => explode(',', $model->drv_trip_type),
					'data'			 => Drivers::getTripType(),
					'htmlOptions'	 => array(
						'multiple'		 => 'multiple',
						'placeholder'	 => 'Approved for following trip types',
						'width'			 => '100%',
						'style'			 => 'width:100%',
					),
				));?>
				
			</div>
		</div>
		
		<div class="row " >
            <div class="col-xs-3 col-sm-4 form-group  ">
				<input class="form-control" type="checkbox" id="searchmarkdriver" name="searchmarkdriver" <?php
				if ($qry['searchmarkdriver'] > 0)
				{
					echo 'checked="checked"';
				}
				?> >&nbsp;Mark Bad
			</div>
            <div class="col-xs-3 col-sm-4 form-group  ">
				<input class="form-control" type="checkbox" id="searchdlmismatch" name="searchdlmismatch" <?php
				if ($qry['searchdlmismatch'] > 0)
				{
					echo 'checked="checked"';
				}
				?> >&nbsp;DL Mismatched
			</div>
            <div class="col-xs-3 col-sm-4 form-group  ">
				<input class="form-control" type="checkbox" id="searchdlmismatch" name="searchpanmismatch" <?php
				if ($qry['searchpanmismatch'] > 0)
				{
					echo 'checked="checked"';
				}
				?> >&nbsp;PAN Mismatched
			</div>
			
			<div class="col-xs-6 col-sm-4">
			<button class="btn btn-info  " type="submit"  name="Search" style="width: 185px;">Search</button>
		</div>
			<div class="col-xs-6 col-sm-4">
				&nbsp;
			</div>
		</div>


		<?php $this->endWidget(); ?>

    </div>
</div>
<!--		<h2>Users</h2>-->
<a class="btn btn-primary mb10" href="<?= Yii::app()->createUrl('admin/driver/add') ?>" style="text-decoration: none">Add new</a>
<?php
if (!empty($dataProvider))
{
	$this->widget('booster.widgets.TbGridView', array(
		'responsiveTable'	 => true,
		'dataProvider'		 => $dataProvider,
		'selectableRows'	 => 2,
		'id'				 => 'driverListGrid',
		'template'			 => "<div class='panel-heading'><div class='row m0'>
            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
            </div></div>
            <div class='panel-body'>{items}</div>
            <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered mb0',
		'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
		//'ajaxType' => 'POST',
		'columns'			 => array(
//			array('class' => 'CCheckBoxColumn', 'id' => 'drv_checked[]',),
			array('class'	 => 'CCheckBoxColumn', "value"	 => function ($data) {
												return $data['drv_id'];
											}, 'id' => 'drv_checked[]'),
			array('name'	 => 'drv_photo_path', 'type'	 => 'html', 'value'	 => function ($data) {
					if ($data['drv_photo_path'] != '')
					{
						$path = str_replace('\\', '/', $data['drv_photo_path']);
					}
					else
					{
						$path = "/images/noimg.gif";
					}


					echo CHtml::link(CHtml::image($path, $data['drv_name'], ['style' => 'width: 50px']), Yii::app()->createUrl("admin/driver/view", ["id" => $data['drv_id']]), ["class" => "", "onclick" => "return viewDetail(this)"]);


					//return CHtml::image($path, $data['drv_name'], ['style' => 'width: 50px']);
				}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Photo'),
			array('name'	 => 'drv_name',
				// 'value' => '$data->drv_name', 
				'value'	 => function ($data) 
				{
					$drvName = $data["drv_name"];
					if($data["ctt_name"] != "")
					{
						$drvName = $data["ctt_name"];
					}
					else if($data["ctt_business_name"] != "")
					{
						$drvName = $data["ctt_business_name"];
					}

					echo CHtml::link($drvName, Yii::app()->createUrl("admin/driver/view", ["id" => $data['drv_id']]), ["class" => "", "onclick" => "", 'target' => '_blank'])."<br>";
                    if ($data['drv_is_name_dl_matched'] == 2)
					{
                            echo ' <span class="label label-danger ">DL Mismatch</span><br><br>';
					}
                    if ($data['drv_is_name_pan_matched'] == 2)
					{
						echo ' <span class="label label-danger ">Pan Mismatch</span><br>';
					}
					echo ($data['drv_code']!='') ? '<b>'.$data['drv_code']."</b><br>" : '';
					if ($data['drv_approved'] == 1)
					{
						echo ' <span class="label label-info ">Approved</span>';
					}
                    
					if ($data['drv_is_freeze'] == 1)
					{
						echo ' <span class="label label-danger ">Block</span>';
					}
$icon	 = '<img src="/images/icon/eye.png"  style="cursor:pointer ;height:16px; width:16px;" title="Value">';
//echo CHtml::link($icon, Yii::app()->createUrl("admin/driver/profile", ["id" => $data['drv_id']]), ["class" => "", "onclick" => "", 'target' => '_blank']);	
				}
				, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'htmlOptions' => array('style' => 'word-break: break-all;min-width:90px'), 'header'			 => 'Name'),
				array('name' => 'drv_phone', 
                                            'filter' => CHtml::activeTextField($model, 'drv_phone', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('drv_phone'))), 
											'value' => '$data["drv_phone"]',
                                            'value' => function ($data) {
												echo CHtml::link("Show Contact", Yii::app()->createUrl("admin/contact/view", ["ctt_id" => $data['cr_contact_id'],'viewType' =>'driver']), ["class" => "", "onclick" => "return viewContactDriver(this)"]);
											},
											'sortable' => true, 'header' => $model->getAttributeLabel('drv_phone')),			
						
						
			array('name' => 'eml_email_address', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Email',
				'value' => function ($data)
					{
						if(trim($data['eml_email_address']) != '')
						{
							echo ContactEmail::getEmailFromString($data['eml_email_address']);
						}
					},
				),
			array('name'	 => 'R4Ascore', 'value'	 => function($data) {
							echo ($data['R4Ascore'] > 0) ? CHtml::link($data['R4Ascore'], Yii::app()->createUrl('admin/driver/view', array('id' => $data['drv_id'])), array('target' => '_blank')) : 'NA';
						}, 'sortable'			 => true,
						'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
						'htmlOptions'		 => array('class' => 'text-center'),
						'header'			 => 'R4A score(Ready for Approval Score)'),
			array('name' => 'drv_licence_path', 'value' => '$data[drv_licence_path]!=""?"Yes":"No"', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Licence Proof'),
			array('name'	 => 'drv_lic_exp_date', 'value'	 => function ($data) {
					$rdate = '';
					if ($data['drv_lic_exp_date'] != '')
					{
						$rdate = DateTimeFormat::DateToLocale($data['drv_lic_exp_date']);
					}
					return $rdate;
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Licence Expiry'),
			array('name' => 'drv_issue_auth',
                'value' => function($data){
                   
                    echo States::model()->getNameById($data['drv_issue_auth']);
                }, 
                'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Licence Issue Auth'),
			array('name'	 => 'drv_issue_date',
				'value'	 => function ($data) {
					if ($data['drv_issue_date'] != '')
					{
						return DateTimeFormat::DateTimeToLocale($data['drv_issue_date']);
					}
					return '';
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Licence Issue date'),
			array('name'	 => 'drv_doj',
				'value'	 => function ($data) {
					if ($data['drv_doj'] != '')
					{
						return DateTimeFormat::DateToLocale($data['drv_doj']);
					}
					return '';
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Joining Date'),
				array('name'	 => 'drv_created',
				'value'	 => function ($data) {
					return DateTimeFormat::DateTimeToLocale($data['drv_created']);
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Added On'),
			//  array('name' => 'usr_city', 'value' => '$data->vhc', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Assigned vehicles'),
			array('name' => 'drv_mark_driver_count', 'value' => '$data[drv_mark_driver_count]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Bad Count'),
			array('name'	 => 'drs_last_trip_date',
				'value'	 => function ($data) {
					if ($data['drs_last_trip_date'] != NULL)
					{
						echo DateTimeFormat::DateTimeToLocale($data['drs_last_trip_date']);
					}
					else
					{
						echo '';
					}
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Last Trip'),
			array('name'	 => 'drs_last_logged_in',
				'value'	 => function ($data) {
					if ($data['drs_last_logged_in'] != NULL)
					{
						echo DateTimeFormat::DateTimeToLocale($data['drs_last_logged_in']);
					}
					else
					{
						echo '';
					}
				},
				'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Last Logged In'),			
			array('name'	 => 'total_trips', 'value'	 => function ($data) {
					$totalTrips = ($data['total_trips'] > 0 ) ? $data['total_trips'] : 0;
					return $totalTrips;
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Total Trips'),
			array(
				'header'			 => 'Action',
				'class'				 => 'CButtonColumn',
				'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
				'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
				'template'			 => '{add}{docview}{view}{edit}{detail}<br>{markedbadlist}{resetmarkedbad}{log}{addremark}{driverFreeze}{driverUnfreeze}{linkuser}',
				'buttons'			 => array(
					'merge'			 => array(
						'click'		 => 'function(e){
                                    try
                                        {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                var mergebox=bootbox.dialog({ 
                                                message: data, 
                                                className:"bootbox-lg",
                                                title:"Merge Drivers",
                                                size: "large",
                                                callback: function(){   
                                                },
                                                onEscape: function(){                                                
                                                   $(mergebox).modal("hide");
                                                },
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/merge", array("drv_id"=>$data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\merge.png',
						'label'		 => '<i class="fa fa-credit-card"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example2', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs merge p0', 'title' => 'Merge Driver'),
					),
					'add'		 => array(
						'click'		 => 'function(e){
                                    try
                                        {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                bootbox.dialog({ 
                                                message: data, 
                                                title:"Add Transaction",
                                                size: "large",
                                                className:"bootbox-lg",    
                                                callback: function(){   }
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/addtransaction", array("id"=>$data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\add_transaction.png',
						'label'		 => '<i class="fa fa-file-text-o"></i>',
						'options'	 => array('data-toggle'	 => 'ajaxModal',
							'id'			 => 'example',
							'style'			 => '',
							'rel'			 => 'popover',
							'data-placement' => 'left',
							'class'			 => 'btn btn-xs jobDetail53 p0',
							'title'			 => 'Add Transaction'),
					),
					'docview'			 => array(
								 				'url'		 => 'Yii::app()->createUrl("admin/document/view", array(\'ctt_id\' => $data[cr_contact_id],\'viewType\' =>"driver"))',
												'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\uploads.png',
												'label'		 => '<i class="fa fa-email"></i>',
												'options'	 => array( 'target'=>'_blank','style' => '', 'class' => 'btn btn-xs p0', 'title' => 'Document Upload'),
							                  ),
					'view'		 => array(
						'click'		 => 'function(e){
                                    try
                                        {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                bootbox.dialog({ 
                                                message: data, 
                                                title:"View Transaction",
                                                size: "large",
                                                className:"bootbox-lg",    
                                                callback: function(){   }
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/viewtransaction", array("id"=>$data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\show_transactions.png',
						'label'		 => '<i class="fa fa-file-text-o"></i>',
						'options'	 => array('data-toggle'	 => 'ajaxModal',
							'id'			 => 'example',
							'style'			 => '',
							'rel'			 => 'popover',
							'data-placement' => 'left',
							'class'			 => 'btn btn-xs jobDetail5 p0',
							'title'			 => 'View Transaction'),
					),
                    'linkuser'			 => array(
								'click'		 => 'function(){
                                                                    $href = $(this).attr(\'href\');
                                                                    $.ajax({
                                                                        url: $href,
                                                                        dataType: "html",
                                                                        success: function(data){
                                                                               var linkuserbootbox1 = bootbox.dialog({ 
                                                                                   message: data,  
                                                                                   title:"Link User",
                                                                                   size: "large",
                                                                                   callback: function(){   }
                                                                               });
                                                                                linkuserbootbox1.on("hidden.bs.modal", function () { $(this).data("bs.modal", null); });
                                                                        },
                                                                        error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                        }
                                                                    });
                                                            
                                                                    return false;
                                                    }',
								'url'		 => 'Yii::app()->createUrl("aaohome/driver/linkuser", array("drvId" => $data[drv_id]))',
								'label'		 => '<i class="fa fa-users"></i>',
								'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'padding: 4px ;margin-left: 4px', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs linkUser', 'title' => 'Link User')
							),
//					'delete'		 => array(
//						'click'		 => 'function(){
//                                            var con = confirm("Are you sure you want to delete this Driver?");
//                                            return con;
//                                        }',
//						'url'		 => 'Yii::app()->createUrl("admin/driver/del", array(\'drvid\' => $data[drv_id]))',
//						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\customer_cancel.png',
//						'label'		 => '<i class="fa fa-remove"></i>',
//						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conDelete p0', 'title' => 'Delete Driver'),
//					),
					'edit'			 => array(
						'url'		 => 'Yii::app()->createUrl("admin/driver/add", array(\'drvid\' => $data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\edit_booking.png',
						'label'		 => '<i class="fa fa-edit"></i>',
						'options'	 => array('style' => '', 'class' => 'btn btn-xs edit p0', 'title' => 'Edit Driver'),
					),
					'markedbadlist'	 => array(
						'click'		 => 'function(e){
                                    try
                                        {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                bootbox.dialog({ 
                                                message: data, 
                                                className:"bootbox-lg",
                                                title:"Mark Bad Drivers",
                                                size: "large",
                                                callback: function(){   }
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/markedbadlist", array("drv_id"=>$data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\bad_driver_1.png',
						'label'		 => '<i class="fa fa-credit-card"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'example2', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs markBad p0', 'title' => 'Marked Bad Driver'),
					),
					'detail'		 => array(
						'click'		 => 'function(e){
                                    try
                                        {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                bootbox.dialog({ 
                                                message: data, 
                                                title:"Driver Details",
                                                size: "large",
                                                className:"bootbox-lg",    
                                                callback: function(){   }
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/view", array("id"=>$data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\show_details.png',
						'label'		 => '<i class="fa fa-file-text-o"></i>',
						'options'	 => array('data-toggle'	 => 'ajaxModal',
							'id'			 => 'example',
							'style'			 => '',
							'rel'			 => 'popover',
							'data-placement' => 'left',
							'class'			 => 'btn btn-xs jobDetail p0',
							'title'			 => 'Show Details'),
					),
					'resetmarkedbad' => array(
						'click'		 => 'function(){
                                    $href = $(this).attr(\'href\');
                                    jQuery.ajax({type: \'GET\',
                                    url: $href,
                                    success: function (data){
                                        bootbox.dialog({
                                            message: data,
                                            title: \'Reset Bad Count For Driver\',
                                            onEscape: function () {

                                                // user pressed escape
                                            }
                                        });
                                    }
                                });
                                    return false;
                                    }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/resetmarkedbad", array("refId" =>$data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\reset_marked_bad_driver.png',
						'visible'	 => '($data[drv_mark_driver_count]>0)',
						'label'		 => '<i class="fa fa-refresh"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs resetMarkBad p0', 'title' => 'Reset Marked Bad Driver'),
					),
					'driverFreeze'	 => array(
						'click'		 => 'function(e){                                                        
                                    try
                                    {
                                        $href = $(this).attr("href");
                                        jQuery.ajax({type:"GET",url:$href,success:function(data)
                                        {
                                            bootbox.dialog({ 
                                            message: data, 
                                            className:"bootbox-sm",
                                            title:"UnBlock Driver",
                                            success: function(result){
                                                if(result.success)
                                                {

                                                }else
                                                {
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
                                    { 
                                        alert(e); 
                                    }
                                    return false;

                                }',
						'url'		 => 'Yii::app()->createUrl("aaohome/driver/freeze", array("drv_id" => $data[drv_id],"drv_is_freeze"=>$data[drv_is_freeze]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
						'visible'	 => '($data[drv_is_freeze] == 1)',
						'label'		 => '<i class="fa fa-toggle-on"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'admFreeze', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs driverFreeze p0', 'title' => 'UnBlock Driver')
					),
					'driverUnfreeze' => array(
						'click'		 => 'function(e){                                                        
                                    try
                                    {
                                        $href = $(this).attr("href");
                                        jQuery.ajax({type:"GET",url:$href,success:function(data)
                                        {
                                            bootbox.dialog({ 
                                            message: data, 
                                            className:"bootbox-sm",
                                            title:"Block Driver",
                                            success: function(result){
                                                if(result.success)
                                                {

                                                }else
                                                {
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
                                    { 
                                        alert(e); 
                                    }
                                    return false;

                                }',
						'url'		 => 'Yii::app()->createUrl("aaohome/driver/freeze", array("drv_id" => $data[drv_id],"drv_is_freeze"=>$data[drv_is_freeze]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\active.png',
						'visible'	 => '($data[drv_is_freeze] == 0)',
						'label'		 => '<i class="fa fa-toggle-on"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'admFreeze', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs driverUnfreeze p0', 'title' => 'Block Driver')
					),
					'log'			 => array(
						'click'		 => 'function(){
                                            $href = $(this).attr(\'href\');
                                            jQuery.ajax({type: \'GET\',
                                            url: $href,
                                            success: function (data)
                                            {

                                                var box = bootbox.dialog({
                                                    message: data,
                                                    title: \'Driver Log\',
                                                    size: \'large\',
                                                    onEscape: function () {

                                                        // user pressed escape
                                                    }
                                                });
                                            }
                                        });
                                    return false;
                                }',
						'url'		 => 'Yii::app()->createUrl("admin/driver/showlog", array("drvId" => $data[drv_id]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\show_log.png',
						'label'		 => '<i class="fa fa-list"></i>',
						'options'	 => array('data-toggle'	 => 'ajaxModal',
							'style'			 => '',
							'class'			 => 'btn btn-xs conshowlog p0',
							'title'			 => 'Show Log'),
					),
					'addremark'		 => array(
														'click'		 => 'function(e){                                                        
                                                        try
                                                            {
                                                            $href = $(this).attr("href");
                                                            jQuery.ajax({type:"GET",url:$href,success:function(data)
                                                            {
                                                                bootbox.dialog({ 
                                                                message: data, 
                                                                className:"bootbox-sm",
                                                                title:"Add Remark",
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
														'url'		 => 'Yii::app()->createUrl("aaohome/driver/addremark", array("drv_id" => $data[drv_id]))',
														'imageUrl'	 => Yii::app()->request->baseUrl . '\images\add_remarks.png',
														//'visible'	 => '$data[drv_active] == 1',
														'label'		 => '<i class="fa fa-toggle-on"></i>',
														'options'	 => array('data-toggle' => 'ajaxModal', 'id' => 'remark', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs addremark p0', 'title' => 'Add Remark')
													),
					'htmlOptions'	 => array('class' => 'center'),
				)
			)
		)
			)
	);
}
?>

<div>
    <div class="row">
        <form name="messageForm" method="get" action="">
            <div class="row">
                <textarea rows="4" cols="80" id="messageText" placeholder="Enter your message here." style="text-decoration:none; margin-left:30px;"></textarea>
            </div>
        </form>
    </div>
    <br>
    <div class="row">
        <div class="col-xs-12">
            <div class="col-md-3">
                <div class="form-group"><label><b>Send message via: </b></label></div>
            </div>
            <div class="col-md-3">
                <div class="form-group"><label><input type="checkbox" id="chk_sms" checked/> <b>SMS</b></label></div>
            </div>
            <div class="col-md-3">
                <div class="form-group"><label><input type="checkbox" id="chk_email" checked/> <b>E-Mail</b></label></div>
            </div>
            <div class="col-md-3">
                <div class="form-group"><label><input type="checkbox" id="chk_app" checked/> <b>Push Notification on App</b></label></div>
            </div>
        </div>
    </div>    
    <a href="#" class="btn btn-primary mb10" onclick="BroadcastMessage();" style="text-decoration: none;margin-left: 1px;">Send Message</a>
</div>


<script type="text/javascript">


    $(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');
    });

    function refreshDriverGrid() {
        $('#driverListGrid').yiiGridView('update');
    }

    function viewDetail(obj)
    {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Driver Details',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
                if ($('body').hasClass("modal-open"))
                {
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }

            }
        });
        return false;
    }



    function edit(obj)
    {
        var $drvid = $(obj).attr('drv_id');
        var href2 = '<?= Yii::app()->createUrl("admin/driver/add"); ?>';
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "json",
            "data": {"drvid": $drvid},
            "success": function (data) {
                alert(data);
            }
        });
    }


    function showimage(url) {

        bootbox.alert("<img src='/uploadedFiles/" + url + "' width='100%'>", function () {
            console.log("It was awesome!");
        });
    }
    function showimage1(url) {

        bootbox.alert("<img src='/uploadedFiles/" + url + "' width='100%'>", function () {
            console.log("It was awesome!");
        });
    }
    function showimage2(url) {

        bootbox.alert("<img src='/uploadedFiles/" + url + "' width='100%'>", function () {
            console.log("It was awesome!");
        });
    }
    function BroadcastMessage() {
        //var keys = $('#driverListGrid').yiiGridView('getSelection');
		var keys = $('input[type="checkbox"][name="drv_checked\\[\\]"]:checked').map(function () {
            return this.value;
        }).get();
        var numrows = keys.length;
        var messageText = document.getElementById("messageText").value;
        var sms = document.getElementById("chk_sms").checked;
        var email = document.getElementById("chk_email").checked;
        var app = document.getElementById("chk_app").checked;

        function smscheck() {
            if (sms == true)
                return "<b>SMS</b><br>";
            else
                return "";
        }
        function emailcheck() {
            if (email == true)
                return "<b>E-Mail</b><br>";
            else
                return "";
        }
        function appcheck() {
            if (app == true)
                return "<b>Push Notification on App</b><br>";
            else
                return"";
        }
        if (keys == '') {
            bootbox.alert("Please select atleast one driver.");
        } else {
            bootbox.confirm("Do you want to send the message:<b><br> " + messageText
                    + "</b><br>Using the following methods: " + "<br>" + smscheck() + emailcheck() + appcheck() + "To <b>" + numrows + "</b>  selected drivers."
                    , function (confirmed) {
                        if (confirmed) {
                            window.location.href = '<?php echo Yii::app()->createUrl('admin/driver/BroadcastMessage'); ?>?drv_id=' + keys.join() + '&&message=' + messageText + '&&sms=' + sms + '&&email=' + email + '&&app=' + app;
                            ;
                        }
                    });
        }
    }

	function viewContactDriver(obj) {
        var href2 = $(obj).attr("href");
		$.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Driver Contact',
                    size: 'large',
                    onEscape: function () {
                        // user pressed escape
                    },
                });
                if ($('body').hasClass("modal-open"))
                {
                    box.on('hidden.bs.modal', function (e) {
                        $('body').addClass('modal-open');
                    });
                }

            }
        });
        return false;
    }
</script>