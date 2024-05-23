<style>
    .list_booking{
        background: #fff;
        -webkit-box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.21);
        -moz-box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.21);
        box-shadow: 0px 0px 17px 0px rgba(0,0,0,0.21);
        margin: 5px 0 0 0;
    }
    .list_heading{ background: #EFEFEF; overflow: hidden;}
    .gray-color{ color: #848484;}
    .gozo_green{ background: #48b9a7;}
    .gozo_bluecolor{ color: #0766bb;}
    .gozo_greencolor{ color:#48b9a7;}
    .gozo_red{ background: #f34747;}
    .text_right{ text-align: right;}
    .margin_top{ margin-top: 40px;}
    .car_img{ overflow: hidden;}
    .car_img img{ width: 100%;}
    @media (max-width: 768px) {
        .text_right{ text-align: center;}
        .margin_top{ margin-top: 10px;}
    }
</style>
<div class="hidden-xs"><a class="btn btn-primary mr10" href="<?= Yii::app()->createUrl('admpnl/cache/flushlist') ?>" role="button"><?= "Clean All Cache" ?></a></div>
<div class="row">
	<div class="col-xs-6">
		<div class="list_booking">
			<div class="">
				<div class="row">
					<div class="col-xs-12 col-sm-8">
						<h4 class="mt15 pl10">
							Refresh Config Data
						</h4>
					</div>
					<div class="col-sm-4 col-md-4 text_right mt10 mb10"><a class="btn btn-primary mr10" href="<?= Yii::app()->createUrl('admpnl/cache/refreshConfig') ?>" role="button"><?= "Clean Cache" ?></a></div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-6">
		<div class="list_booking">
			<div class="">
				<div class="row">
					<div class="col-xs-12 col-sm-8">
						<h4 class="mt15 pl10">
							Refresh Queue
						</h4>
					</div>
					<div class="col-sm-4 col-md-4 text_right mt10 mb10"><a class="btn btn-primary mr10" href="<?= Yii::app()->createUrl('admpnl/cache/refreshQueue') ?>" role="button"><?= "Clean Queue" ?></a></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
foreach ($model as $key => $val)
{
	?>
	<div class="row">
		<div class="col-xs-6">
			<div class="list_booking">
				<div class="">
					<div class="row">
						<div class="col-xs-12 col-sm-8">
							<h4 class="mt15 pl10">
								<?= $val ?>
							</h4>
						</div>
						<div class="col-sm-4 col-md-4 text_right mt10 mb10"><a class="btn btn-primary mr10" href="<?= Yii::app()->createUrl('admpnl/cache/clear', array('key' => $key)) ?>" role="button"><?= "Clean Cache" ?></a></div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
