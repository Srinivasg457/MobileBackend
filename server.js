import fs from "fs";
import https from "https";
import { WebSocketServer, WebSocket } from "ws";

const serverOptions = {
  cert: fs.readFileSync("/etc/letsencrypt/live/work-room.io/fullchain.pem"),
  key: fs.readFileSync("/etc/letsencrypt/live/work-room.io/privkey.pem"),
};

const httpsServer = https.createServer(serverOptions);

const wss = new WebSocketServer({ server: httpsServer });

httpsServer.listen(8090, () => {
  console.log("Secure WebSocket server running on wss://localhost:8090");
});

wss.on("connection", (ws) => {
  console.log("New client connected.");

  ws.on("message", (data, isBinary) => {
    if (!isBinary) {
      const textData = data.toString("utf-8");
      wss.clients.forEach((client) => {
        if (client !== ws && client.readyState === WebSocket.OPEN) {
          client.send(textData);
          console.log("Sent to client:", textData);
        }
      });
    } else {
      console.log("Received binary data");
      wss.clients.forEach((client) => {
        if (client !== ws && client.readyState === WebSocket.OPEN) {
          client.send(data);
        }
      });
    }
  });

  ws.on("close", () => {
    console.log("Client disconnected.");
  });

  ws.on("error", (err) => {
    console.error("WebSocket error:", err.message);
  });
});
