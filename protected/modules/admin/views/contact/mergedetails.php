<style>
	.modal {
		overflow-y:auto;
	}

</style>
<div class="row">

    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			$params									 = array_filter($_REQUEST);
			$dataProvider->getPagination()->params	 = $params;
			$dataProvider->getSort()->params		 = $params;
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'id'				 => 'contactlist' . $cttid,
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
					array('name'	 => 'Contact Id',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[ctt_id];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Contact Id'),
					array('name'	 => 'Contact Name',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[ctt_first_name] . ' ' . $data[ctt_last_name];
						},
						'sortable'								 => true,
						'headerHtmlOptions'						 => array(),
						'header'								 => 'Name'
					),
					array('name'	 => 'License No',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[ctt_license_no];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'License No'
					),
					array('name'	 => 'Pan No',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[ctt_pan_no];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Pan No'
					),
					array('name'	 => 'Bank Name',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[ctt_bank_name];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Bank Name'
					),
					array('name'	 => 'Branch Name',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[ctt_bank_branch];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Branch Name'
					),
					array('name'	 => 'Bank Account No',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[ctt_bank_account_no];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Bank A/C No'
					),
					array('name'	 => 'IFSC Code',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[ctt_bank_ifsc];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'IFSC Code'
					),
					array('name'	 => 'Beneficiary Name',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[ctt_beneficiary_name];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Beneficiary Name'
					),
					array('name'	 => 'Beneficiary Id',
						'filter' => true,
						'value'	 => function($data) {
							echo $data[ctt_beneficiary_id];
						},
						'sortable'			 => true,
						'headerHtmlOptions'	 => array(),
						'header'			 => 'Beneficiary Id'
					),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{merge}',
						'buttons'			 => array(
							'merge'			 => array(
								'visible'	 => '(Yii::app()->request->getParam("ctt_id")==null) ? true : false',
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
								'options'	 => array('data-toggle' => 'ajaxModal', 'id' => $data[ctt_id], 'class' => 'btn btn-xs ignoreMergeView p0', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs merge p0', 'title' => 'Merge Contact'),
							),
							'htmlOptions'	 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>





