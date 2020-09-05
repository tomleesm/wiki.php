var articleContent = document.getElementById('readArticleContent').innerHTML;

// 產生預覽
document.getElementById('readArticleContent').innerHTML = md.render(articleContent);
