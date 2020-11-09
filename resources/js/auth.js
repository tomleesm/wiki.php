const changeRoleModal = new BSN.Modal('#change-role-modal');
// 切換 role 選單時
document.querySelector('.role.option').addEventListener('change', function(event) {
    // 顯示 modal
    changeRoleModal.show();
});
