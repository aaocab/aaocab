 <style>
    .dlgComments .dijitDialogPaneContent{
        overflow: auto;
    }
</style>
<div class="panel-advancedoptions" >
    <div class="row">
        <div class="col-md-12"> 
            <div class="panel" >
                <div class="panel-body panel-no-padding p0 pt10">
                    <div class="panel-scroll1">
                        <div style="width: 100%; overflow: auto;  border: 1px #aaa solid;color: #444;">
                            
                            <table class="table table-bordered mb0">
                                <tr>
                                   <th>Vendor Id</th> 
                                   <th>Vendor Name</th>
				   <th>Rank</th>
				</tr>
                                <?php
                                #print_r($bkgVendorRank);
                                foreach ($bkgVendorRank as $val)
                                {
                                ?>
                                <tr>
                                    <td><?=$val['vendor_id']?></td>
                                    <td><?=$val['vendor_name']?></td>
                                    <td><?=$val['bvr_rank']?></td>
                                </tr>
                                <?php	
                                }
                                if(empty($bkgVendorRank))
                                {
                                    echo '<tr><td colspan="3"> No record found</td></tr>';
                                }
                                ?>
                                
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



