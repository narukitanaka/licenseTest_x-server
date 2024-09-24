<?php
// CORSヘッダーを追加
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

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

// POSTされたライセンスキーを取得
if (isset($_POST['license_key'])) {
    $license_key = $_POST['license_key'];

    // データベースでライセンスキーを検索
    $sql = "SELECT * FROM license_keys WHERE license_key = :license_key";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':license_key', $license_key, PDO::PARAM_STR);
    $stmt->execute();

    // クエリ結果の取得
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        // ライセンスが存在する場合、statusを1に更新
        $update_sql = "UPDATE license_keys SET status = 1, updated_at = NOW() WHERE license_key = :license_key";
        $update_stmt = $pdo->prepare($update_sql);
        $update_stmt->bindParam(':license_key', $license_key, PDO::PARAM_STR);
        if ($update_stmt->execute()) {
            // 更新が成功したら「valid」を返す
            echo 'valid';
        } else {
            // 更新に失敗した場合はエラーとして無効と返す
            echo 'invalid';
        }
    } else {
        // ライセンスが見つからなければ「invalid」を返す
        echo 'invalid';
    }
} else {
    // ライセンスキーがPOSTされていない場合
    echo 'no_key';
}
?>