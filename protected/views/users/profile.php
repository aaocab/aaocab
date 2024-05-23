

<div class="panel panel-profile panel-default" style="margin-top: 9px;">
    <div class="panel-body col-xs-12" id="businessDetailsPanel1">
        <div class="row">
            <h2 class="mt0"><?= $model['username'] ?></h2>


        </div>
        <hr class="outsider">
      
            <div class="col-xs-12">
                <div class="table-responsive table-userinfo">
                     <table class="table table-condensed">
                        <tbody>
                            <tr>
                                <td style="">Name:</td>
                                <td><?= $model['username'] ?></td>
                            </tr>
                             <tr>
                                <td style="">Contact:</td>
                                <td> <?= ($model['phone'] != '') ? $model['usr_country_code'] . " " . $model['phone'] : "" ?></td>
                            </tr>
                           
                            <tr>
                                <td style="width: 170px">Email</td>
                                <td><?= $model['email'] ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>


           
    </div>

