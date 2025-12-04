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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
            background: #0a0a0a;
        }
        video.bg-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }
        .container {
            text-align: center;
            position: relative;
            z-index: 2;
        }
        .play-btn {
            width: 110px;
            height: 110px;
            background: linear-gradient(135deg, #123786, #123786);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.4);
            transition: all 0.3s ease;
            margin: 20px auto;
            border: none;
            outline: none;
        }
        .play-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 35px rgba(255, 255, 255, 0.6);
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
        #countdown {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.9);
            color: #ffffff;
            font-size: 150px;
            font-weight: 700;
            display: flex;
            justify-content: center;
            align-items: center;
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s, opacity 0.5s;
            z-index: 3;
        }
        #countdown.show {
            visibility: visible;
            opacity: 1;
        }
        .name-input {
            margin: 20px auto;
            padding: 10px 15px;
            width: 200px;
            border-radius: 5px;
            border: none;
            font-size: 16px;
            text-align: center;
        }
    </style>
</head>
<body>
    <video class="bg-video" autoplay muted loop>
        <source src="/vidio/vidio.mp4" type="video/mp4">
    </video>
    <div class="container">
        <h1 class="title">SCREAM GAME WIKRAMA</h1>
        <input type="text" class="name-input" id="playerName" placeholder="Masukkan Nama">
        <button class="play-btn" id="playBtn"></button>
    </div>
    <div id="countdown"></div>
    <script>
        const playBtn = document.getElementById('playBtn');
        const countdown = document.getElementById('countdown');
        const playerNameInput = document.getElementById('playerName');

        playBtn.addEventListener('click', () => {
            const playerName = playerNameInput.value.trim();
            if (!playerName) {
                alert('Masukkan nama terlebih dahulu!');
                return;
            }
            localStorage.setItem('playerName', playerName);
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
