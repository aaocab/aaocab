<style>
    .reportBtn .btn{
        width:250px;
        margin-left: 40px;
    }
</style>
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.min.js');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/form-select2/select2.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/assets/plugins/bootbox/bootbox.min.js');
$pageno	 = Yii::app()->request->getParam('page');
?>
<div id="content" class="mb20">
    <div class="col-xs-12">
        <div class="actions">
            <ul >
                <li class="col-xs-2"><a href="<?php echo Yii::app()->createUrl('admin/index/dashboard') ?>">Dashboard</a></li>
                <li class="col-xs-2"><a href="<?php echo Yii::app()->createUrl('admin/user/list') ?>">Users</a></li>
                <li class="col-xs-2"><a href="#">Payments</a></li>
                <li class="col-xs-2"><a href="<?php echo Yii::app()->createUrl('admin/index/leads') ?>">Leads</a></li>
                <li class="col-xs-2"><a href="<?php echo Yii::app()->createUrl('admin/index/notifications') ?>"  >Feedbacks</a></li>
                <li class="col-xs-2"><a href="<?php echo Yii::app()->createUrl('admin/user/reports') ?>"  style="background-color:  #FFF;color: #000">Reports</a></li>
            </ul>
        </div>
    </div>




    <div class="col-sm-12 col-xs-12 search-form mt20 ">
		<?php
		$form	 = $this->beginWidget('booster.widgets.TbActiveForm', array(
			'id'					 => 'citySelection-form-form',
			'action'				 => Yii::app()->createUrl('admin/user/reports', array('page' => $pageno)),
			'enableClientValidation' => true,
			'method'				 => 'get',
			'errorMessageCssClass'	 => 'help-block'));
		?>
        <ul class="col-xs-12" style="list-style: none;">
            <li class="col-xs-4"> <?php
				$arr	 = Yii::app()->request->getParam('Providers');
				echo $form->select2Group($model, 'pvr_city', array('label'			 => '',
					'widgetOptions'	 => array('data'			 => $model->getCityByProvider(),
						'val'			 => $arr['pvr_city'],
						'events'		 => array(' change' => 'js: function(e) { searchby(); }'),
						'htmlOptions'	 => array('style' => 'width: 100%', 'placeholder' => "Please select city")
				)));
				?>
                <input type="hidden" name="noofProvider" id="noofProvider" value="">
            </li>

            <li class="col-xs-4 text-primary" id="vendor"><?php
				if ($_GET['noofProvider'] != '')
				{
					echo 'Total Vendors signed up : ' . Yii::app()->request->getParam('noofProvider') . '.';
				}
				?></li>
            <li class="col-xs-4">
                  <!--  <input type="submit" name="phone_submit" value="search" class="col-xs-1 btn-info">-->
            </li>
        </ul>
		<?php $this->endWidget(); ?>
    </div>

    <div class="col-sm-12 col-xs-12 search-form mt20 mb20 row ">
        <div class="col-xs-12 reportBtn pb20"><a id="showprogressPanel" class="btn btn-primary" style="text-decoration: none;">Vendor Progress report</a> </div>
        <div id="progress" >
            <div class="projects" >
                <table cellpadding="0" cellspacing="0" >
                    <tbody id="result">
                    </tbody>
                </table>

            </div>
        </div>
        <div class="col-xs-12 reportBtn"><a  id="showvendorPanel" class="btn btn-primary" style="text-decoration: none;">Top Vendors for city</a></div>
        <div id="vendorReport" style="display: none;">Comming Soon</div>
    </div>







</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#showprogressPanel").click(function () {

            $("#progress").toggle("slow");
            $CityID = $("#noofProvider").val();
            $href = '<?= Yii::app()->createUrl("admin/user/providercity"); ?>';
            $.ajax({
                url: $href,
                dataType: "json",
                data: {"id": $val},
                "success": function (data) {
                    $('#result').html('');
                    var items = [];
                    var emaildate = "";
                    var mobiledate = "";
                    var imagedate = "";
                    var bcheckdate = "";
                    var lasttime = "";
                    var lastactive = "";

                    var html = '<tr><th></th><th>User ID#</th><th>Sign Up</th>\n\
        <th>Email Verified</th><th>Phone Verified</th><th>Picture Added</th>\n\
<th>Background Checked</th><th>Service added</th><th>Provider not logged in for</th></tr>';

                    $.each(data.info, function (i, item) {

                        if (item.usr_email_verify == 1)
                        {
                            if (item.usr_email_verify_date != '' && item.usr_email_verify_date != null)
                            {
                                emaildate = item.usr_email_verify_date;
                            } else
                            {
                                emaildate = "Not Available";
                            }
                        } else
                        {
                            emaildate = "No";
                        }
                        if (item.usr_mobile_verify == 1)
                        {
                            if (item.usr_mobile_verify_date != '' && item.usr_mobile_verify_date != null)
                            {
                                mobiledate = item.usr_mobile_verify_date;
                            } else
                            {
                                mobiledate = "Not Available";
                            }
                        } else
                        {
                            mobiledate = "No";
                        }
                        if (item.pvr_image_modify_date != '' && item.pvr_image_modify_date != null)
                        {
                            imagedate = item.pvr_image_modify_date;
                        } else
                        {
                            imagedate = "Not Available";
                        }
                        if (item.pvr_background_check == 1)
                        {
                            if (item.pvr_background_check_date != '' && item.pvr_background_check_date != null)
                            {
                                bcheckdate = item.pvr_background_check_date;
                            } else
                            {
                                bcheckdate = "Not Available";
                            }
                        } else
                        {
                            bcheckdate = "No";
                        }

                        if (item.lasttime != '')
                        {
                            lasttime = item.lasttime;
                        } else
                        {
                            lasttime = "No";
                        }

                        if (item.lastactive != '' && item.lastactive != null)
                        {
                            lastactive = item.lastactive + 'hrs';
                        } else
                        {
                            lastactive = "0";
                        }

                        items.push('<tr><td>' + item.pvr_name + '</td><td>' + item.pvr_user_id + '</td>\n\
            <td>' + item.usr_created_at + '</td><td>' + emaildate + '</td><td>' + mobiledate + '</td>\n\
            <td>' + imagedate + '</td><td>' + bcheckdate + '</td><td>' + lasttime + '</td><td>' + lastactive + '</td></tr>');
                    });
                    $('#result').append(html + items.join(''));
                }
            });
        });
        $("#showvendorPanel").click(function () {
            // alert("gfhhg");
            $("#vendorReport").toggle("slow");
        });
    });
    function searchby()
    {
        $val = $('#<?= CHtml::activeId($model, 'pvr_city') ?>').val();
        $href = '<?= Yii::app()->createUrl("admin/user/providercity"); ?>';
        $.ajax({
            url: $href,
            dataType: "json",
            data: {"id": $val},
            "success": function (data) {
                //alert(data.info);
                $("#vendor").show("slow");
                $("#vendor").text("Total Vendors signed up:" + data.count + '.');
                $("#noofProvider").val(data.count);
            }
        });
    }

</script>