
<style>

    .table{
        margin-bottom: 0;
    }
    th {font-size: 14px !important;
        padding: 5px !important;
        background-color: #60aaef;
        color:#fff;
    }

    td {
        font-size: 13px !important;
        padding: 4px !important;
    }
</style>
<div class="row">
    <div class="col-xs-12 mb20 mt15">
        <div class="row">
            <div class="panel panel-default">
                <div class="panel-body mt10 n pr0 pb0 pl0">
                    <?
                    if ($status == 'del') {
                        ?>
                        <div class="alert alert-success">Place successfully deleted</div>
                    <? } ?> 
                    <div class='row mt50 n pt0'>
                        <div class="col-xs-12 text-right mb10">
                            <a class="btn btn-info" id="addPlace" href="<?= Yii::app()->createUrl('place/create'); ?>"  name="sub" >Add Place</a>
                        </div>
                    </div>
                    <table class="table table-bordered" >

                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>Zip Code</th>
                            <th>Action</th>
                        </tr>

                        <tbody>
                            <?
                            if (count($models) < 1) {
                                ?>
                                <tr><td colspan="5">No Records Found</td></tr>
                                <?
                            }
                            else {
                                foreach ($models as $key => $val) {
                                    $address = '';
                                    $address = $val['address1'];
                                    $address.=($val['address2'] == '') ? '' : ', ' . $val['address2'];
                                    $address.=($val['address3'] == '') ? '' : ', ' . $val['address3'];
                                    ?>
                                    <tr>
                                        <td><?= $val['name'] ?></td>

                                        <td><?= $address ?></td>
                                        <td><?= $val->city0->cty_name; ?></td>
                                        <td><?= $val['zip'] ?></td>
                                        <td style="text-align: center">                                        
                                            <a class="btn btn-xs btn-info" href="<?= Yii::app()->createUrl('place/update', array('id' => $val['user_place_id'])); ?>" title="Edit Place"><i class="fa fa-edit"></i></a>
                                            <a class="btn btn-xs btn-danger" href="<?= Yii::app()->createUrl('place/deleteme', array('id' => $val['user_place_id'])); ?>" title="Delete Place"  onclick="return confirm('Do you really want to delete this place record?')" ><i class="fa fa-close"></i></a>
                                        </td>
                                    </tr>
                                    <?
                                }
                            }
                            ?>
                        </tbody>
                    </table>

                </div>
                <div class="panel-footer ">                    
                    <?php                           
                    // the pagination widget with some options to mess
                    $this->widget('CLinkPager', array('pages' => $userPlace->pagination));
                    ?>
                </div>
            </div>
        </div>

    </div>
</div>
<script type="text/javascript">
    var baseUrl = "<?php echo Yii::app()->request->baseUrl; ?>";

    function confirmDelete() {
        if (confirm("Do you really want to delete this place ?")) {
            return true;
        } else {
            return false;
        }
    }

</script>