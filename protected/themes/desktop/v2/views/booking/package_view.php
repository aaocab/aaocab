<div class="container">
	<div class="row">
		<div class="col-12 border-bottom pb10">
	<?php if($resultset == []){?>
		<div class="pb5 text-center font25" style="color:red;">
			<b>Package Not Exist</b>
		</div>
	<?php }else{ ?>
	<h2 class="text-center m0 font-24"><b><?= $resultset[0]['pck_name']; ?></b></h2>
	<h3 class="text-center font-16 color-gray font-normal">
			<?= $resultset[0]['pck_auto_name']; ?> 
		</h3>
		</div>
	<div class="col-lg-9 mt30">
		
			<h4 class="font-24 mb30">
				<b><?= $resultset[0]['pck_desc']; ?></b> 
			</h4>
            <!-- package city start here -->
            <?php
           
            foreach($pck_city_details as $city_details)
			{?>
				<h5><b><?= $city_details['cty_name']; ?>:</b></h5>
				<p class="mb30">
                <?= $city_details['cty_city_desc']; ?> 
				</p>
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
				<?= $form->hiddenField($model, 'bkg_package_id', ['value' => $resultset[0]['pcd_pck_id']]); ?>  
				<?= $form->hiddenField($model, 'bkg_pickup_date_date', ['value' => $pdate]); ?>  
				<?= $form->hiddenField($model, 'bkg_pickup_date_time', ['value' => $ptime]); ?>  


				<div class="Submit-button text-right"> 
				<?php
					if($resultset[0]['package_rate']!=""){ 
					echo CHtml::submitButton('Book Package', array('class' => 'btn btn-primary'));

					}else{ ?>
					<a href="tel:+919051877000" class="btn btn-primary" style="text-decoration: none;">Call / Email us to book</a>
				<?php } ?> 
				</div>
			    <div class ="pt5 text-justify">
					
					<?
					foreach ($resultset as $pack)
					{
						?>
						<div class="row mt10">
							<div class="col-12">
								<div class="bg-gray p15">
							<div class="pb5">
								<b>Day  <?= $pack['pcd_day_serial'] ?>: <?= rtrim($pack['fcity'] . ', ' . $pack['pcd_from_location'], ', ') . " To " . rtrim($pack['tcity'] . ', ' . $pack['pcd_to_location'], ', '); ?></b>
							</div>
							<div class="row">
								<div class="col-12 pl40"> <?= str_replace('\n', '<br>', $pack['pcd_description']); ?></div>
							</div>
						</div>
							</div>
						</div>
						<?
					}
					?>
				</div>
              <p></p>
				 <?php
					 if($resultset[0]['pck_inclusions']!="")
					 {
					 ?>
					   <h5 class=" mt30"><b>Inclusion:</b></h5> <textarea style="height:150px; overflow: auto;" class="form-control border-none"><?=$resultset[0]['pck_inclusions']; ?></textarea>
					<?php
					  }
					 ?>
					<?php
					 if($resultset[0]['pck_exclusions']!="")
					 {
					 ?>
					  <h5 class=" mt30"><b>Exclusion:</b></h5> <textarea style="height:250px; overflow: auto;" class="form-control border-none"><?=$resultset[0]['pck_exclusions']; ?></textarea>
					<?php
					  }
			         ?>
                    <?php
					 if($resultset[0]['pck_notes']!="")
					 {
					 ?>
					 <h5 class=" mt30"><b>Notes and Disclaimers:</b></h5><textarea style="height:250px; overflow: auto;" class="form-control border-none"> <?= $resultset[0]['pck_notes']; ?></textarea>
					<?php
					  }
					 ?>
				<div class="col-12 text-center mb30 mt20">
				<div class="Submit-button " style="margin-top: 5px;"> 
				<?php
                 if($resultset[0]['package_rate']!=""){ 
					echo CHtml::submitButton('Book Package', array('class' => 'btn btn-primary'));
				 }else { ?>
					 <a href="tel:+16507414696" class="btn btn-primary" style="text-decoration: none;" alt="International">Call / Email us to book</a>
				 <?php } ?>
					</div>
				</div>
				<?php
				$this->endWidget();
			?>
	</div>
		<div class="col-lg-3 mt30">
			<div class="p0 text-center">
						<?php
					
						if($resultset[0]['pci_images']!="")
						{
							
						?>
				<img src="<?= $resultset[0]['pci_images']; ?>" class="img-fluid">
					   <?php
						}

					   ?>
					</div>
		</div>
	<?php } ?>
</div>
</div>




