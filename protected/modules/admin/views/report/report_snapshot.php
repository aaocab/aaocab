
<section>
    <div class="container">
        <div class="row">
			<div class="col-xs-12">
				<div class="panel">
					<div class="panel-body">
						<div class="row">
							<div class="col-xs-8">
								<h3 class=" ">Booking Snapshot</h3>
								<div class="col-xs-6  ">Number of New state:</div>
								<div class="col-xs-6  "><?= $data['new'] ?></div>
								<div class="col-xs-6  ">Number of Assigned state:</div>
								<div class="col-xs-6  "><?= $data['assigned'] ?></div>
								<div class="col-xs-6  ">Number of On-the way:</div>
								<div class="col-xs-6  "><?= $data['onTheWay'] ?></div>
								<div class="col-xs-6  ">Number of Completed but not settled:</div>
								<div class="col-xs-6  "><?= $data['completed'] ?></div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-8">
								<h3 class=" ">Lead Snapshot</h3>
								<div class="col-xs-6  ">Lead Pending (to be followed up):</div>
								<div class="col-xs-6  "><?= $data['pendingLeads'] ?></div>
							</div>
						</div>
						<div class="row pt20 ">
							<div class="col-xs-6">
								<div class="col-xs-12  bg bg-light pt10">

									<div class="row">
										<div class="col-xs-5">
											<label class="control-label">From</label>
											<div class="input-group margin-bottom-sm">
												<span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
												<?
												$this->widget(
														'booster.widgets.TbDatePicker', array(
													'name'			 => 'from_date',
													'htmlOptions'	 => array('class' => 'col-xs-8 form-control p10 border-none border-radius'),
														)
												);
												?>
											</div>
										</div>
										<div class="col-xs-5">
											<label class="control-label">To</label>
											<div class="input-group margin-bottom-sm">
												<span class="input-group-addon"><i class="fa fa-calendar fa-fw"></i></span>
												<?
												$this->widget(
														'booster.widgets.TbDatePicker', array(
													'name'			 => 'to_date',
													'htmlOptions'	 => array('class' => 'col-xs-8 form-control p10 border-none border-radius'),
														)
												);
												?>
											</div>
										</div>
										<div class="col-xs-2 pt5">

											<a class="btn btn-primary mt20" id="ldbtn">Search</a>
										</div>
									</div>

									<div class="row">
										<div class="col-xs-12 h5 ">Aggregate avg lead closure time:  <span id="closureTime"></span></div></div>
								</div>

							</div>
							<? //= $snapshot    ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<script type="text/javascript">
    var date = new Date();
    date.setDate(date.getDate() - 1);


    $('#from_date').datepicker({
        format: 'dd/mm/yyyy',
        //startDate: date
        endDate: date
    }).on('changeDate', function (selected) {
        startDate = new Date(selected.date.valueOf());
        startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
        $('#to_date').datepicker('setStartDate', startDate);
    });
    $('#to_date').datepicker({
        format: 'dd/mm/yyyy',
        endDate: date
    }).on('changeDate', function (selected) {
        FromEndDate = new Date(selected.date.valueOf());
        FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
        $('#from_date').datepicker('setEndDate', FromEndDate);
    });


    $('#ldbtn').click(function (e) {
        stdate = $('#from_date').val();
        endate = $('#to_date').val();
        if (stdate != '' && endate != '') {
            $('#closureTime').text('');
            $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/report/getleadclosure')) ?>",
                "data": {"stdate": stdate, 'endate': endate},
                success: function (data1)
                {
                    if (data1.ctime != '') {
                        $('#closureTime').text(data1.ctime + ' day(s)');
                    }
                }
            });
        } else {
            alert('Please select a date range');
            if (stdate == '') {
                $('#from_date').focus();
            } else {
                $('#to_date').focus();
            }
        }
    });
</script>





