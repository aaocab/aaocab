 
<?php
$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div id="content" class=" mt20" style="width: 100%!important;overflow: auto;">
	<a href="#" class="btn btn-primary mb10" onclick="transferWithdrawable();" style="text-decoration: none;margin-left: 20px;">Release Withdrawable payment for selected vendors
	</a>
	<div class="row1">

		<div class="col-xs-12">
			<div class="panel panel-default">
				<div class="panel-body" >
					<?php
					/* @var $dataProvider CActiveDataProvider */
					if (!empty($dataProvider))
					{
						$GLOBALS['cityData']					 = Cities::getCityName();
						$params									 = array_filter($_REQUEST);
						$dataProvider->getPagination()->pageSize = 30;
						$dataProvider->getPagination()->params	 = $params;
						$dataProvider->getSort()->params		 = $params;
						$this->widget('booster.widgets.TbGridView', array(
							'id'				 => 'vendorListGrid',
							'responsiveTable'	 => true,
							'selectableRows'	 => 2,
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
								array('class'	 => 'CCheckBoxColumn', "value"	 => function ($data) {
										return $data['vnd_id'];
									},'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'id' => 'vnd_checked[]'),
								array('name'				 => 'vnd_name',
									'filter'			 => CHtml::activeTextField($model, 'vnd_name', array('class' => 'form-control', 'placeholder' => 'Search by ' . $model->getAttributeLabel('vnd_name'))),
									'value'				 => $data["vnd_name"],
									'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => $model->getAttributeLabel('vnd_name')),
								array('name'	 => 'security', 'value'	 =>
									function ($data) {
										echo
										"Bank: " . $data["bank_name"] .
										"<br>Branch: " . $data["bank_branch"] .
										"<br>Beneficiary Name: " . $data["beneficiary_name"] .
										"<br>Account Type: " . $data["account_type"] .
										"<br>IFSC: " . $data["bank_ifsc"] .
										"<br>Account No: " . $data["bank_account_no"];
									}
									, 'sortable'			 => true, 'filter'			 => FALSE,
									'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-center'),
									'htmlOptions'		 => array('class' => ''), 'header'			 => 'Bank Details'),
								array('name'				 => 'security', 'value'				 => ' $data["vrs_security_amount"]', 'sortable'			 => true, 'filter'			 => FALSE,
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
									'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Security Deposit in <i class="fa fa-inr"></i>'),
								array('name'				 => 'vendor_amount', 'value'				 => '$data["vendor_amount"]', 'sortable'			 => true, 'filter'			 => FALSE,
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
									'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Vendor Amount <br>in <i class="fa fa-inr"></i>'),
								array('name'				 => 'vrs_withdrawable_balance', 'value'				 => ' $data["vrs_withdrawable_balance"]', 'sortable'			 => true, 'filter'			 => FALSE,
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
									'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Payable Amount <br>in <i class="fa fa-inr"></i>'),
								array('name'				 => 'locked', 'value'				 => ' $data["locked_amount"]', 'sortable'			 => true, 'filter'			 => FALSE,
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'),
									'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Locked Amount <br>in <i class="fa fa-inr"></i>'),
						)));
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<a href="#" class="btn btn-primary mb10" onclick="transferWithdrawable();" style="text-decoration: none;margin-left: 20px;">Release Withdrawable payment for selected vendors
	</a>





</div>
<script>
	function transferWithdrawable() {
		debugger;
		// var keys = $('#vendorListGrid').yiiGridView('getSelection');
		var keys = $('input[type="checkbox"][name="vnd_checked\\[\\]"]:checked').map(function () {
			return this.value;
		}).get();

		var numrows = keys.length;

		if (keys == '') {
			bootbox.alert("Please select atleast one Vendor.");
		} else {

			bootbox.confirm("Do you want to pay to <b>" + numrows + "</b>  selected vendors."
					, function (confirmed) {
						if (confirmed) {
							window.location.href = '<?php echo Yii::app()->createUrl('admin/vendor/paytransfer'); ?>?vnd_ids=' + keys.join();
						}
					});
		}
	}
</script>