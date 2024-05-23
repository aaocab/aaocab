<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-12">
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

            <?php
            $error = '';
            $errorshow = ($error == '') ? 'hide' : '';
            ?>
            
                <div class="row m0 mb20">
                                <?php
                                if ($model->rtg_customer_overall) {
                                    ?> 
									<div class='col-12 col-lg-6 mt20'>

                                        <?= $model->getAttributeLabel('rtg_customer_overall') ?><br/>
                                        <?php
                                        $this->widget('CStarRating', array(
                                            'model' => $model,
                                            'attribute' => 'rtg_customer_overall',
                                            'minRating' => 1,
                                            'maxRating' => 5,
                                            'starCount' => 5,
                                            'value' => $model->rtg_customer_overall,
                                            'readOnly' => true,
                                        ));
                                        ?>
									</div>
								<?php  }
                                if ($model->rtg_customer_driver) {
                                    ?> <div class='col-12 col-lg-6 mt20'>
                                        <?= $model->getAttributeLabel('rtg_customer_driver') ?><br/>
                                        <?php
                                        $this->widget('CStarRating', array(
                                            'model' => $model,
                                            'attribute' => 'rtg_customer_driver',
                                            'minRating' => 1,
                                            'maxRating' => 5,
                                            'starCount' => 5,
                                            'value' => $model->rtg_customer_driver,
                                            'readOnly' => true,
                                        ));
                                        ?></div>
								<?php   }
                                if ($model->rtg_customer_csr) {
                                    ?> <div class='col-12 col-lg-6 mt20'>
                                        <?= $model->getAttributeLabel('rtg_customer_csr') ?><br/>
                                        <?php
                                        $this->widget('CStarRating', array(
                                            'model' => $model,
                                            'attribute' => 'rtg_customer_csr',
                                            'minRating' => 1,
                                            'maxRating' => 5,
                                            'starCount' => 5,
                                            'value' => $model->rtg_customer_csr,
                                            'readOnly' => true,
                                        ));
                                        ?></div>
								<?php	}
                                if ($model->rtg_customer_car) {
                                    ?> <div class='col-12 col-lg-6 mt20'>
                                        <?= $model->getAttributeLabel('rtg_customer_car') ?><br/>
                                        <?php
                                        $this->widget('CStarRating', array(
                                            'model' => $model,
                                            'attribute' => 'rtg_customer_car',
                                            'minRating' => 1,
                                            'maxRating' => 5,
                                            'starCount' => 5,
                                            'value' => $model->rtg_customer_car,
                                            'readOnly' => true,
                                        ));
                                        ?></div>
                                <?php  }
                                if ($model->rtg_customer_review) {
                                    ?> <div class='col-12 mt20 pb5'>
                                        <?= $model->getAttributeLabel('rtg_customer_review') ?> </div>
                                    <div class="col-12 p15 card mt10 mb20">
                                        <?= $model->rtg_customer_review;
                                        ?>


                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        
        </div>
    </div>
</div>