<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SCREAM GAME WIKRAMA</title>

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

        .sidebar {
            position: absolute;
            left: 50px;
            top: 40px;
            width: 120px;
            height: 80%;
            border: 2px solid #000;
            border-radius: 20px;
        }

        .icon-list {
            position: absolute;
            left: 130px;
            top: 90px;
            display: flex;
            flex-direction: column;
            gap: 100px;
        }

        .icon-list img {
            width: 45px;
            height: 45px;
            opacity: 0.8;
            transition: 0.25s;
        }

        .icon-list img.active {
            opacity: 1;
            transform: scale(1.2);
        }

        .title {
            text-align: center;
            margin-top: 120px;
            font-size: 70px;
            font-weight: 800;
            color: #0b0b0b;
            letter-spacing: 3px;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.35);
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
            border-radius: 12px;
            width: 300px;
            text-align: center;
        }

        #restartBtn {
            background-color: #123786;
            color: #ffffff;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
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

        .icon-list img.pop {
            animation: pop 0.3s ease;
        }

    </style>
    <div class="sidebar"></div>

    <div class="icon-list">
        <img id="i-quest" src="images/1.png">
        <img id="i-gold" src="images/2.png">
        <img id="i-silver" src="images/3.png">
        <img id="i-bronze" src="images/4.png">
    </div>

    <div class="score-box">
        <div>SCORE : <span id="score">0</span></div>
        <img src="images/wikrama.png">
    </div>

    <div id="timer">00</div>
    <div class="title">TERIAK SEKARANG</div>
    <div id="message">Gak Kedengeran</div>
    <button id="startBtn">TERIAK SEKARANG</button>
    <div class="wave"></div>

    <div id="resultModal" class="modal">
        <div class="modal-content">
            <h2>Terima Kasih</h2>
            <img src="images/karakter.png" width="120">
            <p>Score Anda: <span id="finalScore">0</span></p>
            <p id="finalReward"></p>
            <button id="restartBtn">Restart</button>
        </div>
    </div>

    <script>
        const startBtn = document.getElementById('startBtn');
        const message = document.getElementById('message');
        const scoreEl = document.getElementById('score');
        const timerEl = document.getElementById('timer');
        const resultModal = document.getElementById('resultModal');
        const finalScoreEl = document.getElementById('finalScore');
        const finalRewardEl = document.getElementById('finalReward');
        const restartBtn = document.getElementById('restartBtn');

        let isPlaying = false;
        let score = 0;
        let audioContext, analyser, dataArray, micSource;
        let gameStartTime = 0;
        let gameDuration = 10;
        let rafId = null;

        function pad(n) { return n < 10 ? '0' + n : '' + n; }

        function updateGiftIcon(level) {
            document.querySelectorAll('.icon-list img').forEach(img => {
                img.classList.remove('active', 'pop');
            });

            let activeId;
            if (level <= 20) activeId = 'i-quest';
            else if (level <= 40) activeId = 'i-gold';
            else if (level <= 70) activeId = 'i-silver';
            else activeId = 'i-bronze';

            const activeIcon = document.getElementById(activeId);
            activeIcon.classList.add('active', 'pop');
        }

        function getLevelMessage(level) {
            if (level <= 20) return "Quest Dapat!";
            else if (level <= 40) return "Gold Dapat!";
            else if (level <= 70) return "Silver Dapat!";
            else return "Bronze Dapat!";
        }

        startBtn.addEventListener('click', async function () {
            if (isPlaying) return;

            isPlaying = true;
            score = 0;
            scoreEl.innerText = score;
            message.innerText = 'Mulai! Teriakkan sekencang mungkin!';
            startBtn.disabled = true;
            startBtn.innerText = 'Playing...';
            finalRewardEl.innerText = '';

            try {
                await startMic();
                gameStartTime = Date.now();
                animateTimer();
                detectSound();
            } catch (err) {
                console.error(err);
                message.innerText = 'Tidak dapat mengakses mikrofon. Izinkan akses mikrofon.';
                stopGame();
            }
        });

        restartBtn.addEventListener('click', function () {
            resultModal.classList.remove('show');
            startBtn.disabled = false;
            startBtn.innerText = 'TERIAK SEKARANG';
            scoreEl.innerText = 0;
            message.innerText = 'Gak Kedengeran';
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

            let level = Math.min(100, Math.floor(rms * 300));

            updateGiftIcon(level);

            if (level > 10) {
                score += Math.round(level / 10);
                scoreEl.innerText = score;
                message.innerText = getLevelMessage(level);
            } else {
                message.innerText = 'Suara Kamu Kurang Kedengeran';
            }

            const elapsed = (Date.now() - gameStartTime) / 1000;
            if (elapsed >= gameDuration) {
                endGame();
                return;
            }
            rafId = requestAnimationFrame(detectSound);
        }

        function animateTimer() {
            const elapsed = Math.floor((Date.now() - gameStartTime) / 1000);
            const remain = Math.max(0, gameDuration - elapsed);
            timerEl.innerText = pad(remain);
            if (remain > 0) {
                setTimeout(animateTimer, 250);
            }
        }

        function endGame() {
            isPlaying = false;
            if (rafId) cancelAnimationFrame(rafId);

            try {
                if (audioContext && audioContext.state !== 'closed')
                    audioContext.close();
            } catch { }

            finalScoreEl.innerText = score;

            let lastLevel = Math.min(100, score * 10);
            finalRewardEl.innerText = "Hadiah Terakhir: " + getLevelMessage(lastLevel);

            resultModal.classList.add('show');
        }

        function stopGame() {
            isPlaying = false;
            startBtn.disabled = false;
            startBtn.innerText = 'TERIAK SEKARANG';
            if (rafId) cancelAnimationFrame(rafId);
            try {
                if (audioContext && audioContext.state !== 'closed')
                    audioContext.close();
            } catch { }
        }
    </script>

    </body>

</html>