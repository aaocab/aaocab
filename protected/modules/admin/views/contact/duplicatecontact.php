<style>
.modal {
  overflow-y:auto;
}
	
</style>
<div class="row">
    <div class="col-xs-12">
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
		 $this->endWidget(); ?>
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
				'id' => 'contactlist'.$cttid,
				'filter'			 => $model,
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					
					
//					 array(
//							'visible'=>Yii::app()->request->getParam("ctt_id")==null ? false : true,
//							'class'			 => 'CCheckBoxColumn',
//							'header'		 => 'html',
//							'id'			 => 'contact_id',
//							'selectableRows' => '{items}',
//							'selectableRows' => 2,
//							'value'			 => '$data["ctt_id"]',
//							'headerTemplate' => '<label>{item}<span></span></label>',
//							'htmlOptions'	 => array('style' => 'width: 20px'),
//						),
					array(
						'visible'=>Yii::app()->request->getParam("ctt_id")==null ? false : true,
						'name' => 'Contact Link', 
						 'filter' => false,
						'value' =>function($data) {
						   echo CHtml::link("Merge Contact", Yii::app()->createUrl("admin/contact/mergecontact", ["ctt_id" => Yii::app()->request->getParam("ctt_id"),'mgrctt_id' =>$data['ctt_id']]), ['target'=>'_blank']);
						},
						'sortable' => true, 'headerHtmlOptions' => array(), 'header' => ''),
								
					array('name' => 'Contact Id', 
						 'filter' => false,
						'value' =>function($data) {
						    echo   Yii::app()->request->getParam("ctt_id")== null? $data['ctt_id']:  CHtml::link($data['ctt_id'], Yii::app()->createUrl("admin/contact/form", ["ctt_id" => $data['ctt_id']]), ['target'=>'_blank']);
						},
						'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Contact Id'),
					
					array(
						'visible'=>Yii::app()->request->getParam("ctt_id")==null ? true : false,
						'name' => 'Name',
						'filter' => CHtml::activeTextField($model, 'contactperson', array('class' => 'form-control', 'placeholder' => 'Search by Name ')),
						'value' => '$data[contactperson]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Name'),			
								
								
					array('name' => 'phone', 
						 'filter' => CHtml::activeTextField($model, 'phone_no', array('class' => 'form-control', 'placeholder' => 'Search by Phone')),
						'value' =>function($data) {
							echo $data[phn_phone_no];
							if ($data['phn_is_verified'] != 0)
							{
								echo ' <span><img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Verified" width="26"></span>';
							}
							else
							{
								echo ' <span><img src="/images/icon/unblock.png" style="cursor:pointer" title="UnVerified" width="26"></span>';
							}
							
						},
						'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Phone'),
					array('name' => 'email', 
						'filter' => CHtml::activeTextField($model, 'email_address',  array('class' => 'form-control', 'placeholder' => 'Search by Email')),
						'value' =>function($data) {
							echo $data[eml_email_address];
							if ($data['eml_is_verified'] != 0)
							{
								echo ' <span><img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Verified" width="26"></span>';
							}
							else
							{
								echo ' <span><img src="/images/icon/unblock.png" style="cursor:pointer" title="UnVerified" width="26"></span>';
							}
						}, 
						'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Email'),
					array('name' => 'Voter No',
						'filter' => CHtml::activeTextField($model, 'ctt_voter_no', array('class' => 'form-control', 'placeholder' => 'Search by Voter No ')),
						'value' => '$data[ctt_voter_no]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Voter No'),
					array('name' => 'aadhaar_no',
						'filter' => CHtml::activeTextField($model, 'ctt_aadhaar_no', array('class' => 'form-control', 'placeholder' => 'Search by Aadhaar No')),
						'value' => '$data[ctt_aadhaar_no]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Aadhaar No'),
					array('name' => 'pan_no', 
							'filter' => CHtml::activeTextField($model, 'ctt_pan_no', array('class' => 'form-control', 'placeholder' => 'Search by Pan No')),
						'value' => '$data[ctt_pan_no]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Pan No'),
					array('name' => 'ctt_license_no', 
							'filter' => CHtml::activeTextField($model, 'ctt_license_no', array('class' => 'form-control', 'placeholder' => 'Search by License No')),
						'value' => '$data[ctt_license_no]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Licens No'),
				array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{merge}',
						'buttons'			 => array(
							'merge'			 => array(
								'visible'=>'(Yii::app()->request->getParam("ctt_id")==null) ? true : false',
						         'click'		 => 'function(e){
                                    try
                                        {
                                            $href = $(this).attr("href");
                                            jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)
                                            {
                                                var mergebox=bootbox.dialog({ 
                                                message: data, 
                                                className:"bootbox-lg",
                                                title:"Merge Contact",
                                                size: "large",
                                               
                                                onEscape: function(){                                                
                                                   bootbox.hideAll();
                                                },
                                            });
                                            }}); 
                                            }
                                            catch(e)
                                            { alert(e); }
                                            return false;
                                         }',
						'url'		 => 'Yii::app()->createUrl("admin/contact/mergeduplicatecontact", array(\'ctt_id\' => $data[ctt_id],\'phone_no\' => $data[phn_phone_no],\'ctt_aadhaar_no\' => $data[ctt_aadhaar_no],\'ctt_pan_no\' => $data[ctt_pan_no],\'ctt_voter_no\' => $data[ctt_voter_no],\'ctt_license_no\' => $data[ctt_license_no],\'email_address\' => $data[eml_email_address]))',
						'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\driver\merge.png',
						'label'		 => '<i class="fa fa-credit-card"></i>',
						'options'	 => array('data-toggle' => 'ajaxModal', 'id' => $data[ctt_id],'class' => 'btn btn-xs ignoreMergeView p0',  'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs merge p0', 'title' => 'Merge Contact'),
					    ),
							
							'htmlOptions'	 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
	
	<?php //if($cttid!=""){?>
<!--	 <div class="panel-footer">
                <div class="col-xs-12 pl0 ">
                    <button type="button" class="btn btn-primary" onclick="mergeProcess()">Merge</button>
                </div>
            </div>-->
	<?php //} ?>
</div>
<script type="text/javascript">
    $(document).ready(function(){
       $('.bootbox').removeAttr('tabindex');
		 
		$("#contact_id_all").click(function (){
		if (this.checked)
		{
			$('#mergeform .checker span').addClass('checked');
			$('#mergeform input[name="contact_id[]"]').attr('checked', 'true');
		} else
		{
			$('#mergeform .checker span').removeClass('checked');
			$('#mergeform input[name="contact_id[]"]').attr('checked', 'false');

		}
	});
    });
    function mergeProcess() {
        var $keys = [];
        $('[name="contact_id[]"]').each(function () {
            if ($(this).prop('checked') == true) {
				 $keys.push($(this).val());
            }
        });
        var numrows = $keys.length;
        if (numrows < 1)
        {
            bootbox.alert("Please select atleast 1 contact");
        } 
		else if(numrows > 1)
		{
			bootbox.alert("More Than 1 Contact Not Allowed");
		}
		else {
            $strDrvKeys = $keys.join();
			window.location.href = '<?php echo Yii::app()->createUrl('admin/contact/mergecontact'); ?>?ctt_id=' +<?= $cttid ?> + '&mgrctt_id=' + $strDrvKeys;
//            var dialog1 = bootbox.confirm("Do you want to merge the selected contact."
//                    , function (confirmed) {
//                        if (confirmed) {
//                            window.location.href = '<?php echo Yii::app()->createUrl('admin/contact/mergecontact'); ?>?ctt_id=' +<?= $cttid ?> + '&mgrctt_id=' + $strDrvKeys;
//                        } else {
//                            $(dialog1).modal('hide');
//                        }
//                    });
        }
    }
</script>

