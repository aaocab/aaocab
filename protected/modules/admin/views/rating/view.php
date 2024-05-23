
<style>

    div .comments {
        border-bottom:1px #333 solid;
        padding:3px;
        line-height: 14px;
        font-weight: normal;
    }
    div .comments .comment {
        padding:3px;
        max-width:100%
    }
    div .comments .footer {
        padding:2px 5px;
        color: #888;
        text-align: right;
        font-style: italic;
        font-size: 0.85em;
        height: auto;
        width: auto;
    }

    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    .modal-backdrop{
        height: 650px !important;
    }   

    .rounded {
        border:1px solid #ddd;
        border-radius: 10px;

    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-xs-12">
            <!--------------------Shadow_popup---------------------------->
            <div class="loddingOpt loadingPop" style="display:none;">
                <div class="shadow_popup">
                    <div class="spinner">
                        <div class="spinner-container container1">
                            <div class="circle1"></div>
                            <div class="circle2"></div>
                            <div class="circle3"></div>
                            <div class="circle4"></div>
                        </div>
                        <div class="spinner-container container2">
                            <div class="circle1"></div>
                            <div class="circle2"></div>
                            <div class="circle3"></div>
                            <div class="circle4"></div>
                        </div>
                        <div class="spinner-container container3">
                            <div class="circle1"></div>
                            <div class="circle2"></div>
                            <div class="circle3"></div>
                            <div class="circle4"></div>
                        </div>
                        <p>Loading...</p>
                    </div>
                </div>
            </div>
            <!--------------------end_popup---------------------------->

			<?
			$error		 = '';
			$errorshow	 = ($error == '') ? 'hide' : '';
			?>
            <div class="panel" >
                <div class="panel-heading"></div>
                <div class="panel-body panel-body panel-no-padding">
                    <div class="panel-scroll1">

                        <div style="width: 100%; padding: 3px; overflow: auto; line-height: 10px; font: normal arial; border-radius: 5px; -moz-border-radius: 5px; border: 1px #aaa solid;color: #444;">
                            <div class="col-xs-12 ">
								<?
								if ($model->rtg_csr_customer)
								{
									?> <div class='col-xs-6 mt20'>

										<?= $model->getAttributeLabel('rtg_csr_customer') ?><br>
										<?
										$this->widget('CStarRating', array(
											'model'		 => $model,
											'attribute'	 => 'rtg_csr_customer',
											'minRating'	 => 1,
											'maxRating'	 => 5,
											'starCount'	 => 5,
											'value'		 => $model->rtg_csr_customer,
											'readOnly'	 => true,
										));
										?></div><?
								}
								if ($model->rtg_csr_vendor)
								{
									?> <div class='col-xs-6 mt20'>
										<?= $model->getAttributeLabel('rtg_csr_vendor') ?><br>
										<?
										$this->widget('CStarRating', array(
											'model'		 => $model,
											'attribute'	 => 'rtg_csr_vendor',
											'minRating'	 => 1,
											'maxRating'	 => 5,
											'starCount'	 => 5,
											'value'		 => $model->rtg_csr_vendor,
											'readOnly'	 => true,
										));
										?></div><?
								}

								if ($model->rtg_csr_review)
								{
									?> <div class='col-xs-12 mt20 pb5'>
										<?= $model->getAttributeLabel('rtg_csr_review') ?> </div>
									<div class="col-xs-12 p15 rounded mt10 mb20">
										<?= $model->rtg_csr_review;
										?>


									</div>
									<?
								}
								?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>



</script>