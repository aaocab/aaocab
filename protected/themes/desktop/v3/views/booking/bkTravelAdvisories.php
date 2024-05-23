<?php
	if (!empty($note))
	{
		?>
		<div class="sidenav">
			<div class="dropdown-container row">
				<div class="col-12 compact">
					<div class="weight700 text-center mt-1 merriw">Special instructions & advisories that may affect your planned travel</div>
<div class="card-body">
<p><b>Note</b></p>		<ul class="pl15 font-12">			
										<?php
										for ($i = 0; $i < count($note); $i++)
										{
											?>   
					
					<li class="mb15"><?= ($note[$i]['dnt_note']) ?></li>
<!--					<td><?//= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_from'])) ?></td>
					<td><?//= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_to'])) ?></td>-->
					<?php
										}
										?>
</ul>
</div>
</div>
			</div>

		</div>
	<?php } ?>