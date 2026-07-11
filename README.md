# CricHD Live Streaming Platform

<p align="center">
  <img src="assets/logo.png" alt="CricHD Logo" width="200">
</p>

<p align="center">
  <strong>A PHP-based live sports streaming platform supporting multiple video players</strong>
</p>

<p align="center">
  <a href="#features">Features</a> •
  <a href="#tech-stack">Tech Stack</a> •
  <a href="#installation">Installation</a> •
  <a href="#usage">Usage</a> •
  <a href="#players">Players</a> •
  <a href="#contributing">Contributing</a>
</p>

---

## 📋 Overview

CricHD is a lightweight PHP web application for aggregating and streaming live sports channels. It features a responsive grid-based channel browser, multiple video player backends (JWPlayer, Clappr, Kaltura), and a clean dark-themed UI optimized for sports streaming.

> ⚠️ **Disclaimer**: This project is for educational purposes only. Ensure you have proper rights to stream any content.

## ✨ Features

- 🏏 **40+ Sports Channels** - Cricket, Football, Tennis, Rugby, F1, and more
- 🔍 **Live Search** - Filter channels by name in real-time
- 📱 **Responsive Design** - Mobile-friendly grid layout
- 🎬 **Multiple Players** - JWPlayer, Clappr (HLS), and Kaltura support
- 🖥️ **Fullscreen Support** - One-click fullscreen toggle
- ⚡ **Fast Loading** - Minimal dependencies, CDN-optimized
- 🎨 **Dark Theme** - Eye-friendly UI for extended viewing

## 🛠️ Tech Stack

| Category | Technology |
|----------|-----------|
| Backend | PHP 7.4+ |
| Frontend | HTML5, CSS3, JavaScript |
| Styling | Custom CSS + Bootstrap 5.2 |
| Video Players | JWPlayer, Clappr, Kaltura Player |
| Icons | Font Awesome 6.2 |
| Streaming | HLS (`.m3u8`) |

## 📁 Project Structure

```
crichd-streaming-site/
├── 📄 index.php              # Main channel listing page
├── 📄 play.php               # Player wrapper with fullscreen
├── 📄 embed.php              # Clappr HLS player embed
├── 📄 plays.php              # JWPlayer standalone
├── 📄 animal_plannet.php     # JWPlayer with playlist support
├── 📄 kplay.php              # Kaltura Player integration
├── 📄 kaltura_player.php     # Kaltura Player (duplicate - legacy)
├── 📄 playshemaroo.php       # JWPlayer with HMAC token auth
├── 📂 assets/
│   ├── logo.png              # Site logo
│   └── player.css            # Custom player styles
├── 📂 players/
│   └── custom-player.js      # Custom HTML5 video player
├── 📄 style.css              # Main stylesheet
├── 📄 script.js              # Custom video player logic
├── 📄 LICENSE                # MIT License
└── 📄 README.md              # This file
```

## 🚀 Installation

### Prerequisites

- PHP 7.4 or higher
- Web server (Apache/Nginx)
- Modern web browser with HLS support

### Quick Start

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/crichd-streaming.git
   cd crichd-streaming
   ```

2. **Configure your web server**
   - Point document root to the project folder
   - Ensure `.php` files are processed by PHP

3. **Add channel logos** (optional)
   ```bash
   mkdir -p logo/
   # Add your channel logo images to the logo/ directory
   ```

4. **Access the site**
   ```
   http://localhost/
   ```

## 🎯 Usage

### Adding a New Channel

Edit `index.php` and add a new `.channel-box` div:

```html
<div class="channel-box">
    <a href="./play.php?c=your_channel_id">
        <div class="channel-logo" style="background-image: url('./logo/your_logo.png');"></div>
        <div class="channel-info">Your Channel Name</div>
    </a>
</div>
```

### Player Selection

| Player | File | Best For |
|--------|------|----------|
| Clappr (HLS) | `embed.php` | Adaptive bitrate streaming |
| JWPlayer | `plays.php` | Advanced controls & playlists |
| Kaltura | `kplay.php` | Enterprise streaming |
| Custom | `script.js` | Full control over UI |

### URL Parameters

- `?c=channel_id` - Select channel (used by `play.php`)
- `?max=stream_url` - Direct stream URL (used by players)

## 🎬 Players

### 1. Clappr Player (`embed.php`)
- HLS.js integration
- Level selector for quality
- Audio track selector
- Auto-recovery on errors

### 2. JWPlayer (`plays.php`, `animal_plannet.php`)
- Netflix-style skin
- Caption/subtitle support
- Playlist functionality
- Social sharing

### 3. Kaltura Player (`kplay.php`)
- Enterprise-grade player
- DRM support ready
- Analytics integration

### 4. Custom Player (`script.js`)
- Built from scratch
- Speed control (0.25x - 2x)
- Picture-in-picture
- Custom captions
- Buffer visualization

## ⚙️ Configuration

### Stream Sources

Update stream URLs in respective player files:

```php
// embed.php
source: 'https://your-stream-url.m3u8',

// plays.php
file: maxvalue, // Passed via URL parameter

// animal_plannet.php
file: "./Animal_Planet.m3u8",
```

### Styling

Main styles are in `style.css`. Player-specific styles are inline or in `assets/player.css`.

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📜 License

Distributed under the MIT License. See `LICENSE` for more information.

## 🙏 Acknowledgments

- [JWPlayer](https://www.jwplayer.com/) - Video player
- [Clappr](https://github.com/clappr/clappr) - Open source player
- [Kaltura](https://corp.kaltura.com/) - Video platform
- [Bootstrap](https://getbootstrap.com/) - CSS framework
- [Font Awesome](https://fontawesome.com/) - Icons

---

<p align="center">
  Made with ❤️ for sports fans everywhere
</p>
