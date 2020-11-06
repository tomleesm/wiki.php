### 新聞稿

專案完成了，寫在 GitHub 上的 wiki 文件想要找個地方統一整理，於是...

- 想要用 Markdown 寫 wiki，但是 Mediawiki 的 Markdown 外掛很鳥？
- 聽說 [Gollum](https://github.com/gollum/gollum) 很不錯，但你知道它對中文的支援很差嗎？
- 找到 [Wiki.js](https://wiki.js.org/) ，但用過之後覺得不像是 wiki？

要不要試試 wiki.php ？

- 支援 Markdown 格式
- 編輯時自動預覽
- 支援中文條目
- 和 mediawiki 一樣使用 `[[條目]]` 連結到其他頁面

### 常見問答

問: GitHub wiki 很好用啊，為什麼要另外找個地方統一整理？   
答: 因為 GitHub wiki 附屬在每個專案，容易造成 wiki 紀錄分散在不同的地方，不便日後查詢。

問: Mediawiki 的 Markdown 外掛很鳥？能否說明的更具體一點？   
答: 它不支援 Syntax highlighting、表格、表情符號、上標/下標、註腳

問: Gollum 的中文支援有那麼差嗎？   
答: 當你用 Gollum 編輯條目「新聞」，它會自動轉成英文拼音「Xin Wen」，雖然可以儲存中文內容，但是無法使用 `[[新聞]]` 來連結，必須使用 `[[Xin Wen]]`。雖然可以設定參數 --h1-title，但是情況依舊相同，而中文支援較佳的 adapter rugged 很難安裝

問: 我覺得 Wiki.js 超讚的，你怎麼會覺得它不好呢？   
答: Wiki.js 功能強大，但是它比較類似 Google 文件，不像 wiki 那樣直接輸入 `[[條目]]` 連結到其他頁面。

問: wiki.php 支援 markdown 到什麼程度呢？   
答: 除了基本的 markdown，還支援 GitHub Flavored Markdown

### 客戶經驗談

John 是一個記性不太好的工程師，所以他平常有在 GitHub wiki 寫筆記的習慣，把專案中遇到的問題、解決方案和常見模式都記下來。做了幾個專案後，他發現常常要去之前的專案找筆記，所以想要找一個 wiki 程式集中管理，最好是用 markdown，這樣就不用轉換語法了。有一天他找到了 wiki.php，除了支援基本的 markdown 語法，編輯時自動更新預覽，讓他可以立刻知道會有什麼效果，還能用 `[[include:條目]]` 這樣的語法內嵌其他條目，這樣就能做到類似目錄選單的功能

### 使用手冊

請參考 [使用手冊](https://github.com/tomleesm/wiki/wiki)
