<?php
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

class RealtimeDataHandler implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Добавляем новое соединение в список клиентов
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $conn, $msg)
    {
        // Этот метод не используется, так как мы не получаем сообщения от клиентов
    }

    public function onClose(ConnectionInterface $conn)
    {
        // Удаляем соединение из списка клиентов
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function onNewData($data)
    {
        // Проходимся по всем клиентам и отправляем новые данные
        foreach ($this->clients as $client) {
            $client->send(json_encode($data));
        }
    }
}