 var WS = "ws://laravel.onivers.com:8080";

// Variables globales
var templateMsg;
var templateUser;
var webSocket;

$.holdReady(true);

function createWebSocket () {
    webSocket = new WebSocket(WS);
    webSocket.onopen = function(e) {
        console.log('connection successful');
        $.holdReady(false);
    };
    webSocket.onclose = function(e) {
        console.log('disconnection successful');
    };
    webSocket.onmessage = function(response) {
        console.log('recieved: ' + response.data);
        var result = JSON.parse(response.data);
        $('#chat').trigger(result.action, result.data);
    };
    webSocket.sendJson = function (data) {
        var sending = JSON.stringify(data);
        console.log('sending: ' + sending);
        this.send(sending);
    }
}

createWebSocket();

$(function () {
    templateMsg = $(".templateMsg").clone().removeClass("template");
    templateUser = $(".templateUser").clone().removeClass("template");
    $("#email").focus();
    $("#btnLogin").on("click", login);
    $("#btnLogout").on("click", logout);
    $("#password").on("keyup", function (evt) {
        doIfKeyIsEnter(evt, login);
    });
    $("#msg").on("keyup", function (evt) {
        doIfKeyIsEnter(evt, sendMsg);
    });
    $("#btnSend").on("click", sendMsg);
    $("#chat").on("loginHttpFailed", function () {
        $("#login").show();
    });
    $("#chat").on("loginFailed", function () {
        $("#warnFormat").show();
    });
    $("#chat").on("login", function () {
        $("#login").hide();
        $("#chat").show();
        $("#messages").empty();
        $("#usersList").empty();
        webSocket.sendJson({
            action: "usersList"
        });
        $("#msg").val('');
        $("#msg").focus();
    });
    $("#chat").on("logout", function () {
        createWebSocket();
        $("#chat").hide();
        $("#login").show();
        $("#email").focus();
    })
    $("#chat").on("send", function (evt, data) {
        addMsg(data);
    });
    $("#chat").on("connection", function (evt, username) {
        addUser(username);
    });
    $("#chat").on("disconnection", function (evt, username) {
        removeUser(username);
    });
    // Auto login si l'utlisateur s'est déjà auth en HTTP 
    autoLoginHttp();
})

function doIfKeyIsEnter(event, callback)  {
    var charCode = (event.which) ? event.which : event.keyCode;
    if (charCode == 13) {
        callback();
    }
}

function autoLoginHttp() {
    $("#login").hide();
    webSocket.sendJson({
        "action": "loginHttp"
    });
}

function sendMsg() {
    var msg = $("#msg").val();
    webSocket.sendJson({
        action: "send",
        msg: msg
    });
    $("#msg").val('');
    $("#msg").focus();
}

function addMsg(data) {
    var tmpl = templateMsg.clone();
    $(".username", tmpl).text(data.email);
    $(".message", tmpl).text(data.msg);
    tmpl.appendTo("#messages");
    // auto-scroll vers le bas du chat
    $("#messages").scrollTop($("#messages").prop('scrollHeight'));
}

function addUser(user) {
    console.log(user.email + ' connected');
    var tmpl = templateUser.clone();
    tmpl.attr('id', 'user_' + user.id);
    $(".usernameOnline", tmpl).text(user.email);
    $('#user_' + user.id).remove();
    tmpl.appendTo("#usersList");
}

function removeUser(user) {
    console.log(user.email + ' disconnected');
    $('#user_' + user.id).remove();
}

function login() {
    var email = $("#email").val();
    var password = $("#password").val();
    $(".warning").hide();
    webSocket.sendJson({
        "action": "login",
        "email": email,
        "password": password
    });
}

function logout() {
    webSocket.close();
    $("#chat").trigger("logout")
}
