<div class="row">
    <div class="col-xs-12">

		<?php
		$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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

			<div class="col-xs-12 col-sm-2 col-md-2">
				<?= $form->textFieldGroup($model, 'pastDays', array('label' => 'Show previous days:', 'htmlOptions' => array('placeholder' => 'Previous days'))) ?>
			</div>
			<div class="col-xs-12 col-sm-2 col-md-2">
				<?= $form->textFieldGroup($model, 'nextDays', array('label' => 'Show next days:', 'htmlOptions' => array('placeholder' => 'Next Days'))) ?>
			</div>
			<div class="col-xs-12 col-md-4 mt20 pt5 mb10 text-center">
				<button class="btn btn-primary" type="submit" style="width: 185px;"  name="bookingSearch">Search</button>		
				<a  class="btn btn-primary mb10" href="/admpnl/CalendarEvent/Create" target="_blank" style="text-decoration: none;margin-left: 20px;float:left">Add Event</a>

                <a  class="btn btn-primary mb10" href="/admpnl/CalendarEvent/MapYearEventDate" target="_blank" style="text-decoration: none;float:right">View event Calendar</a> 

			</div>
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
				'id'				 => 'Eventlist',
				'dataProvider'		 => $dataProvider,
				'template'			 => "<div class='panel-heading'><div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                    </div></div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'><div class='row m0'><div class='col-xs-12 col-sm-6 p5'>{summary}</div><div class='col-xs-12 col-sm-6 pr0'>{pager}</div></div></div>",
				'itemsCssClass'		 => 'table table-striped table-bordered mb0',
				'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
				'columns'			 => array(
					array('name' => 'cle_dt', 'type' => 'html', 'value' => '$data["cle_dt"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Date'),
					array('name' => 'cle_day_name', 'value' => '$data[cle_day_name]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Day'),
					array('name' => 'cle_month_name', 'value' => '$data[cle_month_name]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Month'),
					array('name'	 => 'cle_dow_type', 'value'	 => function ($data) {
							echo $data['cle_dow_type'] == 1 ? "Weekend" : "Weekday";
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array(), 'header'			 => 'DOW'),
					array('name'	 => 'etg_affects_region_type', 'type'	 => 'html', 'value'	 => function ($data) {
							echo $data['etg_affects_region_type'] == -1 ? "No Region" : ($data['etg_affects_region_type'] == 0 ? "National" : "Regional");
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Affects Region'),
					array('name' => 'hde_name', 'type' => 'html', 'value' => '$data["hde_name"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Event Name'),
					array('name' => 'cle_weighted_factor', 'type' => 'html', 'value' => '$data["cle_weighted_factor"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'System Margin'),
					array('name' => 'etg_margin', 'type' => 'html', 'value' => '$data["etg_margin"]', 'sortable' => true, 'headerHtmlOptions' => array(), 'header' => 'Manual Margin'),
					array('name'	 => 'etg_region_id', 'type'	 => 'html',
						'value'	 => function ($data) {
							if ($data['etg_region_id'] != null)
							{
								$regionArr	 = explode(",", $data['etg_region_id']);
								$region		 = "";
								foreach ($regionArr as $region)
								{
									$regionName .= States::findUniqueZone($region) . ",";
								}
								echo trim($regionName, ",");
							}
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Region'),
					array('name'	 => 'etg_source_mzone_id', 'type'	 => 'html',
						'value'	 => function ($data) {

							if ($data['etg_source_mzone_id'] != null)
							{
								echo "Mzone: " . Zones::getMasterZoneNameById($data['etg_source_mzone_id']) . "<br/>";
							}
							else
							{
								echo "Mzone: NA<br/>";
							}

							if ($data['etg_source_zone_id'] != null)
							{
								echo "Zone: " . Zones::getNameByCityId($data['etg_source_zone_id']) . "<br/>";
							}
							else
							{
								echo "Zone: NA<br/>";
							}

							if ($data['etg_source_state_id'] != null)
							{
								echo "State: " . States::getSatetNameById($data['etg_source_state_id']) . "<br/>";
							}
							else
							{
								echo "State: NA<br/>";
							}

							if ($data['etg_source_city_id'] != null)
							{
								echo "City: " . Cities::getCtyNameById($data['etg_source_city_id']) . "<br/>";
							}
							else
							{
								echo "City: NA<br/>";
							}
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Source '),
					array('name'	 => 'etg_destination_mzone_id', 'type'	 => 'html',
						'value'	 => function ($data) {
							if ($data['etg_destination_mzone_id'] != null)
							{
								echo "Mzone: " . Zones::getMasterZoneNameById($data['etg_destination_mzone_id']) . "<br/>";
							}
							else
							{
								echo "Mzone: NA<br/>";
							}

							if ($data['etg_destination_zone_id'] != null)
							{
								echo "Zone: " . Zones::getNameByCityId($data['etg_destination_zone_id']) . "<br/>";
							}
							else
							{
								echo "Zone: NA<br/>";
							}

							if ($data['etg_destination_state_id'] != null)
							{
								echo "State: " . States::getSatetNameById($data['etg_destination_state_id']) . "<br/>";
							}
							else
							{
								echo "State: NA<br/>";
							}

							if ($data['etg_destination_city_id'] != null)
							{
								echo "City: " . Cities::getCtyNameById($data['etg_destination_city_id']) . "<br/>";
							}
							else
							{
								echo "City: NA<br/>";
							}
						}, 'sortable'			 => false, 'headerHtmlOptions'	 => array(), 'header'			 => 'Destination'),
			)));
		}
		?>
    </div>
</div>
<script type="text/javascript">
    $(document).on("input", "#CalendarEvent_nextDays,#CalendarEvent_pastDays", function () {
        this.value = this.value.replace(/\D/g, '');
    });
</script>

