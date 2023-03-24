const ws = new WebSocket('ws://localhost:8080');

ws.onopen = (event) => {
    console.log('Connected to server');
};

ws.onmessage = (event) => {
    const dataList = document.getElementById('data-list');
    dataList.innerHTML = '';
    const data = JSON.parse(event.data);
    for (let item of data) {
        const li = document.createElement('li');
        li.textContent = item.name + ': ' + item.value;
        dataList.appendChild(li);
    }
};

ws.onclose = (event) => {
    console.log('Disconnected from server');
};