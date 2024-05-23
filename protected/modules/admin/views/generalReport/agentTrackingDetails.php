<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body ">
                    <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
						<?php
						$uniqueId		 = $model->aat_id;
						$fromCity		 = $model->aat_from_city;
						$tocity			 = $model->aat_to_city;
						$bookingId		 = $model->aat_booking_id;
						$fromMmtCode	 = $model->aat_from_mmt_code;
						$toMmtCode		 = $model->aat_to_mmt_code;
						$bookingType	 = $model->aat_booking_type;
						$errorType		 = $model->aat_error_type;
						$errorMsg		 = $model->aat_error_msg;
						$ipAddress		 = $model->aat_ip_address;
						$createAt		 = $model->aat_created_at;
						$agtId			 = $model->aat_agent_id;
						$crtDate		 = strtotime($model->aat_created_at);
						$aatType		 = $model->aat_type;
						$bookingType	 = $model->aat_booking_type;
						$routes			 = ['fromCity'		 => $fromCity,
							'toCity'		 => $tocity,
							'bookingId'		 => $bookingId,
							'fromMmtCode'	 => $fromMmtCode,
							'toMmtCode'		 => $toMmtCode,
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
						$link			 = "/doc/{$serverId}" . '/partner/api/' . $agtId . '/' . $year . '/' . $month . '/' . $today . '/' . $hour . '/' . $aatType . '_' . $uniqueId . '_' . $bookingType . '.apl';
						$str			 = '/';
						$resStr			 = str_replace($str, DIRECTORY_SEPARATOR, $link);
						$file			 = $basePath . $resStr;
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
						else if ($model->aat_s3_data != '')
						{
							$spaceFile = Stub\common\SpaceFile::populate($model->aat_s3_data);
							if ($spaceFile->getFile() != null)
							{
								echo nl2br($spaceFile->getFile()->getContents());
							}
						}
						?>
                    </div>
                    <div>&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
</div> 
