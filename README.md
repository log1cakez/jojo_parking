# 🚗 JoJo Smart Parking System

An IoT-based smart parking management system that combines ESP32 hardware with a web-based dashboard for real-time parking monitoring and access control.

## 🌟 Features

- **Real-time Parking Monitoring** - Ultrasonic sensors detect vehicle presence
- **RFID Access Control** - Secure entry/exit with authorized cards
- **Automated Gate Control** - Servo motors control entrance/exit gates
- **Web Dashboard** - Beautiful real-time monitoring interface
- **Database Logging** - Complete access logs and parking history
- **LCD Display** - On-site status display
- **Emergency Mode** - Manual gate override functionality

## 🏗️ System Architecture

```
[ESP32 Hardware] ←→ [WiFi] ←→ [Web Server] ←→ [MySQL Database]
     ↓
[RFID Reader] [Ultrasonic Sensors] [Servo Motors] [LCD Display]
```

## 📋 Hardware Requirements

- **ESP32 Development Board** (with WiFi)
- **MFRC522 RFID Module**
- **3x HC-SR04 Ultrasonic Sensors**
- **2x SG90 Servo Motors**
- **16x2 LCD Display with I2C**
- **2x Push Buttons**
- **2x LEDs** (Green & Red)
- **Buzzer**
- **Breadboard & Jumper Wires**

## 💻 Software Requirements

- **Arduino IDE** (latest version)
- **XAMPP/WAMP/Laragon** (for local web server)
- **MySQL Database**
- **Web Browser**

## 🚀 Quick Start

### 1. Database Setup
```sql
-- Import database_setup.sql in phpMyAdmin
-- Creates 'jojo' database with required tables
```

### 2. Web Server Setup
```bash
# Copy files to web server directory
# For Laragon: E:\laragon\www\jojo\
# For XAMPP: C:\xampp\htdocs\jojo\
```

### 3. Hardware Setup
- Connect components according to pin diagram in `SETUP_GUIDE.md`
- Update WiFi credentials in `arduino.txt`
- Update server URL with your computer's IP address
- Upload Arduino code to ESP32

### 4. Access the System
- **Dashboard**: `http://localhost/jojo/dashboard.php`
- **API**: `http://localhost/jojo/parking_api.php`

## 📁 Project Structure

```
jojo/
├── parking_api.php          # Main API endpoint
├── dashboard.php            # Web dashboard interface
├── arduino.txt              # ESP32 hardware code
├── database_setup.sql       # Database initialization
├── integration_test.php     # System testing script
├── quick_start.bat          # Automated setup script
└── SETUP_GUIDE.md          # Complete setup documentation
```

## 🔧 Configuration

### WiFi Settings (arduino.txt)
```cpp
const char* ssid = "YOUR_WIFI_NAME";
const char* password = "YOUR_WIFI_PASSWORD";
const char* serverURL = "http://YOUR_IP/jojo/parking_api.php";
```

### Database Settings (parking_api.php)
```php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jojo";
```

## 📊 API Endpoints

### GET `/parking_api.php`
Returns current system status:
```json
{
  "slots": [...],
  "gates": {...},
  "logs": [...],
  "stats": {...}
}
```

### POST `/parking_api.php`
Updates parking status or logs access:
```json
{
  "slot1": "occupied",
  "slot2": "empty", 
  "slot3": "occupied",
  "entrance_gate": "open",
  "exit_gate": "closed"
}
```

## 🎯 Usage

1. **System Startup**: ESP32 connects to WiFi and initializes sensors
2. **RFID Detection**: Place authorized card near reader
3. **Access Control**: System checks authorization and parking availability
4. **Gate Control**: Servos open/close gates based on access decision
5. **Real-time Updates**: Dashboard shows live status and logs

## 🧪 Testing

Run the integration test:
```bash
php integration_test.php
```

Or use the quick start script:
```bash
quick_start.bat
```

## 📈 Monitoring

- **Serial Monitor**: Hardware status and debug info (115200 baud)
- **Web Dashboard**: Real-time system overview
- **Database**: Access logs and historical data

## 🔒 Security Features

- **Authorized RFID Cards**: Only pre-programmed cards work
- **Parking Capacity**: Prevents overfilling
- **Access Logging**: Complete audit trail
- **Emergency Override**: Manual gate control

## 🛠️ Troubleshooting

### Common Issues:
- **WiFi Connection**: Check credentials and signal strength
- **API Not Responding**: Verify web server is running
- **Database Errors**: Ensure MySQL service is active
- **Hardware Issues**: Check pin connections and power supply

## 📚 Documentation

- **SETUP_GUIDE.md**: Complete hardware and software setup
- **Pin Connections**: Detailed wiring diagram
- **API Reference**: Complete endpoint documentation
- **Troubleshooting**: Common issues and solutions

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 👨‍💻 Author

Created as part of the JoJo Smart Parking System project.

## 🙏 Acknowledgments

- ESP32 community for hardware support
- Arduino libraries for sensor integration
- PHP/MySQL for web backend

---

**Happy Parking! 🚗✨**
