<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body p0">
					<h2>App Usage </h2>

					<div >
						<?php
						if (!empty($dataProvider))
						{
							$this->widget('booster.widgets.TbGridView', array(
								'id'				 => 'appusage-grid',
								'responsiveTable'	 => true,
								// 'filter' => FALSE,
								'dataProvider'		 => $dataProvider,
								'template'			 => "<div class='panel-heading'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'>
                                        <div class='row m0'>
                                           
                                         </div>
                                     </div>",
								'itemsCssClass'		 => 'table table-striped table-bordered mb0',
								'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
								'columns'			 => array(
									array('name'	 => 'apt_device', 'filter' => FALSE, 'value'	 => function($data) {
											echo $data['apt_device'];
										}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Device Name'),
									array('name'	 => 'apt_device_uuid', 'filter' => FALSE, 'value'	 => function($data) {
											echo $data['apt_device_uuid'];
											$adminid = Yii::app()->session->get('_admin__id');
											$auth	 = Yii::app()->authManager;
											$roles	 = $auth->getRoles($adminid);
											$arr	 = array_keys($roles);
											if (in_array("6 - Developer", $arr) || in_array("SuperAdmin", $arr))
											{
												echo " / <BR>" . $data['apt_token_id'];
											}
										}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Device Id / Token'),
									array('name'	 => 'apt_user_type', 'filter' => FALSE, 'value'	 => function($data) {
											echo $data['apt_user_type'];
										}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'User Type'),
									array('name'	 => 'apt_apk_version', 'filter' => FALSE, 'value'	 => function($data) {
											echo $data['apt_apk_version'];
										}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Apk Version'),
									array('name'	 => 'apt_os_version', 'filter' => FALSE, 'value'	 => function($data) {
											echo $data['apt_os_version'];
										}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'OS Version'),
									array('name'	 => 'apt_date', 'filter' => FALSE, 'value'	 => function($data) {
											echo $data['apt_date'];
										}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Loggedin Time'),
									array('name'	 => 'apt_logout', 'filter' => FALSE, 'value'	 => function($data) {
											echo $data['apt_logout'];
										}, 'sortable'			 => false,
										'headerHtmlOptions'	 => array('class' => 'col-xs-4'), 'header'			 => 'Logout Time'),
									array('name'	 => 'apt_status', 'filter' => FALSE, 'value'	 => function($data) {
											$adminid = Yii::app()->session->get('_admin__id');
											$auth	 = Yii::app()->authManager;
											$roles	 = $auth->getRoles($adminid);
											$arr	 = array_keys($roles);
											if ((in_array("6 - Developer", $arr) || in_array("SuperAdmin", $arr)) && $data['apt_status'] == '1')
											{
												echo " <button class='linkforcelogout btn btn-danger' data-id='" . $data['apt_token_id'] . "'><b>Force Logout</b></button>";
											}
											else
											{
												if ($data['apt_status'] == '0' && $data['apt_logout'] == null)
												{
													echo 'Automatic';
												}
												else
												{
													echo 'Manual';
												}
											}
										}, 'sortable'			 => false, 'headerHtmlOptions'	 => array('class' => 'col-xs-2'), 'header'			 => 'Logout'),
							)));
						}
						?>
					</div>

                </div>
			</div>
		</div>
	</div>
</div>
<script>
    $("button.linkforcelogout").on("click", function () {
        var aptToken = $(this).data('id');
        var href = '<?= Yii::app()->createUrl("aaohome/user/forcelogout"); ?>';
        $.ajax
                ({
                    url: href,
                    data: {"aptToken": aptToken},
                    type: 'get',
                    "dataType": "json",
                    success: function (data)
                    {
                        if (data.success == true) {
                            userAppusage();
                        }
                    }
                });

    });

</script>