<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kaltura Player</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <style>
        * {
            overflow-x: hidden;
        }
        #video-player {
            width: 100% !important;
            height: 100% !important;
        }
        option {
            background-color: black;
        }
        body {
            margin: 0;
            background: #000;
        }
    </style>
    <script src="https://qa-apache-php7.dev.kaltura.com/p/1091/sp/109100/embedPlaykitJs/uiconf_id/15215933/partner_id/1091"></script>
</head>
<body>
    <div id="video-player" style="width: 100%; height: 100vh; position: absolute;"></div>

    <script>
        // Get stream URL from URL parameters
        const urlParams = new URLSearchParams(window.location.search);
        const streamUrl = urlParams.get('max') || '';

        try {
            const config = {
                targetId: "video-player",
                provider: {
                    partnerId: "1091",
                    uiConfId: "15215933"
                }
            };

            var kalturaPlayer = KalturaPlayer.setup(config);

            kalturaPlayer.setMedia({
                session: {
                    isAnonymous: true,
                    partnerId: 1234,
                    uiConfId: 1234
                },
                sources: {
                    hls: [
                        {
                            url: streamUrl,
                            mimetype: "application/x-mpegURL"
                        }
                    ],
                    progressive: [],
                    id: "1_r0tzzzgp",
                    type: "Live",
                    poster: "",
                    dvr: false,
                    vr: null,
                    metadata: {
                        name: "Live Stream",
                        description: "Live sports streaming via Kaltura"
                    }
                },
                plugins: {}
            });
        } catch (e) {
            console.error("Kaltura Player Error:", e.message);
            document.getElementById('video-player').innerHTML = 
                '<div style="color: white; text-align: center; padding-top: 20%;">' +
                '<p>Error loading player</p>' +
                '<p style="color: #aaa;">' + e.message + '</p>' +
                '</div>';
        }
    </script>
</body>
</html>
