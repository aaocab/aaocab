<div class="row">
    <div class="col-xs-12">
		<?php
		if (Yii::app()->user->hasFlash('success'))
		{
			?>
			<div class="alert alert-block alert-success">
				<?php echo Yii::app()->user->getFlash('success'); ?>
			</div>
		<?php } ?>
		<?php
		$selectizeOptions	 = ['create'			 => false, 'persist'			 => true, 'selectOnTab'		 => true,
			'createOnBlur'		 => true, 'dropdownParent'	 => 'body',
			'optgroupValueField' => 'id', 'optgroupLabelField' => 'text', 'optgroupField'		 => 'id',
			'openOnFocus'		 => true, 'preload'			 => false,
			'labelField'		 => 'text', 'valueField'		 => 'id', 'searchField'		 => 'text', 'closeAfterSelect'	 => true,
			'addPrecedence'		 => false,];
		$form				 = $this->beginWidget('booster.widgets.TbActiveForm', array(
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
		?>
        <div class="well pb20">
			<div class="col-xs-12 col-sm-4 col-md-2"> 
				<div class="form-group">
					<label class="control-label">Zone Name</label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'scr_zone_id',
						'val'			 => $model->scr_zone_id,
						'asDropDownList' => FALSE,
						'options'		 => array(
							'data'		 => new CJavaScriptExpression(Zones::model()->getJSON(Zones::model()->getDopdownList())),
							'allowClear' => true
						),
						'htmlOptions'	 => array('required' => true, 'class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Zone Name')
					));
					?>

				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-2"> 
				<div class="form-group">
					<label class="control-label">State Name</label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'scr_state_id',
						'val'			 => $model->scr_state_id,
						'asDropDownList' => FALSE,
						'options'		 => array(
							'data'		 => new CJavaScriptExpression(States::model()->getJSON(States::model()->getDopdownList())),
							'allowClear' => true
						),
						'htmlOptions'	 => array('required' => true, 'class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select State Name')
					));
					?>

				</div>
			</div>
			<div class="col-12 col-sm-4 col-md-2 mb30">
				<label class="control-label">City Name</label>
				<?php
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'scr_city_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select City Name",
					'fullWidth'			 => false,
					'options'			 => array('allowClear' => true),
					'htmlOptions'		 => array('width'	 => '100%',
						'id'	 => 'Booking_fromcity1'
					),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
                                            populateSource(this, '{$model->scr_city_id}');
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
				<?php echo $form->error($model, 'scr_city_id'); ?>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-2"> 
				<div class="form-group">
					<label class="control-label">Region Name</label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'scr_region_id',
						'val'			 => $model->scr_region_id,
						'asDropDownList' => FALSE,
						'options'		 => array(
							'data'		 => new CJavaScriptExpression(ServiceClassRule::model()->getJSON(States::model()->findRegionName())),
							'allowClear' => true
						),
						'htmlOptions'	 => array('required' => true, 'class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Region Name')
					));
					?>

				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-2"> 
				<div class="form-group">
					<label class="control-label">Trip Type</label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'scr_trip_type',
						'val'			 => $model->scr_trip_type,
						'asDropDownList' => FALSE,
						'options'		 => array(
							'data'		 => new CJavaScriptExpression(ServiceClassRule::model()->getJSON($tripType)),
							'allowClear' => true
						),
						'htmlOptions'	 => array('required' => true, 'class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Trip Type')
					));
					?>

				</div>
			</div>
			<div class="col-xs-12 col-sm-3 col-md-2"> 
				<div class="form-group">
					<label class="control-label" style="margin-left:5px;">Cab Type</label>
					<?php
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'scr_scv_id',
						'val'			 => $model->scr_scv_id,
						'asDropDownList' => FALSE,
						'options'		 => array(
							'data'		 => new CJavaScriptExpression(SvcClassVhcCat::model()->getJSON(SvcClassVhcCat::model()->getVctSvcList())),
							'allowClear' => true
						),
						'htmlOptions'	 => array('class' => 'p0', 'style' => 'width: 100%', 'placeholder' => 'Select Name')
					));
					?>
				</div>
			</div>
			<div class="col-xs-12 text-left">
				<?= $form->checkboxListGroup($model, 'scr_is_allowed', array('label' => '', 'widgetOptions' => array('data' => array(1 => 'Supported'), 'htmlOptions' => []))) ?>
			</div>
			<div class="col-xs-12 text-left">
				<button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>		
			</div>
			<div class="col-xs-12 text-right pb20"> <a href="<?= Yii::app()->createUrl('admin/vehicle/addcabrule') ?>" class="btn btn-info pl20 pr20"  style="width: 185px;" ><i class="fa fa-plus"></i> Add </a></div>
        </div>
		<?php $this->endWidget(); ?>
    </div>

    <div class="col-xs-12">
		<?php
		if (!empty($dataProvider))
		{
			
			$params									 = array_filter($_REQUEST);
			$dataProvider->getPagination()->params	 = $params;
			$dataProvider->getSort()->params		 = $params;
			$this->widget('booster.widgets.TbExtendedGridView', array(
				'id'				 => 'cabrulelist',
				'responsiveTable'	 => true,
				'dataProvider'		 => $dataProvider,
				 'template' => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
                'itemsCssClass' => 'table table-striped table-bordered mb0',
                'htmlOptions' => array('class' => 'table-responsive panel panel-primary  compact'),
                //     'ajaxType' => 'POST',
                'columns' => array(
					array('name'	 => 'scr_area_type', 'value'	 => function($data)
						{
							if ($data[scr_area_type] != null)
							{
								echo $data[areaType];
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Area Type'),
					array('name'	 => 'scr_area_id', 'value'	 => function($data)
						{
							if ($data[scr_area_type] == 1)
							{
								echo Zones::model()->getZoneById($data[scr_area_id]);
							}
							elseif ($data[scr_area_type] == 2)
							{
								echo States::model()->getNameById($data[scr_area_id]);
							}
							elseif ($data[scr_area_type] == 3)
							{
								echo Cities::model()->getName($data[scr_area_id]);
							}
							elseif ($data[scr_area_type] == 4)
							{
								echo States::model()->findRegionName($data[scr_area_id]);
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Area Name'),
					array('name'	 => 'scr_trip_type', 'value'	 => function($data) use($tripType)
						{
							echo $tripType[$data[scr_trip_type]];
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Trip Type'),
					array('name'	 => 'scr_scv_id', 'value'	 => function($data)
						{
							echo SvcClassVhcCat::model()->getNameById($data[scr_scv_id]);
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Cab Type (Tier)'),
					array('name'	 => 'scr_markup_type', 'value'	 => function($data)
						{
							if ($data[scr_markup_type] == 1)
							{
								echo 'Percentage';
							}
							elseif ($data[scr_markup_type] == 2)
							{
								echo 'Value';
							}
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Markup Type'),
					array('name'	 => 'scr_markup', 'value'	 => function($data)
						{
							echo $data[scr_markup];
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Markup Amount'),
					array('name'	 => 'scr_is_allowed', 'value'	 => function($data)
						{
							if ($data['scr_is_allowed'] == 1)
							{
								echo 'Yes';
							}
							else
							{
								echo 'No';
							}
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'Supported'),
					array('name' => 'scr_create_date', 'filter' => FALSE, 'value' => 'date("d/M/Y h:i A", strtotime($data["scr_create_date"]))', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('Created Date')),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => ''),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{edit}{log}{active}{inactive}',
						'buttons'			 => array(
							'edit'		 => array(
								'url'		 => 'Yii::app()->createUrl("admin/vehicle/addcabrule", array(\'id\' => $data["scr_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\city\edit_booking.png',
								'options'	 => array('style' => 'margin-right: 4px', 'class' => 'btn btn-xs cpmarkup1 p0', 'title' => 'Edit'),
							),
							'log'		 => array(
								'click'		 => 'function(){
                                            $href = $(this).attr(\'href\');
                                            jQuery.ajax({type: \'GET\',
                                            url: $href,
                                            success: function (data)
                                            {

                                                var box = bootbox.dialog({
                                                    message: data,
                                                    title: \'Rules Log\',
                                                    size: \'large\',
                                                    onEscape: function () {

                                                        // user pressed escape
                                                    }
                                                });
                                            }
                                        });
                                    return false;
                                }',
								'url'		 => 'Yii::app()->createUrl("admin/vehicle/showRulesLog", array(\'id\' => $data["scr_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\vendor\show_log.png',
								'label'		 => '<i class="fa fa-list"></i>',
								'options'	 => array('data-toggle'	 => 'ajaxModal',
									'style'			 => '',
									'class'			 => 'btn btn-xs conshowlog p0',
									'title'			 => 'Show Log'),
							),
							'active'	 => array(
								"click"		 => "function(e){   var con = confirm('are you sure want to activate vehicle rules?'); 
                                                        if(con){change_status(this);}}",
								'url'		 => 'Yii::app()->createUrl("aaohome/vehicle/ruleStatus", array("id" => $data["scr_id"],"scr_active"=>$data[scr_active]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\inactive.png',
								'visible'	 => '($data[scr_active] == 0)',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle' => '', 'id' => '', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs activateCat p0', 'title' => 'Activate')
							),
							'inactive'	 => array(
								"click"		 => "function(e){   var con = confirm('Are you sure want to deactivate vehicle rules?');
                                                        if(con){change_status(this);}}",
								'url'		 => 'Yii::app()->createUrl("aaohome/vehicle/ruleStatus", array("id" => $data["scr_id"],"scr_active"=>$data[scr_active]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\active.png',
								'visible'	 => '($data[scr_active] == 1)',
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('data-toggle' => '', 'id' => '', 'style' => '', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-xs deactivateCat p0', 'title' => 'Deactive')
							),
							'htmlOptions' => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>

<script>
$(document).ready(function () {
        $('.bootbox').removeAttr('tabindex');
    });
    function populateSource(obj, cityId)
    {

        obj.load(function (callback)
        {
            var obj = this;
            if ($sourceList == null)
            {
                xhr = $.ajax({
                    url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>' + cityId,
                    dataType: 'json',
                    success: function (results)
                    {
                        $sourceList = results;
                        obj.enable();
                        callback($sourceList);
                        obj.setValue(cityId);
                    },
                    error: function ()
                    {
                        callback();
                    }
                });
            } else
            {
                obj.enable();
                callback($sourceList);
                obj.setValue(cityId);
            }
        });
    }
    function loadSource(query, callback)
    {
        $.ajax({
            url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery')) ?>?apshow=1&q=' + encodeURIComponent(query),
            type: 'GET',
            dataType: 'json',
            global: false,
            error: function ()
            {
                callback();
            },
            success: function (res)
            {
                callback(res);
            }
        });
    }

    function refreshCategoryGrid() {
        $('#cabrulelist').yiiGridView('update');
    }
    function change_status(obj) {
        event.preventDefault();
        $href = $(obj).attr("href");
        $.ajax({
            type: "GET",
            url: $href,
            success: function (data)
            {
                if (data)
                {
                    refreshCategoryGrid();
                } else
                {
                    alert('Sorry error occured');
                }

            }, error: function (xhr, status, error) {
                alert('Sorry error occured');
            }
        });
        return false;
    }

</script>