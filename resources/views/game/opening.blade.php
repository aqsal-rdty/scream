<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scream Game Wikrama</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
        }

        .container {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .play-btn {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #ffd700, #ffb347);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(255, 215, 0, 0.4);
            transition: all 0.3s ease;
            margin: 0 auto 30px;
            border: none;
            outline: none;
        }

        .play-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(255, 215, 0, 0.6);
        }

        .play-btn:active {
            transform: scale(0.95);
        }

        .play-btn::after {
            content: '';
            display: block;
            width: 0;
            height: 0;
            border-left: 35px solid white;
            border-top: 20px solid transparent;
            border-bottom: 20px solid transparent;
            margin-left: 10px;
        }

        .title {
            font-size: 36px;
            font-weight: 700;
            letter-spacing: 3px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }

        .img-right {
            position: absolute;
            right: 0;
            width: 50%;
            height: 100%;
            background-image: url('https://via.placeholder.com/800x600/4a6fa5/ffffff?text=Wikrama');
            background-size: cover;
            background-position: center;
            clip-path: polygon(15% 0%, 100% 0%, 100% 100%, 0% 100%);
            z-index: 0;
        }

        #countdown {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.9);
            color: #ffd700;
            font-size: 150px;
            font-weight: 700;
            display: flex;
            justify-content: center;
            align-items: center;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.5s;
            z-index: 2;
        }

        #countdown.show {
            visibility: visible;
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="img-right"></div>
    <div class="container">
        <button class="play-btn" id="playBtn"></button>
        <h1 class="title">SCREAM GAME WIKRAMA</h1>
    </div>
    <div id="countdown"></div>

    <script>
        const playBtn = document.getElementById('playBtn');
        const countdown = document.getElementById('countdown');

        playBtn.addEventListener('click', () => {
            startCountdown();
        });

        function startCountdown() {
            let count = 5;
            countdown.textContent = count;
            countdown.classList.add('show');

            const interval = setInterval(() => {
                count--;
                countdown.textContent = count;

                if (count <= 0) {
                    clearInterval(interval);
                    window.location.href = "/game";
                }
            }, 1000);
        }
    </script>
</body>
</html>