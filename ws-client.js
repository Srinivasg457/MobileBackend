const ws = new WebSocket("wss://work-room.io:8090");

ws.onopen = () => console.log("[WS] connected");
ws.onerror = (e) => console.error("[WS] error:", e);
ws.onclose = () => console.warn("[WS] closed");

window.changeOrganizationSetting = (employeeId, userId, settings) => {
    const payload = {
        type: "organization-settings",
        employeeId: +employeeId,
        userId: +userId,
        settings
    };

    if (ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify(payload));
        console.log("[WS] sent organization-settings:", payload);
    } else {
        console.warn("[WS] not ready – packet skipped");
    }
};
