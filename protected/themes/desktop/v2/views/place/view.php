<div class="row">
    <div class="col-12">
        <?php
        if ($status == 'del') {
            ?>
            <div class="alert alert-success">Place successfully deleted</div>
        <?php } ?> 
        <div class="row">
            <div class="col-12 mb10 pt10">
                <a class="btn-orange m0 mb10" id="addPlace" href="<?= Yii::app()->createUrl('place/create'); ?>"  name="sub" >Add Place</a>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <table class="table table-bordered" >
                    <tr class="thead-dark">
                        <th>Name</th>
                        <th>Address</th>
                        <th>City</th>
                        <th class="text-center">Zip Code</th>
                        <th class="text-center">Action</th>
                    </tr>
                    <tbody>
                        <?php
                        if (count($models) < 1) {
                            ?>
                            <tr><td colspan="5">No Records Found</td></tr>
                            <?php
                        } else {
                            foreach ($models as $key => $val) {
                                $address = '';
                                $address = $val['address1'];
                                $address .= ($val['address2'] == '') ? '' : ', ' . $val['address2'];
                                $address .= ($val['address3'] == '') ? '' : ', ' . $val['address3'];
                                ?>
                                <tr>
                                    <td><?= $val['name'] ?></td>

                                    <td><?= $address ?></td>
                                    <td><?= $val->city0->cty_name; ?></td>
                                    <td class="text-center"><?= $val['zip'] ?></td>
                                    <td style="text-align: center">                                        
                                        <a class="btn btn-xs bg-yellow color-white" href="<?= Yii::app()->createUrl('place/update', array('id' => $val['user_place_id'])); ?>" title="Edit Place"><i class="fas fa-pencil-alt"></i></a>
                                        <a class="btn btn-xs btn-danger" href="<?= Yii::app()->createUrl('place/deleteme', array('id' => $val['user_place_id'])); ?>" title="Delete Place"  onclick="return confirm('Do you really want to delete this place record?')" ><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <?php
                // the pagination widget with some options to mess
                $this->widget('CLinkPager', array('pages' => $userPlace->pagination));
                ?>
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