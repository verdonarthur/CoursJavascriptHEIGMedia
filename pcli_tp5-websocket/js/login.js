var WEB_SERVICE = "wss://chabloz.eu";
var ws;

$(function () {
        var MESSAGE_TEMPLATE = $(".message").clone();
        $(".message").remove();

        var USER_TEMPLATE = $(".user").clone();
        $(".user").remove();

        $("#messages").scrollTop($("#messages").prop('scrollHeight'));
        var nickname;
        var users = {};

        ws = new WebSocket(WEB_SERVICE);

        $("#chat").toggle();
        //$("#login").toggle();

        // login
        $("#btnLogin").on("click", function () {
            // check username
            if ($("#nickname").val().match(/^[a-z]+$/i)) {
                ws.send(JSON.stringify({
                    action: "login",
                    "username": $("#nickname").val()
                }));
            }
        });

        ws.onmessage = function (response) {
            var result = JSON.parse(response.data);
            console.log(result);

            if (result.action === "login") {
                if (result.data === "login successfull") {
                    $("#login").toggle();
                    $("#chat").toggle();
                } else {
                    $("#nickname").css({"background-color": "red"});
                }
            }

            if (result.action === "send") {
                var msgLi = MESSAGE_TEMPLATE.clone();
                msgLi.append("<span>" + result.data.username + " :</span> " + result.data.msg);
                $("#messages").append(msgLi);
            }

            if (result.action === "connection") {
                var userLi = USER_TEMPLATE.clone();
                userLi.text(result.data);
                $("#users").append(userLi);
            }


            if (result.status === 'error') {
                return
            }
        }

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

        // send a message
        function sendMessage() {
            var msg = $("#m").val();
            $("#m").val("");
            ws.send(JSON.stringify({action: "send", msg: msg}));
        }

        // logout
        $("#logout").on("click", function () {
            ws.send(JSON.stringify({action: "logout"}));
            $("#login").toggle();
            $("#chat").toggle();
            $("#messages").empty();
            nickname = "";
        });
    }
);
