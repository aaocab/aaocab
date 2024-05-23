 
<div class="panel ">    
    <div class = "    text-center h3 m0"><b><?= $resultset[0]['pck_name']; ?></b>
		<div class="col-xs-12 h5 text-center ">
			<?= $resultset[0]['pck_auto_name']; ?> 
		</div>
	</div>
    <div class="panel-body ">       

        <div class="row">

			<div class="col-xs-12 text-center h4">
				<b><?= $resultset[0]['pck_desc']; ?></b> 
			</div>
			<div class = " pt5 text-justify">				

				<?
				foreach ($resultset as $pack)
				{
					?>
					<div class="col-xs-12 h5">
						<br>
						<div class="pb5">
							<b>Day <?= $pack['pcd_day_serial'] ?>:  <?= $pack['fcity'] . ', ' . $pack['pcd_from_location'] . " To " . $pack['tcity'] . ', ' . $pack['pcd_to_location']; ?></b>
						</div>
						<div class="row">
							<div class="col-xs-12 pl40">  <?= str_replace('\n', '<br>', $pack['pcd_description']); ?> </div>
						</div>
					</div>
					<?
				}
				?>
			</div>
			<div class="col-xs-12 text-center h4">
				<?= $resultset[0]['pck_url']; ?>
			</div>
        </div>
       <div></div>
    </div>
</div>



