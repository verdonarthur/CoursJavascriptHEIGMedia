<?php namespace App\Ws;

use App;
use Config;
use Crypt;
use Illuminate\Session\SessionManager;
use Ratchet\ConnectionInterface;

class HandlerWithHttpSession extends Handler {

    public function onOpen(ConnectionInterface $conn) {
        parent::onOpen($conn);
        // Crée une instance pour la gestion de la session de cet utilisateur
        $session = (new SessionManager(App::getInstance()))->driver();
        // Si une session Laravel HTTP existait déjà, on la reprend
        $cookies = $conn->WebSocket->request->getCookies();
        if (isset($cookies[Config::get('session.cookie')])) {
            $laravelCookie = urldecode($cookies[Config::get('session.cookie')]);
            $idSession = Crypt::decrypt($laravelCookie);
            $session->setId($idSession);
        }
        $conn->session = $session;
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $from->session->start();
        parent::onMessage($from, $msg);
        $from->session->save();
    }

}