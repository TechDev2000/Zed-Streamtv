// functions/proxy/[[path]].js
// Enhanced proxy with M3U8 URL rewriting + Basic Auth

const ALLOWED_ORIGINS = ['https://api.bobtvafrica.site'];

// Basic Auth credentials - CHANGE THESE!
const AUTH_CONFIG = {
  enabled: true,  // Set to false to disable auth
  username: 'admin',      // CHANGE THIS
  password: 'changeme123' // CHANGE THIS - use a strong password!
};

/**
 * Verify Basic Auth credentials
 */
function verifyAuth(request) {
  if (!AUTH_CONFIG.enabled) return true;

  const authHeader = request.headers.get('Authorization');
  if (!authHeader || !authHeader.startsWith('Basic ')) {
    return false;
  }

  const base64Credentials = authHeader.slice(6);
  const credentials = atob(base64Credentials);
  const [username, password] = credentials.split(':');

  return username === AUTH_CONFIG.username && password === AUTH_CONFIG.password;
}

/**
 * Request Basic Auth
 */
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

/**
 * Rewrite M3U8 playlist URLs to route through proxy
 * Converts relative/absolute segment URLs to proxy URLs
 */
function rewritePlaylist(content, proxyBaseUrl, originalBaseUrl) {
  // Handle different line types in M3U8
  const lines = content.split('\n');
  const rewritten = lines.map(line => {
    const trimmed = line.trim();

    // Skip comments and tags (except EXT-X-KEY and EXT-X-MEDIA which may have URIs)
    if (trimmed.startsWith('#')) {
      // Rewrite URI attributes in tags
      if (trimmed.includes('URI="')) {
        return line.replace(/URI="([^"]+)"/g, (match, uri) => {
          const absoluteUrl = resolveUrl(uri, originalBaseUrl);
          const proxyUrl = `${proxyBaseUrl}/proxy/${absoluteUrl.replace(/^https?:\/\//, '')}`;
          return `URI="${proxyUrl}"`;
        });
      }
      return line;
    }

    // Skip empty lines
    if (!trimmed) return line;

    // Rewrite segment URLs (relative or absolute)
    const absoluteUrl = resolveUrl(trimmed, originalBaseUrl);

    // Build proxy URL - encode the target URL properly
    const targetPath = absoluteUrl.replace(/^https?:\/\//, '');
    return `${proxyBaseUrl}/proxy/${targetPath}`;
  });

  return rewritten.join('\n');
}

/**
 * Resolve relative URL to absolute
 */
function resolveUrl(url, base) {
  if (url.startsWith('http://') || url.startsWith('https://')) {
    return url;
  }

  const baseUrl = new URL(base);

  if (url.startsWith('/')) {
    return `${baseUrl.origin}${url}`;
  }

  // Relative path
  const basePath = baseUrl.pathname.split('/').slice(0, -1).join('/');
  return `${baseUrl.origin}${basePath}/${url}`;
}

/**
 * Get proxy base URL from request
 */
function getProxyBaseUrl(request) {
  const url = new URL(request.url);
  return `${url.protocol}//${url.host}`;
}

export async function onRequest(context) {
  const { request } = context;
  const url = new URL(request.url);

  // Handle CORS preflight
  if (request.method === 'OPTIONS') {
    return new Response(null, {
      status: 204,
      headers: {
        'Access-Control-Allow-Origin': '*',
        'Access-Control-Allow-Methods': 'GET, HEAD, POST, OPTIONS',
        'Access-Control-Allow-Headers': 'Authorization, Content-Type, *',
        'Access-Control-Max-Age': '86400'
      }
    });
  }

  // Verify authentication
  if (!verifyAuth(request)) {
    return requestAuth();
  }

  // Extract target path: /proxy/v1/iptv/live/96017/play.m3u8
  const pathSegments = url.pathname.split('/').slice(2);
  const targetPath = pathSegments.join('/');

  // Determine protocol (default to https)
  let targetUrl;
  if (targetPath.startsWith('http://') || targetPath.startsWith('https://')) {
    targetUrl = targetPath;
  } else {
    targetUrl = `https://${targetPath}${url.search}`;
  }

  // Validate origin
  let targetOrigin;
  try {
    targetOrigin = new URL(targetUrl).origin;
  } catch {
    return new Response('Invalid target URL', { status: 400 });
  }

  if (!ALLOWED_ORIGINS.includes(targetOrigin)) {
    return new Response(`Forbidden: Origin ${targetOrigin} not allowed`, { status: 403 });
  }

  // Prepare headers
  const headers = new Headers(request.headers);
  headers.delete('host');
  headers.set('Host', new URL(targetUrl).host);
  headers.set('X-Forwarded-By', 'cloudflare-pages-proxy');
  headers.set('X-Forwarded-For', request.headers.get('CF-Connecting-IP') || '');

  try {
    const response = await fetch(targetUrl, {
      method: request.method,
      headers: headers,
      body: ['GET', 'HEAD'].includes(request.method) ? null : await request.blob(),
      redirect: 'follow'
    });

    const contentType = response.headers.get('content-type') || '';
    const isM3U8 = contentType.includes('application/vnd.apple.mpegurl') || 
                   contentType.includes('application/x-mpegURL') ||
                   targetUrl.endsWith('.m3u8');

    const modifiedHeaders = new Headers(response.headers);
    modifiedHeaders.set('Access-Control-Allow-Origin', '*');
    modifiedHeaders.set('Access-Control-Allow-Methods', 'GET, HEAD, POST, OPTIONS');
    modifiedHeaders.set('Access-Control-Allow-Headers', 'Authorization, Content-Type, *');
    modifiedHeaders.set('Access-Control-Expose-Headers', 'Content-Length, Content-Type');
    modifiedHeaders.delete('x-frame-options');
    modifiedHeaders.delete('content-security-policy');

    // Rewrite M3U8 playlists to route segments through proxy
    if (isM3U8) {
      const text = await response.text();
      const proxyBaseUrl = getProxyBaseUrl(request);
      const rewritten = rewritePlaylist(text, proxyBaseUrl, targetUrl);

      modifiedHeaders.set('Content-Type', 'application/vnd.apple.mpegurl');

      return new Response(rewritten, {
        status: response.status,
        headers: modifiedHeaders
      });
    }

    // For TS segments and other binary content, stream through
    return new Response(response.body, {
      status: response.status,
      statusText: response.statusText,
      headers: modifiedHeaders
    });

  } catch (error) {
    return new Response(JSON.stringify({ 
      error: 'Proxy Error', 
      message: error.message,
      target: targetUrl 
    }), { 
      status: 502,
      headers: {
        'Content-Type': 'application/json',
        'Access-Control-Allow-Origin': '*'
      }
    });
  }
}
