<style>
.help-block { display: none;}
</style>
<div class="row title-widget">
    <div class="col-12">
        <div class="container">
            <?php echo $this->pageTitle; ?>
        </div>
    </div>
</div>
<div class="row mb30">
	<div class="col-12">
		<img src="/images/banner-voucher.jpg" alt="" class="img-fluid">
	</div>
</div>

<div class="row">
    <div class="col-12 col-lg-6 offset-lg-3 mb30">
        <div class="bg-white-box">
<?php if (!empty($errors)): 
		$errorList = json_decode($errors);
?>
		<div class="alert alert-danger" style="font-size: 18px;" role="alert">
		<ul style="list-style-type:none;">
			<?php 
			foreach($errorList as $v)
			{				
				foreach($v as $v1)
				{
				?>
					<li><?php echo $v1; ?></li>
			<?php 		
				}					
			}
			?>
		</ul>
		</div>
		<?php endif; ?>
			<?php
			$form = $this->beginWidget('CActiveForm', array(
				'id' => 'buyForm', 'enableClientValidation' => true,
				'clientOptions' => array(
				'validateOnSubmit' => true,
				'errorCssClass' => 'has-error'
				),
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// See class documentation of CActiveForm for details on this,
				// you need to use the performAjaxValidation()-method described there.
				'enableAjaxValidation' => false,
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
				'class' => 'form-horizontal',
				),
				));
			/* @var $form CActiveForm */
			?>
    
                <div class="row mb15">
                    <label for="usr_address1" class="col-md-4 control-label text-right">Voucher</label>
                    <div class="col-md-7 pt5 font-18">
                        <b><?php echo $voucherModel->vch_code; ?></b> (<?= $voucherModel->vch_title; ?>)
                    </div>
                </div>
				<div class="row mb15">
                    <label for="usr_address1" class="col-md-4 control-label text-right">Description</label>
                    <div class="col-md-7 pt5 font-12">
                        <?php echo $voucherModel->vch_desc;?>
                    </div>
                </div>

                <div class="row mb15">
                    <label for="usr_address1" class="col-md-4 control-label text-right">Selling Price</label>
                    <div class="col-md-7 font-30 color-green">
                        &#x20B9;<b><?php echo $voucherModel->vch_selling_price; ?></b>
                    </div>
                </div>

                <div class="row mb15">
                    <label for="usr_address2" class="col-md-4 control-label text-right">Number</label>
                    <div class="col-md-7">
					<div class="form-group">
                        <?php
                        $qtyData = [];
                        for ($i = 1; $i <= 50; $i++) {
                            $qtyData[$i] = ($i > 0) ? $i : "Select Quantity";
                        }
                        ?>
                        <?= $form->dropDownList($model, 'vsb_qty', array('data' => $qtyData),array('class' => 'form-control','placeholder'=>'Enter Quantity')) ?>
                        <?php echo $form->error($model, 'vsb_qty', ['class' => 'help-block error']); ?>
                    </div>
                    </div>
                </div>

                <div class="row mb15">
                    <label for="usr_address1" class="col-md-4 control-label text-right">Name</label>
                    <div class="col-md-7">
                    <div class="form-group">
                        <?= $form->textField($model, 'vsb_name', array('class' => 'form-control border-radius')) ?>
                        <?php echo $form->error($model,'vsb_name',['class' => 'help-block error']);?>
                    </div>  
                    </div>
                </div>
                <div class="row mb15">
                    <label for="usr_address1" class="col-md-4 control-label text-right">Email</label>
                    <div class="col-md-7">
                    <div class="form-group">
                        <?= $form->textField($model, 'vsb_email', array('class' => 'form-control border-radius')) ?>
                        <?php echo $form->error($model,'vsb_email',['class' => 'help-block error']);?>
                    </div>
                    </div>
                </div>



                <div class="row mb15">
                    <div class="col-11 text-right">
                        <button type="submit" class="btn-orange pl30 pr30"  name="sub" value="Submit">Buy</button>
                        <a href="<?= Yii::app()->createUrl('voucher') ?>" class="btn-back text-uppercase" >Back</a>
                    </div>
                </div>
        </div>
    </div>
</div>
<?= $form->hiddenField($model, "vsb_vch_id ", ['value' => 1]); ?>
<?php $this->endWidget(); ?>

