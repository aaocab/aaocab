<?php
$version	 = Yii::app()->params['siteJSVersion'];
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/aao/editAccounts.js?v=' . $version);
?>
<style type="text/css">
    .form-group {
        margin-bottom: 0;
        margin-left: 0 !important;
        margin-right: 0 !important;
    }
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
    .modal-backdrop{
        height: 650px !important;
    }   

    .error{
        color:#ff0000;
    }

    .rounded {
        border:1px solid #ddd;
        border-radius: 10px;
    }

    .bg-warning{
        color: #333333;
    }


    .bordered {
        border:1px solid #ddd;
        min-height: 45px;
        line-height: 1.2;
        text-align: center;
    }

    .form-control{
        border: 1px solid #a5a5a5;
        text-align: center;

    }

    .modal-title{
        text-align: center;
        font-size: 1.5em;
        font-weight: 400;
    }
</style>
    <div class="panel">
    <div class="panel-heading"></div>
    <div class="panel-body">
    <div class="col-12 mb30"><div class="row">

            <div class="row" style="margin-top: 10px"> 
                <div class="col-xs-12 col-sm-7 col-md-12">  
                   <input type="hidden" id="cityid" name="cityid" value="<?= $cityId ?>">
                        <table class="table table-bordered" style="">
                            <thead>
                                <tr style="color: black;background: whitesmoke">
                                    <th><u>City Name</u></th>
                                    <th><u>City Display Name</u></th>
                                    <th><u>Zone</u></th>
                                </tr>
                            </thead>
                            <tbody id="count_booking_row">    
                                <tr>
                                    <td><?php  echo $cityName; ?></td>
                                    <td><?php  echo $cityFullName; ?></td>
                                    <td><?php  echo $allZone ?></td>
                                </tr>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>