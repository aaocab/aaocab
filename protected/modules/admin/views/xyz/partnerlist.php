<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12">            
            <div class="panel" >
                <div class="panel-body ">
                    <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
						<?php
						$uniqueId		 = $model->pat_id;
						$fromCity		 = $model->pat_from_city;
						$tocity			 = $model->pat_to_city;
						$bookingId		 = $model->pat_booking_id;
						$fromMmtCode	 = $model->pat_from_code;
						$toMmtCode		 = $model->pat_to_code;
						$bookingType	 = $model->pat_booking_type;
						$errorType		 = $model->pat_error_type;
						$errorMsg		 = $model->pat_error_msg;
						$ipAddress		 = $model->pat_ip_address;
						$createAt		 = $model->pat_created_at;
						$agtId			 = $model->pat_agent_id;
						$crtDate		 = strtotime($createAt);
						$patType		 = $model->pat_type;
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


						$serverId = Config::getServerID();
						$basePath = yii::app()->basePath . "/doc/{$serverId}";
						$link = '/partner/api/' . $agtId . '/' . $year . '/' . $month . '/' . $today . '/' . $hour . '/' . $patType . '_' . $uniqueId . '_' . $bookingType . '.apl';
						
						$file = $basePath . $link;
						$file	= str_replace('/', DIRECTORY_SEPARATOR, $file);

						$f = fopen($file, "r");
						while ($line = fgets($f, 1000))
						{
							echo $line . "<br>";
						}
						?>
                    </div>
                    <div>&nbsp;</div>
                </div>
            </div>
        </div>
    </div>
</div> 