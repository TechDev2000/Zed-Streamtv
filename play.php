<?php
/**
 * Player Wrapper
 * Embeds the selected player with fullscreen support
 */

// Get the channel ID from the query string
$channelId = isset($_GET['c']) ? htmlspecialchars($_GET['c']) : '';

// Validate channel ID (alphanumeric + underscore only)
if (!empty($channelId) && !preg_match('/^[a-zA-Z0-9_]+$/', $channelId)) {
    $channelId = '';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo empty($channelId) ? 'Player' : $channelId; ?> | CricHD</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #000;
            overflow: hidden;
            height: 100vh;
            width: 100vw;
            font-family: Arial, sans-serif;
        }

        #player-container {
            position: relative;
            width: 100%;
            height: 100vh;
        }

        iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: none;
        }

        #fullscreen-button {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(0, 123, 255, 0.9);
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            border-radius: 5px;
            z-index: 1000;
            transition: background-color 0.3s ease;
            backdrop-filter: blur(5px);
        }

        #fullscreen-button:hover {
            background: rgba(0, 86, 179, 0.9);
        }

        .error-message {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #fff;
            font-size: 18px;
            text-align: center;
            padding: 20px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #d32f2f;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }

        .back-link:hover {
            background: #b71c1c;
        }
    </style>
</head>
<body>

<?php if (!empty($channelId)): ?>
    <div id="player-container">
        <iframe 
            src="https://onstream.tiiny.io/embed.php?id=<?php echo $channelId; ?>" 
            allow="autoplay; fullscreen"
            allowfullscreen>
        </iframe>
        <button id="fullscreen-button" title="Toggle Fullscreen">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1h-4zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zM.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5z"/>
            </svg>
            Fullscreen
        </button>
    </div>
<?php else: ?>
    <div class="error-message">
        <div>
            <p>⚠️ No channel specified.</p>
            <p style="margin-top: 10px; color: #aaa; font-size: 14px;">
                Please select a channel from the main page.
            </p>
            <a href="./index.php" class="back-link">← Back to Channels</a>
        </div>
    </div>
<?php endif; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const iframe = document.querySelector("iframe");
        const fullscreenButton = document.getElementById("fullscreen-button");

        if (!iframe || !fullscreenButton) return;

        let isFullscreen = false;

        fullscreenButton.addEventListener("click", function () {
            const container = document.getElementById("player-container");

            if (!isFullscreen) {
                if (container.requestFullscreen) {
                    container.requestFullscreen();
                } else if (container.webkitRequestFullscreen) {
                    container.webkitRequestFullscreen();
                } else if (container.msRequestFullscreen) {
                    container.msRequestFullscreen();
                }
                fullscreenButton.innerHTML = `
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M5.5 0a.5.5 0 0 1 .5.5v4A1.5 1.5 0 0 1 4.5 6h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5zm5 0a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 10 4.5v-4a.5.5 0 0 1 .5-.5zM0 10.5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 6 11.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zm10 1a1.5 1.5 0 0 1 1.5-1.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4z"/>
                    </svg>
                    Exit
                `;
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                } else if (document.webkitExitFullscreen) {
                    document.webkitExitFullscreen();
                } else if (document.msExitFullscreen) {
                    document.msExitFullscreen();
                }
                fullscreenButton.innerHTML = `
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1h-4zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zM.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5z"/>
                    </svg>
                    Fullscreen
                `;
            }
            isFullscreen = !isFullscreen;
        });

        // Listen for fullscreen change events
        document.addEventListener('fullscreenchange', function() {
            isFullscreen = !!document.fullscreenElement;
            if (!isFullscreen) {
                fullscreenButton.innerHTML = `
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M1.5 1a.5.5 0 0 0-.5.5v4a.5.5 0 0 1-1 0v-4A1.5 1.5 0 0 1 1.5 0h4a.5.5 0 0 1 0 1h-4zM10 .5a.5.5 0 0 1 .5-.5h4A1.5 1.5 0 0 1 16 1.5v4a.5.5 0 0 1-1 0v-4a.5.5 0 0 0-.5-.5h-4a.5.5 0 0 1-.5-.5zM.5 10a.5.5 0 0 1 .5.5v4a.5.5 0 0 0 .5.5h4a.5.5 0 0 1 0 1h-4A1.5 1.5 0 0 1 0 14.5v-4a.5.5 0 0 1 .5-.5zm15 0a.5.5 0 0 1 .5.5v4a1.5 1.5 0 0 1-1.5 1.5h-4a.5.5 0 0 1 0-1h4a.5.5 0 0 0 .5-.5v-4a.5.5 0 0 1 .5-.5z"/>
                    </svg>
                    Fullscreen
                `;
            }
        });
    });
</script>

</body>
</html>
