document.querySelector('#search-keyword').addEventListener('focus', function() {
    document.querySelector('#search-jump-to').classList.remove('d-none');
    document.querySelector('#search-jump-to-keyword').focus();
});
