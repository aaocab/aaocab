<div class="content-boxed-widget">
    <div class="content p0 pb10 bottom-0">
        <div class=" text-center"> 
            <h3 class="mb0">BILLING INFORMATION</h3><br/>
            <div class="checkout-total">
                <strong class="font-14 regularbold">Full Name</strong>
                <span class="font-14"><?php echo $model->vor_bill_fullname; ?></span>
                <div class="clear"></div>
                
                <strong class="font-14 regularbold">Email</strong>
                <span class="font-14"><?php echo $model->vor_bill_email; ?></span>
                <div class="clear"></div>
                <?php if(!empty($model->vor_bill_contact)) { ?>
                <strong class="font-14 regularbold">Phone</strong>
                <span class="font-14"><?php echo $model->vor_bill_contact; ?></span>
                <div class="clear"></div>
                 <?php } if(!empty($model->vor_bill_state)) { ?>				  
                <strong class="font-14 regularbold">State</strong>
                <span class="font-14"><?php echo $model->vor_bill_state; ?></span>
                <div class="clear"></div>				
                 <?php } if(!empty($model->vor_bill_city)) { ?>	
                <strong class="font-14 regularbold">City</strong>
                <span class="font-14"><?php echo $model->vor_bill_city; ?></span>
                <div class="clear"></div>
                  <?php } if(!empty($model->vor_bill_postalcode)) { ?>	
                <strong class="font-14 regularbold">Postal Code</strong>
                <span class="font-14"><?php echo $model->vor_bill_postalcode; ?></span>
                <div class="clear"></div>
                 <?php } ?>
                <strong class="font-16 half-top">Total Cost</strong>
                <span class="font-22 ultrabold half-top"><b><?php echo $model->vor_total_price; ?></b></span>
                <div class="clear"></div>
            </div>
        </div>
    </div>
</div> 