<style>
.widgit_table td{ border:#AFAFAF 1px solid!important; text-align: left; padding: 5px!important;}
</style>

<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/fontawesome-web/css/all.min.css?v0.6');
?>

<div class="content p0 accordion-path bottom-0 specialinsadv">
		<div class="accordion accordion-style-0 content-boxed-widget p0">
			<div class="accordion-border">
				<a href="javascript:void(0)" class="font18 uppercase" data-accordion="accordion-9">Special instructions & advisories<i class="fa fa-plus"></i></a>
				<div class="accordion-content" id="accordion-9" style="display: none;">
		<div class="accordion-text mt5">
		<div class="content-boxed-widget mt5 mb5 widgit_table p0">
		<span class="bottom-10 font-16"><b>Special instructions & advisories that may affect your planned travel</b></span>
<?php
		for ($i = 0; $i < count($note); $i++)
		{
			?>
                <div class="mb10" style="border: #e3e3e3 1px solid; padding: 10px;">
    <p class="color-gray line-height16 mb0 color-orange font-12">Place</p>
    <p class="line-height16 mb20 color-black">
        <?php if($note[$i]['dnt_area_type'] ==3){?>
		<?= ($note[$i]['cty_name']) ?>
	   <?php }else if($note[$i]['dnt_area_type'] ==2){?>
		<?= ($note[$i]['dnt_state_name']) ?>
	   <?php }else if($note[$i]['dnt_area_type'] ==0){?>
	   <?="Applicable to all"?>
	   <?php }else if($note[$i]['dnt_area_type'] ==4){?>
			 <?= Promos::$region[$note[$i]["dnt_area_id"]]?>
	  <?php
	   }
	  ?>
    </p>
    
    <p class="color-gray line-height16 mb0 color-orange font-12">Note</p>
    <p class="line-height16 mb20 color-black"><?= ($note[$i]['dnt_note']) ?></p>
    
    <p class="color-gray line-height16 mb0 color-orange font-12">Valid From</p>
    <p class="line-height16 mb20 color-black"><?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_from'])) ?></p>
    
    <p class="color-gray line-height16 mb0 color-orange font-12">Valid To</p>
    <p class="line-height16 mb20 color-black"><?= (DateTimeFormat::DateTimeToLocale($note[$i]['dnt_valid_to']))?></p>
</div>
	<?php
}
?>

</div>	
		</div>			
		</div>
			</div>
		</div>
</div>