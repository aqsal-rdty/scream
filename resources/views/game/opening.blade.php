<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scream Game Wikrama</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            height: 100vh;
            overflow: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: "Poppins", sans-serif;
            color: white;
            background: #070707;
        }
        video.bg-video {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            filter: brightness(0.45);
        }
        .container {
            text-align: center;
            position: relative;
            z-index: 10;
            animation: fadeIn 1s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .title {
            font-size: 40px;
            font-weight: 700;
            letter-spacing: 3px;
            text-shadow: 0 3px 15px rgba(0,0,0,0.5);
            margin-bottom: 25px;
        }
        .name-input {
            padding: 12px 16px;
            width: 250px;
            border-radius: 12px;
            border: none;
            font-size: 16px;
            outline: none;
            text-align: center;
            background: rgba(255,255,255,0.15);
            color: white;
            backdrop-filter: blur(6px);
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        }
        .name-input::placeholder { color: #e7e7e7; }
        .play-btn {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0f5ae0, #002f8e);
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            border: none;
            outline: none;
            box-shadow: 0 12px 40px rgba(0, 102, 255, 0.5);
            transition: 0.3s ease;
            margin: auto;
        }
        .play-btn:hover { transform: scale(1.12); }
        .play-btn:active { transform: scale(0.95); }
        .play-btn::after {
            content: '';
            border-left: 40px solid white;
            border-top: 22px solid transparent;
            border-bottom: 22px solid transparent;
            margin-left: 8px;
        }
        .play-btn:disabled {
            background: #555;
            cursor: not-allowed;
            box-shadow: none;
            transform: none;
        }
        #countdown {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.85);
            color: #ffffff;
            font-size: 170px;
            font-weight: 700;
            display: flex;
            justify-content: center;
            align-items: center;
            visibility: hidden;
            opacity: 0;
            transition: 0.4s ease;
            z-index: 20;
        }
        #countdown.show { visibility: visible; opacity: 1; }
        #leaderboard {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 320px;
            background: rgba(20,20,20,0.75);
            backdrop-filter: blur(15px);
            border-radius: 15px;
            padding: 18px;
            border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 12px 35px rgba(0,0,0,0.45);
            transition: 0.3s ease;
            transform-origin: bottom right;
        }
        #leaderboard.hidden {
            transform: scale(0);
            opacity: 0;
            pointer-events: none;
        }
        .leaderboard-header {
            text-align:center;
            margin-bottom: 12px;
        }
        .leaderboard-header h3 {
            font-size: 22px;
            font-weight: 700;
        }
        .leaderboard-rankings {
            max-height: 350px;
            overflow-y: auto;
            padding-right: 4px;
        }
        .ranking-header, .ranking-item {
            display: grid;
            grid-template-columns: 1fr 2fr 1fr 1fr;
            text-align: center;
            padding: 10px 0;
            font-size: 13px;
        }
        .ranking-item {
            border-bottom: 1px solid rgba(255,255,255,0.06);
            transition: 0.2s;
        }
        .ranking-item:hover { background: rgba(255,255,255,0.07); }
        .first  { background: rgba(255,215,0,0.15); }
        .second { background: rgba(192,192,192,0.15); }
        .third  { background: rgba(205,127,50,0.15); }
        .reward-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
        }
        #toggleLeaderboard {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 12px 18px;
            background: #0055ff;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-size: 15px;
            font-weight: 600;
            color: white;
            box-shadow: 0 5px 20px rgba(0, 85, 255, 0.5);
            transition: 0.2s;
            z-index: 15;
        }
        #toggleLeaderboard:hover {
            transform: scale(1.05);
        }
        .allow-mic-btn {
            display: block;
            margin: 15px auto;
            padding: 12px 24px;
            border: none;
            background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
            color: white;
            font-size: 16px;
            font-weight: 600;
            border-radius: 12px;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(255, 107, 107, 0.4);
            transition: all 0.3s ease;
            outline: none;
        }
        .allow-mic-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 107, 107, 0.5);
        }
        .allow-mic-btn:active {
            transform: scale(0.98);
        }
        .allow-mic-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
            box-shadow: none;
        }
        .allow-mic-btn.success {
            background: linear-gradient(135deg, #4CAF50, #66BB6A);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }
        .allow-mic-btn.success:hover {
            box-shadow: 0 8px 25px rgba(76, 175, 80, 0.5);
        }
    </style>
</head>
<body>
    <video class="bg-video" autoplay muted loop>
        <source src="/vidio/bg.mp4" type="video/mp4">
    </video>
    <button id="toggleLeaderboard">Leaderboard</button>
    <div id="leaderboard" class="hidden">
        <div class="leaderboard-header">
            <h3>🏆 Leaderboard</h3>
            <p>Top 10 Players</p>
        </div>
        <div class="leaderboard-rankings">
            <div class="ranking-header">
                <span>Rank</span>
                <span>Name</span>
                <span>Score</span>
                <span>Reward</span>
            </div>
            <ul id="leaderboardList">
                @if($leaderboard->isEmpty())
                    <li style="text-align: center; padding: 20px; color: #aaa;">Belum ada skor</li>
                @else
                    @foreach($leaderboard as $index => $entry)
                    <li class="ranking-item {{ $index == 0 ? 'first' : ($index == 1 ? 'second' : ($index == 2 ? 'third' : '')) }}">
                        <span>{{ $index + 1 }}</span>
                        <span style="text-align:left;">{{ $entry->name }}</span>
                        <span>{{ $entry->score }}</span>
                        <span>
                            @if($entry->reward == 'Quest')
                                <img src="/images/Q.png" class="reward-icon">
                            @elseif($entry->reward == 'Gold')
                                <img src="/images/G.png" class="reward-icon">
                            @elseif($entry->reward == 'Silver')
                                <img src="/images/S.png" class="reward-icon">
                            @elseif($entry->reward == 'Bronze')
                                <img src="/images/B.png" class="reward-icon">
                            @else - @endif
                        </span>
                    </li>
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
    <div class="container">
        <h1 class="title">SCREAM GAME WIKRAMA</h1>
        <input type="text" id="playerName" class="name-input" placeholder="Masukkan Nama">
        <button id="allowMicBtn" class="allow-mic-btn">🎤 Izinkan Mikrofon</button>
        <button class="play-btn" id="playBtn" disabled></button>
    </div>
    <div id="countdown"></div>
    <script>
        const playBtn = document.getElementById('playBtn');
        const countdown = document.getElementById('countdown');
        const playerNameInput = document.getElementById('playerName');
        const allowMicBtn = document.getElementById('allowMicBtn');
        let micPermissionGranted = false;

        playerNameInput.addEventListener('input', () => {
            const playerName = playerNameInput.value.trim();
            playBtn.disabled = !(playerName && micPermissionGranted);
        });

        async function requestMicPermission() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                micPermissionGranted = true;
                allowMicBtn.textContent = "Mikrofon Diizinkan";
                allowMicBtn.classList.add('success');

                const playerName = playerNameInput.value.trim();
                playBtn.disabled = !playerName;
                stream.getTracks().forEach(track => track.stop());
            } catch (error) {
                console.error("Error accessing microphone:", error);
                allowMicBtn.textContent = "Izin Ditolak";
                allowMicBtn.style.background = "#ef5350";
            }
        }

        allowMicBtn.addEventListener('click', requestMicPermission);

        playBtn.addEventListener('click', () => {
            const playerName = playerNameInput.value.trim();
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

        const toggleBtn = document.getElementById('toggleLeaderboard');
        const leaderboard = document.getElementById('leaderboard');
        toggleBtn.addEventListener('click', () => {
            leaderboard.classList.toggle('hidden');
        });
    </script>
</body>
</html>