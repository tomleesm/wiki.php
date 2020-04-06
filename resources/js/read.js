// 抓取要轉換的 markdown
var articleContent = document.querySelector('.article.content').innerHTML;
// markdown 轉成 HTML
var result = md.render(articleContent);
// 顯示在頁面上
document.querySelector('.article.content').innerHTML = result;
