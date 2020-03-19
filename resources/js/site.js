var MarkdownIt = require('markdown-it'),
  md = new MarkdownIt();

var result = md.render('# markdown-it rulezz!');
document.querySelector('.article.content').innerHTML = result;
