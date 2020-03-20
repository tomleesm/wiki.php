var MarkdownIt = require('markdown-it'),
  md = new MarkdownIt({
    html:         false,
    langPrefix:   'highlight highlight-source-', // 配合 Markdown CSS
    linkify:      true,
    typographer:  true,
  });

// 抓取要轉換的 markdown
var articleContent = document.querySelector('.article.content').innerHTML;
// markdown 轉成 HTML
var result = md.render(articleContent);
document.querySelector('.article.content').innerHTML = result;
