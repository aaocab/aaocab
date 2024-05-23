<style>


    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            

            <div class="panel" >

                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">

                            <table class="table table-bordered mb0"  >
                                <tr>
                                    <th>User</th>
                                    <th>Date</th>
                                </tr>
								<?
								if ($lmodel['rut_log'] != '')
								{
									if (CJSON::decode($lmodel['rut_log']) != '')
									{
										$comment = CJSON::decode($lmodel['rut_log']);
										foreach ($comment as $cm)
										{
											?>
											<tr>
												<td><?= ucfirst(Admins::model()->findNameList()[$cm[0]]) ?></td>                                  
												<td><?= date('d/M/Y h:i A', strtotime($cm[1])) ?></td> 
											</tr>
											<?
										}
									}
								}
								?>
                            </table>

							<?php
// the pagination widget with some options to mess
							$this->widget('CLinkPager', array('pages' => $usersList->pagination));
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



