<div class="row">


    <div class="panel-body">

        <div class="col-xs-12">
            <div class="panel panel-default main-tab1">
                <div class="panel-body panel-border">
                    <div class="row">


                        <div class="col-xs-12 col-sm-5 table-responsive">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <td><b>Create Date:</b></td>
                                    <td><?php echo date('d/m/Y h:i A', strtotime($record['scq_create_date'])) ?></td>
                                </tr>
                                <tr>
                                    <td><b>Create By:</b></td>
									<?php
									$scq_created_by_type = $record['scq_created_by_type'];
									$name				 = "";
									switch ($scq_created_by_type)
									{
										case 1:
											$userModel	 = Users::model()->findByPk($record['scq_created_by_uid']);
											$name		 = $userModel->usr_name . " " . $userModel->usr_lname . "(Customer)";
											break;
										case 2:
											$userModel	 = Users::model()->findByPk($record['scq_created_by_uid']);
											$name		 = $userModel->usr_name . " " . $userModel->usr_lname . "(Vendor)";
											break;
										case 3:
											$userModel	 = Users::model()->findByPk($record['scq_created_by_uid']);
											$name		 = $userModel->usr_name . " " . $userModel->usr_lname . "(Driver)";
											break;
										case 4:
											$userModel	 = Admins::model()->findByPk($record['scq_created_by_uid']);
											$name		 = $userModel->gozen . "(Admin)";
											break;
										case 5:
											$userModel	 = Agents::model()->findByPk($record['scq_created_by_uid']);
											$name		 = $userModel->adm_fname . " " . $userModel->adm_lname . "(Agent)";
											break;
										case 6:
											$name		 = "(Corporate)";
											break;
										case 10:
											$name		 = "(System)";
											break;
									}
									?>
                                    <td><?php echo ($name == '') ? '--' : $name ?></td>
                                </tr>

                                <tr>
                                    <td><b>Disposed Date:</b></td>
                                    <td><?php echo $record['scq_disposition_date'] != null ? date('d/m/Y h:i A', strtotime($record['scq_disposition_date'])) : '--'; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Disposed By:</b></td>
                                    <td>
										<?php
										if ($record['scq_disposed_by_uid'] == $record['scq_assigned_uid'] && $record['scq_disposition_date'] != null)
										{
											$userModel = Admins::model()->findByPk($record['scq_disposed_by_uid']);
											echo $userModel->gozen . "(Admin)";
										}
										else if ($record['scq_disposition_date'] != null)
										{
											echo "(System)";
										}
										else
										{
											echo '--';
										}
										?>
                                    </td>
                                </tr>                        


                                <tr>
                                    <td><b>Followed Up By Type :</b></td>
                                    <td><?php echo $record['scq_to_be_followed_up_by_type'] == 1 ? "Team" : "CSR"; ?></td>
                                </tr>
                                <tr>
                                    <td><b><?php echo $record['scq_to_be_followed_up_by_type'] == 1 ? "Team" : "CSR"; ?>:</b></td>
                                    <td>
										<?php
										if ($record['scq_to_be_followed_up_by_type'] == 2)
										{
											$userModel = Admins::model()->findByPk($record['scq_to_be_followed_up_by_id']);
											echo $userModel->gozen . "(Admin)";
										}
										else if ($record['scq_to_be_followed_up_by_type'] == 1)
										{
											$team = Teams::getByID($record['scq_to_be_followed_up_by_id']);
											echo $team;
										}
										else
										{
											echo "NA";
										}
										?>
                                    </td>
                                </tr> 


                                <tr>
                                    <td><b>Followed Up With Type  :</b></td>
                                    <td><?php echo $record['scq_to_be_followed_up_with_type'] == 1 ? "By Contact Name" : "By Phone No."; ?></td>
                                </tr>
                                <tr>
                                    <td><b><?php echo $record['scq_to_be_followed_up_with_type'] == 1 ? "Contact Name" : " Phone No."; ?>:</b></td>
                                    <td>
										<?php
										if ($record['scq_to_be_followed_up_with_type'] == 1)
										{
											$userModel = Contact::model()->findByPk($record['scq_to_be_followed_up_with_value']);
											echo $userModel->ctt_name;
										}
										else
										{
											echo $record['scq_to_be_followed_up_with_value'];
										}
										?>
                                    </td>
                                </tr> 

                                <tr>
                                    <td><b>Entity Type:</b></td>
                                    <td>
										<?php
										switch ($record['scq_to_be_followed_up_with_entity_type'])
										{
											case 1:
												$userModel	 = Users::model()->findByPk($record['scq_to_be_followed_up_with_entity_id']);
												echo $userModel->usr_name . " " . $userModel->usr_lname . "(Customer)";
												break;
											case 2:
												$vendorModel = Vendors::model()->findByPk($record['scq_to_be_followed_up_with_entity_id']);
												echo $vendorModel->vnd_name . "(Vendor)";
												break;
											case 3:
												$driverModel = Drivers::model()->findByPk($record['scq_to_be_followed_up_with_entity_id']);
												echo $driverModel->drv_name . "(Driver)";
												break;
											case 4:
												$userModel	 = Admins::model()->findByPk($record['scq_to_be_followed_up_with_entity_id']);
												echo $userModel->gozen . "(Admin)";
												break;
											case 5:
												$userModel	 = Agents::model()->findByPk($record['scq_to_be_followed_up_with_entity_id']);
												echo $userModel->adm_fname . " " . $userModel->adm_lname . "(Agent)";
												break;
											case 6:
												echo "(Corporate)";
												break;
											case 10:
												echo "(System)";
												break;
											case 11:
												echo "(Service Request)";
												break;
										}
										?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Follow Up Date:</b></td>
                                    <td>
<?php echo $record['scq_follow_up_date_time'] != null ? date('d/m/Y h:i A', strtotime($record['scq_follow_up_date_time'])) : '--'; ?>

                                    </td>
                                </tr> 

                                <tr>
                                    <td><b>Unique Code :</b></td>
                                    <td>
<?php echo $record['scq_unique_code'] != null ? $record['scq_unique_code'] : '--'; ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td><b>Follow Up Call :</b></td>
                                    <td>
										<?php
										$audioDetails = CallStatus::getAudioDetails($record['scq_id'], $record['scq_assigned_uid']);
										if (!empty($audioDetails))
										{
											echo $audioDetails['cst_modified'] != null ? date('d/m/Y h:i A', strtotime($audioDetails['cst_modified'])) : '--';
										}
										else
										{
											echo '--';
										}
										?>
                                    </td>
                                </tr>


                            </table>

                        </div>
                        <div class="col-xs-12 col-sm-7 table-responsive">
                            <table class="table table-striped table-bordered">
                                <tr>
                                    <td><b>Priority:</b></td>
                                    <td><?php
										switch ($record['scq_follow_up_priority'])
										{
											case 1:
												echo "Best Effort";
												break;
											case 2:
												echo "Low";
												break;
											case 3:
												echo "Medium";
												break;
											case 4:
												echo "High";
												break;
											case 5:
												echo "Very Urgent";
												break;
										}
										?></td>
                                </tr>
                                <tr>
                                    <td><b>Priority Score:</b></td>
                                    <td><?php echo ($record['scq_priority_score'] == '') ? '--' : $record['scq_priority_score'] ?></td>
                                </tr>

                                <tr>
                                    <td><b>Creation Comments:</b></td>
                                    <td><?php echo $record['scq_creation_comments'] != null ? $record['scq_creation_comments'] : '--'; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Disposition Comments:</b></td>
                                    <td><?php
										echo $record['scq_disposition_comments'] != null ? $record['scq_disposition_comments'] : '--';
										?></td>
                                </tr>                        


                                <tr>
                                    <td><b>Queue Type :</b></td>
                                    <td><?php echo Teams::getQueueDetailsById($record['scq_follow_up_queue_type'])['tqm_queue_name']; ?></td>
                                </tr>
                                <tr>
                                    <td><b>Booking Id:</b></td>
                                    <td>
<?php echo $record['scq_related_bkg_id'] != null ? $record['scq_related_bkg_id'] : ($record['scq_related_lead_id'] != null ? $record['scq_related_lead_id'] : '--'); ?>
                                    </td>
                                </tr> 

                                <tr>
                                    <td><b>Assigned Date :</b></td>
                                    <td><?php echo $record['scq_assigned_date_time'] != null ? date('d/m/Y h:i A', strtotime($record['scq_assigned_date_time'])) : '--'; ?></td>
                                </tr> 

                                <tr>
                                    <td><b>Assigned To :</b></td>
                                    <td><?php echo $record['scq_assigned_uid'] != null ? Admins::model()->findByPk($record['scq_assigned_uid'])->gozen : '--'; ?></td>
                                </tr>

                                <tr>
                                    <td><b>Status:</b></td>
                                    <td>
										<?php
										switch ($record['scq_status'])
										{
											case 0:
												echo "In Active";
												break;
											case 1:
												echo "Active";
												break;
											case 2:
												echo "Closed";
												break;
											case 3:
												echo "Partial Closed";
												break;
										}
										?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Platform :</b></td>
                                    <td>
										<?php
										switch ($record['scq_platform'])
										{
											case 0:
												echo "Bot";
												break;
											case 1:
												echo "Web(desktop)";
												break;
											case 2:
												echo "Web(mobile)";
												break;
											case 3:
												echo "Vendor App";
												break;
											case 4:
												echo "Consumer App";
												break;
											case 5:
												echo "Driver App";
												break;
											case 6:
												echo "IVR call";
												break;
											case 7:
												echo "Admin Panel";
												break;
										}
										?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Originating Followup :</b></td>
                                    <td>
										<?php
										$data = ServiceCallQueue::getPrevAndForwardScq($record['scq_id']);
										if ($data['prevScq'] > 0 && $data['nextScq'] > 0)
										{
											echo CHtml::link($data['prevScq'], Yii::app()->createUrl("admin/scq/view", ["id" => $data['prevScq']]), ['target' => '_blank']) . "( PREV ) , ";
											echo CHtml::link($data['nextScq'], Yii::app()->createUrl("admin/scq/view", ["id" => $data['nextScq']]), ['target' => '_blank']) . "( NEXT ) ";
										}
										elseif ($data['prevScq'] > 0)
										{
											echo CHtml::link($data['prevScq'], Yii::app()->createUrl("admin/scq/view", ["id" => $data['prevScq']]), ['target' => '_blank']) . "( PREV )  ";
										}
										elseif ($data['nextScq'] > 0)
										{
											echo CHtml::link($data['nextScq'], Yii::app()->createUrl("admin/scq/view", ["id" => $data['nextScq']]), ['target' => '_blank']) . "( NEXT ) ";
										}
										else
										{
											echo "--";
										}
										?>

                                    </td>
                                </tr>
								<tr>
                                    <td><b>Images Link :</b></td>
                                    <td>
										<?php
										echo $docImages != null ? CHtml::link("Show Images", Yii::app()->createUrl("admpnl/scq/ServiceCallBackDoc", ["id" => $record['scq_id']]), ['target' => '_blank']) : '--';
										?>

                                    </td>
                                </tr>
                            </table>
                        </div> 

                    </div>

                </div>
            </div>
        </div>

    </div>


</div>



