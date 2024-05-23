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
					<label class="control-label">Area Type</label>
					<?php
					$filters			 = [
						1	 => 'Zone',
						2	 => 'State',
						3	 => 'City',
						4	 => 'Region',
						5	 => 'Route'
					];
					$dataPay			 = ServiceClassRule::model()->getJSON($filters);
					$this->widget('booster.widgets.TbSelect2', array(
						'model'			 => $model,
						'attribute'		 => 'scr_area_type',
						'val'			 => $model->scr_area_type,
						'asDropDownList' => FALSE,
						'options'		 => array('data' => new CJavaScriptExpression($dataPay), 'allowClear' => true),
						'htmlOptions'	 => array('class' => 'p0 area', 'style' => 'width:100%;margin-left:5px;', 'onChange' => 'getAreaName(this)', 'placeholder' => 'Select Types', 'id' => 'scr_area_type')
					));
					?>

				</div>
			</div>

			<div class="col-12 col-sm-4 col-md-2 mb30 " style = "position:static !important;">
				<label class="control-label">Area Name</label>
				<!--				<div class="controls">
									<select class="form-control" name="scr_area_id" id="areaId" style="width: 100%;">
										<option value="">Select Area Name</option>
									</select>
								</div>-->
				<?php
				$this->widget('ext.yii-selectize.YiiSelectize', array(
					'model'				 => $model,
					'attribute'			 => 'scr_area_id',
					'useWithBootstrap'	 => true,
					"placeholder"		 => "Select Area",
					'fullWidth'			 => false,
					'options'			 => array('allowClear' => true),
					'htmlOptions'		 => array('width'	 => '100%',
						'id'	 => 'scr_area_id'
					),
					'defaultOptions'	 => $selectizeOptions + array(
				'onInitialize'	 => "js:function(){
                                  populateSource(this, '{$model->scr_area_id}');
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
                        }", 'allowClear'	 => true
					),
				));
				?>
				<?php echo $form->error($model, 'scr_area_id'); ?>
			</div>
			<div class="col-xs-12 col-sm-3 col-md-2"> 
				<div class="form-group">
					<label class="control-label" style="margin-left:5px;">Cab Name</label>
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
			<div class="col-xs-7 col-md-2 mt20 pt5 mb10 text-right">
				<button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>		
			</div>
			<!--<div class="col-xs-5 col-md-3 mt20 pt5 text-left"> <a href="<?= Yii::app()->createUrl('admin/quotation/addcabrule') ?>" class="btn btn-info pt5 pb5"><i class="fa fa-plus"></i> Add </a></div>-->
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
			$this->widget('booster.widgets.TbGridView', array(
				'responsiveTable'	 => true,
				'id'				 => 'contactlist',
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name'	 => 'scr_area_type', 'value'	 => function($data)
						{
							if ($data[scr_area_type] != null)
							{
								echo $data[areaType];
							}
							else
							{
								echo '-';
							}
						}, 'sortable'								 => false, 'headerHtmlOptions'						 => array(), 'header'								 => 'Area Type'),
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
							else
							{
								echo '-';
							}
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Area Name'),
					array('name'	 => 'scr_trip_type', 'value'	 => function($data) use($tripType)
						{
							echo $tripType[$data[scr_trip_type]];
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Trip Type'),
					array('name'	 => 'scr_scv_id', 'value'	 => function($data)
						{
							echo SvcClassVhcCat::model()->getNameById($data[scr_scv_id]);
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Cab Type (Tier)'),
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
					array('name'	 => 'scr_markup_type', 'value'	 => function($data)
						{
							if ($data[scr_is_allowed] == 1)
							{
								echo 'Yes';
							}
							else
							{
								echo 'No';
							}
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Is Allowed'),
					array('name' => 'scr_create_date', 'filter' => FALSE, 'value' => 'date("d/M/Y h:i A", strtotime($data["scr_create_date"]))', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => $model->getAttributeLabel('Created Date')),
					array(
						'header'			 => 'Action',
						'class'				 => 'CButtonColumn',
						'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center', 'class' => ''),
						'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
						'template'			 => '{edit}{active}{inactive}',
						'buttons'			 => array(
							'edit'			 => array(
								'url'		 => 'Yii::app()->createUrl("admin/quotation/addcabrule", array(\'id\' => $data["scr_id"]))',
								'imageUrl'	 => Yii::app()->request->baseUrl . '\images\icon\city\edit_booking.png',
								'options'	 => array('style' => 'margin-right: 4px', 'class' => 'btn btn-xs cpmarkup1 p0', 'title' => 'Edit'),
							),
							'active'		 => array(
								'visible'	 => '$data["scr_active"] == 0',
								'click'		 => 'function(){
								  var con = confirm("Are you sure you want to Active this cab rules?");
								  return con;
								  }',
								'url'		 => 'Yii::app()->createUrl("admin/quotation/status", array("id" => $data["scr_id"]))',
								'imageUrl'	 => false,
								'label'		 => '<i class="fa fa-toggle-off"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs btn-danger credit-detail', 'title' => 'Active'),
							),
							'inactive'		 => array(
								'visible'	 => '$data["scr_active"] == 1',
								'click'		 => 'function(){
												var con = confirm("Are you sure you want to Deactive this cab rules?");
												return con;
												}',
								'url'		 => 'Yii::app()->createUrl("admin/quotation/status", array("id" => $data["scr_id"]))',
								'imageUrl'	 => false,
								'label'		 => '<i class="fa fa-toggle-on"></i>',
								'options'	 => array('style' => '', 'class' => 'btn btn-xs btn-success credit-deactivate', 'title' => 'Deactive'),
							),
							'htmlOptions'	 => array('class' => 'center'),
						))
			)));
		}
		?>
    </div>
</div>

<script>


    function getAreaName(obj = "", parent_id = 0, areaId = 0)
    {
        var parent_id = (parent_id > 0) ? parent_id : $(obj).val();
        var selectize = $("#scr_area_id")[0].selectize;
        selectize.clear();
        selectize.clearOptions();
        if (parent_id == 3)
        {
            $.ajax({
                url: '<?= CHtml::normalizeUrl(Yii::app()->createUrl('lookup/allcitylistbyquery', ['apshow' => 1, 'city' => ''])) ?>',
                dataType: 'json',
                data: {
                    // city: cityId
                },
                //  async: false,
                success: function (results) {
                    selectize.load(function (callback) {
                        callback(results);
                    });
                    if (areaId > 0) {
                        selectize.setValue(areaId);
                    }
                    return;
                },
                error: function () {
                    callback();
                }
            });
            return;
        }
        var url = "<?= Yii::app()->createAbsoluteUrl('/admin/quotation/getareaname') ?>" + "?areaType=" + parent_id;
        $.ajax({
            type: "GET",
            url: url,
            cache: false,
            success: function (data) {
                var obj = $.parseJSON(data);
                var arr = [];


                $.each(obj, function (key, value)
                {
                    if (parent_id == 1)
                    {
                        arr.push({
                            id: value.zon_id,
                            text: value.zon_name
                        });
                    }
                    if (parent_id == 2)
                    {
                        arr.push({
                            id: value.stt_id,
                            text: value.stt_name
                        });
                    }

                    if (parent_id == 4)
                    {
                        arr.push({
                            id: key,
                            text: value
                        });
                    }
                });
                selectize.load(function (callback)
                {
                    callback(arr);
                });
                if (areaId > 0) {
                    selectize.setValue(areaId);
                }
            }
        });
    }

    function populateSource(obj, areaId) {
        obj.load(function (callback) {
            var obj = this;
            obj.enable();
			var parent_id = $("#scr_area_type").val();
            if (parent_id > 0 && areaId > 0) {
            getAreaName(obj, parent_id, areaId);
        }
        });
    }
    function loadSource(query, callback) {
        if (!query.length)
            return callback();
        var parent_id = $('#scr_area_type').val();
        if (parent_id == 3) {
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
    }

</script>
