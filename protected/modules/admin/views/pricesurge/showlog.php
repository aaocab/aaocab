<style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>

<div class="row">
    <div class="col-md-12">
		<div class="panel" >
			<div class="panel-body panel-no-padding p0 pt10">
				<div class="panel-scroll1">
					<div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
						<table class="table table-responsive table-bordered">
							<thead>
								<tr>
									<td>Modified By</td>
									<td>Modified Date</td>
									<td>Value Changed To</td>
								</tr>
							</thead>
							<tbody>
								<?php
								foreach ($logArr as $k => $v)
								{
									?>
									<tr>
										<td> <?= $v['prc_adm_name'] ?></td>
										<td><?= DateTimeFormat::DateTimeToLocale($v['prc_date']) ?></td>
										<td><?= $v['prc_value_changed'] ?></td>
									</tr>
									<?
								}
								?>
							</tbody>
						</table>
					</div>
				</div>

            </div>
        </div>
    </div>
</div>

