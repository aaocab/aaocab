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
									<?php if ($hash!= '')
									{ ?>	<th>Lead ID</th>
											<?php } ?>
                                    <th>Description</th>
                                    <th>Date-Time</th>
                                </tr>
								<?
								foreach ($lmodel as $key => $val)
								{
									?>
									<tr>
										<td><?= $val['blgAdmin']['adm_fname']; ?></td>
											<?php if ($hash!= '')
											{ ?>
									<td><?php echo $val['blg_booking_id']; ?></td>
									<?php } ?>
										<td><?= $val['blg_desc']; ?></td>
										<td><?= $val['blg_created']; ?></td>                                    

									</tr>
									<?
								}
								?>
                            </table>
							<?
							$this->widget('CLinkPager', array('pages' => $usersList->pagination));
							?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

