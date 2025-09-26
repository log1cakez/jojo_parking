<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JoJo Smart Parking Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .dashboard {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .header {
            background: rgba(255, 255, 255, 0.95);
            color: #2c3e50;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .header h1 {
            font-size: 2.5em;
            margin-bottom: 10px;
            color: #3498db;
        }
        
        .header p {
            font-size: 1.2em;
            color: #7f8c8d;
        }
        
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
            transition: transform 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 3em;
            font-weight: bold;
            margin: 15px 0;
        }
        
        .available { color: #27ae60; }
        .occupied { color: #e74c3c; }
        .total { color: #3498db; }
        
        .parking-layout {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            margin-bottom: 20px;
            position: relative;
            text-align: center;
        }
        
        .layout-title {
            font-size: 1.5em;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        
        .slots-container {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }
        
        .slot {
            width: 120px;
            height: 180px;
            border: 4px solid #34495e;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            border-radius: 10px;
            transition: all 0.3s ease;
            font-size: 1.1em;
        }
        
        .slot-number {
            font-size: 1.3em;
            margin-bottom: 10px;
        }
        
        .slot-status {
            font-size: 0.9em;
            padding: 5px 10px;
            border-radius: 20px;
            color: white;
        }
        
        .slot.empty { background: #27ae60; border-color: #219653; }
        .slot.occupied { background: #e74c3c; border-color: #c0392b; }
        
        .gates-container {
            display: flex;
            justify-content: space-between;
            margin: 30px 0;
        }
        
        .gate {
            width: 100px;
            height: 40px;
            background: #f39c12;
            text-align: center;
            line-height: 40px;
            font-weight: bold;
            border-radius: 5px;
            color: white;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        
        .gate.open { background: #27ae60; }
        .gate.closed { background: #e74c3c; }
        
        .logs-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .logs-title {
            font-size: 1.5em;
            margin-bottom: 20px;
            color: #2c3e50;
            text-align: center;
        }
        
        .log-entry {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .log-entry:last-child {
            border-bottom: none;
        }
        
        .log-time {
            color: #7f8c8d;
            font-size: 0.9em;
        }
        
        .log-action.granted { color: #27ae60; }
        .log-action.denied { color: #e74c3c; }
        
        .refresh-btn {
            background: #3498db;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background 0.3s ease;
            display: block;
            margin: 20px auto;
        }
        
        .refresh-btn:hover {
            background: #2980b9;
        }
        
        .last-update {
            text-align: center;
            color: #7f8c8d;
            margin-top: 20px;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <div class="header">
            <h1>🚗 JoJo Smart Parking System</h1>
            <p>Real-time Parking Management Dashboard</p>
        </div>
        
        <div class="stats-container">
            <div class="stat-card">
                <h3>Total Slots</h3>
                <div class="stat-number total" id="total-slots">3</div>
                <p>Parking Capacity</p>
            </div>
            <div class="stat-card">
                <h3>Available Slots</h3>
                <div class="stat-number available" id="available-slots">3</div>
                <p>Ready for Parking</p>
            </div>
            <div class="stat-card">
                <h3>Occupied Slots</h3>
                <div class="stat-number occupied" id="occupied-slots">0</div>
                <p>Currently in Use</p>
            </div>
        </div>
        
        <div class="parking-layout">
            <div class="layout-title">🏢 Parking Lot Layout</div>
            
            <div class="gates-container">
                <div class="gate" id="entrance-gate">🚪 ENTRANCE</div>
                <div class="gate" id="exit-gate">🚪 EXIT</div>
            </div>
            
            <div class="slots-container">
                <div class="slot empty" id="slot-1">
                    <div class="slot-number">Slot #1</div>
                    <div class="slot-status">EMPTY</div>
                </div>
                <div class="slot empty" id="slot-2">
                    <div class="slot-number">Slot #2</div>
                    <div class="slot-status">EMPTY</div>
                </div>
                <div class="slot empty" id="slot-3">
                    <div class="slot-number">Slot #3</div>
                    <div class="slot-status">EMPTY</div>
                </div>
            </div>
        </div>
        
        <div class="logs-container">
            <div class="logs-title">📋 Recent Access Logs</div>
            <div id="access-logs">
                <div class="log-entry">
                    <span class="log-time">Loading...</span>
                    <span class="log-action">Please wait</span>
                </div>
            </div>
        </div>
        
        <button class="refresh-btn" onclick="fetchData()">🔄 Refresh Data</button>
        <div class="last-update" id="last-update">Last updated: Never</div>
    </div>

    <script>
        function fetchData() {
            fetch('parking_api.php')
                .then(response => response.json())
                .then(data => {
                    updateDashboard(data);
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }
        
        function updateDashboard(data) {
            // Update statistics
            document.getElementById('total-slots').textContent = data.stats.total_slots;
            document.getElementById('available-slots').textContent = data.stats.available_slots;
            document.getElementById('occupied-slots').textContent = data.stats.occupied_slots;
            
            // Update parking slots
            data.slots.forEach(slot => {
                const slotElement = document.getElementById(`slot-${slot.slot_number}`);
                if (slotElement) {
                    slotElement.className = `slot ${slot.status}`;
                    slotElement.querySelector('.slot-status').textContent = 
                        slot.status.toUpperCase();
                }
            });
            
            // Update gates
            const entranceGate = document.getElementById('entrance-gate');
            const exitGate = document.getElementById('exit-gate');
            
            if (data.gates) {
                entranceGate.className = `gate ${data.gates.entrance_gate}`;
                entranceGate.textContent = `🚪 ENTRANCE: ${data.gates.entrance_gate.toUpperCase()}`;
                
                exitGate.className = `gate ${data.gates.exit_gate}`;
                exitGate.textContent = `🚪 EXIT: ${data.gates.exit_gate.toUpperCase()}`;
            }
            
            // Update access logs
            const logsContainer = document.getElementById('access-logs');
            logsContainer.innerHTML = '';
            
            if (data.logs && data.logs.length > 0) {
                data.logs.forEach(log => {
                    const logEntry = document.createElement('div');
                    logEntry.className = 'log-entry';
                    
                    const time = new Date(log.timestamp).toLocaleString();
                    const actionClass = log.status.includes('granted') ? 'granted' : 'denied';
                    
                    logEntry.innerHTML = `
                        <span class="log-time">${time}</span>
                        <span class="log-action ${actionClass}">
                            ${log.card_uid} - ${log.action} - ${log.status}
                        </span>
                    `;
                    
                    logsContainer.appendChild(logEntry);
                });
            } else {
                logsContainer.innerHTML = '<div class="log-entry">No access logs found</div>';
            }
            
            // Update last update time
            document.getElementById('last-update').textContent = 
                `Last updated: ${new Date().toLocaleString()}`;
        }
        
        // Fetch data immediately and then every 3 seconds
        fetchData();
        setInterval(fetchData, 3000);
    </script>
</body>
</html>