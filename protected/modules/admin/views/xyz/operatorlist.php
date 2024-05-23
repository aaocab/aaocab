<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body ">
                    <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
						<?php
						$uniqueId		 = $model->oat_id;
						$fromCity		 = $model->oat_from_city;
						$tocity			 = $model->oat_to_city;
						$bookingId		 = $model->oat_booking_id;
//						$fromMmtCode	 = $model->pat_from_code;
//						$toMmtCode		 = $model->pat_to_code;
						$bookingType	 = $model->oat_booking_type;
						$errorType		 = $model->oat_error_type;
						$errorMsg		 = $model->oat_error_msg;
						$ipAddress		 = $model->oat_ip_address;
						$createAt		 = $model->oat_created_at;
						$agtId			 = $model->oat_operator_id;
						$crtDate		 = strtotime($createAt);
						$oatType		 = $model->oat_type;
						$routes			 = ['fromCity'		 => $fromCity,
							'toCity'		 => $tocity,
							'bookingId'		 => $bookingId,
//							'fromMmtCode'	 => $fromMmtCode,
//							'toMmtCode'		 => $toMmtCode,
							'bookingType'	 => $bookingType,
							'errorType'		 => $errorType,
							'errorMsg'		 => $errorMsg,
							'ipAddress'		 => $ipAddress,
							'createdAt'		 => $createAt
						];
						$trackingDetails = json_encode($routes);
						$year			 = date('Y', $crtDate);
						$month			 = date('m', $crtDate);
						$today			 = date('d', $crtDate);
						$hour			 = date('H', $crtDate);
						
						$serverId		 = Config::getServerID();
						$basePath		 = yii::app()->basePath;
						$link			 = "/doc/{$serverId}" . '/operator/api/' . $agtId . '/' . $year . '/' . $month . '/' . $today . '/' . $hour . '/' . $oatType . '_' . $uniqueId . '_' . $bookingType . '.apl';
						$str			 = '/';
						$resStr			 = str_replace($str, DIRECTORY_SEPARATOR, $link);
						$file			 = $basePath . $resStr;
						$f = fopen($file, "r");
						fclose($f);
						print_r($trackingDetails);
						echo "<br><br>";
						echo "\r\n\r\n============\r\n\r\n";
						echo "<br><br>";
						if (file_exists($file))
						{
							$f		 = fopen($file, "r");
							while ($line	 = fgets($f, 1000))
							{
								echo $line . "<br>";
							}
							fclose($f);
						}
						else if ($model->oat_s3_data != '' && $model->oat_s3_data != '{}' && $model->oat_s3_data != null)
						{
							$spaceFile = Stub\common\SpaceFile::populate($model->oat_s3_data);
							if ($spaceFile->getFile() != null)
							{
								echo $spaceFile->getFile()->getContents();
							}
						}
						else
						{
							echo "File not found";
						}
						?>
                    </div>
                    <div>&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
</div> 