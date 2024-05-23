//Document ready function
const __data = {
    chatRoomId: 0,
    lastMsgId: 0,
};

$(() => {
    let getParams = location.search.substr(1).split("?");
    __data.chatRoomId = atob(getParams[0]).split("=")[1];
    //fetchMessages();
    window.setInterval(function () {
        fetchMessages()
    }, 12000);
});

const checkNewMessages = async () => {
    //console.log("cll");
    let url = serverBaseUrl() + "/bot/fetch";
    let msgList = await promisingAjaxCall(url, "POST", JSON.stringify(__data), "application/json");
    let result = JSON.parse(msgList);
    if (result.success && !isEmpty(result.data))
    {
        renderUI(result.data);
    }
};

const fetchMessages = async () => {
    console.log("cll");
    let url = serverBaseUrl() + "/bot/fetch";
    let msgList = await promisingAjaxCall(url, "POST", JSON.stringify(__data), "application/json");
    let result = JSON.parse(msgList);
    if (result.success && !isEmpty(result.data))
    {
        renderUI(result.data);
    }
};

const sendMessage = async() => {
    let msg = $(".write_msg").val().trim();
    if (isEmpty(msg))
    {
        alert("Oops!!. You can't send a blank message");
        return false;
    }
    let url = serverBaseUrl() + "/bot/addMsg";
    let params = ["chatId", "msg"];
    let values = [__data.chatRoomId, msg];
    let dataString = createJSON(params, values);
    let addMsgSucess = await promisingAjaxCall(url, "POST", dataString, "application/json");
    let result = JSON.parse(addMsgSucess);
    let arrMsg = [];
    if (result.success && !isEmpty(result.data))
    {
        __data.lastMsgId = result.data.id;
        arrMsg.push(result.data);
        renderUI(arrMsg);
        $("html,body").animate({scrollTop: "10000"}, "slow");
    }
    $(".write_msg").val('');
};

const renderUI = (data) => {
    Object.entries(data).forEach(([key, value]) =>
    {
        let msg = `${value["msg"]}`;
        let id = `${value["id"]}`;
        let time = convertTimestamptoTime(`${value["time"]}`);
        let appendHtml = "";

        if (__data.lastMsgId === id)
        {
            return;
        }
        __data.lastMsgId = id;
        if (parseInt(`${value["user_id"]}`) === 0)
        {
            appendHtml +=
                    `<div class="outgoing_msg" id="out_${id}">
                <div class="sent_msg">
                    <p>${msg}</p> 
                    <span class="time_date"></span>
                </div>
            </div>`;

        } else
        {
            appendHtml +=
                    `<div class="incoming_msg" id="in_${id}">
                <div class="received_msg">
                    <div class="received_withd_msg">
                        <p>${msg}</p>
                        <span class="time_date"></span>
                    </div>
                </div>
            </div>`;
        }

        $(".msg_history").append(appendHtml);

    });
    $("html,body").animate({scrollTop: $(document).height()}, "fast");
};


    $(document).keypress(function (event) {
        var keycode = (event.keyCode ? event.keyCode : event.which);
        if (keycode === 13) {
            $('.msg_send_btn').click();
            $('.write_msg').val('');
        }
        event.stopPropagation();
    });


