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
  }).then(function ( image ) {
    // 組成 markdown 圖片語法
    const mdString = '![' + image.originalName + '](/images/' + image.id + ')';
    // 圖片語法新增到輸入區
    insertSyntax(mdString);
    // 更新預覽
    refreshPreview();
  });
});

// 新增標籤或字串到輸入區，並選取之前選取的範圍或游標位置
// 參考 https://developer.mozilla.org/en-US/docs/Web/API/HTMLTextAreaElement#Examples
function insertSyntax(sStartTag, sEndTag) {
  var textarea = document.querySelector('.edit textarea');
      // 選取範圍的索引值開頭
      nSelStart = textarea.selectionStart,
      // 選取範圍的索引值結尾
      nSelEnd = textarea.selectionEnd,
      sOldText = textarea.value;

                       // 從 textarea 內容的開頭，一直到目前選取範圍的開頭之前
      textarea.value = sOldText.substring(0, nSelStart) +
                       // 如果要新增的是成對標籤，則爲 <tag>目前選取範圍</tag>，如果是單一標籤，則爲 <tag>
                       (arguments.length > 1 ? sStartTag + sOldText.substring(nSelStart, nSelEnd) + sEndTag : sStartTag) +
                       // 從目前選取範圍的開頭，一直到 textarea 內容的結尾
                       sOldText.substring(nSelEnd);

                  // 如果新增的是成對標籤或沒有選取範圍，則從目前的選取範圍開頭或游標位置加上第一個標籤的字串長度
                  // 如果新增的是單一標籤或有選取範圍，則爲選取範圍的開頭或目前游標位置
      var start = (arguments.length > 1 || nSelStart === nSelEnd) ? nSelStart + sStartTag.length : nSelStart,
                  // 如果新增的是成對標籤，則抓取選取範圍結尾，否則抓取選取範圍開頭
                  // 再加上第一個標籤的字串長度
          end   = (arguments.length > 1 ? nSelEnd : nSelStart) + sStartTag.length;
      // 修改選取範圍或游標位置
      textarea.setSelectionRange(start, end);
      textarea.focus();
}
