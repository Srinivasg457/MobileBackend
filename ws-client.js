function changeOrganizationSetting(employeeId, settings) {
    //const ws = new WebSocket('ws://localhost:8090');
    const ws = new WebSocket("wss://work-room.io:8090");

    const payload = {
        type: "organization-settings",
        employeeId: +employeeId,
        settings
    };

    if (ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify(payload));
        console.log("[WS] sent organization-settings:", payload);

        // Close after sending
        ws.close();
    } else if (ws.readyState === WebSocket.CONNECTING) {
        // Wait until it opens, then send and close
        ws.addEventListener("open", () => {
            ws.send(JSON.stringify(payload));
            console.log("[WS] sent organization-settings:", payload);
            ws.close();
        });
    } else {
        console.warn("[WS] not ready – packet skipped");
    }
}