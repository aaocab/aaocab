/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function createRequestObject() {
    var obj;
    var browser = navigator.appName;
    if (browser == "Microsoft Internet Explorer") {
        obj = new ActiveXObject("Microsoft.XMLHTTP");
    } else {
        obj = new XMLHttpRequest();
    }
    return obj;
}
var httpGozo;
function sendReq(req) {   
    httpGozo = createRequestObject();
    httpGozo.open('get', req);
    httpGozo.onreadystatechange = handleResponse;
    httpGozo.send(null);
}

function handleResponse() {    
    if (httpGozo.readyState == 4) {
        var response = httpGozo.responseText;
        document.getElementById('mffGozoRates').innerHTML=response;
    }
}
sendReq('http://www.aaocab.com/index/mffRates');
