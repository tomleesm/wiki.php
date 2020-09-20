const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
// 載入頁面和輸入時，更新編輯預覽
refreshPreview();

document.getElementById('editArticleContent').addEventListener('keyup', function() {
    refreshPreview();
});

function refreshPreview() {
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

document.querySelector('.edit').addEventListener('dragstart', function(event) {
  event.dataTransfer.setData('image/*');

  event.stopPropagation();
  event.preventDefault();
});
document.querySelector('.edit').addEventListener('drop', function(event) {
  event.stopPropagation();
  event.preventDefault();

  var formData = new FormData();
  // 抓取要拖曳上傳的檔案
  // 一次只抓一個，所以是 files[0]
  const image = event.dataTransfer.files[0];
  // image 要包成 Blob 或 File 物件
  // 後端 php 才能用 $request->file('image') 抓到檔案
  const file = new File([ image ], image.name, { type: "image/*" });
  formData.append('image', file);

  fetch('/upload/image', {
    method: 'POST',
    headers: new Headers({
        'X-CSRF-TOKEN': token
    }),
    body: formData
  }).then(function( response ) {
    // 回傳 json 物件
     return response.json();
  }).then(function ( json ) {
     console.log(json);
  });
});
