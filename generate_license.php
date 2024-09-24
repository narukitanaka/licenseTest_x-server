<?php
// データベース接続情報
$host = 'localhost';
$dbname = 'ghdemo_licensetest';
$username = 'ghdemo_userlic';
$password = 'De5rFTU7';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// ライセンスキーを16桁で生成する関数
function generateLicenseKey($length = 16) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// ライセンスキーを生成
$license_key = generateLicenseKey();

// ライセンスキーをデータベースに保存
$sql = "INSERT INTO license_keys (license_key, status) VALUES (:license_key, 0)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':license_key', $license_key, PDO::PARAM_STR);

if ($stmt->execute()) {
    echo $license_key; // 成功した場合、生成したライセンスキーを返す
} else {
    echo 'エラーが発生しました';
}
?>