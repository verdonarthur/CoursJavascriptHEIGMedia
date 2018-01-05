<?php namespace App\Ws\Traits;

use Ratchet\ConnectionInterface;

trait JsonSender {

    public function jsonSend(ConnectionInterface $client, $action, $data) {
        $client->send(json_encode([
            'action' => $action,
            'data' => $data,
        ]));
    }

    public function jsonBroadcast($clients, $action, $data) {
        foreach ($clients as $client) {
            $this->jsonSend($client, $action, $data);
        }
    }

    public function jsonBroadcastToOther($from, $clients, $action, $data) {
        foreach ($clients as $client) {
            if ($client !== $from) {
                $this->jsonSend($client, $action, $data);
            }
        }
    }

    public function broadcastToAuthJson($clients, $action, $data) {
        foreach ($clients as $client) {
            if (isset($client->auth)) {
                $this->jsonSend($client, $action, $data);
            }
        }
    }


}
