<div class="panel">
    <div class="panel panel-heading"></div>
    <div class="panel panel-body">
        <div class="row">
			<?
			/* @var $model Agents */
			?>
            <div class="col-xs-6"><b>Owner Name: </b><span class="ml10"><?= $model->agt_owner_name; ?></span></div>
            <div class="col-xs-6"><b>Company Name: </b><span class="ml10"><?= $model->agt_company; ?></span></div>

            <div class="col-xs-6"><b>Email: </b><span class="ml10"><?= $model->agt_email; ?></span></div>
            <div class="col-xs-6"><b>Phone: </b><span class="ml10"><?= $model->agt_phone; ?></span></div>

            <div class="col-xs-6"><b>City: </b><span class="ml10"><?= $model->agt_city; ?></span></div>
            <div class="col-xs-6"><b>Address: </b><span class="ml10"><?= $model->agt_address; ?></span></div>

            <div class="col-xs-6"><b>Driver license: </b><span class="ml10"><?= $model->agt_driver_license; ?></span></div>
            <div class="col-xs-6"><b>Driver license Expiry Date: </b><span class="ml10"><?= $model->agt_license_expiry_date; ?></span></div>

            <div class="col-xs-6"><b>Trade license: </b><span class="ml10"><?= $model->agt_trade_license; ?></span></div>
            <div class="col-xs-6"><b>Driver license issued by state: </b><span class="ml10"><?= States::model()->findByPk($model->agt_license_issued_state)->stt_name; ?></span></div>

            <div class="col-xs-6"><b>Voter ID: </b><span class="ml10"><?= $model->agt_voter_id; ?></span></div>
            <div class="col-xs-6"><b>Aadhaar Number: </b><span class="ml10"><?= $model->agt_aadhar_id; ?></span></div>

            <div class="col-xs-6"><b>Voter Path: </b><span class="ml10"><a href="<?= $agentRelModel->arl_voter_id_path ?>" target="_BLANK"><?= basename($agentRelModel->arl_voter_id_path); ?></a></span></div>
            <div class="col-xs-6"><b>Aadhaar Path: </b><span class="ml10"><a href="<?= $model->agt_aadhar ?>" target="_BLANK"><?= basename($model->agt_aadhar) ?></a></span></div>

            <div class="col-xs-6"><b>Driver License Copy: </b><span class="ml10"><a href="<?= $agentRelModel->arl_driver_license_path ?>" target="_BLANK"><?= basename($agentRelModel->arl_driver_license_path) ?></a></span></div>
            <div class="col-xs-6"><b>Owner Photo: </b><span class="ml10"><a href="<?= $model->agt_owner_photo ?>" target="_BLANK"><?= basename($model->agt_owner_photo) ?></a></span></div>

            <div class="col-xs-6"><b>Company Address Proof: </b><span class="ml10"><a href="<?= $model->agt_company_add_proof ?>" target="_BLANK"><?= basename($model->agt_company_add_proof) ?></a></span></div>
            <div class="col-xs-6"></div>

            <div class="col-xs-12 mt10">Other Details</div>
            <div class="col-xs-6"><b>Commission value type: </b><span class="ml10"><?= ($model->agt_commission_value == 1) ? "Percentage" : "Fixed Value"; ?></span></div>
            <div class="col-xs-6"><b>Commission value: </b><span class="ml10"><?= ($model->agt_commission > 0) ? $model->agt_commission : 0; ?></span></div>

        </div>  
    </div>
    <div class="row text-center">
        <a href="<?= Yii::app()->createUrl("admpnl/agent/approve", array("agt_id" => $model->agt_id, "agt_approve" => 1, "approve" => true)) ?>"  onclick="showMessage(<?= $model->agt_type ?>,<?= $model->agt_commission ?>);"><div class="btn btn-lg btn-success p5">Approve</div></a>
        <a href="<?= Yii::app()->createUrl("admpnl/agent/approve", array("agt_id" => $model->agt_id, "agt_approve" => 2, "disapprove" => true)) ?>"><div class="btn btn-lg btn-danger p5">Disapprove</div></a>
    </div>
</div>
<script>
    function showMessage(agtType, agtMarkup) {
        if (agtType == 2) {
            if (agtMarkup > 0) {
                return true;
            }
            alert("You can set agent markup or commission after approval.");
            return true;
        }
    }
</script>