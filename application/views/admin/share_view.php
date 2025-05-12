<!-- share_view.php -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Start Share</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 2rem;
    }
    #status {
      margin-top: 1rem;
      color: green;
    }
  </style>
</head>
<body>

  <h1>Screen Sharing</h1>
  <button onclick="startSharing()">Start Share</button>
  <p id="status">Connecting to server...</p>

  <script>
    let socket;
    const statusEl = document.getElementById("status");

    function connectWebSocket() {
      socket = new WebSocket("ws://your-server-ip:3000"); // Replace with your actual WebSocket server address

      socket.onopen = () => {
        statusEl.textContent = "Connected to server.";
      };

      socket.onmessage = (event) => {
        console.log("Message from server:", event.data);
      };

      socket.onerror = (error) => {
        statusEl.textContent = "WebSocket error occurred.";
        console.error("WebSocket error:", error);
      };

      socket.onclose = () => {
        statusEl.textContent = "Disconnected from server.";
      };
    }

    function startSharing() {
      if (socket && socket.readyState === WebSocket.OPEN) {
        socket.send(JSON.stringify({ type: "start_share", userId: 123 })); // Replace userId dynamically if needed
        statusEl.textContent = "Share request sent.";
      } else {
        statusEl.textContent = "WebSocket not connected.";
      }
    }

    window.onload = connectWebSocket;
  </script>

</body>
</html>
