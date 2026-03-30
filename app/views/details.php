<h2><?= $product['name'] ?></h2>

<div style="display:flex; gap:30px;">
    <div style="width:40%;">
        <img src="uploads/<?= $product['image'] ?>" width="100%">
    </div>

    <div style="width:60%;">
        <h3>Giá: <?= number_format($product['price']) ?>đ</h3>

        <p><?= $product['description'] ?></p>

        <h4>Thông số kỹ thuật:</h4>
        <ul>
            <li>Độ phân giải: <?= $product['camera_resolution'] ?></li>
            <li>FPS: <?= $product['camera_fps'] ?></li>
            <li>Ống kính: <?= $product['camera_lens'] ?></li>
        </ul>

        <button>Thêm vào giỏ hàng</button>
    </div>
</div>