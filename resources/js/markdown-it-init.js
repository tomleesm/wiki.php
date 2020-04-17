md = require('markdown-it')({
  html:         false,
  langPrefix:   'highlight highlight-source-', // 配合 Markdown CSS
  linkify:      true,
  typographer:  true,
});

const wikilinks = require('@tomleesm/markdown-it-wikilinks')({
  makeAllLinksAbsolute: true,
  baseURL: '/read/',
  uriSuffix: '?parent=' + document.querySelector('meta[name="breadcrumb-parent"]').content,
  htmlAttributes: {
    'class': 'wikilink'
  }
});
const wikianchor = require('markdown-it-anchor');
const wikitoc = require('markdown-it-toc-done-right');

md.use(wikilinks);
md.use(wikianchor);
md.use(wikitoc, { listType: 'ul' });
