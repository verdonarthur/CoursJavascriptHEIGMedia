<!DOCTYPE html>
<html lang="fr">
    <head>
	<title>Chat</title>
	<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script>
            var LOGIN_URL = "{{ action('AuthController@login') }}";
        </script>
        <script src="{{ asset('js/jquery-1.11.1.min.js') }}"></script>
	<script src="{{ asset('js/chat2.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
    </head>
    <body>
        <div id="container">
            <h1>Chat COMEM+</h1>
            <div id="chat">
                <div id="messages">
                    <div class="template templateMsg">
                        <span class="username"></span>
                        <span class="message"></span>
                    </div>
                </div>
                <div id="online">
                    <h4>Online users</h4>
                    <div id="usersList">
                        <div class="template templateUser">
                            <span class="usernameOnline"></span>
                        </div>
                    </div>
                </div>
                <input type="text" id="msg" maxlength="200">
                <button id="btnSend">Send</button>
            </div>
        </div>
    </body>
</html>

