MarkdownIt = require('markdown-it'),
  md = new MarkdownIt({
    html:         false,
    langPrefix:   'highlight highlight-source-', // 配合 Markdown CSS
    linkify:      true,
    typographer:  true,
  });
