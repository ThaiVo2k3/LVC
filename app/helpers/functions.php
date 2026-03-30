<?php
function taoSlug($text)
{
    // Chuyển về chữ thường
    $text = mb_strtolower($text, 'UTF-8');

    // Bảng chuyển đổi ký tự tiếng Việt
    $unicode = array(
        'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd' => 'đ',
        'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i' => 'í|ì|ỉ|ĩ|ị',
        'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
    );

    foreach ($unicode as $nonUnicode => $uni) {
        $text = preg_replace("/($uni)/i", $nonUnicode, $text);
    }

    // Xóa ký tự đặc biệt, chỉ giữ chữ, số, dấu gạch ngang
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    $text = trim($text, '-');

    return $text;
}

function urlSanPham($ten, $id)
{
    return '/camera-quan-sat/' . taoSlug($ten) . '-' . $id;
}

function layIdTuSlug($slug)
{
    $parts = explode('-', $slug);
    return (int)end($parts);
}
function formatVND($number)
{
    return number_format((float)$number, 0, ',', '.') . ' ₫';
}
function generateID($length = 10)
{
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $result = '';

    for ($i = 0; $i < $length; $i++) {
        $result .= $characters[random_int(0, strlen($characters) - 1)];
    }

    return $result;
}
function e($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
