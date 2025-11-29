# Live-Tv-Server-theme                              ![Badge](https://hitscounter.dev/api/hit?url=https%3A%2F%2Fgithub.com%2Fsohag1192%2FLive-Tv-Server-theme%2F&label=Visitors&icon=github&color=%230a58ca&message=&style=for-the-badge&tz=UTC)


A PHP-based live TV server theme with **Flussonic token authentication**.  
This project provides secure streaming URLs for HLS playback, with optional CDNBye P2P acceleration.

---

## 🚀 Features
- 🔐 Secure token generation for Flussonic streams
- 🎥 HLS streaming with DVR support
- ⚡ Random salt + SHA1 hashing for token uniqueness
- 🌍 Flexible IP handling (CDN/P2P friendly)
- 🖥️ Simple PHP theme for live TV server

---

## 📂 File Structure
- `index.php`, `index1.php`, `index2.php` → Frontend templates
- `stream.php` → Token generator and stream URL builder
- `.htaccess` → Apache rewrite rules
- `upload.bat` → Batch script for Git automation
- `img/` → Screenshots and assets

---


## 📸 Demo Screenshot
Here’s how the theme looks in action:

<p align="center">
  <img src="https://github.com/sohag1192/Live-Tv-Server-theme/blob/main/img/index.png" width="30%">
  <img src="https://github.com/sohag1192/Live-Tv-Server-theme/blob/main/img/index1.png" width="30%">
  <img src="https://github.com/sohag1192/Live-Tv-Server-theme/blob/main/img/index2.png" width="30%">
</p>

---

## ⚙️ Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/sohag1192/Live-Tv-Server-theme.git
   cd Live-Tv-Server-theme
   ```
2. Configure your Flussonic server address and secret key in `stream.php`.
3. Deploy the PHP files on your web server (Apache/Nginx with PHP support).
4. Access streams via:
   ```
   https://yourserver/stream.php?stream=channel_name&type=live
   ```

---

## 🔧 Example Usage
```url
https://yourserver/stream.php?stream=channel1&type=live
```
Returns a JSON response with secure token and HLS URL.

---

## 📜 License
Maintained by **Md. Sohag Rana**.  
Keep your Flussonic secret key private.
```




