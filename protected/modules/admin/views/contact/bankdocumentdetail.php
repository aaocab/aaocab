<div class="main-tab2">
    <div class="col-xs-12 col-sm-6 p0">
        <div class="main-tab2">
            <div class="row p5 new-tab2">
                <div class="col-xs-6"><b>Bank Name:</b></div>
                <div class="col-xs-6 text-right"><?= $model->ctt_bank_name ?></div>
            </div>
			
			<div class="row p5 new-tab2">
					<div class="col-xs-6"><b>Bank Branch:</b></div>
					<div class="col-xs-6 text-right"><?= $model->ctt_bank_branch ?></div>
			</div>
			
			<div class="row p5 new-tab2">
					<div class="col-xs-6"><b>Account No:</b></div>
					<div class="col-xs-6 text-right"><?= $model->ctt_bank_account_no ?></div>
			</div>
			
			<div class="row p5 new-tab2">
					<div class="col-xs-6"><b>Account Type:</b></div>
					<div class="col-xs-6 text-right"><?= $model->accountType[$model->ctt_account_type] ?></div>
			</div>
			
			<div class="row p5 new-tab2">
					<div class="col-xs-6"><b>IFSC Code:</b></div>
					<div class="col-xs-6 text-right"><?= $model->ctt_bank_ifsc ?></div>
			</div>
			
			<div class="row p5 new-tab2">
					<div class="col-xs-6"><b>Beneficiary Name:</b></div>
					<div class="col-xs-6 text-right"><?= $model->ctt_beneficiary_name ?></div>
			</div>
				
			<div class="row p5 new-tab2">
					<div class="col-xs-6"><b>Beneficiary Id:</b></div>
					<div class="col-xs-6 text-right"><?= $model->ctt_beneficiary_id ?></div>
			</div>
			
        </div>
    </div>
    <div class="col-xs-12 col-sm-6 p0">
        <div class="main-tab2">
			
			<div class="row p5 new-tab2 hidden-xs">
                <div class="col-xs-6"><b>Voter No:</b></div>
                <div class="col-xs-6 text-right"><?= $model->ctt_voter_no ?></div>
            </div>
            <div class="row p5 new-tab2">
                <div class="col-sm-8 col-xs-6"><b>Aadhaar  No:</b></div>
                <div class="col-xs-6 col-sm-4 text-right"><?= $model->ctt_aadhaar_no ?></div>
            </div>
			<div class="row p5 new-tab2 hidden-xs">
                <div class="col-xs-6"><b>Pan No:</b></div>
                <div class="col-xs-6 text-right"><?= $model->ctt_pan_no; ?></div>
            </div>
            <div class="row p5 new-tab2">
                <div class="col-sm-8 col-xs-6"><b>License No:</b></div>
                <div class="col-xs-6 col-sm-4 text-right"><?= $model->ctt_license_no ?></div>
            </div>
			<div class="row p5 new-tab2 hidden-xs">
                <div class="col-xs-6"><b>License Issue Date:</b></div>
				<div class="col-xs-6 text-right"><? if($model->ctt_license_issue_date!=null){ echo date('d/m/Y', strtotime($model->ctt_license_issue_date)); }    ?></div>
            </div>
            <div class="row p5 new-tab2">
                <div class="col-sm-8 col-xs-6"><b>License Expiry Date:</b></div>
                <div class="col-xs-6 col-sm-4 text-right"> <? if($model->ctt_license_exp_date!=null){ echo date('d/m/Y', strtotime($model->ctt_license_exp_date)); }    ?></div>
            </div>
			<div class="row p5 new-tab2">
                <div class="col-sm-8 col-xs-6"><b>License Issuing Authority:</b></div>
                <div class="col-xs-6 col-sm-4 "><?php $stateDetails=States::model()->findByPk($model->ctt_dl_issue_authority); echo $stateDetails->stt_name;  ?></div>
            </div>
    </div>

</div>
	</div>