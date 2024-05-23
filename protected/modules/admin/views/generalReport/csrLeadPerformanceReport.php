<style>
	.table-flex { display: flex; flex-direction: column; }
	.tr-flex { display: flex; }
	.th-flex, .td-flex{ flex-basis: 35%; }
	.thead-flex, .tbody-flex { overflow-y: scroll; }
	.tbody-flex { max-height: 250px; }
</style>
<?php
$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
	'id'					 => 'booking-form',
	'enableClientValidation' => true,
//		'method'				 => 'post',
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

<div class='row p15'>
	<div class="col-xs-6 col-sm-4 col-md-4" style="">
		<div class="form-group">
			<label class="control-label">Date Range</label>
			<?php
			$daterang	 = "Select Date Range";
			$from_date	 = ($followUps->from_date == '') ? '' : $followUps->from_date;
			$to_date	 = ($followUps->to_date == '') ? '' : $followUps->to_date;
			if ($from_date != '' && $to_date != '')
			{
				$daterang = date('F d, Y', strtotime($from_date)) . " - " . date('F d, Y', strtotime($to_date));
			}
			?>
			<div id="bkgPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
				<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
				<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
			</div>
			<?= $form->hiddenField($followUps, 'from_date'); ?>
			<?= $form->hiddenField($followUps, 'to_date'); ?>

		</div></div>
	<div class="col-xs-12 col-sm-2 col-md-2">
		<div class="form-group">
			<label class="control-label">Team/Team Leader</label>
			<?php
			$selectizeOptions = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
				'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
				'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
				'openOnFocus'		 => true, 'preload'			 => false,
				'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
				'addPrecedence'		 => false,];
			?> 
			<?php
			$this->widget('ext.yii-selectize.YiiSelectize', array(
				'model'				 => $followUps,
				'attribute'			 => 'adminId',
				'useWithBootstrap'	 => true,
				"placeholder"		 => "Select Team Leader",
				'fullWidth'			 => false,
				'htmlOptions'		 => array('width'	 => '100%',
					'id'	 => 'ServiceCallQueue_adminId'
				),
				'defaultOptions'	 => $selectizeOptions + array(
			'onInitialize'	 => "js:function(){
                                            populateGozen(this, '{$followUps->adminId}');
                                                }",
			'load'			 => "js:function(query, callback){
                                            loadGozen(query, callback);
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

	<div class="col-xs-12 col-sm-2 col-md-2">   
		<label class="control-label"></label>
		<?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary full-width submitCbr')); ?>
	</div>
</div>
<?php $this->endWidget(); ?>
<?php
if (!empty($dataProvider))
{

	$params									 = array_filter($_REQUEST);
	$dataProvider->getPagination()->params	 = $params;
	$dataProvider->getSort()->params		 = $params;
	$this->widget('booster.widgets.TbGridView', array(
		'responsiveTable'	 => true,
		'dataProvider'		 => $dataProvider,
		'template'			 => "<div class='panel-heading'><div class='row m0'>
													<div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
											</div></div>
											<div class='panel-body table-responsive'>{items}</div>
											<div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
		'itemsCssClass'		 => 'table table-striped table-bordered dataTable mb0',
		'htmlOptions'		 => array('class' => 'panel panel-primary  compact'),
		'columns'			 =>
		array
			(
//				array('name'	 => 'adm_id', 'value'	 => $data['adm_id'], 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-1'), 'header'			 => 'Admin Id'),
			array('name' => 'CSR_Name', 'value' => $data['CSR_Name'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-1'), 'header' => 'CSR Name'),
			array('name' => 'TeamLeader', 'value' => $data['TeamLeader'], 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'sortable' => true, 'header' => 'Team Leader'),
			array('name' => 'Total_Days', 'value' => $data['Total_Days'], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Total Days', 'htmlOptions' => ["class" => "text-center"]),
			array('name'	 => 'Average_Followup_Time', 'value'	 => function ($data) {
					echo $data['Average_Followup_Time'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Average Followup Time', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'Max_Followup_Time', 'value'	 => function ($data) {
					echo $data['Max_Followup_Time'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Max Followup Time', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'Total_Followup_Time', 'value'	 => function ($data) {
					echo $data['Total_Followup_Time'];
				}, 'sortable'								 => true, 'headerHtmlOptions'						 => array('class' => 'col-xs-1'), 'header'								 => 'Total Followup Time', 'htmlOptions'							 => ["class" => "text-center"]),
			array('name'	 => 'Median_Followup_Time', 'value'	 => function ($data) {
					echo $data['Median_Followup_Time'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center"), 'header'			 => 'Median Followup Time', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'Total_Follow_ups', 'value'	 => function ($data) {
					echo "{$data['Total_Follow_ups']} <span title='Payment Followup'>({$data['payment_followups']})</span>";
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center"), 'header'			 => 'Total Follow ups', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'Followups_points', 'value'	 => function ($data) {
					echo "{$data['Followups_points']}";
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center"), 'header'			 => 'Follow-up Points', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'Lead_Followup', 'value'	 => function ($data) {
					echo $data['Lead_Followup'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center"), 'header'			 => 'Lead Followup', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'Quote_Followup', 'value'	 => function ($data) {
					echo $data['Quote_Followup'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center"), 'header'			 => 'Quote Followup', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'Call_Back_Request', 'value'	 => function ($data) {
					echo $data['Call_Back_Request'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center"), 'header'			 => 'Call Back Request', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'Quote_Created', 'value'	 => function ($data) {
					echo "{$data['Quote_Created']} <span title='Self Quote Payment Followup'>({$data['self_payment_followups']})</span>";
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center"), 'header'			 => 'Quote Created', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'Quotation_Created_Unqiue_Customer', 'value'	 => function ($data) {
					echo $data['Quotation_Created_Unqiue_Customer'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center"), 'header'			 => 'Quotation Created (Unqiue Customer)', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'Booking_Confirmed', 'value'	 => function ($data) {
					$value	 = "{$data['Booking_Confirmed']} <span><span title='Booking Cancelled'>({$data['Booking_Cancelled']})</span>";
					$value	 .= " <span title='Booking Already Served'>({$data['Booking_Served']})</span></span>";
					echo $value;
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center"), 'header'			 => 'Booking Confirmed', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'Booking_Confirmed_Unqiue_Customer', 'value'	 => function ($data) {
					echo $data['Booking_Confirmed_Unqiue_Customer'];
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center"), 'header'			 => 'Booking Confirmed (Unqiue Customer)', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'performanceScore', 'value'	 => function ($data) {
					switch (true)
					{
						case $data['performanceScore'] >= 4:
							$class	 = 'label-success';
							break;
						case $data['performanceScore'] >= 3 AND $data['performanceScore'] < 4:
							$class	 = 'label-warning';
							break;
						case $data['performanceScore'] < 3:
						default:
							$class	 = 'label-danger';
							break;
					}

					echo "<span class='label font-11 $class'>{$data['performanceScore']}</span>";
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center", 'style' => 'min-width: 70px'), 'header'			 => 'Score', 'htmlOptions'		 => ["class" => "text-center"]),
			array('name'	 => 'Total_Gozo_Amount', 'value'	 => function ($data) {
					echo "{$data['Total_Gozo_Amount']} <span title='Gozo Amount already earned'>({$data['Gozo_Amount_Earned']})</span>";
				}, 'sortable'			 => true, 'headerHtmlOptions'	 => array("class" => "text-center"), 'header'			 => 'Total Gozo Amount', 'htmlOptions'		 => ["class" => "text-center"])
		)
	));
}
?>

<script>
	var start = '<?= date('d/m/Y', strtotime('-31 days')); ?>';
	var end = '<?= date('d/m/Y'); ?>';

	$('#bkgPickupDate').daterangepicker(
			{
				locale: {
					format: 'DD/MM/YYYY',
					cancelLabel: 'Clear'
				},
				dateLimit: {
					'months': 1,
					'days': 0
				},
				"showDropdowns": true,
				"alwaysShowCalendars": true,
				startDate: start,
				endDate: end,
				ranges: {
					'Today': [moment(), moment()],
					'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
					'Last 7 Days': [moment().subtract(6, 'days'), moment()],
					'Last 31 Days': [moment().subtract(31, 'days'), moment()],
					'This Month': [moment().startOf('month'), moment().endOf('month')],
					'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				}
			}, function (start1, end1)
	{
		$('#from_date, #ServiceCallQueue_from_date').val(start1.format('YYYY-MM-DD'));
		$('#to_date, #ServiceCallQueue_to_date').val(end1.format('YYYY-MM-DD'));
		$('#bkgPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
	});
	$('#bkgPickupDate').on('cancel.daterangepicker', function (ev, picker)
	{
		$('#bkgPickupDate span').html('Select Pickup Date Range');
		$('#from_date, #ServiceCallQueue_from_date').val('');
		$('#to_date, #ServiceCallQueue_to_date').val('');
	});

	$gozenList = null;
	function populateGozen(obj, gozen)
	{
		$followUp.populateGozen(obj, gozen);
	}
	function loadGozen(query, callback)
	{
		$followUp.loadGozen(query, callback);
	}
</script>