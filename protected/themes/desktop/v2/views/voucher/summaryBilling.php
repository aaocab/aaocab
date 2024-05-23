<?php ?>
<div class="container mt10 mb20">
    <div class="bg-white-box pb0">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-12 col-lg-4 mb20">
                        <div class="row clsBilling" >
                            <div class=" col-12 sum-height color-gray">Full name</div>
                            <div class=" col-12 text-left font-20"><b><?= $model->vor_bill_fullname; ?></b></div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 mb20">
                        <div class="row clsBilling" >
                            <div class=" col-12 sum-height color-gray">Email</div>
                            <div class=" col-12 text-left"><span  class=""><?= $model->vor_bill_email; ?></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 mb20">
                        <div class="row clsBilling" >
                            <div class=" col-12 sum-height color-gray">Phone</div>
                            <div class=" col-12 text-left font-20"><b><?= $model->vor_bill_contact; ?></b></div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 mb20">
                        <div class="row clsBilling" >
                            <div class=" col-12 sum-height color-gray">State</div>
                            <div class=" col-12 text-left"><span  class=""><?= $model->vor_bill_state; ?></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 mb20">
                        <div class="row clsBilling" >
                            <div class=" col-12 sum-height color-gray">City</div>
                            <div class=" col-12 text-left"><span  class=""><?= $model->vor_bill_city; ?></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-4 mb20">
                        <div class="row clsBilling" >
                            <div class=" col-12 sum-height color-gray">Postal Code</div>
                            <div class=" col-12 text-left"><span  class=""><?= $model->vor_bill_postalcode; ?></span></div>
                        </div>
                    </div>
                    <div class="col-12 col-lg-12 text-right bg-blue2 color-white pt5 pb5">
                        <div class="row clsBilling" >
                            <div class="col-12 sum-height font-18">Total Cost: <span  class="font-22">&#x20B9;<b><?= $model->vor_total_price; ?></b></span></div>
                    <div class="col-12 text-left"></div>
                </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>