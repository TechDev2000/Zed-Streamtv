// functions/live/[channel].js
// Clean URL: /live/96017 -> proxies to api.bobtvafrica.site/v1/iptv/live/96017/play.m3u8

const AUTH_CONFIG = {
  enabled: true,
  username: 'admin',
  password: 'changeme123'
};

function verifyAuth(request) {
  if (!AUTH_CONFIG.enabled) return true;
  const authHeader = request.headers.get('Authorization');
  if (!authHeader || !authHeader.startsWith('Basic ')) return false;
  const credentials = atob(authHeader.slice(6));
  const [username, password] = credentials.split(':');
  return username === AUTH_CONFIG.username && password === AUTH_CONFIG.password;
}

function requestAuth() {
  return new Response('Authentication required', {
    status: 401,
    headers: {
      'WWW-Authenticate': 'Basic realm="IPTV Proxy"',
      'Content-Type': 'text/plain',
      'Access-Control-Allow-Origin': '*'
    }
  });
}

function resolveUrl(url, base) {
  if (url.startsWith('http://') || url.startsWith('https://')) return url;
  const baseUrl = new URL(base);
  if (url.startsWith('/')) return `${baseUrl.origin}${url}`;
  const basePath = baseUrl.pathname.split('/').slice(0, -1).join('/');
  return `${baseUrl.origin}${basePath}/${url}`;
}

function rewritePlaylist(content, proxyBaseUrl, originalBaseUrl) {
  const lines = content.split('\n');
  const rewritten = lines.map(line => {
    const trimmed = line.trim();
    if (trimmed.startsWith('#')) {
      if (trimmed.includes('URI="')) {
        return line.replace(/URI="([^"]+)"/g, (match, uri) => {
          const absoluteUrl = resolveUrl(uri, originalBaseUrl);
          const proxyUrl = `${proxyBaseUrl}/proxy/${absoluteUrl.replace(/^https?:\/\//, '')}`;
          return `URI="${proxyUrl}"`;
        });
      }
      return line;
    }
    if (!trimmed) return line;
    const absoluteUrl = resolveUrl(trimmed, originalBaseUrl);
    const targetPath = absoluteUrl.replace(/^https?:\/\//, '');
    return `${proxyBaseUrl}/proxy/${targetPath}`;
  });
  return rewritten.join('\n');
}

function getProxyBaseUrl(request) {
  const url = new URL(request.url);
  return `${url.protocol}//${url.host}`;
}

export async function onRequest(context) {
  const { request, params } = context;
  const channelId = params.channel;

  if (request.method === 'OPTIONS') {
    return new Response(null, {
      status: 204,
      headers: {
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Methods': 'GET, HEAD, OPTIONS',
        'Access-Control-Allow-Headers': 'Authorization, Content-Type, *',
        'Access-Control-Max-Age': '86400'
      }
    });
  }

  if (!verifyAuth(request)) return requestAuth();

  const targetUrl = `https://api.bobtvafrica.site/v1/iptv/live/${channelId}/play.m3u8`;

  const headers = new Headers(request.headers);
  headers.delete('host');
  headers.set('Host', 'api.bobtvafrica.site');
  headers.set('X-Forwarded-By', 'cloudflare-pages-proxy');

  try {
    const response = await fetch(targetUrl, {
      method: request.method,
      headers: headers,
      redirect: 'follow'
    });

    const contentType = response.headers.get('content-type') || '';
    const isM3U8 = contentType.includes('mpegurl') || contentType.includes('mpegURL') || targetUrl.endsWith('.m3u8');

    const modifiedHeaders = new Headers(response.headers);
    modifiedHeaders.set('Access-Control-Allow-Origin', '*');
    modifiedHeaders.set('Access-Control-Allow-Methods', 'GET, HEAD, OPTIONS');
    modifiedHeaders.set('Access-Control-Allow-Headers', 'Authorization, Content-Type, *');
    modifiedHeaders.delete('x-frame-options');
    modifiedHeaders.delete('content-security-policy');

    if (isM3U8) {
      const text = await response.text();
      const proxyBaseUrl = getProxyBaseUrl(request);
      const rewritten = rewritePlaylist(text, proxyBaseUrl, targetUrl);
      modifiedHeaders.set('Content-Type', 'application/vnd.apple.mpegurl');
      return new Response(rewritten, { status: response.status, headers: modifiedHeaders });
    }

    return new Response(response.body, {
      status: response.status,
      statusText: response.statusText,
      headers: modifiedHeaders
    });

  } catch (error) {
    return new Response(JSON.stringify({ error: error.message }), {
      status: 502,
      headers: { 'Content-Type': 'application/json', 'Access-Control-Allow-Origin': '*' }
    });
  }
}
