<link rel="stylesheet" href="/css/font-awesome/css/font-awesome.css">
<link rel="stylesheet" href="/css/site.min.css">
<link rel="stylesheet" type="text/css" href="/css/component.css"/>
<link href="/css/hover.css" rel="stylesheet" media="all">
<link rel="stylesheet" href="/css/site.css?v=site.css?v=<?= Yii::app()->params['sitecssVersion']; ?>">
<title>Magnetic Field Festival Report</title>
<? if ($error == 0)
{
	?>
	<?php
	$form			 = $this->beginWidget('booster.widgets.TbActiveForm', array(
		'id'					 => 'search-form', 'enableClientValidation' => true,
		'clientOptions'			 => array(
			'validateOnSubmit'	 => true,
			'errorCssClass'		 => 'has-error'
		),
		'enableAjaxValidation'	 => false,
		'errorMessageCssClass'	 => 'help-block',
		'htmlOptions'			 => array(
			'class' => 'form-horizontal'
		),
	));
	?>
	<div class="row" id="bookingsDiv" style="margin-top: 10px;">  
		<div class="col-xs-12 col-sm-12 col-md-12 float-none marginauto">      
			<div class="col-xs-12" style="color: blue;background: whitesmoke;text-align: center">
				<u>Cab Details(Magnetic Fields Festival)</u>
			</div> <div class="col-xs-12" style="text-align: center">(Total :<?= count($model) ?>)</div>
			<div class="col-xs-6">    
				<?
				$vendorListJson	 = Vendors::model()->getJSONCorpvendor();
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $modelBookingMff,
					'attribute'		 => 'vendor_id',
					'val'			 => $model->vendor_id,
					'asDropDownList' => FALSE,
					'options'		 => array('data' => new CJavaScriptExpression($vendorListJson), 'allowClear' => true),
					'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'Select Vendor')
				));
				?>
			</div>
			<div class="col-xs-6">    
				<?
				$zoneListJson	 = Zones::model()->getJSONCorptozone();
				$this->widget('booster.widgets.TbSelect2', array(
					'model'			 => $modelBookingMff,
					'attribute'		 => 'to_zone_id',
					'val'			 => $model->to_zone_id,
					'asDropDownList' => FALSE,
					'options'		 => array('data' => new CJavaScriptExpression($zoneListJson), 'allowClear' => true),
					'htmlOptions'	 => array('style' => 'width:100%', 'placeholder' => 'To Zone')
				));
				?>
			</div>
			<div class="col-xs-12 text-center">
			<?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30')); ?>
			</div>
	<?php $this->endWidget(); ?>
			<table class="table table-bordered" style="background: #fff; font-size: 13px;">
				<thead>
					<tr style="color: black;background: whitesmoke">
						<th rowspan="2"><u>Day</u></th>      
						<th  rowspan="2"><u>Time Range</u></th>   
			<!--         <th ><u>time slice</u></th>   -->
						<th  rowspan="2"><u>Cab Required</u></th>   
						<th  rowspan="2"><u>Sedan</u></th>   
						<th  rowspan="2"><u>SUV</u></th>   
						<th colspan="2">Corporate</th>   
						<th colspan="2">Vendor not assigned</th>   
						<th colspan="2">Vendor not assigned (Corporate)</th>      
						<th colspan="2">Cab not assigned</th>        
					</tr>
					<tr>
						<th><u>Sedan</u></th>
						<th><u>SUV</u></th>
						<th><u>Sedan</u></th>
						<th><u>SUV</u></th>
						<th><u>Sedan</u></th>
						<th><u>SUV</u></th>
						<th><u>Sedan</u></th>
						<th><u>SUV</u></th>
					</tr>
				</thead>
				<tbody>                         
					<? foreach ($model as $data)
					{
						?>
						<tr>
							<td><?= $data['d'] ?></td>
		<!--                <td><?= $data['q'] ?></td>-->
							<td><?= $data['time_range'] ?></td>
							<td><?= $data['cnt'] ?></td>
							<td><?= $data['sedan'] ?></td>
							<td><?= $data['suv'] ?></td>

							<td><?= $data['corp_sedan'] ?></td>
							<td><?= $data['corp_suv'] ?></td> 

							<td><?= $data['unassigned_sedan'] ?></td>
							<td><?= $data['unassigned_suv'] ?></td> 

							<td><?= $data['corp_unassigned_sedan'] ?></td>
							<td><?= $data['corp_unassigned_suv'] ?></td> 

							<td><?= $data['unassigned_cab_sedan'] ?></td>
							<td><?= $data['unassigned_cab_suv'] ?></td>                        
						</tr>
	<? } ?>
				</tbody>
			</table>
		</div>
	</div>
<? } ?>

