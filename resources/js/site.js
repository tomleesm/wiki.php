var MarkdownIt = require('markdown-it'),
  md = new MarkdownIt();

// 抓取要轉換的 markdown
var articleContent = document.querySelector('.article.content').innerHTML;
// markdown 轉成 HTML
var result = md.render(articleContent);
document.querySelector('.article.content').innerHTML = result;
