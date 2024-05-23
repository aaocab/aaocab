

<div class="container">
    <?php
    $form = $this->beginWidget('booster.widgets.TbActiveForm', array(
        'id' => 'create-trip', 'enableClientValidation' => FALSE,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'errorCssClass' => 'has-error'
        ),
        'enableAjaxValidation' => false,
        'errorMessageCssClass' => 'help-block',
        'action' => Yii::app()->createUrl('rcsr/booking/ownmatchtrip'),
        'htmlOptions' => array(
            'class' => 'form-horizontal', 'enctype' => 'multipart/form-data'
        ),
    ));
    /* @var $form TbActiveForm */
    ?>
    <div class="row">
        <?php
        //echo CHtml::errorSummary($model);
        ?>
        <div class="col-md-7">
            <div class="row">
                <div class="col-xs-12">
                    <div class="panel panel-default panel-border">
                        <div class="panel-body pt0">
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="exampleInputName6">Booking *</label>  
                                        <div class="field_wrapper">
                                            <div>
                                                <input type="text" name="bkg_booking_id[]" value=""/><a href="javascript:void(0);" class="remove_button" title="Remove field"><i class="fa fa-times"></i></a>

                                                </br>
                                            </div>
                                        </div>  
                                        <a href="javascript:void(0);" class="add_button" title="Add field"><i class="fa fa-plus"></i></a>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <div class="row">
        <div class="col-xs-12 text-center pb10">
            <?= CHtml::submitButton('Submit', array('class' => 'btn btn-primary pl30 pr30', 'onclick' => "return getBkgIds()")); ?>
        </div>
    </div>
    <div id="driver1"></div>
    <?php $this->endWidget(); ?>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
    var x = 1;
    $(document).ready(function () {
        var maxField = 10; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector     
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var fieldHTML = '<div><input type="text" name="bkg_booking_id[]"  value=""/><a href="javascript:void(0);" class="remove_button" title="Remove field"><i class="fa fa-times"></i></a></br></div>'; //New input field html 
        //Initial field counter is 1


        if (x == 1) {
            $(".remove_button").hide();
        } else {
            $(".remove_button").show();
        }
        $(addButton).click(function () { //Once add button is clicked
            if (x < maxField) { //Check maximum number of input fields
                x++; //Increment field counter
                $(wrapper).append(fieldHTML); // Add field html
            }
            if (x > 1) {
                $(".remove_button").show();
            }
        });
        $(wrapper).on('click', '.remove_button', function (e) { //Once remove button is clicked

            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--;
            if (x == 1) {
                $(".remove_button").hide();
            }

            //Decrement field counter
        });
    });
    function getBkgIds()
    {
        $href = "<?= Yii::app()->createUrl('rcsr/booking/unmatchedBkgId') ?>";
        jQuery.ajax({type: 'POST', url: $href, data: $("form").serialize(), dataType: 'json',
            success: function (data) {
                //alert(data.success);
                if (data.success)
                {

                    $("#create-trip").submit();
                } else
                {
                    alert('These Match Can Not Be Possible');
                }
            }
        });

        return false;
    }
</script>