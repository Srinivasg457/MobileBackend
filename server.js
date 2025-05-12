import { WebSocketServer, WebSocket } from 'ws';

const wss = new WebSocketServer({ port: 8090 });

console.log('WebSocket server running on ws://localhost:8090');

wss.on('connection', (ws) => {
  console.log('New client connected.');

  ws.on('message', (data, isBinary) => {
    if (!isBinary) {
      const textData = data.toString('utf-8');
      wss.clients.forEach((client) => {
        if (client !== ws && client.readyState === WebSocket.OPEN) {
          client.send(textData);
          console.log('Sent to client:', textData);
        }
      });
    } else {
      console.log('Received non-buffer data:', data);
      wss.clients.forEach((client) => {
        if (client !== ws && client.readyState === WebSocket.OPEN) {
          client.send(data);
        }
      });
    }
  });

  ws.on('close', () => {
    console.log('Client disconnected.');
  });

  ws.on('error', (err) => {
    console.error('WebSocket error:', err.message);
  });
});
