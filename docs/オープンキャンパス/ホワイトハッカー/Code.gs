/**
 * パスワード解読タイム計測ツール ── ウェブアプリの入口
 * ※このファイルは触りません。生徒が触るのは index.html だけです。
 */
function doGet() {
  return HtmlService.createHtmlOutputFromFile('index')
    .setTitle('パスワード解読タイム計測ツール')
    .addMetaTag('viewport', 'width=device-width, initial-scale=1')
    .setXFrameOptionsMode(HtmlService.XFrameOptionsMode.ALLOWALL);
}
