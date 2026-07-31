/**
 * 阿蘇あか牛ラン ── ウェブアプリの入口
 * ※このファイルは触りません。生徒が触るのは index.html だけです。
 */
function doGet() {
  return HtmlService.createHtmlOutputFromFile('index')
    .setTitle('阿蘇あか牛ラン')
    .addMetaTag('viewport', 'width=device-width, initial-scale=1')
    .setXFrameOptionsMode(HtmlService.XFrameOptionsMode.ALLOWALL);
}
