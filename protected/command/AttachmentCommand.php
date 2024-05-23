<?php

class AttachmentCommand extends BaseCommand
{

// public $layout = 'column2';
// protected $email_receipient;
	public $arr = array();

	public function actionRestoreimage()
	{

		echo "Entered ";
		echo "\n";

		$dirfinal	 = "attachments";
		$i			 = 0;
		$v			 = 0;
		$d			 = 0;

		$baseFolder	 = PUBLIC_PATH . DIRECTORY_SEPARATOR . $dirfinal;
		$dh			 = opendir($baseFolder);

		$d0				 = dir($baseFolder);
		$dFileTypeArr	 = [
			'profile'	 => 'drv_photo_path',
			'adhar'		 => 'drv_aadhaar_img_path',
			'pan'		 => 'drv_pan_img_path',
			'voterid'	 => 'drv_voter_id_img_path',
			'license'	 => 'drv_licence_path',
			'address1'	 => 'drv_adrs_proof1',
			'address2'	 => 'drv_adrs_proof2',
			'police'	 => 'drv_police_certificate'
		];
		$dFileTypeArr1	 = [
			'profile'	 => 'drv_photo_path1',
			'adhar'		 => 'drv_aadhaar_img_path1',
			'pan'		 => 'drv_pan_img_path1',
			'voterid'	 => 'drv_voter_id_img_path1',
			'license'	 => 'drv_licence_path1',
			'address1'	 => 'drv_adrs_proof11',
			'address2'	 => 'drv_adrs_proof21',
			'police'	 => 'drv_police_certificate1'
		];
		$dFileTypeArr2	 = [
			'profile'	 => 'drv_photo_path2',
			'adhar'		 => 'drv_aadhaar_img_path2',
			'pan'		 => 'drv_pan_img_path1',
			'voterid'	 => 'drv_voter_id_img_path2',
			'license'	 => 'drv_licence_path2',
			'address1'	 => 'drv_adrs_proof12',
			'address2'	 => 'drv_adrs_proof22',
			'police'	 => 'drv_police_certificate2'
		];

		$vFileTypeArr	 = [
			'INSURANCE'			 => 'vhc_insurance_proof',
			'FrontLicensePlate'	 => 'vhc_front_plate',
			'RearLicensePlate'	 => 'vhc_rear_plate',
			'PUC'				 => 'vhc_pollution_certificate',
			'RC'				 => 'vhc_reg_certificate',
			'Permit'			 => 'vhc_permits_certificate',
			'Fitness'			 => 'vhc_fitness_certificate'
		];
		$vFileTypeArr1	 = [
			'INSURANCE'			 => 'vhc_insurance_proof1',
			'FrontLicensePlate'	 => 'vhc_front_plate1',
			'RearLicensePlate'	 => 'vhc_rear_plate1',
			'PUC'				 => 'vhc_pollution_certificate1',
			'RC'				 => 'vhc_reg_certificate1',
			'Permit'			 => 'vhc_permits_certificate1',
			'Fitness'			 => 'vhc_fitness_certificate1'
		];
		$vFileTypeArr2	 = [
			'INSURANCE'			 => 'vhc_insurance_proof2',
			'FrontLicensePlate'	 => 'vhc_front_plate2',
			'RearLicensePlate'	 => 'vhc_rear_plate2',
			'PUC'				 => 'vhc_pollution_certificate2',
			'RC'				 => 'vhc_reg_certificate2',
			'Permit'			 => 'vhc_permits_certificate2',
			'Fitness'			 => 'vhc_fitness_certificate2'
		];


//  $updid = 0;
//$insactive = 99;

		while ($file = $d0->read())
		{
			if ($file != "." && $file != "..")
			{

				if ($file > 0)
				{
					// echo $file . "\n";
					$i++;
					$d1		 = $baseFolder . DIRECTORY_SEPARATOR . $file;
					$df1	 = dir($d1);
					//  echo "In folder $d1";
					//   echo "\n";
					while ($file1	 = $df1->read())
					{
						if ($file1 != "." && $file1 != "..")
						{
							//         echo $file1 . "\n";
							$d2 = $d1 . DIRECTORY_SEPARATOR . $file1;

							//  echo "In folder $d2";
							//     echo "\n";
							$originDir	 = '';
							$destDir	 = '';
							if ($file1 == 'vehicles' || $file1 == 'drivers')
							{
								$originDir	 = $d1 . DIRECTORY_SEPARATOR . $file1;
								$destDir	 = $baseFolder . DIRECTORY_SEPARATOR . $file1;

								$f			 = 0;
								$df2		 = dir($d2);
								while ($fileFinal	 = $df2->read())
								{
									if ($fileFinal != "." && $fileFinal != "..")
									{
										$f++;


										if ($file1 == 'drivers')
										{
											$d++;
											$resultd1 = [];
											if ($fileFinal > 0 && $fileFinal == 7580)
											{
												$sqld		 = "SELECT drm.drv_id,drm.drv_id1,drm.drv_name1,
                                                        drv.drv_aadhaar_img_path,
                                                        drb.drv_aadhaar_img_path drv_aadhaar_img_path1,
                                                        din.drv_aadhaar_img_path drv_aadhaar_img_path2,

                                                        drv.drv_pan_img_path,
                                                        drb.drv_pan_img_path drv_pan_img_path1,
                                                        din.drv_pan_img_path drv_pan_img_path2,

                                                        drv.drv_photo_path,
                                                        drb.drv_photo_path drv_photo_path1,
                                                        din.drv_photo_path drv_photo_path2,

                                                        drv.drv_voter_id_img_path,
                                                        drb.drv_voter_id_img_path drv_voter_id_img_path1,
                                                        din.drv_voter_id_img_path drv_voter_id_img_path2,

                                                        drv.drv_licence_path,
                                                        drb.drv_licence_path drv_licence_path1,
                                                        din.drv_licence_path drv_licence_path2,

                                                        drv.drv_adrs_proof1,
                                                        drb.drv_adrs_proof1 drv_adrs_proof11,
                                                        din.drv_adrs_proof1 drv_adrs_proof12,

                                                        drv.drv_adrs_proof2,
                                                        drb.drv_adrs_proof2 drv_adrs_proof21,
                                                        din.drv_adrs_proof2 drv_adrs_proof22,

                                                        drv.drv_police_certificate,
                                                        drb.drv_police_certificate drv_police_certificate1,
                                                        din.drv_police_certificate drv_police_certificate2

                                                        from drivers drv
                                                        LEFT join driver_map drm
                                                        ON drv.drv_id = drm.drv_id
                                                        LEFT JOIN drivers_info din
                                                        ON drv.drv_id = din.drv_driver_id
                                                        LEFT join drivers_bak drb
                                                        ON drm.drv_id1 = drb.drv_id "
														. " where (drm.drv_id1 = $fileFinal OR drv.drv_id = $fileFinal )"
														//  . " AND drm.drv_id <> drm.drv_id1 "
														. " ORDER by drv.drv_approved ,drb.drv_approved, drb.drv_modified";
												//    echo $sql;
												$resultd1	 = Yii::app()->db->createCommand($sqld)->queryRow();
												$resDArr1	 = array_filter($resultd1);
												echo "Details of $file $file1 $fileFinal: ";
												var_dump($resDArr1);
												$d3			 = $d2 . DIRECTORY_SEPARATOR . $fileFinal;
												$df3		 = dir($d3);
												$files		 = [];
												while ($fileImage	 = $df3->read())
												{
													if ($fileImage != "." && $fileImage != "..")
													{
														$files[] = $fileImage;
													}
												}

												foreach ($resDArr1 as $k => $v1)
												{
													echo "\n";
													if (array_key_exists($k, $resDArr1))
													{
														echo $k . ' - ' . $v1;
														$val	 = [];
														$val	 = explode('/', $v1);
														// var_dump($val);
														$type	 = 0;
														if ($val[2] > 0)
														{
															unset($val[2]);
														}
														var_dump($val);
														$val1	 = implode('/', $val);
														$from	 = $originDir . DIRECTORY_SEPARATOR . $fileFinal . DIRECTORY_SEPARATOR . $val[5];
														$to		 = $destDir . DIRECTORY_SEPARATOR . $fileFinal . DIRECTORY_SEPARATOR . $val[5];
														if (in_array($k, $dFileTypeArr))
														{
															$type	 = 1;
															$k1		 = $k . '1';
															$k2		 = $k . '2';

															if (in_array($val[5], $files))
															{
																//echo "Exist";
																$transaction = Yii::app()->db->beginTransaction();
																try
																{
																	if (Drivers::model()->updateDriverAttachmentPath($fileFinal, $k, $val1, 'drivers'))
																	{

																		unset($resDArr1[$k]);
																		if ($resDArr1[$k1] != '' && in_array($k1, $dFileTypeArr1))
																		{
																			unset($resDArr1[$k1]);
																		}
																		if ($resDArr1[$k2] != '' && in_array($k2, $dFileTypeArr2))
																		{
																			unset($resDArr1[$k2]);
																		}
																	}
																	echo "\n ";
																	echo $from;
																	echo "\n ";
																	echo $to;
																	if (file_exists($from))
																	{
																		if ($this->moveFile($from, $to))
																		{
																			$transaction->commit();
																			echo "Transaction Committed";
																		}
																		else
																		{
																			$transaction->rollback();
																		}
																	}
																	else
																	{
																		$transaction->rollback();
																	}
																}
																catch (Exception $e)
																{
																	$transaction->rollback();
																}
																echo "\n ";
																echo $val1;
																echo "\n ";
																echo $k;
																echo "\n ";
															}
														}
														if (in_array($k, $dFileTypeArr1))
														{
															$k1	 = $k;
															$k	 = rtrim($k1, '1');
															$k2	 = $k . '2';

															if (in_array($val[5], $files))
															{
																$transaction = Yii::app()->db->beginTransaction();
																try
																{
																	if (Drivers::model()->updateDriverAttachmentPath($fileFinal, $k, $val1, 'drivers'))
																	{

																		unset($resDArr1[$k]);
																		if ($resDArr1[$k1] != '' && in_array($k1, $dFileTypeArr1))
																		{
																			unset($resDArr1[$k1]);
																		}
																		if ($resDArr1[$k2] != '' && in_array($k2, $dFileTypeArr2))
																		{
																			unset($resDArr1[$k2]);
																		}
																	}
																	echo "\n ";
																	echo $from;
																	echo "\n ";
																	echo $to;
																	if ($this->moveFile($from, $to))
																	{
																		$transaction->commit();
																		echo "Transaction Committed";
																	}
																	else
																	{
																		$transaction->rollback();
																	}
																}
																catch (Exception $e)
																{
																	$transaction->rollback();
																}
															}
															echo "in 1";
														}
														if (in_array($k, $dFileTypeArr2))
														{
															$k2	 = $k;
															$k	 = rtrim($k2, '2');
															$k1	 = $k . '1';
															if (in_array($val[5], $files))
															{
																$transaction = Yii::app()->db->beginTransaction();
																try
																{
																	if (Drivers::model()->updateDriverAttachmentPath($fileFinal, $k, $val1, 'drivers'))
																	{

																		unset($resDArr1[$k]);
																		if ($resDArr1[$k1] != '' && in_array($k1, $dFileTypeArr1))
																		{
																			unset($resDArr1[$k1]);
																		}
																		if ($resDArr1[$k2] != '' && in_array($k2, $dFileTypeArr2))
																		{
																			unset($resDArr1[$k2]);
																		}
																	}
																	echo "\n ";
																	echo $from;
																	echo "\n ";
																	echo $to;
																	if ($this->moveFile($from, $to))
																	{
																		$transaction->commit();
																		echo "Transaction Committed";
																	}
																	else
																	{
																		$transaction->rollback();
																	}
																}
																catch (Exception $e)
																{
																	$transaction->rollback();
																}
															}
															echo "in 2";
														}
													}

													echo "\n";
												}
												echo "============================================== \n";
											}
										}


										if ($file1 == 'vehicles')
										{
											$v++;
											$resultv1 = [];
											if ($fileFinal > 0)
											{
												$sqlv = "SELECT vhm.vhc_id,vhm.vhc_id1,vhm.vhc_number1,

                                                        vhc.vhc_insurance_proof,
                                                        vhb.vhc_insurance_proof vhc_insurance_proof1,
                                                        vin.vhc_insurance_proof vhc_insurance_proof2,

                                                        vhc.vhc_front_plate,
                                                        vhb.vhc_front_plate vhc_front_plate1,
                                                        vin.vhc_front_plate vhc_front_plate2,

                                                        vhc.vhc_rear_plate,
                                                        vhb.vhc_rear_plate vhc_rear_plate1,
                                                        vin.vhc_rear_plate vhc_rear_plate2,

                                                        vhc.vhc_pollution_certificate,
                                                        vhb.vhc_pollution_certificate vhc_pollution_certificate1,
                                                        vin.vhc_pollution_certificate vhc_pollution_certificate2,

                                                        vhc.vhc_reg_certificate,
                                                        vhb.vhc_reg_certificate vhc_reg_certificate1,
                                                        vin.vhc_reg_certificate vhc_reg_certificate2,

                                                        vhc.vhc_permits_certificate,
                                                        vhb.vhc_permits_certificate vhc_permits_certificate1,
                                                        vin.vhc_permits_certificate vhc_permits_certificate1,

                                                        vhc.vhc_fitness_certificate,
                                                        vhb.vhc_fitness_certificate vhc_fitness_certificate1,
                                                        vin.vhc_fitness_certificate vhc_fitness_certificate2

                                                        from vehicles vhc
                                                        INNER join vehicle_map vhm
                                                        ON vhc.vhc_id = vhm.vhc_id
                                                        LEFT JOIN vehicles_info vin
                                                        ON vhc.vhc_id = vin.vhc_vehicle_id "
														//  . " AND vhm.vhc_id <> vhm.vhc_id1 "
														. "INNER join vehicles_bak vhb
                                                        ON vhm.vhc_id1 = vhb.vhc_id
                                                        where vhm.vhc_id1 = $fileFinal
                                                        ORDER by vhc.vhc_approved ,vhb.vhc_approved, vhb.vhc_modified_at
                                                        ";

												$resultv1	 = Yii::app()->db->createCommand($sqlv)->queryRow();
												$resVArr1	 = array_filter($resultv1);
												echo "Details of $file $file1 $fileFinal: ";
												var_dump($resVArr1);
												$d3			 = $d2 . DIRECTORY_SEPARATOR . $fileFinal;
												$df3		 = dir($d3);
												$files		 = [];
												while ($fileImage	 = $df3->read())
												{
													if ($fileImage != "." && $fileImage != "..")
													{
														$files[] = $fileImage;
													}
												}
												foreach ($resVArr1 as $k => $v1)
												{
													echo "\n";
													if (array_key_exists($k, $resVArr1))
													{
														echo $k . ' - ' . $v1;
														$val	 = [];
														$val	 = explode('/', $v1);
														// var_dump($val);
														$type	 = 0;
														if ($val[2] > 0)
														{
															unset($val[2]);
														}
														var_dump($val);
														$val1	 = implode('/', $val);
														$from	 = $originDir . DIRECTORY_SEPARATOR . $fileFinal . DIRECTORY_SEPARATOR . $val[5];
														$to		 = $destDir . DIRECTORY_SEPARATOR . $fileFinal . DIRECTORY_SEPARATOR . $val[5];
														if (in_array($k, $vFileTypeArr))
														{
															$type	 = 1;
															$k1		 = $k . '1';
															$k2		 = $k . '2';

															if (in_array($val[5], $files))
															{
																//echo "Exist";
																$transaction = Yii::app()->db->beginTransaction();
																try
																{
																	if (Vehicles::model()->updateAttachmentPath($fileFinal, $k, $val1, 'vehicles'))
																	{

																		unset($resVArr1[$k]);
																		if ($resVArr1[$k1] != '' && in_array($k1, $vFileTypeArr1))
																		{
																			unset($resVArr1[$k1]);
																		}
																		if ($resVArr1[$k2] != '' && in_array($k2, $vFileTypeArr2))
																		{
																			unset($resVArr1[$k2]);
																		}
																	}
																	echo "\n ";
																	echo $from;
																	echo "\n ";
																	echo $to;
																	if ($this->moveFile($from, $to))
																	{
																		$transaction->commit();
																		echo "Transaction Committed";
																	}
																	else
																	{
																		$transaction->rollback();
																	}
																}
																catch (Exception $e)
																{
																	$transaction->rollback();
																}
																echo "\n ";
																echo $val1;
																echo "\n ";
																echo $k;
																echo "\n ";
															}
														}
														if (in_array($k, $vFileTypeArr1))
														{
															$k1	 = $k;
															$k	 = rtrim($k1, '1');
															$k2	 = $k . '2';

															if (in_array($val[5], $files))
															{
																$transaction = Yii::app()->db->beginTransaction();
																try
																{
																	if (Vehicles::model()->updateAttachmentPath($fileFinal, $k, $val1, 'vehicles'))
																	{

																		unset($resVArr1[$k]);
																		if ($resVArr1[$k1] != '' && in_array($k1, $vFileTypeArr1))
																		{
																			unset($resVArr1[$k1]);
																		}
																		if ($resVArr1[$k2] != '' && in_array($k2, $vFileTypeArr2))
																		{
																			unset($resVArr1[$k2]);
																		}
																	}
																	echo "\n ";
																	echo $from;
																	echo "\n ";
																	echo $to;
																	if ($this->moveFile($from, $to))
																	{
																		$transaction->commit();
																		echo "Transaction Committed";
																	}
																	else
																	{
																		$transaction->rollback();
																	}
																}
																catch (Exception $e)
																{
																	$transaction->rollback();
																}
															}
															echo "in 1";
														}
														if (in_array($k, $vFileTypeArr2))
														{
															$k2	 = $k;
															$k	 = rtrim($k2, '2');
															$k1	 = $k . '1';
															if (in_array($val[5], $files))
															{
																$transaction = Yii::app()->db->beginTransaction();
																try
																{
																	if (Vehicles::model()->updateAttachmentPath($fileFinal, $k, $val1, 'vehicles'))
																	{

																		unset($resVArr1[$k]);
																		if ($resVArr1[$k1] != '' && in_array($k1, $vFileTypeArr1))
																		{
																			unset($resVArr1[$k1]);
																		}
																		if ($resVArr1[$k2] != '' && in_array($k2, $vFileTypeArr2))
																		{
																			unset($resVArr1[$k2]);
																		}
																	}
																	echo "\n ";
																	echo $from;
																	echo "\n ";
																	echo $to;
																	if ($this->moveFile($from, $to))
																	{
																		$transaction->commit();
																		echo "Transaction Committed";
																	}
																	else
																	{
																		$transaction->rollback();
																	}
																}
																catch (Exception $e)
																{
																	$transaction->rollback();
																}
															}
															echo "in 2";
														}
													}

													echo "\n";
												}
												echo "============================================== \n";
											}
										}
									}
								}
							}
							else
							{
								
							}
						}
					}
				}
				else
				{
					
				}
			}
		}
		echo "\n";
		echo $i;
		echo "\n";
		echo 'vehicles : ' . $v;
		echo "\n";
		echo 'drivers : ' . $d;

		closedir($dh);
	}

	public function moveFile($from, $to)
	{
		$val = explode('\\', $to);
		array_pop($val);
		$t1	 = implode('\\', $val);
		if (is_dir($t1))
		{
			echo "$t1 folder exist";
			echo "\n";
		}
		else
		{
			@mkdir($t1, 777, true);
			echo "$t1 new folder made";

			echo "\n";
		}
		return rename($from, $to);
	}

}
