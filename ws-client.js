if (!window._wsClientLoaded) {
    window._wsClientLoaded = true;

    //const ws = new WebSocket('wss://work-room.io:8090');
    const ws = new WebSocket('ws://localhost:8090');

    ws.binaryType = 'arraybuffer';

    const video = document.getElementById('modalScreen');

    function playVideo(employeeId) {
        try {
            ws.send(JSON.stringify({
                type: 'viewer-join',
                employee_id: parseInt(employeeId)
            }));
        } catch (error) {
            alert("Unable to connect. Please start the server.");
            console.error("WebSocket Error:", error);
        }
    }

    function changeOrganizationSetting(employeeId, settings) {
        const payload = {
            type: "organization-settings",
            employeeId: parseInt(employeeId),
            settings
        };

        if (ws.readyState === WebSocket.OPEN) {
            ws.send(JSON.stringify(payload));
            console.log("[WS] sent organization-settings:", payload);
        } else if (ws.readyState === WebSocket.CONNECTING) {
            ws.addEventListener("open", () => {
                ws.send(JSON.stringify(payload));
                console.log("[WS] sent organization-settings after connect:", payload);
            });
        } else {
            console.warn("[WS] not ready – packet skipped");
        }
    }

    ws.addEventListener('message', (event) => {
        if (typeof event.data !== 'string') {
            const blob = new Blob([event.data], { type: 'image/jpeg' });
            const url = URL.createObjectURL(blob);

            const img = document.getElementById('modalScreen');
            if (img) {
                img.src = url;
                img.onload = () => URL.revokeObjectURL(url);
            }

            const now = new Date();
            const timestampEl = document.getElementById("modalTimestamp");
            if (timestampEl) {
                timestampEl.innerText = `Last updated: ${now.toLocaleTimeString()}`;
            }
        }
    });

    // Optional: expose functions to global scope if needed from HTML
    window.playVideo = playVideo;
    window.changeOrganizationSetting = changeOrganizationSetting;
}