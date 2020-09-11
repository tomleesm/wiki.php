// 載入頁面和輸入時，更新編輯預覽
refreshPreview();

document.getElementById('editArticleContent').addEventListener('keyup', function() {
    refreshPreview();
});

function refreshPreview() {
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  // 抓取編輯條目的 textarea 的值
  const markdown = document.getElementById('editArticleContent').value;
  var formData = new FormData();
  formData.append('markdown', markdown);

  fetch('/render-markdown', {
    method: 'POST',
    headers: new Headers({
        'X-CSRF-TOKEN': token
    }),
    body: formData
  }).then(function( response ) {
     return response.text();
  }).then(function ( text ) {
     document.querySelector('.preview').innerHTML = text;
  }).then(function () {
    // 用 Fetch callback 確保執行完 AJAX 後才載入 prism.js
    var script = document.createElement("script");
    script.src = '/js/prism.js';
    document.body.appendChild(script);
  });
}
