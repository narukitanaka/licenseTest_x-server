window.verifyLicense = function (licenseKey) {
  console.log("ライセンスキーを検証中:", licenseKey);

  // CSSを動的に読み込む
  const link = document.createElement("link");
  link.rel = "stylesheet";
  link.href = "https://gh-check.com/license-test/license-warning.css";
  document.head.appendChild(link);

  // 警告ポップアップを作成して初期は非表示
  const popup = document.createElement("div");
  popup.className = "license-warning_wrap";
  popup.style.display = "none";
  popup.innerHTML = `
      <div class="warning-contents">
        <p>このテーマはライセンス購入者のみが使用できます</p>
      </div>
  `;
  document.body.appendChild(popup);

  // サーバーにライセンスキーを送信して検証
  fetch("https://gh-check.com/license-test/vertify_license.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: new URLSearchParams({ license_key: licenseKey }),
  })
    .then((response) => response.text())
    .then((result) => {
      console.log("サーバーからの応答:", result);
      if (result.trim() === "valid") {
        console.log("ライセンスが有効です。ポップアップを表示しません。");
      } else {
        console.log("ライセンスが無効です。ポップアップを表示します。");
        popup.style.setProperty("display", "block", "important"); // ライセンスが無効ならポップアップを表示
      }
    })
    .catch((error) => {
      console.error("ライセンスの検証に失敗しました:", error);
      popup.style.setProperty("display", "block", "important"); // 検証に失敗した場合もポップアップを表示
    });
};
