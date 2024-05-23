<div class="row">
	<div class="col-xs-12">
		<div class="row mb20">
			<div class="col-xs-12 widget-tab-box3">
				<div class="widget-tab-box2">
					<div class="row">						
						<div class="row m0">
							<?php
							if ($data['vnd_accepted_zone_name'] <> NULL)
							{
								$vnd_accepted_zone_name = explode(",", $data['vnd_accepted_zone_name']);
								for ($i = 0; $i <= count($vnd_accepted_zone_name); $i++)
								{
									if ($vnd_accepted_zone_name[$i] != '')
									{
										?>
										<div class="pl15 mb15 pull-left" style="display: inline-block; line-height: 24px;" >
											<span class="tags-btn"  style="background:#12AFCB"> <?= $vnd_accepted_zone_name[$i] ?></span> 
										</div>
										<?php
									}
								}
							}
							?> 							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>