<?php namespace App\Ws;

use Request;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use App\Ws\Traits\JsonSender;
use SplObjectStorage;

class Handler implements MessageComponentInterface {
    // Trait pour les méthode d'envoit de données JSON au(x) client(s)
    use JsonSender;

    protected $clients;
    protected $controller;

    public function __construct() {
        $this->clients = new SplObjectStorage();
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        echo sprintf('Connection %d sending message "%s"' . "\n", $from->resourceId, $msg);
        // Le message devrait être au format JSON
        $data = json_decode($msg, true);
        if (json_last_error() != JSON_ERROR_NONE) {
            $this->jsonSend($from, 'error', 'JSON invalid');
            return;
        }
        // Le message doit contenir une action
        if (!isset($data['action'])) {
            $this->jsonSend($from, 'error', 'action missing');
            return;
        }
        // L'action doit être existante sur le controller
        if (!method_exists($this->controller, $data['action'])) {
            $this->jsonSend($from, 'error', 'action invalid');
            return;
        }
        // Utilise Request pour le passage des paramètres au controller
        Request::replace($data);
        // Execute l'action en fournissant le client actuel et tous les clients
        call_user_func_array(
            [$this->controller, $data['action']],
            [$from, $this->clients]
        );
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

