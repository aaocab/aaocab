<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body p0">
                    <div class="">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444; padding: 5px">
							<?php
							if (!empty($dataProvider))
							{
								foreach ($dataProvider as $val)
								{
									$url = Yii::app()->createAbsoluteUrl('admin/booking/view', ['id' => $val]);
									echo '<a href="' . $url . '" target="_blank">' . $val . '</a>, ';
								}
								
							}
							else
							{
								echo "No record found!";
							}
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
