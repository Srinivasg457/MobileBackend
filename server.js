import fs from 'fs';
import https from 'https';
// import { WebSocketServer } from 'ws';
import WebSocket, { WebSocketServer } from "ws";

//For production with SSL:
const serverOptions = {
  cert: fs.readFileSync('/etc/letsencrypt/live/work-room.io/fullchain.pem'),
  key: fs.readFileSync('/etc/letsencrypt/live/work-room.io/privkey.pem')
};
const httpsServer = https.createServer(serverOptions);
const wss = new WebSocketServer({ server: httpsServer });
httpsServer.listen(8090, () => {
  console.log('Secure WebSocket server running on wss://localhost:8090');
});

// For local dev without SSL:
// const wss = new WebSocketServer({ port: 8090 });

const streamers = new Map();
const viewers = new Map();

wss.on('connection', (ws) => {
  console.log('New client connected.');

  ws.employeeId = null;

  ws.on('message', (data, isBinary) => {
    if (!isBinary) {
      try {
        const msg = JSON.parse(data.toString());

        switch (msg.type) {
          case "connect-streamer":
            ws.employeeId = msg.employee_id;

            if (streamers.has(ws.employeeId)) {
              const existingWs = streamers.get(ws.employeeId);

              if (existingWs && existingWs.readyState === WebSocket.OPEN) {
                ws.send(
                  JSON.stringify({
                    action: "logout",
                    reason: "Duplicate connection",
                  })
                );
              }
            } else {
              streamers.set(ws.employeeId, ws);
              console.log(`Streamer connected: ${ws.employeeId}`);
            }
            break;
          case "organization-settings":
            if (!msg.employeeId) {
              for (const [id, streamerSocket] of streamers.entries()) {
                if (streamerSocket.readyState === ws.OPEN) {
                  streamerSocket.send(
                    JSON.stringify({
                      action: "settings",
                      settings: msg.settings,
                    })
                  );
                  console.log(`Sent settings to department`);
                }
              }
            } else {
              const streamerSocket = streamers.get(msg.employeeId);
              if (streamerSocket && streamerSocket.readyState === ws.OPEN) {
                streamerSocket.send(
                  JSON.stringify({
                    action: "settings",
                    settings: msg.settings,
                  })
                );
                console.log(`Sent settings to streamer: user ${ws.employeeId}`);
              } else {
                console.warn(
                  `Streamer not available for user ${ws.employeeId}`
                );
              }
            }
            break;
          case "approval-time":
            if (!msg.approveTime) {
              console.warn("No approveTime provided");
              break;
            }

            // Optionally, broadcast approval time to the specific streamer or all viewers
            if (msg.employeeId) {
              // Send to a specific streamer
              const streamerSocket = streamers.get(msg.employeeId);
              if (streamerSocket && streamerSocket.readyState === ws.OPEN) {
                streamerSocket.send(
                  JSON.stringify({
                    action: "approval-time",
                    approveTime: msg.approveTime
                  })
                );
                console.log(`Sent approval-time to streamer ${msg.employeeId}:`, msg.approveTime);
              }
            } else {
              // Broadcast to all streamers (or viewers if you want)
              for (const [id, streamerSocket] of streamers.entries()) {
                if (streamerSocket.readyState === ws.OPEN) {
                  streamerSocket.send(
                    JSON.stringify({
                      action: "approval-time",
                      approveTime: msg.approveTime
                    })
                  );
                }
              }
              console.log("Broadcasted approval-time to all streamers:", msg.approveTime);
            }
            break;
          case "viewer-join":
            viewers.set(ws, msg.employee_id);
            console.log(`Viewer joined for: ${msg.employee_id}`);
            const streamerSocket = streamers.get(msg.employee_id);
            if (streamerSocket && streamerSocket.readyState === ws.OPEN) {
              streamerSocket.send(
                JSON.stringify({
                  action: "start",
                  employee_id: msg.employee_id,
                })
              );
              console.log(`Sent start command to streamer: ${msg.employee_id}`);
            } else {
              console.warn(
                `Streamer not available for employee_id: ${msg.employee_id}`
              );
            }
            break;

          case "screen-frame":
            break;

          default:
            console.warn(`Unknown message type: ${msg.type}`);
        }
      } catch (err) {
        console.error('Failed to parse message:', err.message);
      }
    } else {
      if (!ws.employeeId) return;
      const senderId = ws.employeeId;

      for (const [viewerWs, targetEmployeeId] of viewers.entries()) {
        if (targetEmployeeId === senderId && viewerWs.readyState === ws.OPEN) {
          viewerWs.send(data);
        }
      }
    }
  });

  ws.on('close', () => {
    console.log('Client disconnected.');

    if (ws.employeeId && streamers.get(ws.employeeId) === ws) {
      streamers.delete(ws.employeeId);
    }

    if (viewers.has(ws)) {
      viewers.delete(ws);
    }
  });

  ws.on('error', (err) => {
    console.error('WebSocket error:', err.message);
  });
});

console.log('WebSocket server running on ws://localhost:8090');
