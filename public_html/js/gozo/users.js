/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var users = function () {
    var model = {};
    
    this.forgotPassword = function() {
        var model = this.model;
        $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('Users/forgotpassword')) ?>",
                data: {"forgotemail": model.email},
                success: function (data)
                {
                    eval(callback+'(data)');
                }
            });
    };
    
    this.getCitiesFromState = function() {
        var model = this.model;
        $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('users/cityfromstate')) ?>",
                "data": {"id": model.stateId},
                success: function (data)
                {
                    eval(callback+'(data)');
                }
            });
    };
    
    this.getStateFromCountry = function() {
        var model = this.model;
        $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('users/countrytostate')) ?>",
                "data": {"countryid": model.countryId},
                success: function (data)
                {
                    eval(callback+'(data)');
                }
            });
    };
    
    this.reports = function() {
        var model = this.model;
        $.ajax({
                "type": "GET",
                "dataType": "json",
                "url": "<?= CHtml::normalizeUrl(Yii::app()->createUrl('admin/user/reports2')) ?>",
                data: {"id": model.cityId},
                success: function (data)
                {
                    eval(callback+'(data)');
                }
            });
    };
    
}

       