/* 
 * Home/index 
 */
var Home = function ()
{
    this.selectValueTypePhone = function ()
    {
        if ($('#Users_search_1').is(':checked'))
        {
            $('#ContactEmail_eml_email_address').addClass('hide');
            $('#fullContactNumber').removeClass('hide');
            $('.phn_phone').removeClass('hide');
        } 
        else
        {
            $('#fullContactNumber').addClass('hide');
            $('.phn_phone').addClass('hide');
            $('#ContactEmail_eml_email_address').removeClass('hide');
        }
    };
}