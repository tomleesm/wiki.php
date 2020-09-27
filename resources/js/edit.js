const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const editor = document.querySelector('#editArticleContent');
var simplemde = new SimpleMDE({
  element: editor
});
simplemde.codemirror.on('keyup', function() {
    refreshPreview(simplemde.value());
});
// 載入頁面和輸入時，更新編輯預覽
refreshPreview(simplemde.value());


function refreshPreview(markdown) {
  // 抓取編輯條目的 textarea 的值
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

window.addEventListener("drop", function (e) {
  e = e || event;
  e.preventDefault();
});
simplemde.codemirror.on('drop', function(editor, event) {
  event.preventDefault();

  // 顯示上傳通知
  document.querySelector('.uploading.notification').classList.remove('d-none');
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
    // 隱藏上傳通知
    document.querySelector('.uploading.notification').classList.add('d-none');
    // 回傳 json 物件
     return response.json();
  }).then(function ( image ) {
    // 組成 markdown 圖片語法
    const imageSyntax = '![' + image.originalName + '](/images/' + image.id + ')';
    // 圖片語法新增到輸入區
    insertSyntax(imageSyntax);
    // 更新預覽
    refreshPreview(simplemde.value());
  });
});

// 新增字串到輸入區，並選取之前選取的範圍或游標位置
function insertSyntax(markdown) {
  // 回傳 CodeMirror 物件，以下都是用 CodeMirror API
  var cm = simplemde.codemirror;
  // 鍵盤游標選取的位置：json 物件 { line: 行的索引值, ch: 該行的字元索引值 }
  var startPoint = cm.getCursor('start'); // 選取的開頭
  var endPoint = cm.getCursor('end'); // 選取的結尾

  // replaceRange(要取代的文字, 選取的開頭位置, 選取的結尾位置): 取代選取範圍的文字
  // 只有選取的開頭位置，則新增文字到該位置
  // 在此設定爲新增文字到選取的結尾位置
  cm.replaceRange(markdown, {
    line: endPoint.line,
    ch: endPoint.ch
  });

  // 設定選取範圍
  cm.setSelection(startPoint, endPoint);
  // 聚焦輸入的 textarea
  cm.focus();
}
