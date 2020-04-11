const wikilinks = require('@tomleesm/markdown-it-wikilinks')({
  makeAllLinksAbsolute: true,
  baseURL: '/article/',
  uriSuffix: '',
  htmlAttributes: {
    'class': 'wikilink'
  }
});

md = require('markdown-it')({
  html:         false,
  langPrefix:   'highlight highlight-source-', // 配合 Markdown CSS
  linkify:      true,
  typographer:  true,
}).use(wikilinks);
