<style type="text/css">
    .yii-selectize ,.selectize-input  {
        min-width: 100px!important;   
	}
</style>

<?
$version				 = Yii::app()->params['siteJSVersion'];
$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
	'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
	'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
	'openOnFocus'		 => true, 'preload'			 => false,
	'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
	'addPrecedence'		 => false,];
?>
<div class="panel-advancedoptions" >
    <div class="row">
		<?php
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'hawkeyesearch-form', 'enableClientValidation' => true,
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

      
		
		<div class="col-xs-12 col-sm-4 col-md-4" style="">
			<div class="form-group">
				<label class="control-label">Pickup Date</label>
                                
				<?php
				$daterang	         = "Select Pickup Date Range";
				$hpl_pickup_date1	 = $_REQUEST['Hawkeye']['hpl_pickup_date1'];
				$hpl_pickup_date2	 = $_REQUEST['Hawkeye']['hpl_pickup_date2'];
				if ($hpl_pickup_date1 != '' && $hpl_pickup_date2 != '')
				{
                                        $daterang = DateTimeFormat::DatePickerToDate($hpl_pickup_date1) . " - " . DateTimeFormat::DatePickerToDate($hpl_pickup_date2);
				}
                                if ($hpl_pickup_date1 == '' && $hpl_pickup_date2 == '')
				{
                                $date1	 = date('Y-m-d');
			        $date2	 = date('Y-m-d', strtotime("+90 days"));
                                $daterang = $date1 ."-".$date2;   
                                }
				?>                           
				<div id="hplPickupDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
					<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
					<span style="min-width: 240px"><?= $daterang ?></span> <b class="caret"></b>
				</div>
		                   <input name="Hawkeye[hpl_pickup_date1]" id="Hawkeye_hpl_pickup_date1" type="hidden" value="<?=$hpl_pickup_date1;?>">	
                                   <input name="Hawkeye[hpl_pickup_date2]" id="Hawkeye_hpl_pickup_date2" type="hidden" value="<?=$hpl_pickup_date2;?>">

			</div>
		</div>
		<div class="col-xs-6 col-sm-4 col-lg-2">
			<div class="form-group cityinput">
				<label class="control-label">From City</label>
				<?php
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'fromCity',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select From City",
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '100%'
					),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
				populateSourceCity(this, '{$model->fromCity}');
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
		<div class="col-xs-6 col-sm-4   col-lg-2">
			<div class="form-group cityinput">
				<label class="control-label  ">To City</label>
				<?php
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'toCity',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select To City",
					'fullWidth'			 => false,
					'htmlOptions'		 => array('width' => '100%'),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
				populateSourceCity(this, '{$model->toCity}');
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
        <div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 p5">   
			<?php echo CHtml::submitButton('Search', array('class' => 'btn btn-primary full-width')); ?></div>
		<?php $this->endWidget(); ?>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
							<?php
							if (!empty($dataProvider))
							{
								$arr = [];
								if (is_array($dataProvider->getPagination()->params))
								{
									$arr = $dataProvider->getPagination()->params;
								}
								$params1							 = $arr + array_filter($_GET + $_POST);
								/* @var $dataProvider CActiveDataProvider */
								$dataProvider->pagination->pageSize	 = 8;
								$this->widget('booster.widgets.TbGridView', array(
									'id'				 => 'hawkeyepricelist',
									'ajaxUrl'			 => CHtml::normalizeUrl(Yii::app()->createUrl('admin/hawkeyepricelist/list', $params1)),
									'responsiveTable'	 => true,
									'dataProvider'		 => $dataProvider,
									'filter'			 => $model,
									'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
									'itemsCssClass'		 => 'table table-striped table-bordered mb0',
									'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
									'columns'			 => array(
										array('name' => 'fromCity', 'filter' => false, 'value' => '$data[fromCity]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'From City'),
										array('name' => 'toCity', 'filter' => false, 'value' => '$data[toCity]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'To City'),
										array('name' => 'hpl_pickup_date', 'filter' => false, 'value' => '$data[hpl_pickup_date]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Pickup Date'),
										array('name' => 'hpl_compact', 'filter' => false, 'value' => function ($data) { echo '<div class="col-xs-2 ,text-center" ><span>'.$data['hpl_compact'].'</span></div>'.'<input type= "checkbox" >'; }, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Compact'),
										array('name' => 'hpl_sedan', 'filter' => false, 'value' =>  function ($data) { echo '<div class="col-xs-2 ,text-center" ><span>'.$data['hpl_sedan'].'</span></div>'.'<input type= "checkbox" >'; }, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Sedan'),
   										array('name' => 'hpl_suv', 'filter' => false, 'value' => function ($data) { echo '<div class="col-xs-2 ,text-center" ><span>'.$data['hpl_suv'].'</span></div>'.'<input type= "checkbox" >'; },  'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'SUV'),
   										array('name' => 'hpl_tempo_traveller', 'filter' => false, 'value' => '$data[hpl_tempo_traveller]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2', 'class' => 'text-center'), 'htmlOptions' => array('style' => 'text-align: center;'), 'header' => 'Tempo Traveller'),
								)));
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	   $(document).ready(function () {


        //--- changed 1311 --///
        var start = '<?= date('d/m/Y'); ?>';
      
        var end = '<?= date('d/m/Y'); ?>';
        

        $('#hplPickupDate').daterangepicker(
                {
				
                    locale: {
                        format: 'DD/MM/YYYY',
                        cancelLabel: 'Clear'
                    },
                    "showDropdowns": true,
                    "alwaysShowCalendars": true,
                    startDate: start,
                    endDate: end,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Tomorrow': [moment().add(1, 'days'), moment().add(1, 'days')],
                        'Next 7 Days': [moment(), moment().add(6, 'days')],
                        'Next 15 Days': [moment(), moment().add(15, 'days')],
                        'All upcoming': [moment(), moment().add(11, 'month')],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
                    }
                }, function (start1, end1) {
            $('#Hawkeye_hpl_pickup_date1').val(start1.format('DD/MM/YYYY'));
            $('#Hawkeye_hpl_pickup_date2').val(end1.format('DD/MM/YYYY'));
            $('#hplPickupDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#hplPickupDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#hplPickupDate span').html('Select Pickup Date Range');
            $('#Hawkeye_hpl_pickup_date1').val('');
            $('#Hawkeye_hpl_pickup_date2').val('');
        });

    });

	</script>