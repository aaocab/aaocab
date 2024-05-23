<div class="row">
    <div class="panel-body">
        <div class="col-xs-12">
            <div class="panel panel-default main-tab1">
                <div class="panel-body panel-border">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 table-responsive">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <td><b>Entity Type:</b></td>
                                    <td>
										<?php
										$createdByType	 = WhatsappLog::model()->createdByType;
										echo $createdByType[$model->whl_entity_type];
										?>
									</td>
                                </tr>
                                <tr>
                                    <td><b>Entity Id:</b></td>
									<?php
									$name			 = "";
									switch ($model->whl_entity_type)
									{
										case 1:
											$userModel		 = Users::model()->findByPk($model->whl_entity_id);
											$name			 = $userModel->usr_name . " " . $userModel->usr_lname . "(Customer)";
											break;
										case 2:
											$vendorsModel	 = Vendors::model()->findByPk($model->whl_entity_id);
											$name			 = $vendorsModel->vnd_code . "(Vendor)";
											break;
										case 3:
											$driverModel	 = Drivers::model()->findByPk($model->whl_entity_id);
											$name			 = $driverModel->drv_code . "(Driver)";
											break;
										case 4:
											$adminsModel	 = Admins::model()->findByPk($model->whl_entity_id);
											$name			 = $adminsModel->gozen . "(Admin)";
											break;
										case 10:
											$name			 = "(System)";
											break;
									}
									?>
                                    <td><?php echo ($name == '') ? '--' : $name ?></td>
                                </tr>

                                <tr>
                                    <td><b>Phone number:</b></td>
                                    <td><?php echo Yii::app()->user->checkAccess("bookingContactAccess") ? $model->whl_phone_number : Filter::maskPhoneNumber($model->whl_phone_number); ?></td>
                                </tr>
                                <tr>
                                    <td><b>Message Type:</b></td>
                                    <td>
										<?php
										$msgType	 = WhatsappLog::model()->msg_type;
										echo $msgType[$model->whl_message_type];
										?>
                                    </td>
                                </tr>                        
								<tr>
                                    <td><b>Language:</b></td>
                                    <td><?php echo $language; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Message:</b></td>
                                    <td><?php echo nl2br($message); ?></td>
                                </tr>
                            </table>
                        </div>
						<div class="col-xs-12 col-sm-6 table-responsive">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <td><b>Reference Type:</b></td>
                                    <td>
										<?php
										$refType	 = WhatsappLog::model()->ref_type;
										echo $refType[$model->whl_ref_type] != null ? $refType[$model->whl_ref_type] : "NA";
										?>
                                </tr>
                                <tr>
									<td><b>Reference Id:</b></td>
                                    <td>
										<?php echo $model->whl_ref_id != null ? $model->whl_ref_id : "NA"; ?>
                                    </td>
                                </tr> 
                                <tr>
                                    <td><b>Created By Name:</b></td>
                                    <td>
										<?php
										echo $model->whl_created_by_name != null ? $model->whl_created_by_name : "NA";
										?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Created Date:</b></td>
                                    <td>
										<?php echo $model->whl_created_date != null ? DateTimeFormat::DateTimeToLocale($model->whl_created_date) : '--'; ?>
                                    </td>
                                </tr> 
                                <tr>
                                    <td><b>Sent Date:</b></td>
                                    <td>
										<?php echo $model->whl_sent_date != null ? DateTimeFormat::DateTimeToLocale($model->whl_sent_date) : '--'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Delivered Date:</b></td>
                                    <td>
										<?php echo $model->whl_delivered_date != null ? DateTimeFormat::DateTimeToLocale($model->whl_delivered_date) : '--'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Read Date:</b></td>
                                    <td>
										<?php echo $model->whl_read_date != null ? DateTimeFormat::DateTimeToLocale($model->whl_read_date) : '--'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Status:</b></td>
                                    <td><?php
										$statusType	 = WhatsappLog::model()->status;
										echo $statusType[$model->whl_status];
										?>
                                    </td>
                                </tr>
                            </table>
                        </div>
						<?
						if ($model->whl_status == 3)
						{
							?>
							<div class="col-xs-12 col-sm-6 ">
								<div class="alert alert-danger p10">
									<div class="h4">Failed Reason </div>
									<?php
									$errorObj = json_decode($model->whl_sent_response, false);
									echo $errorObj->error->message;
									?>
								</div>
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



