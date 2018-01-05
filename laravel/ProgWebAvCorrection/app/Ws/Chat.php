<?php namespace App\Ws;

use App\Ws\Controllers\ChatController;
use Ratchet\ConnectionInterface;

class Chat extends HandlerWithHttpSession {

     public function __construct()
     {
         parent::__construct();
         $this->controller = new ChatController();
     }

     public function onClose(ConnectionInterface $conn)
     {
         $this->controller->logout($conn, $this->clients);
         parent::onClose($conn);
     }
}

