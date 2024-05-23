<div class="panel panel-default"><div class="panel-body">
        <div class="container">
            <div class="row">
                <div class="col-lg-11 col-xs-12 float-none marginauto">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
                            <h3 class="text-uppercase text-center m0 mb10 weight400">Booking Details Login</h3>
                            <div class="marginauto blue1 form-design p20">
                                <div class="border-blue">
                                    <?= CHtml::beginForm(Yii::app()->createUrl('index/bookingdetails'), "post", ['style' => "margin-bottom: 10px;"]) ?>
                                    <div class="panel-body">
                                        <div class="row">
                                            <!--                                                    <div class="col-xs-12">
                                                                                                    <h5 style="color: red;">{{error}}</h5>
                                                                                                </div>-->
                                            <div class="col-sm-6" style="margin-bottom: 10px;">

                                                <div class="form-group">
                                                    <label for="bid"><b>Booking ID</b></label>
                                                    <div class="mob-centre">
                                                        <input type="text" id="bid" name="bid" value="" required class="form-control"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6" style="margin-bottom: 10px;">
                                                <div class="form-group">
                                                    <label for="phone"><b>Last Name</b></label>
                                                    <div class="mob-centre bootstrap-timepicker">
                                                        <input type="text" id="l_name" name="l_name" value="" class="form-control"/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xs-12">
                                                <button class="btn blue2 col-xs-12 border-radius text-uppercase btn-lg border-none" type="submit">Submit</button></div>
                                        </div>
                                    </div>
                                    <?= CHtml::endForm() ?>
                                </div>
                            </div>

                        </div>
                        <div class="col-xs-12 col-sm-6 p40 text-center">
                            <img src="/images/jaisalmer.jpg" alt="New year celebrations at Jaisalmer" style="max-width: 310px; width: 100%"/>
                        </div>
                    </div></div>
            </div>
        </div>
    </div></div>