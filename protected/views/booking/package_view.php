
<div class = "panel ">
	<?php if($resultset == []){?>
		<div class="pb5 text-center font25" style="color:red;">
			<b>Package Not Exist</b>
		</div>
	<?php }else{ ?>
	<div class = "  text-center h1 m0"><b><?= $resultset[0]['pck_name']; ?></b>
		<div class="col-xs-12 h5 text-center ">
			<?= $resultset[0]['pck_auto_name']; ?> 
		</div>
	</div>
	<div class = "panel-body ">
		<div class="row">
			<div class="col-xs-12 text-center h2">
				<b><?= $resultset[0]['pck_desc']; ?></b> 
			</div>
            <!-- package city start here -->
            <?php
           
            foreach($pck_city_details as $city_details)
			{?>
				<div class="col-xs-12  h4"><b><?= $city_details['cty_name']; ?>: </b></div>
				<div class="col-xs-12  h5">
                <?= $city_details['cty_city_desc']; ?> 
				</div>
			<?php

			}
			?>
            <!-- package city end here -->  
			<?php
				$form = $this->beginWidget('booster.widgets.TbActiveForm', array(
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

				/* @var $form TbActiveForm */
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


				<div class="col-xs-12   text-right">
				<?php
					if($resultset[0]['package_rate']!=""){ 
					echo CHtml::submitButton('Book Package', array('class' => 'btn btn-primary'));

					}else
					{ ?>
					<a href="tel:+919051877000" class="btn btn-primary" style="text-decoration: none;">Call / Email us to book</a>
				<?php } ?> 

				</div></div>
				</div>
			    <div class ="pt5 text-justify">
					<div class="col-xs-12 p0 text-center">
						<?php
					
						if($resultset[0]['pci_images']!="")
						{
							
						?>
						<img src="<?= $resultset[0]['pci_images']; ?>" style="width:333px;height:221px;">
					   <?php
						}

					   ?>
					</div>
					<?
					foreach ($resultset as $pack)
					{
						?>
						<div class="col-xs-12 h4">
							<br>

							<div class="pb5">
								<b>Day  <?= $pack['pcd_day_serial'] ?>: <?= rtrim($pack['fcity'] . ', ' . $pack['pcd_from_location'], ', ') . " To " . rtrim($pack['tcity'] . ', ' . $pack['pcd_to_location'], ', '); ?></b>
							</div>
							<div class="row">
								<div class="col-xs-12 pl40"> <?= str_replace('\n', '<br>', $pack['pcd_description']); ?><br><br></br></div>
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
					   <div class="col-xs-12  h4"><b>Inclusion:</b></div> <textarea style="height:150px" class="form-control border-none"><?=$resultset[0]['pck_inclusions']; ?></textarea>
					<?php
					  }
					 ?>
					<?php
					 if($resultset[0]['pck_exclusions']!="")
					 {
					 ?>
					  <div class="col-xs-12  h4"><b>Exclusion:</b></div> <textarea style="height:200px" class="form-control border-none"><?=$resultset[0]['pck_exclusions']; ?></textarea>
					<?php
					  }
			         ?>
                    <?php
					 if($resultset[0]['pck_notes']!="")
					 {
					 ?>
					 <div class="col-xs-12  h4"><b>Notes and Disclaimers:</b></div><textarea style="height:200px" class="form-control border-none"> <?= $resultset[0]['pck_notes']; ?></textarea>
					<?php
					  }
					 ?>
				<div class="col-xs-12 text-center  ">
					<div class="Submit-button " style="margin-top: 5px;"> 
				<?php
               
                 if($resultset[0]['package_rate']!=""){ 
				 echo CHtml::submitButton('Book Package', array('class' => 'btn btn-primary'));
				  
				 }else
				 { ?>
					 <a href="tel:+16507414696" class="btn btn-primary" style="text-decoration: none;" alt="International">Call / Email us to book</a>
				 <?php } ?> </div>

				</div>
				<?php
				$this->endWidget();
			?>
		</div>
	</div>
	<?php } ?>
</div>




