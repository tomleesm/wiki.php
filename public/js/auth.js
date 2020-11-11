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
/******/ 	return __webpack_require__(__webpack_require__.s = 2);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/auth.js":
/*!******************************!*\
  !*** ./resources/js/auth.js ***!
  \******************************/
/*! no static exports found */
/***/ (function(module, exports) {

var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var changeRoleModal = new BSN.Modal('#change-role-modal', {
  backdrop: 'static',
  // 點選 modal 周圍灰色區域不會關閉 modal
  keyboard: false // 按鍵 Esc 不會關閉 modal

}); // 記住切換 role 之前選擇的 role

document.querySelectorAll('.role.option').forEach(function (option) {
  option.addEventListener('focus', function (event) {
    this.dataset.previousOption = event.target.value;
  });
}); // 切換 role 選單時

document.querySelectorAll('.role.option').forEach(function (option) {
  option.addEventListener('change', function (event) {
    // 選擇角色選單所在的那一列
    var row = this.parentElement.parentElement.parentElement; // 那一列的所有 <td>

    var tds = row.getElementsByTagName('td'); // 選取 <td> 包含的文字

    var name = tds[0].innerText;
    var loginFrom = tds[1].innerText;
    var email = tds[2].innerText; // 選取的 <option> 文字(Editor)和 value(2)

    var roleName = event.target[event.target.selectedIndex].text;
    var modalBody = 'Change role of ' + name + ' (' + loginFrom + ') ' + email + ' to ' + roleName + ' ?'; // 設定 modal 內容

    document.querySelector('#change-role-modal .modal-body > p').innerText = modalBody; // 把 user id 和 role id 加到 modal 中，方便之後存取

    document.querySelector('#change-role-modal button.yes').dataset.userId = row.dataset.userId;
    document.querySelector('#change-role-modal button.yes').dataset.roleId = event.target.value; // 顯示 modal

    changeRoleModal.show(); // 標示觸發 change role modal 的是哪個 <select> 選單

    this.dataset.triggerModal = "true";
  });
}); // 修改使用者角色

document.querySelector('button.yes').addEventListener('click', function () {
  // 抓取 user id 和 role id
  var userId = this.dataset.userId;
  var roleId = this.dataset.roleId;
  changeRole(userId, roleId);
});

function changeRole(userId, roleId) {
  // 修改使用者角色
  // 注意：PUT 加上 formData 物件，用 AJAX 傳送，後端 $request->input() 是抓不到的
  // 這是 Laravel(其實是 Symfony) 的 bug
  // 所以如果都是文字資料，改傳入 json，如下所示
  // 如果是二進位檔案，改用 POST method
  fetch('/user/auth/' + userId, {
    method: 'PUT',
    headers: new Headers({
      "X-CSRF-TOKEN": token,
      "Content-Type": "application/json; charset=utf-8"
    }),
    body: JSON.stringify({
      roleId: roleId
    })
  }).then(function (response) {
    return response.json();
  }).then(function (result) {
    // 關閉 modal
    changeRoleModal.hide(); // 顯示結果訊息
    // 設定 alert 是綠色的成功訊息

    document.querySelector('.alert').classList.add('alert-success');
    document.querySelector('.alert').innerText = result.message;
  });
} // 如果點選 modal 的 No 、 按鈕 x ，選單 <option> 切換回之前的選擇


document.querySelector('button.cancel').addEventListener('click', function () {
  changePreviousOption();
});
document.querySelector('button.no').addEventListener('click', function () {
  changePreviousOption();
});

function changePreviousOption() {
  var triggerSelect = document.querySelector('select[data-trigger-modal]');
  triggerSelect.value = triggerSelect.dataset.previousOption;
  delete triggerSelect.dataset.triggerModal;
  delete triggerSelect.dataset.previousOption;
}

var blockModal = new BSN.Modal('#block-modal'); // 點選按鈕 Block，顯示 modal

document.querySelector('.block').addEventListener('click', function (event) {
  // 選取按鈕 Block 所在的那一列
  var row = this.parentElement.parentElement; // 那一列的所有 <td>

  var tds = row.getElementsByTagName('td'); // 選取 <td> 包含的文字

  var name = tds[0].innerText;
  var loginFrom = tds[1].innerText;
  var email = tds[2].innerText;
  var modalBody = 'Block user ' + name + ' (' + loginFrom + ') ' + email + ' ?'; // 設定 modal 內容

  document.querySelector('#block-modal .modal-body > p').innerText = modalBody; // 顯示 modal

  blockModal.show(); // <tr data-user-id="123"> 在 javascript dataset 的 key 是 camelCase 的 userId
  // https://developer.mozilla.org/zh-TW/docs/Web/API/HTMLElement/dataset

  console.log(row.dataset.userId);
});

/***/ }),

/***/ 2:
/*!************************************!*\
  !*** multi ./resources/js/auth.js ***!
  \************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /home/tom/apps/wiki.php/resources/js/auth.js */"./resources/js/auth.js");


/***/ })

/******/ });