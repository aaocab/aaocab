<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions">
    <div class="row">
        <div class="col-md-12">            
            <div class="panel">
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
                            <table class="table table-bordered mb0"  >
                                <tr>
                                    <th>User</th>
                                    <th>Description</th>
                                    <th>Date-Time</th>
                                </tr>
								<?php
								if ($lmodel['adm_log'] != '')
								{
									if (CJSON::decode($lmodel['adm_log']) != '')
									{
										$comment = CJSON::decode($lmodel['adm_log']);
										foreach ($comment as $cm)
										{
											?>
											<tr>
												<td><?= ucfirst(Admins::model()->findNameList()[$cm[0]]) ?></td>
												<td><?php
													if ($cm[2] > 0)
													{
														$ctr = 1;
														foreach ($cm[2] as $c)
														{
															echo $c;
															if ($ctr != count($cm[2]))
															{
																echo ", ";
															}
															else
															{
																echo ".";
															}
															$ctr = ($ctr + 1);
														}
													}
													?></td>
												<td><?= date('d/M/Y h:i A', strtotime($cm[1])) ?></td>                                      
											</tr>
											<?php
										}
									}
								}
								else
								{
									?>
									<tr><td colspan="3" align="center">Admin Log Doesn't Exist</td></tr>
									<?php
								}
								?>
                            </table>
							<?php
							$this->widget('CLinkPager', array('pages' => $usersList->pagination));
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>