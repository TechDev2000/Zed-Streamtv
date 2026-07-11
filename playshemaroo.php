<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Stream Player</title>
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
            const baseUrl = urlParams.get('max') || '';

            // Append HMAC token (update token as needed)
            const token = 'hdnts=st=1701689581~exp=1701700381~acl=*~data=testdata~hmac=0f4a24b28d14922f6d444ae544f568a6edc786da47d2e103eb996c416f5aaa77';
            const streamUrl = baseUrl ? baseUrl + token : '';

            jwplayer("myElement").setup({
                image: "",
                height: window.innerHeight,
                autostart: false,
                file: streamUrl,
                title: 'Secure Stream',
                description: 'Token-protected streaming',
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
