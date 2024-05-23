<?php if($resultset == []){?>
		<div class="content mt10 text-center font18">
			<p style="color:red;"><b>Package Not Exist</b></p>
		</div>
<?php }else{ ?>
<div class="content-boxed-widget p0 top-0">
	<div class="content p10 gradient-green-blue text-center bottom-0">
		<p class="font-16 color-white bottom-15 line-height20"><b><?= $resultset[0]['pck_name']; ?></b></p>
		<p class="color-white bottom-10 line-height16"><?= $resultset[0]['pck_auto_name']; ?></p>
		<p class="color-white bottom-10 line-height16"><b><?= $resultset[0]['pck_desc']; ?></b></p>
	</div>
 </div>
 <div class="content-boxed-widget">
	<!-- package city start here -->
	<?php
	foreach($pck_city_details as $city_details)
	{?>
		<div class="heading-part mb10 font-16"><b><?= $city_details['cty_name']; ?>: </b></div>
		<div class="col-xs-12  h5">
		<?= $city_details['cty_city_desc']; ?> 
		</div>
	<?php
	}
	?>
	<!-- package city end here -->  
				
			<?php
				$form = $this->beginWidget('CActiveForm', array(
					'id'					 => 'book-package-form', 'enableClientValidation' => true,
					'clientOptions'			 => array(
					),
					// Please note: When you enable ajax validation, make sure the corresponding
					// controller action is handling ajax validation correctly.
					// See class documentation of CActiveForm for details on this,
					// you need to use the performAjaxValidation()-method described there.
					'enableAjaxValidation'	 => false,
					'errorMessageCssClass'	 => 'help-block',
					'action'				 => '/bknw',
					'htmlOptions'			 => array(
						'class' => 'form-horizontal',
					),
				));

				/* @var $form CActiveForm */
				$ptimePackage = Yii::app()->params['defaultPackagePickupTime'];

				$defaultDate = date("Y-m-d $ptimePackage", strtotime('+7 days'));
				$pdate		 = DateTimeFormat::DateTimeToDatePicker($defaultDate);
				$ptime		 = date('h:i A', strtotime($ptimePackage));
				?>
				<input type="hidden" id="step11" name="step" value="1">
				<?= $form->hiddenField($model, 'bkg_booking_type', ['value' => 5, 'id' => 'bkg_booking_type5']); ?>
				<?= $form->hiddenField($model, 'bktyp', ['value' => 5, 'id' => 'bktyp5']); ?>
				<?//= $form->hiddenField($model, 'bkg_package_id', ['value' => $resultset[0]['pcd_pck_id']]); ?>  
				<?= $form->hiddenField($model, 'bkg_pickup_date_date', ['value' => $pdate]); ?>  
				<?= $form->hiddenField($model, 'bkg_pickup_date_time', ['value' => $ptime]); ?>  


				<div class="content mt10 text-center">
					<?php
					if($resultset[0]['package_rate']!=""){ 
					echo CHtml::submitButton('Book Package', array('class' => 'uppercase btn-orange shadow-medium'));

					}else
					{ ?>
					<a href="#" data-menu="phonr-hover1" class="uppercase btn-orange shadow-medium">Call / Email us to book</a>
					<?php } ?> 
				</div>
				<?php if($resultset[0]['pci_images'] != ''){ ?>
				<div class="content p0 bottom-10 text-center">
					<img src="<?= $resultset[0]['pci_images']; ?>" style="width:auto;height:220px;margin: auto;">
				</div>
				<?
				}
               
				foreach ($resultset as $pack)
				{
					?>
					<div class="content p0 bottom-0">
						<br>
						<span class="heading-part mb10 font-16">
							<b>Day  <?= $pack['pcd_day_serial'] ?>: <?= rtrim($pack['fcity'] . ', ' . $pack['pcd_from_location'], ', ') . " To " . rtrim($pack['tcity'] . ', ' . $pack['pcd_to_location'], ', '); ?></b>
						</span>
						<p><?= str_replace('\n', '<br>', $pack['pcd_description']); ?></p>
                        
					</div>
					<?
				}
				?>
                <?php
				if($resultset[0]['pck_inclusions']!="")
				{
				?>
				<div class="content p0 bottom-0">
					<br>
					<span class="heading-part mb10 font-16">
						<b>Inclusions:</b>
					</span>
					<p style="white-space: pre-wrap;"><?=$resultset[0]['pck_inclusions']; ?></p>
				</div>
                 <?php
				 }
				?>
                <?php
				if($resultset[0]['pck_exclusions']!="")
				{
				?>
				<div class="content p0 bottom-0">
						<br>
						<span class="heading-part mb10 font-16">
							<b>Exclusions:</b>
						</span>
						<p style="white-space: pre-wrap;"><?=$resultset[0]['pck_exclusions']; ?></p>
                        
				 </div>
                 <?php
				 }
				?>
               <?php
				if($resultset[0]['pck_notes']!="")
				{
				?>
				<div class="content p0 bottom-0">
						<br>
						<span class="heading-part mb10 font-16">
							<b>Notes and Disclaimers:</b>
						</span>
						<p style="white-space: pre-wrap;"><?=$resultset[0]['pck_notes']; ?></p>
                </div>
			   <?php
				 }
				?>
				<div class="content mt20 text-center">
					<?php
					if($resultset[0]['package_rate']!=""){ 
						echo CHtml::submitButton('Book Package', array('class' => 'uppercase btn-orange shadow-medium'));

					}else
					{ ?>
						<a href="#" data-menu="phonr-hover1" class="uppercase btn-orange shadow-medium">Call / Email us to book</a>
					<?php } ?> 
				</div>
			<?php
				$this->endWidget();
			?>
	</div>
<?php } ?>



