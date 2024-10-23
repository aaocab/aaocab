function serverBaseUrl()
{
    var getUrl = window.location;
    return getUrl.protocol + "//" + getUrl.host;
}

function convertTimestamptoTime(unixTimestamp)
{
    dateObj = new Date(unixTimestamp * 1000);
    utcString = dateObj.toUTCString();
    return utcString.slice(-11, -4);
}


function isEmpty(val)
{
    return (val === undefined || val == null || val.length <= 0) ? true : false;
}

function createJSON(params, values)
{
    if (params.length !== values.length)
    {
        return "";
    }

    var json = {};

    for (var i = 0; i < params.length; ++i)
    {
        json[params[i]] = values[i];
    }
    
    return JSON.stringify(json);
}

async function promisingAjaxCall(url, type, dataString, contentType)
{
    var promise = new Promise((resolve, reject) =>
    {
        $.ajax({
            url: url,
            type: type,
            data: dataString,
            contentType: contentType,
            success: function (data)
            {
                resolve(data);
            },
            error: function (jqXHR, textStatus, ex)
            {
                reject(jqXHR, textStatus, ex);
            }
        });
    });

    return promise;
}