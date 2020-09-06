// 載入頁面和輸入時，更新編輯預覽
refreshPreview();

document.getElementById('editArticleContent').addEventListener('keyup', function() {
    refreshPreview();
});

function refreshPreview() {
  // 抓取編輯條目的 textarea 的值
  var articleContent = document.getElementById('editArticleContent').value;

  // 產生預覽
  // document.querySelector('.preview').innerHTML = md.render(articleContent);
}
