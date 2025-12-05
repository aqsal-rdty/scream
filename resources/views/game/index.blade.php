<!DOCTYPE html>
<html lang="id">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scream Game Wikrama</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #ffffff;
            overflow: hidden;
        }

        .wave {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: #123786;
            border-top-left-radius: 50% 30%;
            border-top-right-radius: 50% 30%;
        }

        .progress-bar {
            position: absolute;
            left: 55px;
            top: 60px;
            width: 45px;
            height: 77%;
            background: #ececec;
            border-radius: 20px;
            border: 2px solid #777;
            overflow: hidden;
        }

        .progress-fill {
            width: 100%;
            height: 0%;
            position: absolute;
            bottom: 0;
            background: linear-gradient(to top, #123786, #0d255a);
            transition: height 0.2s ease;
        }

        .icon-list {
            position: absolute;
            left: 120px;
            top: 90px;
            display: flex;
            flex-direction: column;
            gap: 70px;
        }

        .icon-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .icon-item img {
            width: 45px;
            height: 45px;
            opacity: 0.8;
            transition: 0.25s;
        }

        .icon-item img.active {
            opacity: 1;
            transform: scale(1.2);
        }

        .icon-label {
            font-size: 18px;
            font-weight: bold;
            color: #0b0b0b;
        }

        .title {
            text-align: center;
            margin-top: 205px;
            font-size: 70px;
            font-weight: 800;
            color: #0b0b0b;
            letter-spacing: 3px;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.35);
            animation: zoom 1.3s infinite ease-in-out;
        }

        #message {
            text-align: center;
            margin-top: 40px;
            font-size: 26px;
            color: #333;
        }

        #startBtn {
            display: block;
            margin: 20px auto;
            padding: 18px 28px;
            border: none;
            background: #123786;
            color: #fff;
            font-size: 24px;
            border-radius: 10px;
            cursor: pointer;
        }

        .score-box {
            position: absolute;
            top: 20px;
            right: 40px;
            padding: 20px;
            border-radius: 40px;
            background: #fff;
            width: 160px;
            height: 100px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            text-align: center;
        }

        .score-box img {
            width: 80px;
        }

        #timer {
            font-size: 60px;
            font-weight: bold;
            text-align: center;
            display: none;
            margin-top: 10px;
        }

        .modal {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.6);
            visibility: hidden;
            opacity: 0;
            transition: .2s;
        }

        .modal.show {
            visibility: visible;
            opacity: 1;
        }

        .modal-content {
            background: #fff;
            padding: 25px;
            border-radius: 15px;
            width: 300px;
            text-align: center;
        }

        #restartBtn {
            background-color: #123786;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 18px;
            transition: 0.3s;
        }

        #restartBtn:hover {
            background-color: #0b2f70;
        }

        @keyframes pop {
            0% { transform: scale(1); }
            50% { transform: scale(1.5); }
            100% { transform: scale(1); }
        }

        @keyframes zoom {
            0% { transform: scale(1); }
            50% { transform: scale(1.10); }
            100% { transform: scale(1); }
        }
    </style>
</head>

<body>

    <div class="sidebar"></div>

    <div class="progress-bar">
        <div class="progress-fill" id="progressFill"></div>
    </div>

    <div class="icon-list">
        <div class="icon-item" style="top: 40px;">
            <img id="i-quest" src="images/1.png">
            <span class="icon-label">1000</span>
        </div>
        <div class="icon-item" style="top: 120px;">
            <img id="i-gold" src="images/2.png">
            <span class="icon-label">800</span>
        </div>
        <div class="icon-item" style="top: 200px;">
            <img id="i-silver" src="images/3.png">
            <span class="icon-label">500</span>
        </div>
        <div class="icon-item" style="top: 280px;">
            <img id="i-bronze" src="images/4.png">
            <span class="icon-label">200</span>
        </div>
    </div>

    <div class="score-box">
        <div>SCORE : <span id="score">0</span></div>
        <img src="images/wikrama.png">
    </div>

    <div class="title">TERIAK SEKARANG</div>
    <div id="message">Gak Kedengeran</div>
    <button id="startBtn">TERIAK SEKARANG</button>

    <div id="leaderboard" style="position: fixed; bottom: 20px; right: 20px; 
        background: rgba(255,255,255,0.9); padding: 15px; border-radius: 10px; 
        width: 250px; max-height: 400px; overflow-y: auto; display:none;">
        <h3 style="text-align:center; margin-bottom:10px;">Leaderboard</h3>
        <ul id="leaderboardList" style="list-style:none; padding:0; margin:0;"></ul>
    </div>

    <div class="wave"></div>

    <div id="resultModal" class="modal">
        <div class="modal-content">
            <h2>Terima Kasih</h2>
            <img id="rewardImage" src="images/karakter.png" width="120">
            <p>Score Anda: <span id="finalScore">0</span></p>
            <p id="finalReward"></p>
            <button id="restartBtn">Restart</button>
        </div>
    </div>

    <script>
        const startBtn = document.getElementById('startBtn');
        const message = document.getElementById('message');
        const scoreEl = document.getElementById('score');
        const resultModal = document.getElementById('resultModal');
        const finalScoreEl = document.getElementById('finalScore');
        const finalRewardEl = document.getElementById('finalReward');
        const progressFill = document.getElementById('progressFill');

        let isPlaying = false;
        let score = 0;
        let audioContext, analyser, dataArray, micSource;
        let gameStartTime = 0;
        let gameDuration = 5;
        let rafId = null;

        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                startBtn.click();
            }, 500);
        });

        function updateGiftIcon(score) {
            document.querySelectorAll('.icon-list img').forEach(img => {
                img.classList.remove('active', 'pop');
            });

            let activeId;

            if (score >= 1000) activeId = 'i-quest';
            else if (score >= 800) activeId = 'i-gold';
            else if (score >= 500) activeId = 'i-silver';
            else if (score >= 200) activeId = 'i-bronze';

            if (activeId) {
                const icon = document.getElementById(activeId);
                icon.classList.add('active', 'pop');
            }
        }

        function updateProgressBar() {
            let percent = Math.min((score / 1000) * 100, 100);
            progressFill.style.height = percent + "%";
        }

        function getReward(score) {
            if (score >= 1000) return "Quest Dapat!";
            else if (score >= 800) return "Gold Dapat!";
            else if (score >= 500) return "Silver Dapat!";
            else if (score >= 200) return "Bronze Dapat!";
            else return "Belum dapat hadiah";
        }

        startBtn.addEventListener('click', async () => {
            if (isPlaying) return;

            isPlaying = true;
            score = 0;
            scoreEl.innerText = score;
            progressFill.style.height = "0%";
            message.innerText = "Mulai! Teriakkan sekencang mungkin!";
            startBtn.disabled = true;
            startBtn.innerText = "Playing...";

            try {
                await startMic();
                gameStartTime = Date.now();
                detectSound();
            } catch {
                message.innerText = "Izinkan akses mikrofon.";
            }
        });

        async function startMic() {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            audioContext = new (window.AudioContext || window.webkitAudioContext)();
            micSource = audioContext.createMediaStreamSource(stream);
            analyser = audioContext.createAnalyser();
            analyser.fftSize = 2048;
            micSource.connect(analyser);
            dataArray = new Uint8Array(analyser.frequencyBinCount);
        }

        function detectSound() {
            analyser.getByteTimeDomainData(dataArray);

            let sum = 0;
            for (let i = 0; i < dataArray.length; i++) {
                let v = (dataArray[i] - 128) / 128;
                sum += v * v;
            }
            let rms = Math.sqrt(sum / dataArray.length);

            let level = Math.min(100, Math.floor(rms * 100));

            if (level > 10) {
                score += Math.round(level / 10);
                scoreEl.innerText = score;
                updateProgressBar();
                updateGiftIcon(score);

                message.innerText = "TERIAK LAGI!";
            } else {
                message.innerText = "Suara terlalu kecil";
            }

            const elapsed = (Date.now() - gameStartTime) / 1000;
            if (elapsed >= gameDuration) {
                endGame();
                return;
            }

            rafId = requestAnimationFrame(detectSound);
        }

        function endGame() {
            isPlaying = false;
            if (rafId) cancelAnimationFrame(rafId);
            try { audioContext.close(); } catch {}

            finalScoreEl.innerText = score;
            finalRewardEl.innerText = getReward(score);
            const rewardImage = document.getElementById('rewardImage');
            if (score >= 1000) rewardImage.src = "images/Q.png";
            else if (score >= 800) rewardImage.src = "images/G.png";
            else if (score >= 500) rewardImage.src = "images/S.png";
            else if (score >= 200) rewardImage.src = "images/B.png";
            else rewardImage.src = "images/karakter.png";

            resultModal.classList.add("show");

            sendScore(score);
        }

        async function sendScore(scoreValue) {
            const playerName = localStorage.getItem('playerName');
            try {
                await fetch('/submit-score', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        score: scoreValue,
                        name: playerName
                    })
                });
                console.log("Respon dari server:", result);
            } catch (e) {
                console.error('Error submitting score:', e);
            }
        }

        document.getElementById("restartBtn").addEventListener("click", function () {
            window.location.href = "/opening";
        });
    </script>

</body>
</html>