/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/edit.js":
/*!******************************!*\
  !*** ./resources/js/edit.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var editor = document.querySelector('#editArticleContent');
var simplemde = new SimpleMDE({
  element: editor,
  shortcuts: {
    // 取消預覽和 side by side 的快速鍵
    "togglePreview": null,
    "toggleSideBySide": null
  },
  toolbar: [{
    name: "undo",
    action: SimpleMDE.undo,
    className: "fa fa-undo no-disable",
    title: "Undo"
  }, {
    name: "redo",
    action: SimpleMDE.redo,
    className: "fa fa-repeat no-disable",
    title: "Redo"
  }, "|", {
    name: "bold",
    action: SimpleMDE.toggleBold,
    className: "fa fa-bold",
    title: "Bold"
  }, {
    name: "italic",
    action: SimpleMDE.toggleItalic,
    className: "fa fa-italic",
    title: "Italic"
  }, {
    name: "strikethrough",
    action: SimpleMDE.toggleStrikethrough,
    className: "fa fa-strikethrough",
    title: "Strikethrough"
  }, "|", {
    name: "heading-1",
    action: SimpleMDE.toggleHeading1,
    className: "fa fa-header fa-header-x fa-header-1",
    title: "Big Heading"
  }, {
    name: "heading-2",
    action: SimpleMDE.toggleHeading2,
    className: "fa fa-header fa-header-x fa-header-2",
    title: "Medium Heading"
  }, {
    name: "heading-3",
    action: SimpleMDE.toggleHeading3,
    className: "fa fa-header fa-header-x fa-header-3",
    title: "Small Heading"
  }, {
    name: "heading-smaller",
    action: SimpleMDE.toggleHeadingSmaller,
    className: "fa fa-header",
    title: "Smaller Heading"
  }, {
    name: "heading-bigger",
    action: SimpleMDE.toggleHeadingBigger,
    className: "fa fa-lg fa-header",
    title: "Bigger Heading"
  }, "|", {
    name: "code",
    action: SimpleMDE.toggleCodeBlock,
    className: "fa fa-code",
    title: "Code"
  }, {
    name: "quote",
    action: SimpleMDE.toggleBlockquote,
    className: "fa fa-quote-left",
    title: "Quote"
  }, {
    name: "unordered-list",
    action: SimpleMDE.toggleUnorderedList,
    className: "fa fa-list-ul",
    title: "Generic List"
  }, {
    name: "ordered-list",
    action: SimpleMDE.toggleOrderedList,
    className: "fa fa-list-ol",
    title: "Numbered List"
  }, {
    name: "link",
    action: SimpleMDE.drawLink,
    className: "fa fa-link",
    title: "Create Link"
  }, {
    name: "image",
    action: addImage,
    className: "fa fa-picture-o",
    title: "Insert Image"
  }, {
    name: "table",
    action: SimpleMDE.drawTable,
    className: "fa fa-table",
    title: "Insert Table"
  }, {
    name: "horizontal-rule",
    action: SimpleMDE.drawHorizontalRule,
    className: "fa fa-minus",
    title: "Insert Horizontal Line"
  }, {
    name: "clean-block",
    action: SimpleMDE.cleanBlock,
    className: "fa fa-eraser fa-clean-block",
    title: "Clean block"
  }, "|", {
    name: "fullscreen",
    action: SimpleMDE.toggleFullScreen,
    className: "fa fa-arrows-alt no-disable no-mobile",
    title: "Toggle Fullscreen"
  }, {
    name: "guide",
    action: "https://simplemde.com/markdown-guide",
    className: "fa fa-question-circle",
    title: "Markdown Guide"
  }],
  promptURLs: true
}); // 載入頁面和輸入時，更新編輯預覽

simplemde.codemirror.on('change', function () {
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
  }).then(function (response) {
    return response.text();
  }).then(function (text) {
    document.querySelector('.preview').innerHTML = text;
  }).then(function () {
    // 用 Fetch callback 確保執行完 AJAX 後才載入 prism.js
    var script = document.createElement("script");
    script.src = '/js/prism.js';
    document.body.appendChild(script);
  });
} ////////////// 拖曳圖片以上傳 /////////////////


window.addEventListener("drag", function (event) {
  event.preventDefault();
});
simplemde.codemirror.on('drop', function (editor, event) {
  event.preventDefault(); // 抓取要拖曳上傳的檔案

  var fileList = event.dataTransfer.items;

  for (var i = 0; i < fileList.length; i++) {
    uploadImage(fileList[i].getAsFile());
  }
}); ////////////// 點選工具列圖示 insert image 上傳圖片 ///////////////

var fileInput = document.querySelector('#fileDialog');
fileInput.addEventListener('change', function () {
  var fileList = this.files;

  for (var i = 0; i < fileList.length; i++) {
    uploadImage(fileList[i]);
  }
});

function addImage() {
  // 觸發檔案選取對話框
  fileInput.click();
} // 新增字串到輸入區，並選取之前選取的範圍或游標位置


function insertSyntax(markdown) {
  // 回傳 CodeMirror 物件，以下都是用 CodeMirror API
  var cm = simplemde.codemirror; // 鍵盤游標選取的位置：json 物件 { line: 行的索引值, ch: 該行的字元索引值 }

  var startPoint = cm.getCursor('start'); // 選取的開頭

  var endPoint = cm.getCursor('end'); // 選取的結尾
  // replaceRange(要取代的文字, 選取的開頭位置, 選取的結尾位置): 取代選取範圍的文字
  // 只有選取的開頭位置，則新增文字到該位置
  // 在此設定爲新增文字到選取的結尾位置

  cm.replaceRange(markdown, {
    line: endPoint.line,
    ch: endPoint.ch
  }); // 設定選取範圍

  cm.setSelection(startPoint, endPoint); // 聚焦輸入的 textarea

  cm.focus();
} // 上傳圖片檔，新增圖片語法後，更新預覽
// file 參數必須是 HTML API 的 File 物件，才能在 php 被 $request->file() 抓到


function uploadImage(file) {
  var formData = new FormData();
  formData.append('image', file); // 顯示上傳通知

  document.querySelector('.uploading.notification').classList.remove('d-none');
  fetch('/images', {
    method: 'POST',
    headers: new Headers({
      'X-CSRF-TOKEN': token
    }),
    body: formData
  }).then(function (response) {
    // 隱藏上傳通知
    document.querySelector('.uploading.notification').classList.add('d-none'); // 回傳 json 物件

    return response.json();
  }).then(function (image) {
    // 組成 markdown 圖片語法
    var imageSyntax = '![' + image.originalName + '](/images/' + image.id + ')\n\n'; // 圖片語法新增到輸入區

    insertSyntax(imageSyntax); // 更新預覽

    refreshPreview(simplemde.value());
  });
}

/***/ }),

/***/ 1:
/*!************************************!*\
  !*** multi ./resources/js/edit.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/tom/apps/wiki/resources/js/edit.js */"./resources/js/edit.js");


/***/ })

/******/ });