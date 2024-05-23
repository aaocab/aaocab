<div class="row">
    <div class="col-12">
        <?php
        if ($status == 'del') {
            ?>
            <div class="alert alert-success">Place successfully deleted</div>
        <?php } ?> 
        <div class="row">
            <div class="col-12 mb10">
                <a class="btn btn-primary pl10 pr10" id="addPlace" href="<?= Yii::app()->createUrl('place/create'); ?>"  name="sub" ><img src="/images/bx-plus-circle2.svg" alt="img" width="14" height="14"> Add place</a>
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
                                        <a class="btn btn-sm btn-success p5 pl10 pr10" href="<?= Yii::app()->createUrl('place/update', array('id' => $val['user_place_id'])); ?>" title="Edit Place"><img src="/images/bx-edit-alt.svg" alt="img" width="14" height="14"></a>
                                        <a class="btn btn-sm btn-danger p5 pl10 pr10" href="<?= Yii::app()->createUrl('place/deleteme', array('id' => $val['user_place_id'])); ?>" title="Delete Place"  onclick="return confirm('Do you really want to delete this place record?')" ><img src="/images/bx-trash.svg" alt="img" width="14" height="14"></a>
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