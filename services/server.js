const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 8080 });

let clients = [];

wss.on('connection', (ws) => {
    clients.push(ws);
    console.log('Nuevo cliente conectado.');

    ws.on('close', () => {
        clients = clients.filter(client => client !== ws);
        console.log('Cliente desconectado.');
    });
});

function broadcast(data) {
    clients.forEach(client => {
        if (client.readyState === WebSocket.OPEN) {
            client.send(JSON.stringify(data));
        }
    });
}

setInterval(() => {
    broadcast({ event: 'activity_registered' });
}, 5000); // Actualiza cada 5 segundos
