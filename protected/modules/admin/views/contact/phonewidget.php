<div class="col-xs-12 col-sm-6 col-md-6">
    <div class="form-group">
        <label class="control-label" for="">Phone*<strong style="font-size:10px ">(Click plus icons to add the phone and then save)</strong></label><input value="" placeholder="Phone"  class="form-control ContactPhone" maxlength="10"></div>
    <div class="alert alert-block alert-danger messagePhone" style="display:none"></div>
    <div class="row float-right " style="white-space: nowrap">
        <div class="col-xs-12">
            <a class="btn btn-primary btn-xs  weight400 font-bold " onclick="AddPhone()"title="Add More"><i class="fa fa-plus" aria-hidden="true"></i></a>
        </div>
    </div>
    <div class="tablePhone">
        <?php
        $cpModels = $model->contactPhones;
        if ($cpModels == [])
        {
            $cpModels[] = new ContactPhone();
        }
        $isPrimaryPhone = "";
        $phoneno        = "";
        for ($i = 0; $i < count($cpModels); $i++)
        {
            if ($cpModels[$i]->phn_phone_no != "")
            {
              //  $cpModels[$i]->phn_contact_id

                $btnPhone = $i == 0 ? 'btn btn-success btn-xs phonePrimary ' : 'btn btn-primary btn-xs phonePrimary ';
                if ($i == 0)
                {
                    ?>	<table class="table table-hover"><thead><tr><th>Phone</th><th>Primary</th><th>Remove</th> <th>Resend Link</th></tr></thead><tbody class="addphone">  <?php } ?>
                        <tr>
                            <?php
                            echo $form->hiddenField($cpModels[$i], "[$i]phn_phone_no", array('type' => "hidden", 'class' => "phone_address", 'value' => $uvrid != NULL ? $model->phone_no : $cpModels[$i]->phn_phone_no));
                            echo $form->hiddenField($cpModels[$i], "[$i]phn_is_primary", array('type' => "hidden", 'class' => "phone_primary", 'value' => $uvrid != NULL ? 1 : count($cpModels) == 1 ? 1 : $cpModels[$i]->phn_is_primary));
                            echo $form->hiddenField($cpModels[$i], "[$i]phn_type", array('type' => "hidden", 'class' => "phone_type", 'value' => $uvrid != NULL ? 1 : count($cpModels) == 1 ? 1 : $cpModels[$i]->phn_type));
                            ?>
                            <td  style="word-break:break-all;"><?php echo $cpModels[$i]->phn_phone_no ?><span><?php echo $cpModels[$i]->phn_is_verified == 0 ? '<img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="20">' : '<img src="/images/icon/reconfirmed.png" style="cursor:pointer" title="Contact Verified" width="20">' ?></span></td>
                            <td><button type="button" class="<?php echo $btnPhone; ?>" <?php
                                if ($i == 0)
                                {
                                    echo 'disabled';
                                }
                               ?> ><i class="<?php echo ($cpModels[$i]->phn_is_primary == 1) ? 'fa fa-check-square-o' : 'fa fa-square-o'; ?>" aria-hidden="true"></i></button></td>
                            <td><button type="button" class="btn btn-danger btn-xs removePhone" onclick="removePhone(<?php echo $cpModels[$i]->phn_phone_no ?>,<?php echo $cpModels[$i]->phn_contact_id ?>)"><i class="fa fa-times" aria-hidden="true"></i></button></td>
                            <td><button type="button" onclick="resendLink(<?php echo $cpModels[$i]->phn_phone_no ?>,<?php echo $cpModels[$i]->phn_contact_id ?>,<?php echo Yii::app()->request->getParam('type');?>)"><i class="fa fa-share"></i></button></td>
                        </tr>
                        <?php
                        if ((count($cpModels) - 1) == $i)
                        {
                            ?> 	</tbody> </table>	
                    <?php
                }
            }
        }
        ?>
    </div>
</div>
<script type="text/javascript">
    let phoneArray = "";
    var rowCounterPhone = '<?php echo count($cpModels); ?>';
    $(document).ready(function ()
    {
        $(document).on('click', '.phonePrimary', function () {
            $('.phonePrimary').prop("disabled", false);
            $(this).prop("disabled", true);
            $('.phone_primary').each(function () {
                $(".phone_primary").val(0);
                $('.phonePrimary').removeClass('btn-success');
                $('.phonePrimary').addClass('btn-primary');
                $('.phonePrimary').children('i').removeClass('fa-check-square-o');
                $('.phonePrimary').children('i').addClass('fa-square-o');
            });
            $(this).closest('tr').children('.phone_primary').val(1);
            $(this).closest('td').children('.phonePrimary').removeClass('btn-primary');
            $(this).closest('td').children('.phonePrimary').addClass('btn-success');
            $(this).closest('td').children('.phonePrimary').children('i').removeClass('fa-square-o');
            $(this).closest('td').children('.phonePrimary').children('i').addClass('fa-check-square-o');

        });
        $(document).on('click', '.removePhone', function () {
            $(this).parents("tr").remove();
            if ($('.addphone tr').length == 1)
            {
				
                $(".phone_primary").val(1);
                $('.phonePrimary').prop("disabled", true);
                $('.addphone td').children('.phonePrimary').removeClass('btn-primary');
                $('.addphone td').children('.phonePrimary').addClass('btn-success');
                $('.addphone td').children('.phonePrimary').children('i').removeClass('fa-square-o');
                $('.addphone td').children('.phonePrimary').children('i').addClass('fa-check-square-o');
            }
        });
    });
    
    function resendLink(ph,con,userType)
    {
        bootbox.confirm({
            message: "Are you sure want to resend link ?",
            buttons: {
                confirm: {
                    label: 'OK',
                    className: 'btn-info'
                },
                cancel: {
                    label: 'CANCEL',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                $.ajax({
                        type: 'POST',
                        url: '<?php echo Yii::app()->createUrl('admin/contact/resendVerificationLink'); ?>',
                         data: {"userType": userType,"phone": ph,"contact": con, 'YII_CSRF_TOKEN': "<?= Yii::app()->request->csrfToken ?>"},
                        dataType: 'json',
                         success: function (data)
                        {
                           alert(data);
                        }
                });
                }
            }
        });
    }
    
	function removePhone(ph,con,userType)
    {
        bootbox.confirm({
            message: "Are you sure want to remove phone ?",
            buttons: {
                confirm: {
                    label: 'OK',
                    className: 'btn-info'
                },
                cancel: {
                    label: 'CANCEL',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                if (result) {
                $.ajax({
                        type: 'POST',
                        url: '<?php echo Yii::app()->createUrl('admin/contact/removePhone'); ?>',
                        data: {"userType": userType,"phone": ph,"contact": con, 'YII_CSRF_TOKEN': "<?= Yii::app()->request->csrfToken ?>"},
                        dataType: 'json',
                         success: function (data)
                        {
                           alert(data);
						   $(this).parents("tr").remove();
                        }
                });
                }
            }
        });
    }
    function AddPhone()
    {
        var duplicatePhoneCount = 0;
        $("#msg").html("");
        var phone_address = $(".ContactPhone").val();
        var filter = /[1-9]{1}[0-9]{9}/;
        if (phone_address == "")
        {
            $(".messagePhone").show();
            $(".messagePhone").html('<p>Please provide your phone number!</p>');
            return false;
        } else if (phone_address.length < 10)
        {
            $(".messagePhone").show();
            $(".messagePhone").html('<p>Phone number lenght must be 10 number!</p>');
            return false;
        } else if (!filter.test(phone_address))
        {
            $(".messagePhone").show();
            $(".messagePhone").html('<p>Phone number format is invalid!</p>');
            return false;
        }
        $('.phone_address').each(function ()
        {
            if (phone_address.trim() == $(this).val().trim())
            {
                duplicatePhoneCount++;
            }
        });
        if (duplicatePhoneCount > 0)
        {
            $(".messagePhone").show();
            $(".messagePhone").html('<p>This phone number already exists!</p>');
            return false;
        }
        var href = '<?= Yii::app()->createUrl("admin/contact/checkPhone") ?>';
        $.ajax({
            'url': href,
            'type': 'POST',
            'dataType': "json",
            'data': {
                "phone_address": phone_address,

                "phone_country": "91",

                'YII_CSRF_TOKEN': "<?= Yii::app()->request->csrfToken ?>"
            },
            "success": function (response)
            {
                if (response.success)
                {
                    let  status = "1";
                    let phoneList = response.data;
                    phoneArray = response.data;
                    if (phoneList.length > 0)
                    {
                        let appendHtml =
                                "<table class='table table-hover table-bordered'>\n\
                            <caption float = 'right'><button class='btn btn-primary  weight400 font-bold addContact' onClick = 'addContactPhone(\"" + phone_address + "\",\"" + status + "\")' name='" + phone_address + "' >Add new Contact</button></caption>\n\
                            <thead>\n\
                                <th>Contact Name</th>\n\
                                <th>Contact Phone</th>\n\
                                <th>Is Driver</th>\n\
                                <th>Is Vendor</th>\n\
                                <th>Action</th>\n\
                            <thead>\n\
                            <tbody>";
                        for (let index = 0; index < phoneList.length; index++)
                        {
                            let contactName = phoneList[index].ctt_first_name + " " + phoneList[index].ctt_last_name;
                            if (phoneList[index].hasOwnProperty("ctt_business_name") && phoneList[index].ctt_business_name.length > 0)
                            {
                                contactName = phoneList[index].ctt_business_name;
                            }

                            let isDriver = "No";
                            let isVendor = "No";

                            if (phoneList[index].hasOwnProperty("cr_is_driver"))
                            {
                                isDriver = "Yes" + "(" + phoneList[index].drv_code + ")";
                            }

                            if (phoneList[index].hasOwnProperty("cr_is_vendor"))
                            {
                                isVendor = "Yes" + "(" + phoneList[index].vnd_code + ")";
                            }

                            appendHtml +=
                                    "<tr>\n\
                                                <td>" + contactName + "</td>\n\
                                                <td>" + phoneList[index].phn_phone_no + "</td>\n\
                                                <td>" + isDriver + "</td>\n\
                                                <td>" + isVendor + "</td>\n\
                                                 <td>\n\
                                                    <button class='btn btn-primary  weight400 font-bold ' title='Assign' onclick='sendPhoneId(\"" + phoneList[index].phn_contact_id + "\",\"" + phoneList[index].phn_phone_no + "\",\"" + contactName + "\",\"" + phoneList[index].ctt_license_no + "\")'><i class='fa fa-check-square-o' aria-hidden='true'></i>select</button></td>\n\
                                                </td>\n\
                                                </tr>";
                        }

                        appendHtml += "</tbody></table>";

                        phoneListBox = bootbox.dialog
                                ({
                                    message: appendHtml,
                                    title: "Existing Contacts",
                                    size: 'big',
                                    class: 'panel panel-default',
                                    onEscape: function ()
                                    {
                                        phoneListBox.modal('hide');
                                    }
                                });
                    }
                } else
                {
                    addContactPhone(phone_address, 0);
                }
            }
        });
    }

    function addContactPhone(phone_address, status)
    {

        $(".messagePhone").html("");
        $(".messagePhone").hide();
        var rowCount = $('.addphone tr').length == 0 ? 0 : rowCounterPhone;
        if (rowCount == 0)
        {
            $(".tablePhone").html("");
            var html = '<table class="table table-hover"><thead> <tr> <th>Phone</th> <th>Primary</th> <th>Remove</th> <th>Remove</th></tr></thead> <tbody class="addphone"><tr><input  type="hidden" value="1" name="ContactPhone[' + rowCount + '][phn_is_new]"><input class="phone_address" type="hidden" value="' + phone_address + '" name="ContactPhone[' + rowCount + '][phn_phone_no]"> <input class="phn_primary" type="hidden" value="1" name="ContactPhone[' + rowCount + '][phn_is_primary]"> <input class="phn_type" type="hidden" value="1" name="ContactPhone[' + rowCount + '][phn_type]"> <td style="word-break:break-all;">' + phone_address + '<span><img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="20"></span></td><td><button type="button" class="btn btn-success btn-xs phonePrimary" disabled><i class="fa fa-check-square-o" aria-hidden="true"></i></button></td><td><button type="button" class="btn btn-danger btn-xs removePhone"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr></tbody></table>';
            $(".tablePhone").append(html);
        } else
        {
            var html = '<tr><td  style="word-break:break-all;">' + phone_address + '<span><img src="/images/icon/unblock.png" style="cursor:pointer" title="Contact UnVerified" width="20"></span></td> <input class="phone_address" type="hidden" value="1" name="ContactPhone[' + rowCount + '][phn_is_new]"><input class="phone_address" type="hidden" value="' + phone_address + '" name="ContactPhone[' + rowCount + '][phn_phone_no]"> <input class="phone_primary" type="hidden" value="0" name="ContactPhone[' + rowCount + '][phn_is_primary]"> <input class="phone_type" type="hidden" value="1" name="ContactPhone[' + rowCount + '][phn_type]"> <td><button type="button" class="btn btn-primary btn-xs phonePrimary" ><i class="fa fa-square-o" aria-hidden="true"></i></button></td><td><button type="button" class="btn btn-danger btn-xs removePhone"><i class="fa fa-times" aria-hidden="true"></i></button></td><td>......</td></tr>';
            $(".addphone").append(html);
        }

        $(".ContactPhone").val('');
        rowCounterPhone++;
        if (status)
        {
            phoneListBox.modal('hide');
        }
    }

    function sendPhoneId(cttId, phone, name, licence)
    {

        let drvId = "";
        for (let index = 0; index < phoneArray.length; index++)
        {
            drvId = phoneArray[index].cr_is_driver;
            let vendorFlag = 0;
            let driverFlag = 0;
            let contactFlag = 0;
            let urlCttId = 0;
            if (window.location.href.indexOf("vendor") > -1)
            {
                vendorFlag = 1;
            } else if (window.location.href.indexOf("contact") > -1)
            {
                contactFlag = 1;
                let url = window.location.href;
                let splitUrl = url.split("?");
                let arrCttId = splitUrl[1].split("=");

                urlCttId = arrCttId[1];
            } else
            {
                driverFlag = 1;
            }

            if (phoneArray[index].phn_contact_id == cttId)
            {
                if (contactFlag && cttId != urlCttId)
                {
                    let alertString = "This number belongs to a different contact Id. To claim this, Click Add as new contact Button";
                    alert(alertString);
                    return false;
                }

                if (vendorFlag && (phoneArray[index].hasOwnProperty("cr_is_vendor")))
                {
                    let alertString = "Already registered as a vendor. Cannot register again. Please use existing vendor account " + phoneArray[index].drv_code + " for " + phoneArray[index].phn_phone_no;
                    alert(alertString);
                    return false;
                }
                if (driverFlag && (phoneArray[index].hasOwnProperty("cr_is_driver")))
                {
                    for (let i in phoneArray[index].mapVendors)
                    {
                        vndId = phoneArray[index].mapVendors[i].vnd_id;
                        // && phoneArray[index].hasOwnProperty("phn_is_verified")
                        if (($("#Drivers_drv_vendor_id1").val() === vndId))
                        {
                            let alertString = "Already registered as a driver in this vendor. Cannot register again. Please use existing driver account " + phoneArray[index].drv_code + " for " + phoneArray[index].phn_phone_no;
                            alert(alertString);
                            return false;
                        }
                    }
                }
            }
        }

        $("#contactDetails").html(name + ' | ' + phone + '  | License: ' + licence);
        $('#Drivers_drv_contact_id').val(cttId);
        $('#Vendors_vnd_contact_id').val(cttId);
        $('#Drivers_drv_contact_name').val(name);
        $('#Vendors_vnd_contact_name').val(name);
        $('#Drivers_drv_id').val(drvId);
        bootbox.hideAll();
        $(".contact_div_details").removeClass('hide');
        return false;
    }

</script>