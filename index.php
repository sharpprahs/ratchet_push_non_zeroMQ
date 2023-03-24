<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Realtime Data</title>
</head>
<body>
    <ul id="data-list">
        <?php
        require_once 'connection.php';
        $select_stmt=$db->prepare('SELECT * FROM blog_items ORDER BY id DESC LIMIT 3');
        $select_stmt->setFetchMode(PDO::FETCH_ASSOC);
        $select_stmt->execute();
        while($record = $select_stmt->fetch()) {
            echo '
            <li>'.$record["title"].'</li>';
        }
        ?>
    </ul>
    <!-- <script src="aps.js"></script> -->
    <input type="text" class="inp_data">
    <button class="lols inp">lool</button>
    <script>
        var conn = new WebSocket('ws://localhost:8080');
        conn.onopen = function(e) {
            console.log("Connection established!");

            console.log(e.data)
        };

        conn.onmessage = function(e) {
    // Получаем данные из сервера
    console.log(e.data)
    var data = JSON.parse(e.data);
    var dataList = document.getElementById("data-list");
    dataList.innerHTML = "";
    data.forEach(function(item) {
        var element = document.createElement('li');
        element.textContent = item.title;
        dataList.appendChild(element);
    });
};

</script>
<script>
    function web(){
        let input_data = document.querySelector('.inp_data');
        const params = {
            title: input_data.value,
        };
        const options = {
            method: 'POST',
            body: JSON.stringify( params )  
        };
        fetch( 'http://push/push_data.php', options )
        .then( response => response.json() )
        .then( response => {
           conn.send(response.data);
       });
    }
    let input = document.querySelector('.inp');
    input.addEventListener("click", web);
</script>
</body>
</html>