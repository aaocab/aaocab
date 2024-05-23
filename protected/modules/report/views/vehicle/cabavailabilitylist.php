<div class="row">
	<?php
	$pageno				 = filter_input(INPUT_GET, 'page');
	$datacity			 = Cities::model()->getJSONAllCitiesbyQuery('');
	$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
		'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
		'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
		'openOnFocus'		 => true, 'preload'			 => false,
		'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
		'addPrecedence'		 => false,];
	if (!$showListOnly)
	{
		?>
		<div class="col-xs-12" >
			<?php
			$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
				'id'					 => 'cabavailabilities-form', 'enableClientValidation' => true,
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
				<div class="col-xs-6 col-sm-4 col-lg-3">
					<?
					$daterang	 = "Select Date Range";
					$fromdate	 = ($model->from_date == '') ? '' : $model->from_date;
					$todate		 = ($model->to_date == '') ? '' : $model->to_date;
					if ($fromdate != '' && $todate != '')
					{
						$daterang = date('F d, Y', strtotime($fromdate)) . " - " . date('F d, Y', strtotime($todate));
					}
					?>
					<label  class="control-label">From & To Date Selection</label>
					<div id="cabAvailabilityDate" class="" style="background: #fff; cursor: pointer; padding: 7px 10px; border: 1px solid #ccc; width: 100%">
						<i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
						<span><?= $daterang ?></span> <b class="caret"></b>
					</div>
					<?
					echo $form->hiddenField($model, 'from_date');
					echo $form->hiddenField($model, 'to_date');
					?>
				</div>
				<div class="col-xs-12 col-sm-4 col-md-3"> 
					<div class="form-group">
						<label class="control-label">From</label>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'from_city',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "From",
							'fullWidth'			 => false,
							'htmlOptions'		 => array('width' => '100%',
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
                                  populateSource(this, '{$model->from_city}');
                                                }",
						'load'			 => "js:function(query, callback){
                                loadSource(query, callback);
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
					</div> </div>
				<div class="col-xs-12 col-sm-4 col-md-3"> 
					<div class="form-group">
						<label class="control-label">To</label>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'to_city',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "To",
							'fullWidth'			 => false,
							'htmlOptions'		 => array('width' => '100%',
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
                                  populateSource(this, '{$model->to_city}');
                                                }",
						'load'			 => "js:function(query, callback){
                                loadSource(query, callback);
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
					</div> </div>

				<div class="col-xs-12 col-sm-4 col-md-3 "> 
					<div class="form-group cityinput">
						<label class="control-label">Vendor</label>
						<?php
						$this->widget('ext.yii-selectize.YiiSelectize', array(
							'model'				 => $model,
							'attribute'			 => 'vnd_id',
							'useWithBootstrap'	 => true,
							"placeholder"		 => "Select Vendor",
							'fullWidth'			 => false,
							'options'			 => array('allowClear' => true),
							'htmlOptions'		 => array('width' => '100%',
							),
							'defaultOptions'	 => $selectizeOptions + array(
						'onInitialize'	 => "js:function(){
                                  populateVendor(this, '{$model->vnd_id}');
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
						?>
					</div> 
				</div>

				<div class="col-xs-offset-3 col-sm-offset-0 col-xs-6 col-sm-2 col-md-2 text-center mt20 ">
					<button class="btn btn-primary full-width" type="submit"  name="bookingSearch">Search</button>
				</div>
			</div>
		<?php $this->endWidget(); ?>
		</div>
		<?php } ?>
	<div class="col-xs-12">
		<?php
		$checkExportAccess = false;
		if ($roles['rpt_export_roles'] != null)
		{
			$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
		}
		if ($checkExportAccess && !$showListOnly)
		{
			?>
			<div class="row">
				<?= CHtml::beginForm(Yii::app()->createUrl('vehicle/availabilitylist'), "post", ['style' => "margin-bottom: 10px; margin-top: 10px; margin-left: 20px;"]); ?>
				<input type="hidden" id="export" name="export" value="true"/>
				<input type="hidden" id="vnd_id" name="vnd_id" value="<?= $model->vnd_id ?>"/>
				<input type="hidden" id="from_city" name="from_city" value="<?= $model->from_city ?>"/>
				<input type="hidden" id="to_city" name="to_city" value="<?= $model->to_city ?>"/>
				<input type="hidden" id="from_date" name="from_date" value="<?= $model->from_date ?>"/>
				<input type="hidden" id="to_date" name="to_date" value="<?= $model->to_date ?>"/>
				<button class="btn btn-default" type="submit" style="width: 185px;">Export Below Table</button>
				<?= CHtml::endForm() ?>
			</div>
			<?php
		}
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
                <div class='panel-body'>{items}</div>
                <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name' => 'vnd_name', 'value' => $data['vnd_name'], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Vendor Name'),
					array('name' => 'vhc_number', 'value' => $data['vhc_number'], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Cab Number'),
					array('name' => 'vht_make_model', 'value' => $data['vht_make_model'], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Cab Model'),
					array('name' => 'cab_type', 'value' => $data['cab_type'], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Cab Type'),
					array('name' => 'from_city', 'value' => $data['from_city'], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'From City'),
					array('name' => 'to_city', 'value' => $data['to_city'], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'To City'),
					array('name' => 'vnd_phone', 'value' => $data['vnd_phone'], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Phone No'),
					array('name' => 'drv_name', 'value' => $data['drv_name'], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Driver Name'),
					array('name' => 'cav_date_time', 'value' => 'date("d/m/Y h:i A",strtotime($data[cav_date_time]))', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Date/Time'),
					array('name' => 'cav_duration', 'value' => $data['cav_duration'], 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Expiry Time (Hrs)'),
			)));
		}
		?>


	</div>
</div>
<script>
    $(document).ready(function () {
        var front_end_height = parseInt($(window).outerHeight(true));
        var footer_height = parseInt($("#footer").outerHeight(true));
        var header_height = parseInt($("#header").outerHeight(true));
        var ch = (front_end_height - (header_height + footer_height + 23));
        //console.log("wH: "+front_end_height+" HH : "+header_height+" FH: "+footer_height+"CH :"+ch);
        $("#content").attr("style", "height:" + ch + "px;");
    });
    function confirmDelete() {
        if (confirm("Do you really want to delete this vehicle?")) {
            return true;
        } else {
            return false;
        }
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


    $(document).ready(function () {


        var start = '<?= date('d/m/Y', strtotime('-1 month')); ?>';
        var end = '<?= date('d/m/Y'); ?>';


        $('#cabAvailabilityDate').daterangepicker(
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
                        'Next 7 Days': [moment(), moment().add(7, 'days')],
                        'Next 15 Days': [moment(), moment().add(15, 'days')],
                        'Next 30 Days': [moment(), moment().add(30, 'days')],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Next Month': [moment().add(1, 'month').startOf('month'), moment().add(1, 'month').endOf('month')],
                    }
                }, function (start1, end1) {
            $('#CabAvailabilities_from_date').val(start1.format('YYYY-MM-DD'));
            $('#CabAvailabilities_to_date').val(end1.format('YYYY-MM-DD'));
            $('#cabAvailabilityDate span').html(start1.format('MMMM D, YYYY') + ' - ' + end1.format('MMMM D, YYYY'));
        });
        $('#cabAvailabilityDate').on('cancel.daterangepicker', function (ev, picker) {
            $('#cabAvailabilityDate span').html('Select Date Range');
            $('#CabAvailabilities_to_date').val('');
            $('#CabAvailabilities_from_date').val('');
        });

    });

    $sourceList = null;
    function populateSource(obj, cityId) {

        obj.load(function (callback) {
            var obj = this;
            if ($sourceList == null) {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    data: {
                        // city: cityId
                    },
                    //  async: false,
                    success: function (results) {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function () {
                        callback();
                    }
                });
            } else {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSource(query, callback) {
        //	if (!query.length) return callback();
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function () {
                callback();
            },
            success: function (res) {
                callback(res);
            }
        });
    }



</script>