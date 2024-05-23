<style type="text/css">
	.action_box a {

		border: 0;
		background: none;
	</style>

	<div class="row">
		<div class="col-xs-12">
			<a class="btn btn-warning mt20 m10" href="<?= Yii::app()->createUrl('admin/quoteRequest/create') ?>">Add new</a>
		</div>
	</div>
	<div class="row"><?php
		$form		 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'quote_request_search', 'enableClientValidation' => true,
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
				'class'		 => 'form-horizontal', 'enctype'	 => 'multipart/form-data'
			),
		));
		?>
		<div class="col-sm-4 col-md-2">

			<?php
			$regionarr	 = States::model()->findRegionName();
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'source_region',
				'val'			 => $model->source_region,
				'data'			 => $regionarr,
				'options'		 => ['allowClear' => true],
				'htmlOptions'	 => array('style'			 => 'width:100%',
					'placeholder'	 => 'Select Region', 'class'			 => 'dropdowns')
			));
			?>
		</div>
		<div class="col-sm-4 col-md-2">

			<?php
			$cityarr	 = CustomQuote::getCitList();
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'source_city',
				'val'			 => $model->source_city,
				'data'			 => $cityarr,
				'options'		 => ['allowClear' => true],
				'htmlOptions'	 => array('style'			 => 'width:100%',
					'placeholder'	 => 'Select City', 'class'			 => 'dropdowns')
			));
			?>
		</div>
		<div class="col-sm-4 col-md-2">
			<?php
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'cqt_booking_type',
				'val'			 => "'" . $model->cqt_booking_type . "'",
				'data'			 => $model->booking_type,
				'options'		 => ['allowClear' => true],
				'htmlOptions'	 => array('style'			 => 'width:100%',
					'placeholder'	 => 'Booking type', 'class'			 => 'dropdowns')
			));
			?>
		</div>
		<div class="col-sm-4 col-md-2">
			<?php
			$cartype	 = SvcClassVhcCat::getVctSvcList('list'); //VehicleTypes::model()->getMasterCarDetails();
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'cqt_cab_type',
				'val'			 => $model->cqt_cab_type,
				'data'			 => $cartype,
				'options'		 => ['allowClear' => true],
				'htmlOptions'	 => array('style'			 => 'width:100%',
					'placeholder'	 => 'Select cab type', 'class'			 => 'dropdowns')
			));
			?>
		</div>

		<div class="col-sm-4 col-md-2">
			<?php
			$adminarr	 = CustomQuote::model()->getAdminId();
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'cqt_user_entity_id',
				'val'			 => $model->cqt_user_entity_id,
				'options'		 => ['allowClear' => true],
				'data'			 => $adminarr,
				'htmlOptions'	 => array('style'			 => 'width:100%',
					'placeholder'	 => 'Select User', 'class'			 => 'dropdowns')
			));
			?>
		</div>
		<div class="col-sm-3 col-md-1">
			<?php
			$this->widget('booster.widgets.TbSelect2', array(
				'model'			 => $model,
				'attribute'		 => 'no_of_days',
				'val'			 => $model->no_of_days,
				'options'		 => ['allowClear' => true],
				'data'			 => [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10],
				'htmlOptions'	 => array('style'			 => 'width:100%',
					'placeholder'	 => '#days', 'class'			 => 'dropdowns')
			));
			?>
		</div>
		<div class="col-sm-3 col-md-2">
			<?= $form->checkboxGroup($model, 'includeExpired', ['widgetOptions' => ['htmlOptions' => ['class' => 'checklist ']]]) ?>
		</div>
		<div class="col-xs-12 text-center">
			<input class="btn btn-primary btn-sm" type="submit" name="submit" value="Search">
			<input class="btn btn-info btn-sm" type="reset" name="reset" value="Reset" id="btnreset">
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<div class="row">
		<div class="col-xs-12 mt20">
			<?php
			if (!empty($dataProvider))
			{


				$this->widget('booster.widgets.TbGridView', array(
					'responsiveTable'	 => true,
					'dataProvider'		 => $dataProvider,
					'template'			 => "<div class='panel-heading'>
											<div class='row m0'>
												<div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
												<div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
											</div>
										</div>
										<div class='panel-body'>{items}</div>
											<div class='panel-footer'><div class='row m0'>
												<div class='col-xs-12 col-sm-6 p5'>{summary}</div>
												<div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
											</div>
										</div>",
					'itemsCssClass'		 => 'table table-striped table-bordered mb0',
					'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
					'columns'			 => array
						(
						array('name' => 'region', 'value' => '$data["regionName"]', 'headerHtmlOptions' => array("class" => "col-sm-1"), 'header' => 'Region'),
						array('name' => 'cty_name', 'value' => '$data["cty_name"]', 'headerHtmlOptions' => array("class" => "col-sm-2"), 'header' => 'Source City'),
						array('name' => 'cqt_pickup_date', 'sortable' => true, 'value' => 'DateTimeFormat::DateTimeToLocale($data["cqt_pickup_date"])', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Pickup Date'),
						array('name' => 'bookingtype', 'value' => '$data["cqt_booking_type"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Booking Type'),
						array('name' => 'cabtype', 'value' => '$data["cabtype"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Cab Type'),
						array('name' => 'noofdays', 'value' => '$data["cqt_no_of_days"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'No of Days'),
						array('name' => 'cqt_description', 'value' => '$data["cqt_description"]', 'sortable' => true, 'headerHtmlOptions' => array("class" => "col-sm-4"), 'header' => 'Quote Description'),
						array('name' => 'cqt_created', 'value' => 'DateTimeFormat::DateTimeToLocale($data["cqt_created"])', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Created On'),
						array('name' => 'FullName', 'value' => $data["FullName"], 'sortable' => true, 'headerHtmlOptions' => array("class" => "col-sm-2"), 'header' => 'Created by'),
						array('name'	 => 'vqt_cqt_acc',
							'value'	 => function ($data )
							{
								if ($data['vqt_cqt_id'] > 0)
								{
									echo CHtml::link($data["cnt_vqt_accepted"] . "/" . $data["cnt_vqt_denied"], Yii::app()->createUrl("admin/quoteRequest/detail", ["qotid" => $data['vqt_cqt_id']]), ["class" => "", "onclick" => "return viewDetail(this)"]);
								}
								else
								{
									echo '0' . '/' . '0';
								}
							},
							'headerHtmlOptions'	 => array(), 'htmlOptions'		 => array('class' => 'text-center'), 'header'			 => 'Accepted/Denied<br>Quotation'),
						array(
							'header'			 => 'Action',
							'class'				 => 'CButtonColumn',
							'htmlOptions'		 => array('style' => 'white-space:nowrap;text-align: center;', 'class' => 'action_box'),
							'headerHtmlOptions'	 => array('class' => 'col-xs-1 text-center', 'style' => 'min-width: 100px;'),
							'template'			 => ' {linkuser}',
							'buttons'			 => array(
								'linkuser'		 => array(
									'click'		 => 'function(){
									$href = $(this).attr(\'href\');
									$.ajax({
										url: $href,
										dataType: "html",
										success: function(data){
											   var recreateQuote = bootbox.dialog({ 
												   message: data,  
												   title:"Recreate Quote Request",
												   size: "large",
												   callback: function(){   }
											   });
												recreateQuote.on("hidden.bs.modal", function () { $(this).data("bs.modal", null); });
										},
										error: function(xhr, status, error){
												alert(\'Sorry error occured\');
										}
									});

									return false;
                                                    }',
									'url'		 => 'Yii::app()->createUrl("admin/quoteRequest/create", array("qotid" => $data[cqt_id]))',
									'label'		 => '<i class="fa fa-edit fa-lg"></i>',
									'options'	 => array('data-toggle' => 'ajaxModal', 'style' => 'padding: 2px ; height:32px;width:35px;font-size:25px', 'rel' => 'popover', 'data-placement' => 'left', 'class' => 'btn btn-lg aA', 'title' => 'Copy Quote')
								),
								'htmlOptions'	 => array('class' => 'center'),
							))
				)));
			}
			?>
		</div>
	</div>
	<script>
		function viewDetail(obj) {
			var href2 = $(obj).attr("href");
			$.ajax({
				"url": href2,
				"type": "GET",
				"dataType": "html",
				"success": function (data) {
					var box = bootbox.dialog({
						message: data,
						title: 'Quotation Details',
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
		$('#btnreset').click(function () {
			$(".dropdowns").select2('val', '').trigger('change');
			$(".checklist").removeAttr('checked');
			$(".checklist").parent().removeClass('checked').trigger('change');
		});
	</script>