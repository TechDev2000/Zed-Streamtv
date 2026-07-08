# 🎬 Cloudflare Pages IPTV Proxy

A serverless proxy built on Cloudflare Pages Functions with M3U8 playlist URL rewriting and Basic Authentication.

## Features

- ✅ **M3U8 URL Rewriting** — All `.ts` segments route through your proxy
- ✅ **Basic Authentication** — Password-protect your streams
- ✅ **Clean URLs** — `/live/96017` instead of long API paths
- ✅ **CORS Enabled** — Works from any web player
- ✅ **Built-in Web Player** — HLS.js player with channel grid
- ✅ **M3U Playlist Export** — Download playlist for VLC/Kodi
- ✅ **Edge Network** — Runs on Cloudflare's global CDN

## Project Structure

```
.
├── functions/
│   ├── proxy/[[path]].js    # Generic proxy with URL rewriting
│   ├── live/[channel].js     # Clean URL routes
│   └── api/channels.js       # Channel listing API
├── _routes.json              # Routing configuration
├── index.html                # Web UI with player
└── wrangler.toml             # Wrangler config
```

## Quick Deploy

### 1. Install Wrangler
```bash
npm install -g wrangler
```

### 2. Login to Cloudflare
```bash
wrangler login
```

### 3. Deploy
```bash
wrangler pages project create iptv-proxy
wrangler pages deploy . --project-name=iptv-proxy
```

Or connect your GitHub repo to Cloudflare Pages for auto-deployment.

## Configuration

### Change Authentication Credentials

Edit these files and update the credentials:
- `functions/proxy/[[path]].js`
- `functions/live/[channel].js`

```javascript
const AUTH_CONFIG = {
  enabled: true,
  username: 'YOUR_USERNAME',    // ← Change this
  password: 'YOUR_PASSWORD'     // ← Change this
};
```

### Disable Authentication
Set `enabled: false` in `AUTH_CONFIG`.

## API Endpoints

| Endpoint | Description | Example |
|----------|-------------|---------|
| `GET /api/channels` | List all channels | [your-proxy.pages.dev/api/channels](https://example.pages.dev/api/channels) |
| `GET /live/:id` | Clean proxy URL | `/live/96017` |
| `GET /proxy/:url` | Generic proxy | `/proxy/api.bobtvafrica.site/v1/iptv/live/96017/play.m3u8` |

## Usage Examples

### VLC / Kodi
```
https://your-proxy.pages.dev/live/96017
```

### With Authentication (VLC)
```
https://admin:password@your-proxy.pages.dev/live/96017
```

### Web Player
Open `https://your-proxy.pages.dev` and use the built-in player.

### cURL
```bash
curl -u admin:password https://your-proxy.pages.dev/api/channels
```

## How URL Rewriting Works

1. Client requests `/live/96017`
2. Proxy fetches `api.bobtvafrica.site/.../96017/play.m3u8`
3. Proxy rewrites all segment URLs in the M3U8:
   ```
   # Before (in origin M3U8)
   segment_001.ts
   https://cdn.example.com/segment_002.ts

   # After (rewritten by proxy)
   https://your-proxy.pages.dev/proxy/api.bobtvafrica.site/.../segment_001.ts
   https://your-proxy.pages.dev/proxy/cdn.example.com/segment_002.ts
   ```
4. Player requests segments through proxy → all traffic is proxied

## Important Notes

- **Free Tier**: 100,000 requests/day on Cloudflare Pages free plan
- **Credentials**: Change default `admin`/`changeme123` before deploying
- **HTTPS Only**: Origin must support HTTPS
- **M3U8 Parsing**: Handles relative URLs, absolute URLs, and URI attributes in tags

## License

MIT
