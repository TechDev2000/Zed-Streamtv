// functions/api/channels.js
// Returns JSON list of available channels

const CHANNELS = [
  { id: '96017', name: 'Channel 96017', url: '/live/96017' },
  { id: '95987', name: 'Channel 95987', url: '/live/95987' },
  { id: '95985', name: 'Channel 95985', url: '/live/95985' },
  { id: '96000', name: 'Channel 96000', url: '/live/96000' },
  { id: '95994', name: 'Channel 95994', url: '/live/95994' },
  { id: '95983', name: 'Channel 95983', url: '/live/95983' },
  { id: '95952', name: 'Channel 95952', url: '/live/95952' },
  { id: '95967', name: 'Channel 95967', url: '/live/95967' },
  { id: '318612', name: 'Channel 318612', url: '/live/318612' },
  { id: '318613', name: 'Channel 318613', url: '/live/318613' }
];

export async function onRequest(context) {
  const { request } = context;
  const url = new URL(request.url);
  const proxyBase = `${url.protocol}//${url.host}`;

  const channelsWithFullUrls = CHANNELS.map(ch => ({
    ...ch,
    proxy_url: `${proxyBase}${ch.url}`,
    m3u8_url: `${proxyBase}/proxy/api.bobtvafrica.site/v1/iptv/live/${ch.id}/play.m3u8`
  }));

  return new Response(JSON.stringify({
    channels: channelsWithFullUrls,
    total: channelsWithFullUrls.length,
    proxy_base: proxyBase
  }, null, 2), {
    status: 200,
    headers: {
      'Content-Type': 'application/json',
      'Access-Control-Allow-Origin': '*',
      'Cache-Control': 'public, max-age=300'
    }
  });
}
