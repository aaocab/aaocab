<div class="projects">
	<div class="panel panel-default">
		<div class="panel-body">
			<div class="col-xs-1">
				<?php
				$checkExportAccess = false;
				if ($roles['rpt_export_roles'] != null)
				{
					$checkExportAccess = Filter::checkACL($roles['rpt_export_roles']);
				}
				if ($checkExportAccess)
				{
					echo CHtml::beginForm(Yii::app()->createUrl('report/user/ReferralBonous'), "post", []);
					?>
					<input type="hidden" id="export" name="export" value="true"/>
					<button class="btn btn-default btn-5x pr30 pl30 mt20" type="submit" style="width: 185px;">Export</button>
					<?php echo CHtml::endForm(); ?>	
				<?php } ?>
			</div>	
		</div>
	</div>
</div>

<div class="row">
    <div class="col-xs-12">
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
					array('name' => 'Invitee', 'value' => function($data){
				
						echo $data['referralName']." : ". CHtml::link("Show Contact", Yii::app()->createUrl("admin/contact/view", ["ctt_id" => $data['invitee_contact_id']]), ["class" => "", "onclick" => "return viewContactVendor(this)", 'target' => '_blank']);
						
								
					}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Invitee'),
					array('name' => 'Inviter', 'value' => function($data){
						
						echo $data['inviteeName']." : ". CHtml::link("Show Contact", Yii::app()->createUrl("admin/contact/view", ["ctt_id" => $data['inviter_contact_id']]), ["class" => "", "onclick" => "return viewContactVendor(this)", 'target' => '_blank']);						
						
					}, 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-2'), 'header' => 'Inviter'),
					array('name'	 => 'Bonus Amount', 'value'	 => function($data) 
						{
							echo '<i class="fa fa-inr"></i>' . $data['act_amount'];
							
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2 text-right'), 'htmlOptions'  => array('class' => 'text-right'), 'header'			 => 'Bonus Amount'),
					array('name'	 => 'Bonus Date', 'value'	 => function($data) 
						{
							echo date('d/m/Y H:i:s', strtotime($data['act_date']));
						}, 'sortable'			 => true, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Bonus Date'),
					array('name' => 'Remarks', 'value' => '$data[act_remarks]', 'sortable' => true, 'headerHtmlOptions' => array('class' => 'col-xs-4'), 'header' => 'Remarks'),
			
				)
			));
		}
		?>
    </div>
</div>
<script>
	
	function refreshVendorGrid() {
        $('#vendorListGrid').yiiGridView('update');
    }
	
	function viewContactUser(obj) {
        var href2 = $(obj).attr("href");
        $.ajax({
            "url": href2,
            "type": "GET",
            "dataType": "html",
            "success": function (data) {
                var box = bootbox.dialog({
                    message: data,
                    title: 'User Contact',
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
	