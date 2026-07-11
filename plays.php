<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JWPlayer Stream</title>
    <style>
        html, body, #container {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background: #000;
            color: #fff;
            overflow: hidden;
        }
        #myElement {
            height: 100vh !important;
        }
        body {
            height: 100vh;
        }
        #container {
            position: absolute;
            text-align: center;
        }
        video {
            outline: 0;
        }
        .jw-svg-icon-watermark {
            display: none !important;
        }
    </style>
</head>
<body>
    <div class="video">
        <script src="https://use.fontawesome.com/20603b964f.js"></script>
        <script src="https://content.jwplatform.com/libraries/LJ361JYj.js"></script>
        <script>
            jwplayer.key = 'ypdL3Acgwp4Uh2/LDE9dYh3W/EPwDMuA2yid4ytssfI=';
        </script>

        <div id="myElement"></div>

        <script>
            // Get stream URL from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const streamUrl = urlParams.get('max') || '';

            jwplayer("myElement").setup({
                image: "",
                height: window.innerHeight,
                autostart: false,
                file: streamUrl,
                title: 'Live Stream',
                description: 'Live Sports Streaming',
                aboutlink: 'https://www.jwplayer.com/',
                captions: {
                    color: '#ffb800',
                    fontSize: 30,
                    backgroundOpacity: 0
                },
                sharing: {
                    sites: ['facebook', 'twitter', 'tumblr', 'googleplus'],
                    code: encodeURI("<iframe src='" + window.location.href + "' width='480' height='320'></iframe>"),
                    link: window.location.href
                }
            });
        </script>
    </div>
</body>
</html>
