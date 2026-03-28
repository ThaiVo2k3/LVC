<?php
$code   = $order['code'];
$amount = (int)$order['tong_tien'];

$qrUrl = "https://qr.sepay.vn/img?"
    . "acc=0916353946"
    . "&bank=MBBank"
    . "&amount=" . $amount
    . "&des=" . urlencode($code);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thanh toán đơn hàng #<?= $code ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .main-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .payment-card {
            background: #ffffff;
            width: 100%;
            max-width: 800px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
            overflow: hidden;
        }

        /* LEFT: QR */
        .qr-section {
            flex: 1;
            min-width: 300px;
            padding: 30px;
            text-align: center;
            border-right: 1px solid #edf2f7;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .qr-image-wrapper {
            padding: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .qr-section img {
            width: 200px;
            height: 200px;
            display: block;
        }

        .timer-box {
            width: 100%;
            margin-top: 10px;
        }

        .progress-container {
            height: 6px;
            background: #edf2f7;
            border-radius: 10px;
            overflow: hidden;
            margin-top: 8px;
        }

        #progress-bar {
            height: 100%;
            background: #3182ce;
            width: 100%;
            transition: width 1s linear;
        }

        /* RIGHT: INFO */
        .info-section {
            flex: 1.2;
            min-width: 300px;
            padding: 30px;
            background-color: #fcfcfc;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px dashed #e2e8f0;
        }

        .detail-label {
            color: #718096;
            font-size: 0.9rem;
        }

        .detail-value {
            font-weight: 700;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* COPY BUTTON & TOOLTIP */
        .btn-copy {
            position: relative;
            background: #ebf8ff;
            border: none;
            color: #3182ce;
            padding: 6px 10px;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-copy:hover {
            background: #3182ce;
            color: white;
        }

        .copy-tooltip {
            position: absolute;
            bottom: 120%;
            left: 50%;
            transform: translateX(-50%);
            background: #2d3748;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 400;
            white-space: nowrap;
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .btn-copy.copied .copy-tooltip {
            visibility: visible;
            opacity: 1;
        }

        .warning-note {
            margin-top: 20px;
            padding: 12px;
            background-color: #fffaf0;
            border-left: 4px solid #ed8936;
            font-size: 0.85rem;
            color: #7b341e;
        }

        .status-msg {
            margin-top: 15px;
            padding: 10px;
            border-radius: 6px;
            font-weight: 600;
            display: none;
            text-align: center;
        }

        .status-msg.success {
            display: block;
            background: #f0fff4;
            color: #2f855a;
            border: 1px solid #c6f6d5;
        }

        .status-msg.error {
            display: block;
            background: #fff5f5;
            color: #c53030;
            border: 1px solid #fed7d7;
        }

        @media (max-width: 650px) {
            .qr-section {
                border-right: none;
                border-bottom: 1px solid #edf2f7;
            }
        }
    </style>
</head>

<body>

    <div class="main-wrapper">
        <div class="payment-card">
            <div class="qr-section">
                <h3 style="color: #4a5568; margin-bottom: 15px;">Quét mã thanh toán</h3>
                <div class="qr-image-wrapper">
                    <img src="<?= $qrUrl ?>" alt="QR Code">
                </div>
                <div class="timer-box">
                    <div style="font-size: 0.9rem; color: #718096;">Hiệu lực: <span id="time-left" style="font-weight: 700; color: #2d3748;">60</span>s</div>
                    <div class="progress-container">
                        <div id="progress-bar"></div>
                    </div>
                </div>
                <div id="status-box" class="status-msg"></div>
            </div>

            <div class="info-section">
                <h2 style="margin-bottom: 20px; color: #2d3748;">Đơn hàng #<?= $code ?></h2>

                <div class="detail-row">
                    <span class="detail-label">Ngân hàng</span>
                    <div class="detail-value">
                        <span>MBBank</span>
                        <button class="btn-copy" onclick="copyText(this, 'MBBank')">
                            <i class="fa-regular fa-copy"></i>
                            <span class="copy-tooltip">Đã sao chép!</span>
                        </button>
                    </div>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Số tài khoản</span>
                    <div class="detail-value">
                        <span>0916353946</span>
                        <button class="btn-copy" onclick="copyText(this, '0916353946')">
                            <i class="fa-regular fa-copy"></i>
                            <span class="copy-tooltip">Đã sao chép!</span>
                        </button>
                    </div>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Số tiền</span>
                    <div class="detail-value" style="color: #e53e3e;">
                        <span><?= number_format($amount) ?></span>
                        <button class="btn-copy" onclick="copyText(this, '<?= $amount ?>')">
                            <i class="fa-regular fa-copy"></i>
                            <span class="copy-tooltip">Đã sao chép!</span>
                        </button>
                    </div>
                </div>

                <div class="detail-row" style="border-bottom: none;">
                    <span class="detail-label">Nội dung</span>
                    <div class="detail-value">
                        <span style="background: #fefcbf; padding: 2px 5px;"><?= $code ?></span>
                        <button class="btn-copy" onclick="copyText(this, '<?= $code ?>')">
                            <i class="fa-regular fa-copy"></i>
                            <span class="copy-tooltip">Đã sao chép!</span>
                        </button>
                    </div>
                </div>

                <div class="warning-note">
                    <i class="fa-solid fa-circle-exclamation"></i>Quan trọng: Chuyển chính xác nội dung <b><?= $code ?></b> để đơn hàng được duyệt tự động.
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyText(btn, text) {
            navigator.clipboard.writeText(text).then(() => {
                btn.classList.add('copied');

                setTimeout(() => {
                    btn.classList.remove('copied');
                }, 500);
            });
        }
        const orderCode = "<?= $code ?>";
        let timeLeft = 60;
        const totalTime = 60;
        const timerText = document.getElementById('time-left');
        const progressBar = document.getElementById('progress-bar');
        const statusBox = document.getElementById('status-box');

        const countdown = setInterval(() => {
            timeLeft--;
            if (timerText) timerText.textContent = timeLeft;
            if (progressBar) progressBar.style.width = (timeLeft / totalTime) * 100 + "%";

            if (timeLeft <= 0) {
                clearInterval(countdown);
                clearInterval(polling);
                updateStatus("⏰ Hết thời gian thanh toán", "error");
                cancelOrder();
            }
        }, 1000);

        const polling = setInterval(() => {
            fetch(`/payments/check-status?code=${orderCode}`)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'Đã thanh toán') {
                        clearInterval(countdown);
                        clearInterval(polling);
                        updateStatus("✅ Thanh toán thành công!", "success");
                        setTimeout(() => {
                            window.location.href = "/orders";
                        }, 2500);
                    }
                }).catch(() => {});
        }, 3000);

        function updateStatus(msg, type) {
            statusBox.textContent = msg;
            statusBox.className = "status-msg " + type;
        }

        function cancelOrder() {
            fetch('/payments/cancel', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    code: orderCode
                })
            }).finally(() => {
                setTimeout(() => {
                    window.location.href = "/orders";
                }, 4000);
            });
        }
    </script>
</body>

</html>