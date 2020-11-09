const changeRoleModal = new BSN.Modal('#change-role-modal');

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
    const roleValue = event.target.value;

    const modalBody = 'Change role of '+ name + ' (' + loginFrom + ') ' + email + ' to ' + roleName + ' ?';
    // 設定 modal 內容
    document.querySelector('.modal-body > p').innerText = modalBody;
    // 顯示 modal
    changeRoleModal.show();
});

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
