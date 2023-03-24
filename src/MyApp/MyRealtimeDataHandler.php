<?php

namespace MyApp;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use PDO;

class MyRealtimeDataHandler implements MessageComponentInterface {
    private $connections = [];
    private $pdo;

    public function __construct() {
        $dsn = 'mysql:host=localhost;dbname=testratchet;charset=utf8mb4';
        $username = 'root';
        $password = '';
        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        $this->pdo = new PDO($dsn, $username, $password, $options);
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->connections[$conn->resourceId] = $conn;
    }

    public function onMessage(ConnectionInterface $from, $msg) {
       $this->pushNewData();
   }

   public function onClose(ConnectionInterface $conn) {
    unset($this->connections[$conn->resourceId]);
}

public function onError(ConnectionInterface $conn, \Exception $e) {
    echo "An error has occurred: {$e->getMessage()}\n";
    $conn->close();
}

public function pushNewData() {
    $statement = $this->pdo->query('SELECT * FROM blog_items ORDER BY id DESC LIMIT 3');
    $data = $statement->fetchAll(PDO::FETCH_ASSOC);

    foreach ($this->connections as $conn) {
        $conn->send(json_encode($data));
    }
}

}