<?php
require_once 'connection.php';
$json = json_decode(file_get_contents('php://input'));
$category = "pohui";
$title = $json->title;
$article = "sad";
$published = date('Y/m/d');

$select_stmt=$db->prepare('INSERT INTO blog_items (category, title, article, published) VALUES (:category, :title, :article, :published)');
$select_stmt->execute(array(':category'=>$category,':title'=>$title,':article'=>$article,':published'=>$published));
$select_stmt=$db->prepare('SELECT * FROM blog_items ORDER BY id DESC LIMIT 3');
$select_stmt->execute(array(':pname'=>$pn));
$data=$select_stmt->fetch(PDO::FETCH_ASSOC);
$response = array("data"=>$data);
echo json_encode($response, JSON_UNESCAPED_UNICODE);
// $this->MyRealtimeDataHandler->pushNewData();
?>