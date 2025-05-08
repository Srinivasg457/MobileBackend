const fs = require("fs");
const https = require("https");
const WebSocket = require("ws");

const server = https.createServer({
  cert: fs.readFileSync("/etc/letsencrypt/live/work-room.io/fullchain.pem"),
  key: fs.readFileSync("/etc/letsencrypt/live/work-room.io/privkey.pem"),
});

const wss = new WebSocket.Server({ server });

const clients = [];
let electronClient = null;

wss.on("connection", (ws) => {
  console.log("New client connected");
  clients.push(ws);

  ws.on("message", (message) => {
    try {
      const msg = JSON.parse(message);
      console.log("Received message:", msg);

      if (msg.type === "client-type" && msg.data === "electron") {
        console.log("Electron client identified");
        electronClient = ws;
      } else if (msg.type === "request-share") {
        console.log("Received screen share request");
        if (electronClient && electronClient.readyState === WebSocket.OPEN) {
          console.log("Sending start-share to Electron client");
          electronClient.send(
            JSON.stringify({
              type: "start-share",
              employee_id: msg.employee_id,
            })
          );
        } else {
          console.error("No Electron client connected");
          ws.send(
            JSON.stringify({
              type: "error",
              message: "No Electron client available",
            })
          );
        }
      } else {
        clients.forEach((client) => {
          if (client !== ws && client.readyState === WebSocket.OPEN) {
            client.send(JSON.stringify(msg));
          }
        });
      }
    } catch (err) {
      console.error("Error processing message:", err);
    }
  });

  ws.on("close", () => {
    console.log("Client disconnected");
    if (ws === electronClient) {
      electronClient = null;
      console.log("Electron client disconnected");
    }
    const index = clients.indexOf(ws);
    if (index !== -1) {
      clients.splice(index, 1);
    }
  });

  ws.on("error", (err) => {
    console.error("WebSocket error:", err);
  });
});

server.listen(8090, () => {
  console.log("WebSocket server is running at wss://work-room.io:8090");
});
