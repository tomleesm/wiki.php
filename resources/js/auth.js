const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
const changeRoleModal = new BSN.Modal('#change-role-modal',
    {
        backdrop: 'static', // 點選 modal 周圍灰色區域不會關閉 modal
        keyboard: false // 按鍵 Esc 不會關閉 modal
    });

// 記住切換 role 之前選擇的 role
var previousOption;
document.querySelector('.role.option').addEventListener('focus', function(event) {
    previousOption = event.target.value;
});
// 切換 role 選單時
document.querySelector('.role.option').addEventListener('change', function(event) {
    // 選擇角色選單所在的那一列
    const row = this.parentElement.parentElement.parentElement;
    // 那一列的所有 <td>
    const tds = row.getElementsByTagName('td');
    // 選取 <td> 包含的文字
    const name = tds[0].innerText;
    const loginFrom = tds[1].innerText;
    const email = tds[2].innerText;
    // 選取的 <option> 文字(Editor)和 value(2)
    const roleName = event.target[event.target.selectedIndex].text;

    const modalBody = 'Change role of '+ name + ' (' + loginFrom + ') ' + email + ' to ' + roleName + ' ?';
    // 設定 modal 內容
    document.querySelector('#change-role-modal .modal-body > p').innerText = modalBody;
    // 把 user id 和 role id 加到 modal 中，方便之後存取
    document.querySelector('#change-role-modal button.yes').dataset.userId = row.dataset.userId;
    document.querySelector('#change-role-modal button.yes').dataset.roleId = event.target.value;
    // 顯示 modal
    changeRoleModal.show();
});

// 修改使用者角色
document.querySelector('button.yes').addEventListener('click', function() {
    // 抓取 user id 和 role id
    const userId = this.dataset.userId;
    const roleId = this.dataset.roleId;

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
        "Content-Type": "application/json; charset=utf-8",
    }),
    body: JSON.stringify({roleId: roleId})
  }).then(function( response ) {
     return response.json();
  }).then(function ( result ) {
      // 關閉 modal
      changeRoleModal.hide();
      // 顯示結果訊息
      // 設定 alert 是綠色的成功訊息
      document.querySelector('.alert').classList.add('alert-success');
      document.querySelector('.alert').innerText = result.message;
  });
}
// 如果點選 modal 的 No 、 按鈕 x ，選單 <option> 切換回之前的選擇
document.querySelector('button.cancel').addEventListener('click', function() {
    changePreviousOption();
});
document.querySelector('button.no').addEventListener('click', function() {
    changePreviousOption();
});

function changePreviousOption() {
    document.querySelector('.role.option').value = previousOption;
}

const blockModal = new BSN.Modal('#block-modal');
// 點選按鈕 Block，顯示 modal
document.querySelector('.block').addEventListener('click', function(event) {
    // 選取按鈕 Block 所在的那一列
    const row = this.parentElement.parentElement;
    // 那一列的所有 <td>
    const tds = row.getElementsByTagName('td');
    // 選取 <td> 包含的文字
    const name = tds[0].innerText;
    const loginFrom = tds[1].innerText;
    const email = tds[2].innerText;

    const modalBody = 'Block user '+ name + ' (' + loginFrom + ') ' + email + ' ?';
    // 設定 modal 內容
    document.querySelector('#block-modal .modal-body > p').innerText = modalBody;
    // 顯示 modal
    blockModal.show();
    // <tr data-user-id="123"> 在 javascript dataset 的 key 是 camelCase 的 userId
    // https://developer.mozilla.org/zh-TW/docs/Web/API/HTMLElement/dataset
    console.log(row.dataset.userId);
});
