<style>
    .search-form ul{
        list-style: none ;
        margin-bottom: 20px;
        vertical-align: bottom
    }
    .search-form ul li{
        padding: 0;
    }
    .table {
        margin-bottom: 5px;
    }
    table.table tr th{

    }
    label{
        color: #000000;
    }
    .modal-body{
        padding-top: 20px!important;
    }

</style>

<div class="row m0 mb50">
    <div class=" col-xs-12">
        <h2 class="text-center"></h2>


        <div id="userView1">
            <div class=" col-xs-12">
                <div class="projects  " >

                    <div class="panel panel-default ">
                        <div class="panel-heading"><h4>Documents:</h4></div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8 col-sm-10 col-xs-12" style="float: none; margin: auto">
                                    <div class="row ">
                                        <div class="col-xs-12">
                                            <ul style="list-style: none;padding-left: 0">
												<?php
												if ($model->drv_aadhaar_img != '' || $model->drv_pan_img != '' || $model->drv_voter_id_img != '')
												{
													if ($model->drv_aadhaar_img != '')
													{
														?>
														<li>
															<img src='/uploadedFiles/<?php echo $model->drv_aadhaar_img ?>' width='100%'>
		<!--                                                            <a target="_blank"  onclick="showimage('<?php echo $model->drv_aadhaar_img ?>');" ><?= $model->drv_aadhaar_img ?></a>-->
														</li>
														<?php
													} if ($model->drv_pan_img != '')
													{
														?>
														<li>
															<img src='/uploadedFiles/<?php echo $model->drv_pan_img ?>' width='100%'>
		<!--                                                            <a target="_blank" onclick="showimage1('<?php echo $model->drv_pan_img ?>');"  ><?= $model->drv_pan_img ?></a>-->
														</li>
														<?php
													} if ($model->drv_voter_id_img != '')
													{
														?>
														<li> 
															<img src='/uploadedFiles/<?php echo $model->drv_voter_id_img ?>' width='100%'>
		<!--                                                            <a target="_blank" onclick="showimage2('<?php echo $model->drv_voter_id_img ?>');" ><?= $model->drv_voter_id_img ?></a>-->
														</li>

														<?php
													}
												}
												else
												{
													echo " <li> None </li> ";
												}
												?>
                                            </ul>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


