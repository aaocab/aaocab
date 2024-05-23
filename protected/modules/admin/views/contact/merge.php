<style>
    
	.modal {  overflow-y:auto;}
	
</style>
<div class="row">
    <div class="col-md-12">
        <div class="panel" >
            <div class="panel-body panel-no-padding p0 pt10">

                <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
					<?php
					if (!empty($dataProvider))
					{
					    $params	 = array_filter($_REQUEST);
						$dataProvider->getPagination()->pageSize = 20;
						$dataProvider->getPagination()->params	 = $params;
						$dataProvider->getSort()->params		 = $params;
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'mergeform',
							'responsiveTable'	 => true,
							'emptyText' => $active==11?'<p align="center">Search contact with name,email and phone</p>':"No results found",
							'selectableRows' => 2,
							'filter'			 => $model,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-5 pt5'>{summary}</div><div class='col-xs-12 col-sm-7 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table table-striped table-bordered mb0',
							'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
							'columns'			 => array(
	            	
								 array(
				'class'			 => 'CCheckBoxColumn',
				'header'		 => 'html',
				'id'			 => 'contact_id',
				'selectableRows' => '{items}',
				'selectableRows' => 2,
				'value'			 => '$data["ctt_id"]',
				'headerTemplate' => '<label>{item}<span></span></label>',
				'htmlOptions'	 => array('style' => 'width: 20px'),
			),
								
					
					array('name' => 'Contact Id', 
						 'filter' => false,
						'value' =>function($data) {
							echo $data[ctt_id];
							
						},
						'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Contact Id'),
								array('name' => 'contactperson',
									'filter' => CHtml::activeTextField($model, 'name', array('class' => 'form-control', 'placeholder' => 'Search by name ')),
									'value' => function($data) { 
							          echo $data[contactperson];
							          echo $type;
							            if ($data['ctt_is_verified'] != 0 && $type == ''){
								          echo ' <span><img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Contact Verified" width="26"></span>';
							            }
							           else {
								       echo ' <span><img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="26"></span>';
							        }
						        },'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-4', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Name/Company Name'),
						     array(
								 'name' => 'email', 
							     'filter' => CHtml::activeTextField($model, 'email_address',  array('class' => 'form-control', 'placeholder' => 'Search by email')),
								 'value' =>function($data) {
							          echo $data[eml_email_address];
							          if ($data['eml_is_verified'] != 0){
								       echo ' <span><img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Verified" width="26"></span>';
							         }
							        else{
								      echo ' <span><img src="/images/icon/unblock.png" style="cursor:pointer" title="UnVerified" width="26"></span>';
							        }
						       }, 
						        'sortable' => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-4', 'class' => 'text-center'), 'header' => 'Email'),	   
						 		array('name' => 'phone',  
									 'filter' => CHtml::activeTextField($model, 'phone_no', array('class' => 'form-control', 'placeholder' => 'Search by Phone')),
									'value' =>function($data) {
							        echo $data[phn_phone_no];
							        if ($data['phn_is_verified'] != 0){
								         echo ' <span><img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Verified" width="26"></span>';
							        }
							       else{
								    echo ' <span><img src="/images/icon/unblock.png" style="cursor:pointer" title="UnVerified" width="26"></span>';
							    }
						},
						'sortable' => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-4', 'class' => 'text-center'), 'header' => 'Phone'),
						)));
					}
					
					?>
                </div>
            </div>
            <div class="panel-footer">
                <div class="col-xs-12 pl0 ">
                    <button type="button" class="btn btn-primary" onclick="mergeProcess()">Merge</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
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
