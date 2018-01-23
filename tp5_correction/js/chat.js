var WS = "http://www.chabloz.eu/ws/chat/";

// Variables globales
var templateMsg;
var templateUser;
var timerGetMsg;
var timerGetOnline;

// setup Ajax pour indiquer à JQUERY de passer le cookie de session
$.ajaxSetup({
    data : {
        idTab: new Date().getTime() //génère un id unique par Tab du browser
    },
    xhrFields: {
       withCredentials: true
    }
});

$(function () {
    templateMsg = $(".templateMsg").clone();
    templateMsg.removeClass('template');
    templateUser = $(".templateUser").clone();
    templateUser.removeClass('template');
    $("#btnLogin").on("click", login);
    $("#btnLogout").on("click", logout);
    $("#nickname").on("keyup", loginOnEnter);
    $("#msg").on("keyup", sendOnEnter);
    $("#btnSend").on("click", sendMsg);
    $("#chat").on("login", function () {
        $("#login").hide();
        $("#chat").show();
        $("#messages").empty();
        $("#msg").val('');
        $("#msg").focus();
        timerGetMsg = setInterval(getMsg, 1000);
        getOnline();
        timerGetOnline = setInterval(getOnline, 5000);
    });
    $("#chat").on("logout", function () {
        clearInterval(timerGetMsg);
        clearInterval(timerGetOnline);
        $("#chat").hide();
        $("#login").show();
        $("#nickname").focus();
    })
})

function getMsg() {
    $.get(WS + 'msg/get', {}, function (messages) {
        $.each(messages, function (i, message) {
            var tmpl = templateMsg.clone();
            $(".username", tmpl).text(message.user.username);
            $(".message", tmpl).text(message.message);
            tmpl.appendTo("#messages");
        });
        // auto-scroll vers le bas du chat
        $("#messages").scrollTop($("#messages").prop('scrollHeight'));
    });
}

function getOnline() {
    $.get(WS + 'user/online', {}, function (users) {
        $("#usersList").empty(),
        $.each(users, function (i, user) {
            var tmpl = templateUser.clone();
            $(".usernameOnline", tmpl).text(user.username);
            tmpl.appendTo("#usersList");
        });
    });
}

function sendMsg() {
    var msg = $("#msg").val();
    $.get(WS + 'msg/add', {msg: msg});
    $("#msg").val('');
    $("#msg").focus();
}

function loginOnEnter(event) {
    var charCode = (event.which) ? event.which : event.keyCode;
    if (charCode == 13) {
       login();
    }
}

function sendOnEnter(event) {
    var charCode = (event.which) ? event.which : event.keyCode;
    if (charCode == 13) {
        sendMsg();
    }
}

function login() {
    var user = $("#nickname").val();
    $(".warning").hide();
    // Verification que des caractères
    if (!user.match(/^[a-z]+$/i)) {
        $("#warnFormat").show();
        return;
    }
    $.get(WS + 'user/login', {user: user}, function (data) {
        if (data.status != "success") {
            $("#warnInUse").show();
            return;
        }
        $("#chat").trigger('login');
    });
}

function logout() {
    $.get(WS + 'user/logout', {}, function (data) {
        //todo: gestion evt. des erreurs via data.status
        $("#chat").trigger('logout');
    });
}
