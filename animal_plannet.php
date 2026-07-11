<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JWPlayer Playlist</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <style>
        .jw-button-container {
            bottom: 0%;
            width: 100%;
        }
        .jw-wrapper {
            background-color: #000;
            position: fixed !important;
        }
        .jw-logo {
            display: none;
        }
        body {
            margin: 0;
            background: #000;
        }
    </style>
</head>
<body>
    <div id="container">
        <video></video>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.js"></script>
    <script src="//content.jwplatform.com/libraries/Jq6HIbgz.js"></script>

    <script>
        // Get stream URL from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const streamUrl = urlParams.get('max') || './Animal_Planet.m3u8';

        jwplayer("container").setup({
            controls: true,
            displaytitle: true,
            fullscreen: true,
            primary: 'html5',
            stretching: "extrafit",
            autostart: true,
            quality: "auto",
            volume: "100",
            skin: {
                name: 'Netflix',
            },
            captions: {
                color: '#FFF',
                fontSize: 14,
                backgroundOpacity: 0,
                edgeStyle: 'raised'
            },
            playlist: [
                {
                    title: "Live Stream",
                    description: "Live sports streaming",
                    image: "",
                    sources: [{
                        file: streamUrl,
                        label: 'Auto',
                        type: 'mp4',
                        primary: 'html5',
                    }]
                }
            ]
        });

        jwplayer("container").setCaptions({
            "back": true,
            "backgroundOpacity": "32",
            "edgeStyle": "dropshadow",
            "fontSize": 14,
            "fontOpacity": 100,
            "fontScale": 0.05,
            "windowOpacity": 0,
            "color": "#ffff00"
        });
    </script>
</body>
</html>
