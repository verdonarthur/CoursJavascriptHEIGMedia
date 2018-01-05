var WEB_SERVICE = "https://chabloz.eu/ws/chat/";
var WEB_SERVICE_USER = WEB_SERVICE + "user/";
var WEB_SERVICE_MSG = WEB_SERVICE + "msg/";

$.ajaxSetup({
    xhrFields: {
        withCredentials: true
    }
});

$(function () {
    var MESSAGE_TEMPLATE = $(".message").clone();
    $(".message").remove();

    var USER_TEMPLATE = $(".user").clone().remove();

    $("#messages").scrollTop($("#messages").prop('scrollHeight'));
    var nickname;
    var users = {};

    var timerGetMsg;
    var timerGetUsr;

    $("#chat").toggle();

    // login
    $("#btnLogin").on("click", function () {

        // check username
        if ($("#nickname").val().match(/^[a-z]+$/i)) {

            // get webservice
            $.getJSON(WEB_SERVICE_USER + "login", {user: $("#nickname").val()}, function (data) {
                if (data.status === "success") {
                    $("#login").toggle();
                    $("#chat").toggle();
                    nickname = $("#nickname").val();


                    timerGetMsg = setInterval(getMsg, 2500);
                    timerGetUsr = setInterval(getUsers, 5000);
                } else {
                    $("#msg").empty().text(data.msg);
                }
            });
        } else {
            $("#nickname").css({"background-color": "red"});
        }
    });

    // send message
    $("#send").on("click keyup", function (event) {
        if (event.type != "click") {
            var charCode = (event.which) ? event.which : event.keyCode;
            if (charCode == 13)
                sendMessage();
        } else {
            sendMessage();
        }
    });

    // logout
    $("#logout").on("click", function () {
        $.getJSON(WEB_SERVICE_USER + "logout", {}, function (data) {
            $("#login").toggle();
            $("#chat").toggle();
            $("#messages").empty();
            clearInterval(timerGetMsg);
            clearInterval(timerGetUsr);
            nickname = "";
        });
    });


    // utils function //

    // send a message
    function sendMessage() {
        var msg = $("#m").val();
        $("#m").val("");
        $.getJSON(WEB_SERVICE_MSG + "add", {"msg": msg}, function (data) {
        });
    }

    // get a message
    function getMsg() {
        if (nickname !== "")
            $.getJSON(WEB_SERVICE_MSG + "get", {}, function (data) {
                $(data).each(function (i, msg) {
                    var msgLi = MESSAGE_TEMPLATE.clone();
                    console.log(msg);
                    msgLi.append("<span>" + msg.created_at + " - " + msg.user.username + " :</span> " + msg.message);
                    $("#messages").append(msgLi);
                });
            });
    }

    function getUsers() {

        $.getJSON(WEB_SERVICE_USER + "online", {}, function (data) {
            if (users != data) {
                $("#users").empty();
                $(data).each(function (i, user) {
                    var userLi = USER_TEMPLATE.clone();
                    userLi.text(user.username);
                    $("#users").append(userLi);
                });
                users = data;
            }
        });
    }
});
