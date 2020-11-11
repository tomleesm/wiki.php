const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const editor = document.querySelector('#editArticleContent');

var simplemde = new SimpleMDE({
  element: editor,
  shortcuts: {
    // 取消預覽和 side by side 的快速鍵
    "togglePreview":    null,
    "toggleSideBySide": null,
  },
  toolbar: [
    {
      name:      "undo",
      action:    SimpleMDE.undo,
      className: "fa fa-undo no-disable",
      title:     "Undo"
    },
    {
      name:      "redo",
      action:    SimpleMDE.redo,
      className: "fa fa-repeat no-disable",
      title:     "Redo"
    },
    "|", // 分隔線
    {
      name:      "bold",
      action:    SimpleMDE.toggleBold,
      className: "fa fa-bold",
      title:     "Bold",
    },
    {
      name:      "italic",
      action:    SimpleMDE.toggleItalic,
      className: "fa fa-italic",
      title:     "Italic",
    },
    {
      name:      "strikethrough",
      action:    SimpleMDE.toggleStrikethrough,
      className: "fa fa-strikethrough",
      title:     "Strikethrough",
    },
    "|", // 分隔線
    {
      name:      "heading-1",
      action:    SimpleMDE.toggleHeading1,
      className: "fa fa-header fa-header-x fa-header-1",
      title:     "Big Heading",
    },
    {
      name:      "heading-2",
      action:    SimpleMDE.toggleHeading2,
      className: "fa fa-header fa-header-x fa-header-2",
      title:     "Medium Heading",
    },
    {
      name:      "heading-3",
      action:    SimpleMDE.toggleHeading3,
      className: "fa fa-header fa-header-x fa-header-3",
      title:     "Small Heading",
    },
    {
      name:      "heading-smaller",
      action:    SimpleMDE.toggleHeadingSmaller,
      className: "fa fa-header",
      title:     "Smaller Heading",
    },
    {
      name:      "heading-bigger",
      action:    SimpleMDE.toggleHeadingBigger,
      className: "fa fa-lg fa-header",
      title:     "Bigger Heading",
    },
    "|", // 分隔線
    {
      name:      "code",
      action:    SimpleMDE.toggleCodeBlock,
      className: "fa fa-code",
      title:     "Code",
    },
    {
      name:      "quote",
      action:    SimpleMDE.toggleBlockquote,
      className: "fa fa-quote-left",
      title:     "Quote",
    },
    {
      name:      "unordered-list",
      action:    SimpleMDE.toggleUnorderedList,
      className: "fa fa-list-ul",
      title:     "Generic List",
    },
    {
      name:      "ordered-list",
      action:    SimpleMDE.toggleOrderedList,
      className: "fa fa-list-ol",
      title:     "Numbered List",
    },
    {
      name:      "link",
      action:    SimpleMDE.drawLink,
      className: "fa fa-link",
      title:     "Create Link",
    },
    {
      name:      "image",
      action:    addImage,
      className: "fa fa-picture-o",
      title:     "Insert Image",
    },
    {
      name:      "table",
      action:    SimpleMDE.drawTable,
      className: "fa fa-table",
      title:     "Insert Table",
    },
    {
      name:      "horizontal-rule",
      action:    SimpleMDE.drawHorizontalRule,
      className: "fa fa-minus",
      title:     "Insert Horizontal Line",
    },
    {
      name:      "clean-block",
      action:    SimpleMDE.cleanBlock,
      className: "fa fa-eraser fa-clean-block",
      title:     "Clean block",
    },
    "|", // 分隔線
    {
      name:      "fullscreen",
      action:    SimpleMDE.toggleFullScreen,
      className: "fa fa-arrows-alt no-disable no-mobile",
      title:     "Toggle Fullscreen",
    },
    {
      name:      "guide",
      action:    "https://simplemde.com/markdown-guide",
      className: "fa fa-question-circle",
      title:     "Markdown Guide",
    },
  ],
  promptURLs: true,
});

// 載入頁面和輸入時，更新編輯預覽
simplemde.codemirror.on('change', function() {
    refreshPreview(simplemde.value());
});
refreshPreview(simplemde.value());


function refreshPreview(markdown) {
  // 抓取編輯條目的 textarea 的值
  var formData = new FormData();
  formData.append('markdown', markdown);

  fetch('/preview', {
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

////////////// 拖曳圖片以上傳 /////////////////
window.addEventListener("drag", function (event) {
  event.preventDefault();
});
simplemde.codemirror.on('drop', function(editor, event) {
  event.preventDefault();

  // 抓取要拖曳上傳的檔案
  var fileList = event.dataTransfer.items;
  for(var i = 0; i < fileList.length; i++) {
    uploadImage(fileList[i].getAsFile());
  }
});

////////////// 點選工具列圖示 insert image 上傳圖片 ///////////////
var fileInput = document.querySelector('#fileDialog');
fileInput.addEventListener('change', function() {
  var fileList = this.files;
  for(var i = 0; i < fileList.length; i++) {
    uploadImage(fileList[i]);
  }
});

function addImage() {
  // 觸發檔案選取對話框
  fileInput.click();
}

// 新增字串到輸入區，並選取之前選取的範圍或游標位置
function insertSyntax(markdown) {
  // 回傳 CodeMirror 物件，以下都是用 CodeMirror API
  var cm         = simplemde.codemirror;
  // 鍵盤游標選取的位置：json 物件 { line: 行的索引值, ch: 該行的字元索引值 }
  var startPoint = cm.getCursor('start'); // 選取的開頭
  var endPoint   = cm.getCursor('end'); // 選取的結尾

  // replaceRange(要取代的文字, 選取的開頭位置, 選取的結尾位置): 取代選取範圍的文字
  // 只有選取的開頭位置，則新增文字到該位置
  // 在此設定爲新增文字到選取的結尾位置
  cm.replaceRange(markdown, {
    line: endPoint.line,
    ch:   endPoint.ch
  });

  // 設定選取範圍
  cm.setSelection(startPoint, endPoint);
  // 聚焦輸入的 textarea
  cm.focus();
}

// 上傳圖片檔，新增圖片語法後，更新預覽
// file 參數必須是 HTML API 的 File 物件，才能在 php 被 $request->file() 抓到
function uploadImage(file) {
  var formData = new FormData();
  formData.append('image', file);

  // 顯示上傳通知
  document.querySelector('.uploading.notification').classList.remove('d-none');

  fetch('/images', {
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
    const imageSyntax = '![' + image.originalName + '](/images/' + image.id + ')\n\n';
    // 圖片語法新增到輸入區
    insertSyntax(imageSyntax);
    // 更新預覽
    refreshPreview(simplemde.value());
  });
}

// 條目權限確認 modal
const articleAuthConfirmModal = new BSN.Modal('#article-auth-confirm-modal',
    {
        backdrop: 'static', // 點選 modal 周圍灰色區域不會關閉 modal
        keyboard: false // 按鍵 Esc 不會關閉 modal
    });
// 切換權限選單後，顯示確認 modal
document.querySelector('#article-auth').addEventListener('change', function(event) {
  // 抓取 article id 和 role id，加到按鈕 Yes 的 data
  const articleId = document.querySelector("input[name='article[id]']").value;
  const roleId = event.target.value;
  const buttonYes = document.querySelector('#article-auth-confirm-modal button.yes');
  buttonYes.dataset.articleId = articleId;
  buttonYes.dataset.roleId = roleId;

  // 下拉選單的文字
  const optionName = event.target[event.target.selectedIndex].text;
  const articleName = document.querySelector('.edit h3').innerText;
  const modalBody = optionName + ' can update the article "' + articleName + '" ?';
  // 設定 modal 內容
  document.querySelector('#article-auth-confirm-modal .modal-body > p').innerText = modalBody;
  // 顯示 modal
  articleAuthConfirmModal.show();
});

// 記住切換之前的選擇
document.querySelector('#article-auth').addEventListener('focus', function(event) {
  this.dataset.previousOption = event.target.value;
});

// 如果點選 modal 的 No 、 按鈕 x ，選單 <option> 切換回之前的選擇
document.querySelector('button.cancel').addEventListener('click', function() {
    changePreviousOption();
});
document.querySelector('button.no').addEventListener('click', function() {
    changePreviousOption();
});

function changePreviousOption() {
    const triggerSelect = document.querySelector('#article-auth');
    triggerSelect.value = triggerSelect.dataset.previousOption;
    delete triggerSelect.dataset.previousOption;
}

// 修改條目權限
document.querySelector('button.yes').addEventListener('click', function() {
    // 抓取 article id 和 role id
    const articleId = this.dataset.articleId;
    const roleId = this.dataset.roleId;

    // 關閉 modal
    articleAuthConfirmModal.hide();

    changeArticleAuth(articleId, roleId);
});

function changeArticleAuth(articleId, roleId) {
  // 修改條目權限
  // 注意：PUT, PATCH 加上 formData 物件，用 AJAX 傳送，後端 $request->input() 是抓不到的
  // 這是 Laravel(其實是 Symfony) 的 bug
  // 所以如果都是文字資料，改傳入 json，如下所示
  // 如果是二進位檔案，改用 POST method
  fetch('/articles/auth/' + articleId, {
    method: 'PATCH',
    headers: new Headers({
        "X-CSRF-TOKEN": token,
        "Content-Type": "application/json; charset=utf-8",
    }),
    body: JSON.stringify({roleId: roleId})
  }).then(function( response ) {
     return response.json();
  }).then(function ( result ) {
      // 顯示結果訊息
      // 設定 alert 是綠色的成功訊息
      document.querySelector('.alert').classList.add('alert-success');
      document.querySelector('.alert').innerText = result.message;
  });
}
