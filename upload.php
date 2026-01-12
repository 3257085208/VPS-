<?php
// upload.php - 简单的图床中转代理
header('Content-Type: application/json');

// --- 配置区域 ---
// 你的图床 Token 
$TOKEN = '这里填写图床Token'; 
// 你的图床上传 API 地址
$API_URL = '这里填写你的图床API地址';
// ----------------

// 检查是否有文件上传
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => '没有检测到文件上传']);
    exit;
}

// 准备转发给图床的数据
$file = $_FILES['file'];
$cfile = new CURLFile($file['tmp_name'], $file['type'], $file['name']);
$data = ['file' => $cfile];

// 初始化 cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $API_URL);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // VPS上为了防报错，跳过SSL证书检查
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

// 设置请求头 (Token 在这里发送，前端看不见)
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $TOKEN,
    'Accept: application/json'
]);

// 执行请求
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// 处理结果
if ($error) {
    http_response_code(500);
    echo json_encode(['error' => 'VPS 转发失败: ' . $error]);
} else {
    // 原样返回图床的结果给前端
    http_response_code($http_code);
    echo $response;
}
?>
