<div class="row">
    <div class="panel-body">
        <div class="col-xs-12">
            <div class="panel panel-default main-tab1">
                <div class="panel-body panel-border">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 table-responsive">
                            <table class="table table-striped table-bordered">
                                <tr>
									<td class="col-xs-1 col-sm-1 col-md-1 col-1"><b>Received On</b></td>
                                    <td><b>Message</b></td>
                                    <td><b>Status</b></td>
                                </tr>

								<?php
								foreach ($messages as $val)
								{
									$message				 = ($val['whl_status'] == 'Sent')?$val['wht_template_content']:$val['whl_message'];
									$language				 = WhatsappLog::languageByLangCode($val['wht_lang_code']);
									$messageTemplateIndex	 = json_decode($val['whl_message']);
									?>
									<tr>
										<td><?php echo $val['whl_created_date']; ?></td>
										<td>	
											<?php
											if (preg_match_all("~\{\{\s*(.*?)\s*\}\}~", $message, $arr))
											{
												foreach ($arr[1] as $row)
												{
													echo $message = str_replace('{{' . $row . '}}', $messageTemplateIndex[$row - 1]->text, $message);
												}
											}
											else{
												echo $message;
											}
											?>
										</td>
										<td><?php echo $val['whl_status']; ?></td>
									</tr>


								<?php } ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



