<style>
	a{
		color: #5f5f5f;
	}
a:hover{ text-decoration: none;}
</style>
<div class="container-fluid">
	<div class="row mt30">
		<?php
		$menuname = "";
		$length=count($reportMenu)-1;
		foreach ($reportMenu as $key=>$val)
		{
	
				if($menuname=="" || $menuname!=$val['rpc_name'] ) {
				?>
				<div class="col-xs-12 col-lg-3">
				<div class="panel" style="min-height: 250px; height: 250px; overflow: auto;">
				<div class="panel-body">
				<p><b><?php echo $val['rpc_name'] ?></b></p>
				<ul class="p0">
				<?php } ?>
					<li class="mb10"><a href="<?php echo $val['rpt_link'] ?>"><?php echo $val['rpt_name'] ?><i class="fa fa-info-circle pull-right mt5" data-toggle="tooltip" data-placement="top" title="<?php echo$val['rpt_description']; ?>"></i></a></li>
				<?php if(($reportMenu[$key]['rpc_id']!=$reportMenu[$key+1]['rpc_id'])){
				?>
				</ul></div>	</div></div>
				<?php } ?>
			<?php $menuname=$val['rpc_name']; } ?>
	</div>
</div>