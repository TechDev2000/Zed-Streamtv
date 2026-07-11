<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clappr Player</title>
    <meta name="robots" content="noindex">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            background: #000;
        }
        #player {
            width: 100%;
            height: 100%;
        }
    </style>
    <script src="//cdn.jsdelivr.net/npm/clappr@latest/dist/clappr.min.js"></script>
    <script src="//cdn.jsdelivr.net/gh/clappr/clappr-level-selector-plugin@latest/dist/level-selector.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@clappr/hlsjs-playback@1.2.0/dist/hlsjs-playback.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@c3voc/clappr-audio-track-selector@0.2.4/dist/audio-track-selector.min.js"></script>
</head>
<body>
    <div id="player"></div>

    <script>
        // Get stream URL from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const streamId = urlParams.get('id') || '';

        // Construct stream source (customize this URL pattern)
        const streamSource = streamId ? `https://onstream.tiiny.io/embed.php?id=${streamId}` : '';

        // Initialize Clappr player
        var player = new Clappr.Player({
            source: streamSource,
            plugins: [HlsjsPlayback, LevelSelector, AudioTrackSelector],
            width: '100%',
            height: '100%',
            autoPlay: true,
            mimeType: "application/x-mpegURL",
            mediacontrol: { 
                seekbar: "#ff0000", 
                buttons: "#eee" 
            },
            parentId: "#player",
            hlsUseNextLevel: false,
            hlsMinimumDvrSize: 60,
            hlsRecoverAttempts: 16,
            hlsPlayback: {
                preload: true,
                customListeners: [],
            },
            playback: {
                extrapolatedWindowNumSegments: 2,
                triggerFatalErrorOnResourceDenied: false,
                hlsjsConfig: {
                    // hls.js specific options
                },
            },
        });

        // Error handling
        player.on(Clappr.Events.PLAYER_ERROR, function(error) {
            console.error('Player error:', error);
            setTimeout(function() {
                player.load(streamSource);
            }, 5000);
        });
    </script>
</body>
</html>
