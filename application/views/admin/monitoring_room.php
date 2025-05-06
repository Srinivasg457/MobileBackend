<!-- share_view.php -->
<div class="content-wrapper">



  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 2rem;
    }
    #status {
      margin-top: 1rem;
      color: green;
    }
    #localVideo {
      width: 320px;
      height: 240px;
      border: 1px solid black;
      margin-top: 1rem;
    }
  </style>


  <h1>Screen Sharing</h1>
  <button onclick="startSharing()">Start Share</button>
  <p id="status">Connecting to signaling server...</p>
  <video id="localVideo" autoplay muted></video>

  <script>
    let socket;
    let localStream;
    let peerConnection;
    const statusEl = document.getElementById("status");
    const localVideo = document.getElementById("localVideo");
    const websocketUrl = "ws://127.0.0.1:3000"; // Ensure your server is running here

    async function connectWebSocket() {
      socket = new WebSocket(websocketUrl);

      socket.onopen = () => {
        statusEl.textContent = "Connected to signaling server.";
      };

      socket.onmessage = async (event) => {
        try {
          const message = JSON.parse(event.data);

          if (message.type === 'answer') {
            console.log('Received answer:', message);
            await peerConnection.setRemoteDescription(new RTCSessionDescription(message.answer));
            statusEl.textContent = "Peer connection established.";
          } else if (message.type === 'candidate') {
            console.log('Received ICE candidate:', message);
            if (peerConnection) {
              await peerConnection.addIceCandidate(message.candidate);
            }
          } else if (message.type === 'viewer_connected') {
            statusEl.textContent = "Viewer connected. Initiating media sharing...";
            await startMedia();
            await createOffer();
          }
        } catch (error) {
          console.error("Error processing message from server:", error);
        }
      };

      socket.onerror = (error) => {
        statusEl.textContent = "WebSocket error occurred.";
        console.error("WebSocket error:", error);
      };

      socket.onclose = () => {
        statusEl.textContent = "Disconnected from signaling server.";
      };
    }

    async function startMedia() {
      try {
        localStream = await navigator.mediaDevices.getDisplayMedia({ video: true, audio: false });
        localVideo.srcObject = localStream;
        localStream.getTracks().forEach(track => peerConnection.addTrack(track, localStream));
      } catch (error) {
        console.error("Error accessing screen media:", error);
        statusEl.textContent = "Failed to access screen.";
      }
    }

    async function createPeerConnection() {
      peerConnection = new RTCPeerConnection({
        iceServers: [
          { urls: 'stun:stun.l.google.com:19302' },
          // Add more STUN/TURN servers as needed for better connectivity
        ],
      });

      peerConnection.onicecandidate = (event) => {
        if (event.candidate) {
          socket.send(JSON.stringify({ type: 'candidate', candidate: event.candidate }));
        }
      };

      peerConnection.oniceconnectionstatechange = () => {
        if (peerConnection.iceConnectionState === 'failed' ||
            peerConnection.iceConnectionState === 'disconnected' ||
            peerConnection.iceConnectionState === 'closed') {
          statusEl.textContent = "Peer connection failed.";
          // Potentially attempt to reconnect or notify the user
        }
      };
    }

    async function createOffer() {
      try {
        const offer = await peerConnection.createOffer();
        await peerConnection.setLocalDescription(offer);
        socket.send(JSON.stringify({ type: 'offer', offer: offer }));
        statusEl.textContent = "Offer sent to viewer.";
      } catch (error) {
        console.error("Error creating offer:", error);
        statusEl.textContent = "Failed to create offer.";
      }
    }

    async function startSharing() {
      statusEl.textContent = "Requesting screen share...";
      await createPeerConnection();
      // Inform the server that this user wants to start sharing
      socket.send(JSON.stringify({ type: "start_share_request", userId: 123 })); // Server needs to handle this
      // The server should then notify a viewer (potentially based on userId or a room ID)
      // and the viewer will initiate the connection, leading to the 'viewer_connected' message here.
    }

    window.onload = connectWebSocket;
  </script>



</div>