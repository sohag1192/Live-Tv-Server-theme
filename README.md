*“live tv server theme with flussonic token systems”*.   ![Badge](https://hitscounter.dev/api/hit?url=https%3A%2F%2Fgithub.com%2Fsohag1192%2FLive-Tv-Server-theme%2F&label=Visitors&icon=github&color=%230a58ca&message=&style=for-the-badge&tz=UTC)

---

### 📋 Repository Overview
- **Repo name:** `Live-Tv-Server-Php`  
- **Owner:** [sohag1192](https://github.com/sohag1192)  
- **Purpose:** A PHP-based live TV server theme integrated with **Flussonic token authentication**.  
- **Languages:** ~97% PHP, ~3% Batchfile  
- **Recent files committed:**
  - `.htaccess`
  - `index.php`
  - `index1.php`
  - `index2.php`
  - `stream.php`
  - `upload.bat`

---

### 📝 Suggested README.md Content
Here’s a draft README you can add to your repo for clarity:



# Live-Tv-Server-Php

A PHP-based live TV server theme with **Flussonic token authentication**.  
This project provides secure streaming URLs for HLS playback, with optional CDNBye P2P acceleration.

## Features
- 🔐 Secure token generation for Flussonic streams
- 🎥 HLS streaming with DVR support
- ⚡ Random salt + SHA1 hashing for token uniqueness
- 🌍 Flexible IP handling (CDN/P2P friendly)
- 🖥️ Simple PHP theme for live TV server

## Installation
1. Clone the repository:
   ```bash
   git clone https://github.com/sohag1192/Live-Tv-Server-Php.git
   cd Live-Tv-Server-Php
   ```
2. Configure your Flussonic server address and secret key in `stream.php`.
3. Deploy the PHP files on your web server (Apache/Nginx with PHP support).
4. Access streams via:
   ```
   https://yourserver/stream.php?stream=channel_name
   ```

## File Structure
- `index.php`, `index1.php`, `index2.php` → Frontend templates
- `stream.php` → Token generator and stream URL builder
- `.htaccess` → Apache rewrite rules
- `upload.bat` → Batch script for Git automation

## Example Usage
```url
https://yourserver/stream.php?stream=channel1&type=live
```
Returns a JSON response with secure token and HLS URL.

## License
This project is maintained by **Md. Sohag Rana**.  



