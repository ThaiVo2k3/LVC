<?php
$code   = $order['code'];
$amount = (int)$order['total_price'];

$qrUrl = "https://qr.sepay.vn/img?"
    . "acc=0916353946"
    . "&bank=MBBank"
    . "&amount=" . $amount
    . "&des=" . urlencode($code);
?>

<div class="min-h-screen bg-gray-100 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl grid md:grid-cols-2 overflow-hidden">

        <!-- LEFT: QR -->
        <div class="p-6 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r">

            <h3 class="text-gray-600 font-semibold mb-4">Quét mã thanh toán</h3>

            <div class="relative">
                <img src="<?= $qrUrl ?>" class="w-52 h-52 rounded-lg border p-2 bg-white">

                <!-- Scan animation -->
                <div class="absolute inset-0 overflow-hidden rounded-lg">
                    <div class="scan-line"></div>
                </div>
            </div>

            <!-- TIMER -->
            <div class="w-full mt-4 text-center">
                <p class="text-sm text-gray-500">
                    Hiệu lực:
                    <span id="time-left" class="font-bold text-gray-800">60</span>s
                </p>

                <div class="w-full h-2 bg-gray-200 rounded mt-2">
                    <div id="progress-bar" class="h-full bg-blue-500 rounded"></div>
                </div>
            </div>

            <div id="status-box" class="mt-3 w-full text-center hidden"></div>
        </div>

        <!-- RIGHT: INFO -->
        <div class="p-6 bg-gray-50">

            <h2 class="text-xl font-bold text-gray-800 mb-5">
                Đơn hàng #<?= $code ?>
            </h2>

            <!-- ROW -->
            <?php
            function row($label, $value, $copy)
            {
                echo "
                <div class='flex justify-between items-center py-3 border-b'>
                    <span class='text-gray-500 text-sm'>$label</span>
                    <div class='flex items-center gap-2 font-semibold'>
                        <span>$value</span>
                        <button onclick=\"copyText(this, '$copy')\" 
                            class='bg-blue-100 text-blue-600 px-2 py-1 rounded hover:bg-blue-600 hover:text-white transition'>
                            📋
                        </button>
                    </div>
                </div>";
            }

            row("Ngân hàng", "MBBank", "MBBank");
            row("Số tài khoản", "0916353946", "0916353946");
            row("Số tiền", number_format($amount), $amount);
            row("Nội dung", "<span class='bg-yellow-200 px-2 rounded'>$code</span>", $code);
            ?>

            <div class="mt-5 p-3 bg-yellow-50 border-l-4 border-yellow-400 text-sm text-yellow-800 rounded">
                ⚠️ Chuyển đúng nội dung <b><?= $code ?></b> để hệ thống xác nhận tự động
            </div>

        </div>
    </div>
</div>

<style>
    /* Scan animation */
    .scan-line {
        position: absolute;
        width: 100%;
        height: 3px;
        background: rgba(59, 130, 246, 0.8);
        top: 0;
        animation: scan 2s linear infinite;
    }

    @keyframes scan {
        0% {
            top: 0;
        }

        50% {
            top: 100%;
        }

        100% {
            top: 0;
        }
    }
</style>

<script>
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
                if (data.message === 'Đã thanh toán') {
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
        statusBox.classList.remove("hidden");

        if (type === "success") {
            statusBox.className = "mt-3 p-2 rounded bg-green-100 text-green-700 font-semibold";
        } else {
            statusBox.className = "mt-3 p-2 rounded bg-red-100 text-red-700 font-semibold";
        }

        statusBox.textContent = msg;
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