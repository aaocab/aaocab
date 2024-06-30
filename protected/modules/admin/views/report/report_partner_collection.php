 
<?php
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>



<div class="row"> 
	<div class="col-xs-12">
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'collection-form', 'enableClientValidation' => true,
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
		// @var $form TbActiveForm 
		?>
		<div class="col-xs-12 col-sm-6 col-md-3">
			<div class="form-group cityinput"> 
				<?php // echo $form->drop($model,'cpm_vehicle_type');  ?>
				<label>Channel Partner</label>
				<?php
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'agt_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select Channel Partner",
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '100%'),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
                                  populatePartner(this, '{$model->agt_id}');
                                }",
				'load'			 => "js:function(query, callback){
                                loadPartner(query, callback);
                                }",
				'render'		 => "js:{
                                option: function(item, escape){
                                return '<div><span class=\"\"><i class=\"fa fa-user mr5\"></i>' + escape(item.text) +'</span></div>';
                                },
                                option_create: function(data, escape){
                                return '<div>' +'<span class=\"\">' + escape(data.text) + '</span></div>';
                                }
                                }",
					),
				));
				?>
				<?= $form->error($model, 'cpm_agent_id'); ?>
			</div>
		</div>

		<div class="col-xs-12 col-sm-3  col-md-2   mt20 pt5">   
			<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary')); ?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>
<div class="row"> 
	<div class="col-xs-12">

		<div class="alert alert-info ">
			<div class="row text-center h4 m0"> 
				<div class="col-xs-12 col-md-4  ">
					Total Receivable : <i class="fa fa-inr"></i><?= number_format($totalCollection['totalReceived']); ?>
				</div>
				<div class="col-xs-12 col-md-4  ">
					Total Payable : <i class="fa fa-inr"></i><?=  number_format($totalCollection['totalPayable']); ?>

				</div> 
                               <div class="col-xs-12 col-md-4  ">
					Total Wallet Balance : <i class="fa fa-inr"></i><?=  number_format($totalCollection['totalWalletBal']); ?>

				</div> 
			</div> 
		</div>
	</div>
</div>



<div class="row"> 
	<div class="col-xs-12">

		<?php
		if (!empty($dataProvider))
		{
			?>
			<div class = "panel panel-primary   " id = "yw0">
				<div class = "panel-heading"><div class = "row m0">
						<div class = "col-xs-12 col-sm-6 pt5"><div class = "summary">Total <?php echo count($dataProvider); ?> results.</div></div><div class="col-xs-12 col-sm-6 pr0"></div>
					</div></div>
				<div class="panel-body table-responsive table-bordered m0">
					<table class="table table-striped table-bordered dataTable mb0 table">
						<thead>
							<tr>
								<th class="col-xs-2" id="yw0_c0"><a class="sort-link" href="/aaohome/report/Partnercollection?sort=agt_company">Partner <span class="caret"></span></a></th>
								<th class="col-xs-2" id="yw0_c1"><a class="sort-link" href="/aaohome/report/Partnercollection?sort=Receivable">Receivable <span class="caret"></span></a></th>
								<th class="col-xs-2" id="yw0_c2"><a class="sort-link" href="/aaohome/report/Partnercollection?sort=Payable">Payable <span class="caret"></span></a></th>
                                                                <th class="col-xs-2" id="yw0_c2"><a class="sort-link" href="/aaohome/report/Partnercollection?sort=WalletBalance">Wallet Balance <span class="caret"></span></a></th>
				
                                                        </tr>
						</thead>
						<tbody>
							<?php
							$i = 0;
							foreach ($dataProvider as $data)
							{
								?>
								<tr class="<?php echo $i % 2 == 0 ? 'odd' : 'even' ?>">
									<td>					
										<?php
										$agtName = '';
										if (trim($data['agt_company']) != '')
										{
											$agtName .= $data['agt_company'];
										}
										if (trim($data["agt_fname"] . ' ' . $data["agt_lname"]))
										{
											$agtName .= ' (' . trim($data["agt_fname"] . ' ' . $data["agt_lname"]) . ')';
										}
										echo CHtml::link($agtName, Yii::app()->createUrl("admin/agent/ledgerbooking", ["agtId" => $data['agt_id']]), ["class" => "viewAccount", "target" => "_blank"]);
										echo "<br>";
										$agtActive = '';
										if ($data['agt_active'] == 1)
										{
											$agtActive = ' <span class="label label-success">Active</span>';
										}
										else
										{
											$agtActive = ' <span class="label label-danger">Inactive</span>';
										}
										if ($data["agt_type"] == 1)
										{
											$suffix = ' <span class="label label-warning">' . "Corporate Buyer" . '</span>';
										}
										else if ($data["agt_type"] == 2)
										{
											$suffix = ' <span class="label label-info">' . "Authorized Reseller" . '</span>';
										}
										else
										{
											$suffix = ' <span class="label label-primary">' . "Travel Agent" . '</span>';
										}
										echo $agtActive . $suffix;
										?>							
									</td>
									<td><?php echo number_format($data['Receivable']); ?></td>
									<td><?php echo number_format(-1* $data['Payable']); ?></td>
                                                                        <td><?php echo number_format($data['WalletBalance']); ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table></div>
			</div> 
		<?php } ?>
	</div>  
</div>