<div class="row">
					<div class="col-xs-12">
						<?php
						if (!empty($dataProvider))
						{
							$this->widget('booster.widgets.TbGridView', array(
								'responsiveTable'	 => true,
								'dataProvider'		 => $dataProvider,
								'template'			 => "<div class='panel-heading'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 pt5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>
                                    <div class='panel-body'>{items}</div>
                                    <div class='panel-footer'>
                                        <div class='row m0'>
                                            <div class='col-xs-12 col-sm-6 p5'>{summary}</div>
                                            <div class='col-xs-12 col-sm-6 pr0'>{pager}</div>
                                        </div>
                                    </div>",
								'itemsCssClass'		 => 'table table-striped table-bordered mb0',
								'htmlOptions'		 => array('class' => 'table-responsive panel panel-primary  compact'),
								'columns'			 => array(
									array('name' => 'vnd_name', 'value' => $data['vnd_name'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-left'), 'header' => 'Vendor Name'),
									array('name' => 'vnd_code', 'value' => $data['vnd_code'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-left'), 'header' => 'Vendor Code'),
									array('name' => 'bcbIds', 'value' => $data['bcbIds'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Trip Id'),
									array('name' => 'totalAmount', 'value' => $data['totalAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Total Booking Amount'),
									array('name' => 'advanceAmount', 'value' => $data['advanceAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Total Advanced Amount'),
									array('name' => 'amountToCollect', 'value' => $data['amountToCollect'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Amount To Be Collected'),
									array('name' => 'tripVendorAmount', 'value' => $data['tripVendorAmount'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Trip Vendor Amount'),
									array('name' => 'vndNetEffect', 'value' => $data['vndNetEffect'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Excess Amt To Vendor '),
									array('name' => 'outstanding_balance', 'value' => $data['outstanding_balance'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => '(+) Vendor Payable / (-) Vendor Receivable'),
									array('name' => 'vendorToGozo', 'value' => $data['vendorToGozo'], 'sortable' => true, 'headerHtmlOptions' => array('class' => 'text-center'), 'htmlOptions' => array('class' => 'text-right'), 'header' => 'Net Gozo Recievable'),
							)));
						}
						?>
					</div></div>

