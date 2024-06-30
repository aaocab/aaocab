<div class="row">
    <div class="col-md-12">

        <?php
        $form      = $this->beginWidget('booster.widgets.TbActiveForm', array(
            'id'                     => 'booking-form',
            'enableClientValidation' => true,
            'method'                 => 'get',
            'clientOptions'          => array(
                'validateOnSubmit' => true,
                'errorCssClass'    => 'has-error'
            ),
            'enableAjaxValidation'   => false,
            'errorMessageCssClass'   => 'help-block',
            'htmlOptions'            => array(
                'class' => '',
            ),
        ));
        /* @var $form TbActiveForm */
        ?>
        <div class="col-xs-4 col-sm-4 col-md-4">
            <label class="control-label">Teams</label>
            <?php
            $dataTeam  = Teams::getMappedList();
            $allArr[]  = "All";
            $dataTeams = array_merge($allArr, $dataTeam);
            $this->widget('booster.widgets.TbSelect2', array(
                'model'       => $followUps,
                'attribute'   => 'scq_to_be_followed_up_by_id',
                'data'        => $dataTeams,
                'htmlOptions' => array('style' => 'width:100%', 'placeholder' => 'Select Team(s)')
            ));
            ?>
        </div> 

        <div class="col-xs-2 col-sm-2 col-md-2">   
            <label class="control-label"></label>
            <?php echo CHtml::button('Submit', array('class' => 'btn btn-primary full-width submitCbr')); ?>
        </div>


        <?php $this->endWidget(); ?>


    </div>
</div> 

<br>
<br>
<div class="row">
    <div class="col-md-12 col-sm-10 col-xs-12">
        <div class="panel panel-white">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th style="text-align:center">Team Name</th>
                            <th style="text-align:center">Gozen</th>
                            <th style="text-align:center">Followup Id</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i         = 0;
                        foreach ($result as $row)
                        {
                            $i++;
                            ?>
                            <tr>
                                <td style="text-align:center"><?php echo $row['tea_name']; ?></td>
                                <td style="text-align:center"><?php echo $row['gozen'] ?></td>
                                <td style="text-align:center"><a target="_blank" href="/aaohome/scq/view?id=<?php echo $row['scq_id'] ?>"><?php echo $row['scq_id'] ?></a></td>
                            </tr>
                            <?php
                        }

                        if ($i == 0)
                        {
                            ?>
                            <tr >
                                <td colspan="3">No Record found</td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $(document).on('click', '.submitCbr', function () {
            var teamId = $("#ServiceCallQueue_scq_to_be_followed_up_by_id").val();
            window.location.href = '/aaohome/scq/onlineCsr?cdt_id=' + teamId;
        });
    });

</script>