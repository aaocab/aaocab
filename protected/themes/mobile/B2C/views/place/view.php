<?php
$this->layout	 = 'column1';
?>
<div class="content-boxed-widget p0">
                    <?
                    if ($status == 'del') {
                        ?>
                        <div class="alert alert-success">Place successfully deleted</div>
                    <? } ?> 
                    <div class="content pt15 bottom-20">
						<h3>Favourite Places <a class="uppercase btn-orange shadow-medium pl10 pr10 pt5 pb5 pull-right" id="addPlace" href="<?//= Yii::app()->createUrl('place/create'); ?>"  name="sub" >Add Place</a></h3>
                        
                    </div>

<div class="content-boxed-widget">
<?
if (count($models) < 1) {
echo "No Records Found";
	?>
	
<?
}
else
{
foreach ($models as $key => $val) {
$address = '';
$address = $val['address1'];
$address.=($val['address2'] == '') ? '' : ', ' . $val['address2'];
$address.=($val['address3'] == '') ? '' : ', ' . $val['address3'];
?>
	<div class="content p0 bottom-10 line-height16"><img src="/images/map.svg" width="30" class="pull-right top-5">
		<span class="color-gray-dark">Name</span><br>
		<span class="font-16 uppercase"><b><?= $val['name'] ?></b></span>
	</div>
	<div class="content p0 bottom-10 line-height16">
		<span class="color-gray-dark">Address</span><br>
		<?= $address ?>
	</div>
	<div class="content p0 bottom-10 line-height16">
		<div class="one-half">
		<span class="color-gray-dark">City</span><br>
		<?= $val->city0->cty_name; ?>
		</div>
		<div class="one-half last-column text-right">
			<span class="color-gray-dark">Zip Code</span><br>
			<?= $val['zip'] ?>
		</div>
		<div class="clear"></div>
	</div>
	<div class="content p0 bottom-0 line-height16 text-center">
		
		<a href="<?= Yii::app()->createUrl('place/update', array('id' => $val['user_place_id'])); ?>" class="uppercase bottom-15 ultrabold button shadow-medium button-xs button button-mint default-link">EDIT</a>
		<a href="<?= Yii::app()->createUrl('place/deleteme', array('id' => $val['user_place_id'])); ?>" class="uppercase bottom-15 ultrabold button shadow-medium button-xs button button-red">DELETE</a>
		<div class="clear"></div>
	</div>
<?
}
}
?>
</div>
<div class="panel-footer ">                    
                    <?php                           
                    // the pagination widget with some options to mess
                    $this->widget('CLinkPager', array('pages' => $userPlace->pagination));
                    ?>
                </div>
</div>

<script type="text/javascript">
    var baseUrl = "<?php echo Yii::app()->request->baseUrl; ?>";

    function confirmDelete() {
        if (confirm("Do you really want to delete this place ?")) {
            return true;
        } else {
            return false;
        }
    }


	$('#addPlace').click(function () {
		//alert("fsdf");
    window.location = "<?= Yii::app()->getBaseUrl(true) ?>/place/create";
	return false;
	}); 

</script>