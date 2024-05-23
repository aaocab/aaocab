<style>
    .panel-body{
        padding-top: 0 ;
        padding-bottom: 0;
    }
    .table>tbody>tr>th
    {
        vertical-align: middle
    }

    .table>tbody>tr>td, .table>tbody>tr>th{
        padding: 7px;
        line-height: 1.5em;
    }
</style>
<?
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="row" >

    <div class="col-xs-12">
        <div class="panel panel-default">
            <div class="panel-body">

				<div class="col-xs-12 col-sm-6" style="border-color: 1px #000000 solid;">
					<?php
					$checkContactAccess	 = Yii::app()->user->checkAccess("bookingContactAccess");
					$checkExportAccess	 = Yii::app()->user->checkAccess("Export");
					if ($checkExportAccess)
					{
						?>
						<?= CHtml::beginForm(Yii::app()->createUrl('admin/report/vendorcollection'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>

						<div class="row">
							<div class="col-xs-12">
								<div class="col-xs-12  ">
									<input type="hidden" id="export1" name="export1" value="true"/>
									<input type="hidden" id="export_vnd_operator" name="export_vnd_operator" value="<?= $model->vnd_operator ?>">
									<input type="hidden" id="export_vnd_zone" name="export_vnd_zone" value="<?= $model->vnd_zone ?>">
									<input type="hidden" id="export_vnd_cty" name="export_vnd_cty" value="<?= $model->vnd_cty ?>">
									<input type="hidden" id="export_vnd_amount_pay" name="export_vnd_amount_pay" value="<?= $model->vnd_amount_pay; ?>">
									<input type="hidden" id="export_vnd_amount" name="export_vnd_amount" value="<?= $model->vnd_amount; ?>">
									<input type="hidden" id="export_vnd_rm" name="export_vnd_rm" value="<?= $model->vnd_rm; ?>">
									Select day range:
									<select name="export_day_range" style="margin-top:8px;margin-right:10px;padding:5px;">
										<option value="15" <?php if($model->dayRange==15) { echo "selected='selected'"; }?> >15 days</option>
										<option value="30" <?php if($model->dayRange==30) { echo "selected='selected'"; }?> >30 days</option>
										<option value=""   <?php if($model->dayRange=="") { echo "selected='selected'"; }?>  >All</option>
										<select/>
										<button class="btn btn-default" type="submit" style="width: 185px;">Export table by day range</button>

								</div>
							</div>
						</div>
						<?= CHtml::endForm() ?>

					</div>

					<div class="col-xs-12 col-sm-6">
						<?php
						$form = $this->beginWidget('CActiveForm', array(
							'id'					 => 'frmCSVImport',
							'enableAjaxValidation'	 => true,
							'htmlOptions'			 => array('enctype' => 'multipart/form-data'),
						));
						?>
						<div class="input-row">
							<input type="file" name="file" id="file" accept=".csv" required="required">
							<div id="response"></div>
							<br />
							<button type="submit" id="submit" name="import" class="btn btn-primary">Import CSV File</button>
							<br />
							<?php
							if ($message != '')
							{

								echo '<b>' . $message . '</b>';
							}
							?>
						</div>
						<?php
						$this->endWidget();
						?>
						</br>
					</div>
				</div>
			<?php } ?>
		</div>	

		<div class="row">
			<div class="col-xs-12">

				<?php
				$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
					'id'					 => 'vendorCollectionList', 'enableClientValidation' => true,
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


				<div class="row">
					<div class="col-xs-12 col-sm-4 col-md-3"> 
						<div class="form-group">
							<label class="control-label" style="margin-left:5px;">Search By Vendor</label>
							<?= $form->textFieldGroup($model, 'vnd_operator', array('label' => '', 'widgetOptions' => ['htmlOptions' => [array('placeholder' => 'Operator Name')]])) ?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-3"> 
						<div class="form-group">
							<label class="control-label" style="margin-left:5px;">Search By Zone</label>
							<?php
							$zoneListJson	 = Zones::model()->getJSON();
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'vnd_zone',
								'val'			 => $model->vnd_zone,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($zoneListJson), 'allowClear' => true),
								'htmlOptions'	 => array('style' => 'width:100%;margin-left:5px;', 'placeholder' => 'Zone List')
							));
							?>
						</div>
					</div>

					<div class="col-xs-12 col-sm-4 col-md-3"> 
						<div class="form-group">
							<label class="control-label">Payable/Receivable</label>
							<?php
							$amtPayable		 = Vendors::model()->getAmountPayable();
							$dataPay		 = VehicleTypes::model()->getJSON($amtPayable);
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'vnd_amount_pay',
								'val'			 => $model->vnd_amount_pay,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Payable/Receivable')
							));
							?>
						</div>
					</div>                    
					<div class="col-xs-12 col-sm-4 col-md-3"> 
						<div class="form-group">
							<label class="control-label">Amount</label>
							<?= $form->textFieldGroup($model, 'vnd_amount', array('label' => '', 'widgetOptions' => ['htmlOptions' => [array('placeholder' => 'Amount')]])) ?>
						</div>
					</div>  

					<div class="col-xs-12 col-sm-4 col-md-3"> 
						<div class="form-group">
							<label class="control-label">Relationship Manager</label>
							<?php
							$this->widget('booster.widgets.TbSelect2', array(
								'model'			 => $model,
								'attribute'		 => 'vnd_rm',
								'val'			 => $model->vnd_rm,
								'asDropDownList' => FALSE,
								'options'		 => array('data' => new CJavaScriptExpression(Vendors::model()->getRelationManager()), 'allowClear' => true),
								'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Relationship Manager')
							));
							?>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4 col-md-3"> 
						<div class="form-group cityinput">
							<label class="control-label">Search By City</label>
							<?php
//                                $datacity = Cities::model()->getCityByBooking1();
//                                $this->widget('booster.widgets.TbSelect2', array(
//                                    'model' => $model,
//                                    'attribute' => 'vnd_cty',
//                                    'val' => $model->vnd_cty,
//                                    'asDropDownList' => FALSE,
//                                    'options' => array('data' => new CJavaScriptExpression($datacity), 'allowClear' => true),
//                                    'htmlOptions' => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'City List')
//                                ));

							$this->widget('ext.yii-selectize.YiiSelectize', array(
								'model'				 => $model,
								'attribute'			 => 'vnd_cty',
								'useWithBootstrap'	 => true,
								"placeholder"		 => "Select City",
								'fullWidth'			 => false,
								'htmlOptions'		 => array('width' => '100%',
								//  'id' => 'from_city_id1'
								),
								'defaultOptions'	 => $selectizeOptions + array(
							'onInitialize'	 => "js:function(){
                                        populateSourceCity(this, '{$model->vnd_cty}');
                                            }",
							'load'			 => "js:function(query, callback){
                                            loadSourceCity(query, callback);
                                            }",
							'render'		 => "js:{
                                                option: function(item, escape){
                                                return '<div><span class=\"\"><i class=\"fa fa-map-marker mr5\"></i>' + escape(item.text) +'</span></div>';
                                                },
                                                option_create: function(data, escape){
                                                return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                                }
                                            }",
								),
							));
							?>
						</div>
					</div>

					<?php
					echo $form->hiddenField($model, 'dayRange');
					?>

				</div>
				<div class="row">
					<div class="  col-xs-12 text-center">
						<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20" style="padding: 4px;">
							<button class="btn btn-primary full-width" type="submit"  name="bookingSearch">Search</button>
						</div>
					</div>
				</div>


				<?php $this->endWidget(); ?>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="  table table-bordered">
					<?php
					if (!empty($dataProvider))
					{

						$params									 = array_filter($_REQUEST);
						$dataProvider->getPagination()->params	 = $params;
						$dataProvider->getSort()->params		 = $params;
						$this->widget('booster.widgets.TbExtendedGridView', array(
							'id'				 => 'vendorListGrid',
							'responsiveTable'	 => true,
							'dataProvider'		 => $dataProvider,
							'template'			 => "<div class='panel-heading'><div class='row m0'>
							<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
							</div></div>
							<div class='panel-body table-responsive'>{items}</div>
							<div class='panel-footer'>
							<div class='row' ><div class='col-xs-12 col-sm-6 p5'>{summary}</div>
							<div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
							'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
							'htmlOptions'		 => array('class' => 'panel panel-primary compact'),
							//       'ajaxType' => 'POST',
							'columns'			 => array(
								array('name'	 => 'vnd_name', 'value'	 => function ($data) {

										echo CHtml::link($data["vnd_name"], Yii::app()->createUrl("admin/vendor/view", ["id" => $data['vnd_id']]), ["class" => "", "target" => "_blank"]);
										echo ($data['vnd_code'] != '') ? "<br>( " . $data['vnd_code'] . " )" : '';
										//echo CHtml::link($data['vnd_name'], Yii::app()->createUrl('admin/vendor/vendoraccount/', ['vnd_id' => $data['vnd_id'], 'ven_from_date' => $GLOBALS['venFromDate'], 'ven_to_date' => $GLOBALS['venToDate']]));
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Operator Name'),
								array('name'	 => 'relation_manager', 'value'	 => function ($data) {
										echo $data['relation_manager'];
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-left'), 'htmlOptions'		 => array('class' => 'text-left'), 'header'			 => 'Relationship Manager'),
								array('name'				 => 'vrs_credit_limit', 'value'				 => '$data[vnd_credit_limit]',
									'class'				 => 'booster.widgets.TbEditableColumn',
									'editable'			 => array(
										'type'	 => 'text',
										'url'	 => Yii::app()->createUrl('admin/vendor/updatecredit'),
										'apply'	 => 'Yii::app()->user->checkAccess("vendorEdit")',
									),
									'sortable'			 => true
									, 'htmlOptions'		 => array('style' => 'max-width:200px', 'class' => 'text-center')
									, 'headerHtmlOptions'	 => array('style' => 'max-width:200px', 'class' => 'text-center')
									, 'htmlOptions'		 => array('class' => 'text-right')
									, 'header'			 => 'Credit Limit'),
								array('name'	 => 'vnd_effective_credit_limit', 'value'	 => function ($data) {
										if ($data['vnd_effective_credit_limit'] != 0)
										{
											echo '<i class="fa fa-inr"></i>' . $data['vnd_effective_credit_limit'];
										}
										else
										{
											echo '<i class="fa fa-inr">0</i>';
										}
									}
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Effective Credit Limit'),
								array('name'	 => 'vnd_effective_overdue_days', 'value'	 => function ($data) {
										if ($data['vnd_effective_overdue_days'] != 0)
										{
											$overdueDays = ($data['vnd_effective_overdue_days']) >= 7 ? ($data['vnd_effective_overdue_days'] - 7) : 0;
											echo $overdueDays;
										}
										else
										{
											echo '0';
										}
									}
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Overdue Days'),
								array('name'	 => 'security', 'value'	 => function ($data) {
										if ($data['vnd_security_amount'] > 0)
										{
											echo '<i class="fa fa-inr"></i>' . $data['vnd_security_amount'] . ' on ' . DateTimeFormat::DateToDatePicker($data["vrs_security_receive_date"]);
										}
									}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Security Deposit'),
								array('name'	 => 'lastTrans', 'value'	 => function ($data) {
										$acctData	 = AccountTransDetails::getLastPaymentReceived($data['vnd_id'], '2');
										$paymentRecv = ($acctData['paymentReceived'] > 0) ? $acctData['paymentReceived'] : '0';
										echo '<i class="fa fa-inr">' . $paymentRecv . '</i>';
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Last Payment recvd amt'),
								array('name'	 => 'lastTransDate', 'value'	 => function ($data) {
										$acctData = AccountTransDetails::getLastPaymentReceived($data['vnd_id'], '2');
										echo ($acctData['ReceivedDate'] != '') ? $acctData['ReceivedDate'] : '';
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-left'), 'header'			 => 'Last Payment recvd date'),
								array('name'	 => 'lastTransSent', 'value'	 => function ($data) {
										$acctData	 = AccountTransDetails::getLastPaymentSent($data['vnd_id'], '2');
										$paymentSent = ($acctData['paymentSent'] < 0) ? $acctData['paymentSent'] : '0';
										echo '<i class="fa fa-inr">' . $paymentSent . '</i>';
									}
									, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Last payment sent amount'),
								array('name'	 => 'lastTransSentDate', 'value'	 => function ($data) {
										$acctData = AccountTransDetails::getLastPaymentSent($data['vnd_id'], '2');
										echo ($acctData['sentDate'] != '') ? $acctData['sentDate'] : '';
									}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-left'), 'header'			 => 'Last payment sent date'),
								array('name'	 => 'totTrans',
									'value'	 => function ($data) {
										echo '<i class="fa fa-inr"></i>' . $data['totTrans'] . "<br>";
										echo '<i class="fa fa-inr"></i>' . $data['vrs_locked_amount'];
									}
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Running Balance ;<br> Locked amount'),
								array('name'	 => 'Number of Contact',
									'value'	 => function ($data) {
										echo $data['cntContact'];
									}
									, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => ' Number of Contact'),
								array('name'	 => 'vsm_avg30',
									'value'	 => function ($data) {
										$amtToPay = 0;
										if ($data['totTrans'] < 0)
										{
											$amtToPay	 = (abs($data['totTrans']) - $data['vnd_credit_limit']);
											$amtToPay	 = ($amtToPay > 0) ? $amtToPay : 0;
										}
										echo '<i class="fa fa-inr"></i>' . $data['vsm_avg30'] . '<br>';
										echo '<i class="fa fa-inr"></i>' . $data['vsm_avg10'] . '<br>';
										echo '<i class="fa fa-inr"></i>' . $amtToPay;
									}
									, 'sortable'			 => true
									, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center')
									, 'htmlOptions'		 => array('class' => 'text-right')
									, 'header'			 => 'ADB(30D)<br>ADB(10D)<br>ADB(Amt to pay)'),
								array('name'	 => 'withdrawable_balance',
									'value'	 => function ($data) {
										echo '<i class="fa fa-inr"></i>' . $data['withdrawable_balance'];
									}
									, 'sortable'			 => true
									, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center')
									, 'htmlOptions'		 => array('class' => 'text-right')
									, 'header'			 => 'Withdrawable Balance'),
								array('name' => 'last_trip_completed_date', 'value' => '$data[last_trip_completed_date]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-left'), 'header' => 'Last Trip completed date'),
								array('name'	 => 'vnd_is_freeze',
									'value'	 => function ($data) {
										if ($data['vnd_active'] == 2)
										{
											echo 'Blocked';
										}
										else if ($data['vnd_is_freeze'] == 1)
										{
											echo 'Frozen (System)';
										}
										else if ($data['vnd_is_freeze'] == 2)
										{
											echo 'Frozen (Admin)';
										}
										else if ($data['vnd_cod_freeze'] == 0)
										{
											echo 'COD unfrozen';
										}
										else if ($data['vnd_cod_freeze'] == 1)
										{
											echo 'COD Frozen';
										}
										else
										{
											echo 'Active';
										}
									},
									'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center'), 'htmlOptions'		 => array('class' => 'text-right'), 'header'			 => 'Account status '),
								array('name' => 'trips', 'value' => '$data[trips]', 'sortable' => false, 'headerHtmlOptions' => array('class' => 'col-xs-1 text-center'), 'htmlOptions' => array('class' => 'text-center'), 'header' => 'Trips'),
								array('name'	 => 'rating', 'type'	 => 'raw', 'value'	 => function ($data) {
										echo CHtml::link($data['rating'], Yii::app()->createUrl("admin/rating/listbyvendor", ["vendor_id" => $data['vnd_id']]), ["class" => "viewRating", "onclick" => "return viewRating(" . $data['vnd_id'] . ")"]);
									}, 'sortable'			 => true,
									'htmlOptions'		 => array('class' => 'text-center'),
									'headerHtmlOptions'	 => array('class' => 'text-center'),
									'header'			 => 'Rating'),
								array(
									'header'			 => 'Action',
									'class'				 => 'CButtonColumn',
									'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => 'action_box'),
									'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
									'template'			 => '{showaccount}{log}{addremark}{active}{inactive}<br>{cod_active}{cod_inactive}{vendorassign}{vendorunassign}{agreement}',
									'buttons'			 => array(
										'showaccount'	 => array(
											//, 'ven_from_date' =>c, 'ven_to_date' => $GLOBALS['venToDate']
											'url'		 => 'Yii::app()->createUrl("admin/vendor/vendoraccount", array("vnd_id" => $data["vnd_id"],"ven_from_date"=>$GLOBALS["venFromDate"],"ven_to_date"=>$GLOBALS["venToDate"]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\gozocoins.png',
											'label'		 => '<i class="fa fa-check"></i>',
											'options'	 => array('style'			 => '',
												'target'		 => '_blank',
												'rel'			 => 'popover',
												'data-placement' => 'left',
												'class'			 => 'accdetails btn btn-xs p0',
												'title'			 => 'Vendor Account'),
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
                                                                            title: \'Vendor Log\',
                                                                            size: \'large\',
                                                                            onEscape: function () {

                                                                                // user pressed escape
                                                                            }
                                                                        });
                                                                    }
                                                                    });
                                                                    return false;
                                                                    }',
											'url'		 => 'Yii::app()->createUrl("admin/vendor/showlog", array("vndid" => $data[vnd_id]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\show_log.png',
											'label'		 => '<i class="fa fa-list"></i>',
											'options'	 => array('data-toggle' => 'ajaxModal', 'style' => '', 'class' => 'btn btn-xs conshowlog p0', 'title' => 'Show Log'),
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
											'url'		 => 'Yii::app()->createUrl("admpnl/vendor/addremark", array("vnd_id" => $data[vnd_id]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\add_remarks.png',
											'visible'	 => '($data[vnd_active] == 1 && Yii::app()->user->checkAccess("vendorChangestatus"))',
											'label'		 => '<i class="fa fa-toggle-on"></i>',
											'options'	 => array('data-toggle'	 => 'ajaxModal',
												'id'			 => 'remark',
												'style'			 => '',
												'rel'			 => 'popover',
												'data-placement' => 'left',
												'class'			 => 'btn btn-xs addremark p0',
												'title'			 => 'Add Remark')
										),
										'active'		 => array(
											'click'		 => 'function(e){
                                                                    var con = confirm("Are you sure you want to block this vendor?"); 
                                                                    if(con){
                                                                        $href = $(this).attr(\'href\');
                                                                        $.ajax({
                                                                            url: $href,
                                                                            dataType: "json",
                                                                            className:"bootbox-sm",
                                                                            title:"Block Vendor",
                                                                            success: function(result)
                                                                            {
                                                                                if(result.success)
                                                                                {
                                                                                    refreshVendorGrid();
                                                                                }else
                                                                                {
                                                                                    alert(\'Sorry error occured\');
                                                                                }
                                                                            },
                                                                            error: function(xhr, status, error){
                                                                                alert(\'Sorry error occured\');
                                                                            }
                                                                        });
                                                                    }
                                                                    return false;    
                                                                    }',
											'url'		 => 'Yii::app()->createUrl("admpnl/vendor/changestatus", array("vnd_id" => $data[vnd_id],"vnd_active"=>$data[vnd_active]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\vendor_unblock.png',
											'visible'	 => '($data[vnd_active] == 1 && Yii::app()->user->checkAccess("vendorEdit"))',
											'label'		 => '<i class="fa fa-toggle-on"></i>',
											'options'	 => array('data-toggle'	 => 'ajaxModal',
												'id'			 => 'codActive',
												'style'			 => '',
												'rel'			 => 'popover',
												'data-placement' => 'left',
												'class'			 => 'btn btn-xs active p0',
												'title'			 => 'Block Vendor')
										),
										'inactive'		 => array(
											'click'		 => 'function(){
                                                                    var con = confirm("Are you sure you want to unblock this vendor?"); 
                                                                       if(con){
                                                                           $href = $(this).attr(\'href\');
                                                                           $.ajax({
                                                                               url: $href,
                                                                               dataType: "json",
                                                                               className:"bootbox-sm",
                                                                               title:"Active Vendor",
                                                                               success: function(result){
                                                                                   if(result.success){
                                                                                       refreshVendorGrid();
                                                                                   }else{
                                                                                       alert(\'Sorry error occured\');
                                                                                   }
                                                                               },
                                                                               error: function(xhr, status, error){
                                                                                   alert(\'Sorry error occured\');
                                                                               }
                                                                           });
                                                                       }
                                                                       return false;
                                                                    }',
											'url'		 => 'Yii::app()->createUrl("admpnl/vendor/changestatus", array("vnd_id" => $data[vnd_id],"vnd_active"=> $data[vnd_active]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\vendor_block.png',
											'visible'	 => '($data[vnd_active] == 2 && Yii::app()->user->checkAccess("vendorEdit"))',
											'label'		 => '<i class="fa fa-toggle-off"></i>',
											'options'	 => array('data-toggle'	 => 'ajaxModal',
												'id'			 => 'codInactive',
												'style'			 => '',
												'rel'			 => 'popover',
												'data-placement' => 'left',
												'class'			 => 'btn btn-xs inactive p0',
												'title'			 => 'Unblock Vendor'),
										),
										'vendorassign'	 => array(
											'click'		 => 'function(e){                                                        
                                                                        try
                                                                        {
                                                                            $href = $(this).attr("href");
                                                                            jQuery.ajax({type:"GET",url:$href,success:function(data)
                                                                            {
                                                                                bootbox.dialog({ 
                                                                                message: data, 
                                                                                className:"bootbox-sm",
                                                                                title:"UnFreeze Vendor",
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
											'url'		 => 'Yii::app()->createUrl("admpnl/vendor/freeze", array("vnd_id" => $data[vnd_id],"vnd_is_freeze"=>1))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\unfreeze.png',
											'visible'	 => '($data[vnd_is_freeze] == 1 && Yii::app()->user->checkAccess("vendorChangestatus"))',
											'label'		 => '<i class="fa fa-toggle-on"></i>',
											'options'	 => array('data-toggle'	 => 'ajaxModal',
												'id'			 => 'admFreeze',
												'style'			 => '',
												'rel'			 => 'popover',
												'data-placement' => 'left',
												'class'			 => 'btn btn-xs unfreeze p0',
												'title'			 => 'Unfreeze Vendor')
										),
										'vendorunassign' => array(
											'click'		 => 'function(e){                                                        
                                                                        try
                                                                        {
                                                                            $href = $(this).attr("href");
                                                                            jQuery.ajax({type:"GET",url:$href,success:function(data)
                                                                            {
                                                                                bootbox.dialog({ 
                                                                                message: data, 
                                                                                className:"bootbox-sm",
                                                                                title:"Freeze Vendor",
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
											'url'		 => 'Yii::app()->createUrl("admpnl/vendor/freeze", array("vnd_id" => $data[vnd_id],"vnd_is_freeze"=>0))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\freeze.png',
											'visible'	 => '($data[vnd_is_freeze] == 0 && Yii::app()->user->checkAccess("vendorChangestatus"))',
											'label'		 => '<i class="fa fa-toggle-on"></i>',
											'options'	 => array('data-toggle'	 => 'ajaxModal',
												'id'			 => 'admFreeze',
												'style'			 => '',
												'rel'			 => 'popover',
												'data-placement' => 'left',
												'class'			 => 'btn btn-xs freeze p0',
												'title'			 => 'Freeze Vendor')
										),
										'admunfreeze'	 => array(
											'click'		 => 'function(){
                                                                        var con = confirm("Are you sure you want to admin unfreeze this vendor?"); 
                                                                        if(con){
                                                                        try
                                                                            {
                                                                            $href = $(this).attr("href");
                                                                            jQuery.ajax({type:"GET",url:$href,success:function(data)
                                                                            {
                                                                                bootbox.dialog({ 
                                                                                message: data, 
                                                                                className:"bootbox-sm",
                                                                                title:"Unfreeze Vendor",
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
                                                                            }
                                                                            return false;

                                                                     }',
											'url'		 => 'Yii::app()->createUrl("admpnl/vendor/administrativefreeze", array("vnd_id" => $data[vnd_id],"vnd_is_freeze"=>2))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\admin_unfreeze.png',
											'visible'	 => '($data[vnd_is_freeze] == 2 && Yii::app()->user->checkAccess("vendorChangestatus"))',
											'label'		 => '<i class="fa fa-toggle-on"></i>',
											'options'	 => array('data-toggle'	 => 'ajaxModal',
												'id'			 => 'unfreeze2',
												'style'			 => '',
												'rel'			 => 'popover',
												'data-placement' => 'left',
												'class'			 => 'btn btn-xs admunfreeze p0',
												'title'			 => 'Administrative Unfreeze')
										),
										'admfreeze'		 => array(
											'click'		 => 'function(){
                                                                            var con = confirm("Are you sure you want to freeze this vendor?"); 
                                                                              if(con){
                                                                        try
                                                                            {
                                                                            $href = $(this).attr("href");
                                                                            jQuery.ajax({type:"GET",url:$href,success:function(data)
                                                                            {
                                                                                bootbox.dialog({ 
                                                                                message: data, 
                                                                                className:"bootbox-sm",
                                                                                title:"Freeze Vendor",
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
                                                                            }
                                                                            return false;

                                                                     }',
											'url'		 => 'Yii::app()->createUrl("admpnl/vendor/administrativefreeze", array("vnd_id" => $data[vnd_id],"vnd_is_freeze"=>0))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\admin_freeze.png',
											'visible'	 => '($data[vnd_is_freeze] == 0 && Yii::app()->user->checkAccess("vendorChangestatus"))',
											'label'		 => '<i class="fa fa-toggle-off"></i>',
											'options'	 => array('data-toggle'	 => 'ajaxModal',
												'id'			 => 'freeze2',
												'style'			 => '',
												'rel'			 => 'popover',
												'data-placement' => 'left',
												'class'			 => 'btn btn-xs admfreeze p0',
												'title'			 => 'Administrative Freeze'),
										),
										'cod_active'	 => array(
											'click'		 => 'function(e){
                                                                        var con = confirm("Are you sure you want to freeze COD for this vendor?"); 
                                                                        if(con){
                                                                            $href = $(this).attr(\'href\');
                                                                            $.ajax({
                                                                                url: $href,
                                                                                dataType: "json",
                                                                                className:"bootbox-sm",
                                                                                title:"Inactive COD",
                                                                                success: function(result){
                                                                                    if(result.success){
                                                                                        refreshVendorGrid();
                                                                                    }else{
                                                                                        alert(\'Sorry error occured\');
                                                                                    }
                                                                                },
                                                                                error: function(xhr, status, error){
                                                                                    alert(\'Sorry error occured\');
                                                                                }
                                                                            });
                                                                        }
                                                                        return false;    
                                                                     }',
											'url'		 => 'Yii::app()->createUrl("admpnl/vendor/changecod", array("vnd_id" => $data[vnd_id],"vnd_cod"=>0))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\cod_freeze.png',
											'visible'	 => '($data[vnd_cod_freeze] == 0 && Yii::app()->user->checkAccess("vendorChangestatus"))',
											'label'		 => '<i class="fa fa-toggle-on"></i>',
											'options'	 => array('data-toggle'	 => 'ajaxModal',
												'id'			 => 'codActive',
												'style'			 => '',
												'rel'			 => 'popover',
												'data-placement' => 'left',
												'class'			 => 'btn btn-xs codActive p0',
												'title'			 => 'Freeze COD')
										),
										'cod_inactive'	 => array(
											'click'		 => 'function(){
                                                                     var con = confirm("Are you sure you want to unfreeze COD for this vendor?"); 
                                                                        if(con){
                                                                            $href = $(this).attr(\'href\');
                                                                            $.ajax({
                                                                                url: $href,
                                                                                dataType: "json",
                                                                                className:"bootbox-sm",
                                                                                title:"Active COD",
                                                                                success: function(result){
                                                                                    if(result.success){
                                                                                        refreshVendorGrid();
                                                                                    }else{
                                                                                        alert(\'Sorry error occured\');
                                                                                    }
                                                                                },
                                                                                error: function(xhr, status, error){
                                                                                    alert(\'Sorry error occured\');
                                                                                }
                                                                            });
                                                                        }
                                                                        return false;
                                                                    }',
											'url'		 => 'Yii::app()->createUrl("admpnl/vendor/changecod", array("vnd_id" => $data[vnd_id],"vnd_cod"=>1))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\cod_unfreeze.png',
											'visible'	 => '($data[vnd_cod_freeze] == 1 && Yii::app()->user->checkAccess("vendorChangestatus"))',
											'label'		 => '<i class="fa fa-toggle-off"></i>',
											'options'	 => array('data-toggle'	 => 'ajaxModal',
												'id'			 => 'codInactive',
												'style'			 => '',
												'rel'			 => 'popover',
												'data-placement' => 'left',
												'class'			 => 'btn btn-xs codInactive p0',
												'title'			 => 'Unfreeze COD'),
										),
										'agreement'		 => array(
											'click'		 => 'function(e){
												try {
														$href = $(this).attr("href");
														jQuery.ajax({type:"GET","dataType":"html",url:$href,success:function(data)  
														{
																		var agreement=bootbox.dialog({ 
																		message: data, 
																		className:"bootbox-lg",
																		title:"Approve Agreement",
																		size: "large",
																		callback: function(){   
																		},
																		onEscape: function(){                                                
																		   $(agreement).modal("hide");
																		},
																		});
														}}); 
													}
												catch(e)
												   { alert(e); }
												   return false;
												}',
											'url'		 => 'Yii::app()->createUrl("admin/vendor/agreementShowdoc", array(\'ctt_id\' => $data[contact_id],\'vnd_id\' => $data[vnd_id]))',
											'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\copy_booking.png',
											'label'		 => '<i class="fa fa-check"></i>',
											'options'	 => array('data-toggle'	 => 'ajaxModal',
												'id'			 => 'example2',
												'class'			 => 'btn btn-xs ignoreAgreement p0',
												'rel'			 => 'popover',
												'data-placement' => 'left',
												'class'			 => 'btn btn-xs agreement p0',
												'title'			 => 'Approve Agreement'),
										),
										'htmlOptions'	 => array('class' => 'center'),
									))
						)));
					}
					?> 
				</div>
			</div>  
		</div> 




	</div>  
</div>  

<?php
$version = Yii::app()->params['customJsVersion'];
Yii::app()->clientScript->registerScriptFile(ASSETS_URL . '/js/custom.js?v=' . $version, CClientScript::POS_HEAD);
?>
<script>
    function refreshVendorGrid()
    {
        $('#vendorListGrid').yiiGridView('update');
    }

    function viewRating(vndId) {
        //var href2 = $(obj).attr("href");
        $href2 = $adminUrl + "/rating/listbyvendor";
        $vendorId = vndId;
        $.ajax({
            "url": $href2,
            "type": "GET",
            "dataType": "html",
            data: {"vendor_id": $vendorId},
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: '',
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

    function viewDetail(obj) {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'Vendor Details',
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

<script type="text/javascript">
    $(document).ready(
            function () {
                $("#frmCSVImport").on("submit", function () {

                    $("#response").attr("class", "");
                    $("#response").html("");
                    var fileType = ".csv";
                    var regex = new RegExp("([a-zA-Z0-9\s_\\.\-:])+("
                            + fileType + ")$");
                    if (!regex.test($("#file").val().toLowerCase())) {
                        $("#response").addClass("error");
                        $("#response").addClass("display-block");
                        $("#response").html(
                                "Invalid File. Upload : <b>" + fileType
                                + "</b> Files required.");
                        return false;
                    }
                    return true;
                });
            });

    $('select[name="export_day_range"]').on('change', function () {
        $("#Vendors_dayRange").val($("select[name='export_day_range']").val());
    });
</script>
