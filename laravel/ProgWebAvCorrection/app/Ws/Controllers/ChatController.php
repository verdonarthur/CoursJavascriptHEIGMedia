<?php namespace App\Ws\Controllers;

use Request;
use Auth;
use App\User;
use Ratchet\ConnectionInterface;
use App\Ws\Traits\JsonSender;

class ChatController  {
    // Trait pour les envoit en JSON via WebSocket
    use JsonSender;

    public function loginHttp(ConnectionInterface $from, $clients)
    {
        // Vérifie si l'utilisateur ne s'est pas déjà authentifié en HTTP
        $idUserInSession = $from->session->get(Auth::getName());
        if (!isset($idUserInSession)) {
            $this->jsonSend($from, 'loginHttpFailed', 'No http auth');
            return;
        }
        // Si un id existe, c'est que l'auth HTTP a été faites
        // Persistance de l'authentification pour WS
        $from->auth = true;
        // Sauvegarde de l'id et l'email
        $user = User::find($idUserInSession);
        $from->id = $user->id;
        $from->email = $user->email;
        // Envoit des donnnées au client
        $this->jsonSend($from, 'login', 'login successfull');
        // Broadcast du msg de connexion
        $this->broadcastToAuthJson($clients, 'connection', [
            'email' => $from->email,
            'id' => $from->id,
        ]);
    }

    public function login(ConnectionInterface $from, $clients)
    {
        $credentials = Request::only('email', 'password');
        if (!Auth::attempt($credentials)) {
            $this->jsonSend($from, 'loginFailed', 'wrong credentials');
            return;
        }
        // Persistance de l'authentification
        $from->auth = true;
        // Sauvegarde de l'id et de l'email
        $user = User::whereEmail($credentials['email'])->first();

        $from->id = $user->id;
        $from->email = $user->email;
        // Envoit des donnnées au client
        $this->jsonSend($from, 'login', 'login successfull');
        // Broadcast du msg de connexion
        $this->broadcastToAuthJson($clients, 'connection', [
            'email' => $from->email,
            'id' => $from->id,
        ]);
    }

    public function usersList(ConnectionInterface $from, $clients)
    {
        // Test de l'authentification
        if (!isset($from->auth)) {
            $this->jsonSend($from, 'error', 'forbidden');
            return;
        }
        // Sinon, pour chaque client connecté (et authentifié), on envoit les
        // informations (email et id) au client qui a demandé la liste
        foreach ($clients as $client) {
            if (isset($client->auth)) {
                $this->jsonSend($from, 'connection', [
                    'email' => $client->email,
                    'id' => $client->id,
                ]);
            }
        }
    }

    public function send(ConnectionInterface $from, $clients)
    {
        // Test de l'authentification
        if (!isset($from->auth)) {
            $this->jsonSend($from, 'error', 'forbidden');
            return;
        }
        // Validation du champ msg
        $msg = Request::input('msg');
        if (!is_string($msg) || mb_strlen($msg)>200 || mb_strlen($msg)<1) {
            $this->jsonSend($from, 'error', 'wrong msg format');
            return;
        }
        // Autre validation ?

        // Tout est OK, on envoit le message à tous ceux connectés au Chat
        $this->broadcastToAuthJson($clients, 'send', [
            'email' => $from->email,
            'msg' => $msg,
        ]);
    }

    public function logout(ConnectionInterface $from, $clients)
    {
        // Test de l'authentification
        if (!isset($from->auth)) {
            $this->jsonSend($from, 'error', 'forbidden');
            return;
        }
        $this->jsonSend($from, 'logout', 'logout successfull');
        // Si ce client n'a pas d'autre connexion active, on informe tous les
        // autres clients connectés de sa déconnexion
        $nbConnection = 0;
        foreach ($clients as $client) {
            if (isset($client->auth) && $client->id == $from->id) {
                $nbConnection++;
            }
        }
        if ($nbConnection == 1) {
            $this->broadcastToAuthJson($clients, 'disconnection', [
                'email' => $from->email,
                'id' => $from->id,
            ]);
        }
    }

}
