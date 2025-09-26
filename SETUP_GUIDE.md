# 🚗 JoJo Smart Parking System - Complete Setup & Run Guide

## 📋 Prerequisites

### Hardware Requirements:
- **ESP32 Development Board** (with WiFi capability)
- **MFRC522 RFID Module** (for card reading)
- **3x HC-SR04 Ultrasonic Sensors** (for parking slot detection)
- **2x SG90 Servo Motors** (for entrance/exit gates)
- **16x2 LCD Display with I2C** (for status display)
- **2x Push Buttons** (for manual gate control)
- **2x LEDs** (Green & Red for status indication)
- **Buzzer** (for audio feedback)
- **Breadboard & Jumper Wires**
- **Resistors** (220Ω for LEDs, 10kΩ pull-up for buttons)

### Software Requirements:
- **Arduino IDE** (latest version)
- **XAMPP/WAMP** (for local web server)
- **MySQL Database**
- **Web Browser** (Chrome, Firefox, etc.)

---

## 🔧 Hardware Setup

### Pin Connections (ESP32):

| Component | ESP32 Pin | Notes |
|-----------|-----------|-------|
| RFID SS | GPIO 15 | SPI Slave Select |
| RFID RST | GPIO 32 | Reset Pin |
| RFID MOSI | GPIO 23 | SPI Data Out |
| RFID MISO | GPIO 19 | SPI Data In |
| RFID SCK | GPIO 18 | SPI Clock |
| US1 TRIG | GPIO 5 | Ultrasonic 1 Trigger |
| US1 ECHO | GPIO 34 | Ultrasonic 1 Echo |
| US2 TRIG | GPIO 17 | Ultrasonic 2 Trigger |
| US2 ECHO | GPIO 35 | Ultrasonic 2 Echo |
| US3 TRIG | GPIO 16 | Ultrasonic 3 Trigger |
| US3 ECHO | GPIO 33 | Ultrasonic 3 Echo |
| Servo Entrance | GPIO 13 | Entrance Gate |
| Servo Exit | GPIO 12 | Exit Gate |
| LED Green | GPIO 25 | Status LED |
| LED Red | GPIO 26 | Status LED |
| Button Entrance | GPIO 14 | Manual Entrance |
| Button Exit | GPIO 27 | Manual Exit |
| Buzzer | GPIO 4 | Audio Feedback |
| LCD SDA | GPIO 21 | I2C Data |
| LCD SCL | GPIO 22 | I2C Clock |

### Physical Layout:
```
[ENTRANCE GATE] → [SLOT 1] [SLOT 2] [SLOT 3] → [EXIT GATE]
     ↑                ↑        ↑        ↑           ↑
   Servo 1         US Sensor  US Sensor  US Sensor  Servo 2
```

---

## 💻 Software Setup

### Step 1: Install Arduino IDE & Libraries

1. **Download Arduino IDE** from [arduino.cc](https://www.arduino.cc/en/software)
2. **Install ESP32 Board Package:**
   - Go to File → Preferences
   - Add this URL: `https://dl.espressif.com/dl/package_esp32_index.json`
   - Go to Tools → Board → Boards Manager
   - Search "ESP32" and install "ESP32 by Espressif Systems"

3. **Install Required Libraries:**
   - Go to Tools → Manage Libraries
   - Install these libraries:
     - `MFRC522` by Miguel Balboa
     - `LiquidCrystal I2C` by Frank de Brabander
     - `ESP32Servo` by Kevin Harrington
     - `ArduinoJson` by Benoit Blanchon

### Step 2: Setup Web Server (XAMPP)

1. **Download XAMPP** from [apachefriends.org](https://www.apachefriends.org/)
2. **Install XAMPP** and start Apache & MySQL services
3. **Create Project Directory:**
   ```
   C:\xampp\htdocs\jojo\
   ```
4. **Copy Files:**
   - Copy `parking_api.php` to `C:\xampp\htdocs\jojo\`
   - Copy `dashboard.php` to `C:\xampp\htdocs\jojo\`
   - Copy `integration_test.php` to `C:\xampp\htdocs\jojo\`

### Step 3: Setup Database

1. **Open phpMyAdmin:** http://localhost/phpmyadmin
2. **Run Database Setup:**
   - Click "Import" tab
   - Choose `database_setup.sql` file
   - Click "Go" to execute

3. **Verify Tables Created:**
   - `jojo` database should contain:
     - `parking_slots` (3 slots)
     - `system_status` (gate status)
     - `access_logs` (access history)

---

## 🚀 Running the Project

### Step 1: Configure Arduino Code

1. **Open `arduino.txt` in Arduino IDE**
2. **Update WiFi Credentials:**
   ```cpp
   const char* ssid = "YOUR_WIFI_NAME";
   const char* password = "YOUR_WIFI_PASSWORD";
   ```
3. **Update Server URL:**
   ```cpp
   const char* serverURL = "http://YOUR_COMPUTER_IP/jojo/parking_api.php";
   ```
   - Find your computer's IP: `ipconfig` (Windows) or `ifconfig` (Mac/Linux)
   - Example: `http://192.168.1.100/jojo/parking_api.php`

### Step 2: Upload Arduino Code

1. **Select Board:** Tools → Board → ESP32 Dev Module
2. **Select Port:** Tools → Port → (your ESP32 port)
3. **Upload Code:** Click Upload button (→)

### Step 3: Start Web Services

1. **Start XAMPP Services:**
   - Apache (for web server)
   - MySQL (for database)

2. **Test API:** Open browser and go to:
   ```
   http://localhost/jojo/parking_api.php
   ```
   Should show JSON data

3. **Open Dashboard:** Go to:
   ```
   http://localhost/jojo/dashboard.php
   ```

### Step 4: Test Integration

1. **Run Integration Test:**
   ```bash
   php C:\xampp\htdocs\jojo\integration_test.php
   ```

2. **Check Serial Monitor:**
   - In Arduino IDE: Tools → Serial Monitor
   - Set baud rate to 115200
   - Should show WiFi connection and system status

---

## 🧪 Testing the System

### 1. **WiFi Connection Test**
- Check Serial Monitor for "WiFi connected!" message
- Note the IP address displayed

### 2. **RFID Card Test**
- Place authorized RFID card near reader
- Should see "ACCESS GRANTED" on LCD
- Check dashboard for access log entry

### 3. **Ultrasonic Sensor Test**
- Place object in front of sensors
- LCD should update slot status
- Dashboard should reflect changes

### 4. **Gate Control Test**
- Press entrance/exit buttons
- Servos should move gates
- LEDs should change color

### 5. **Dashboard Test**
- Refresh dashboard every 3 seconds
- Should show real-time updates
- Access logs should appear

---

## 🔧 Troubleshooting

### Common Issues:

#### **WiFi Connection Failed**
- ✅ Check WiFi credentials
- ✅ Ensure ESP32 and computer on same network
- ✅ Check signal strength

#### **API Not Responding**
- ✅ Verify XAMPP Apache is running
- ✅ Check file paths in `C:\xampp\htdocs\jojo\`
- ✅ Test API URL in browser

#### **Database Errors**
- ✅ Ensure MySQL service is running
- ✅ Verify database tables exist
- ✅ Check database credentials in `parking_api.php`

#### **Hardware Not Working**
- ✅ Check pin connections
- ✅ Verify power supply (5V for servos)
- ✅ Test components individually

#### **LCD Not Displaying**
- ✅ Check I2C address (usually 0x27)
- ✅ Verify SDA/SCL connections
- ✅ Check power supply

---

## 📊 System Monitoring

### Real-time Monitoring:
1. **Serial Monitor:** Hardware status and debug info
2. **Dashboard:** Web-based system overview
3. **Database:** Access logs and historical data

### Performance Optimization:
- Database updates every 3 seconds
- Ultrasonic checks every 1 second
- RFID checks every 250ms
- Display updates every 1 second

---

## 🎯 Expected Behavior

### Normal Operation:
1. **System Startup:** LCD shows "Smart Parking System Starting"
2. **WiFi Connection:** "WiFi CONNECTED" with IP address
3. **Ready State:** LCD shows slot status (S1:E S2:E S3:E)
4. **Card Detection:** RFID card triggers access check
5. **Gate Control:** Servos open/close based on access
6. **Dashboard Updates:** Real-time status on web interface

### Emergency Mode:
- Press both buttons simultaneously
- Both gates open immediately
- Emergency beep sequence
- Logged in database

---

## 📞 Support

If you encounter issues:
1. Check Serial Monitor for error messages
2. Run integration test script
3. Verify all connections
4. Test components individually
5. Check database connectivity

**Happy Parking! 🚗✨**
