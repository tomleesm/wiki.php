### 標題

# h1 標題
## h2 標題
### h3 標題
#### h4 標題
##### h5 標題
###### h6 標題

### 水平分隔線

___

---

***

### 強調

**粗體** __粗體__ *斜體* _斜體_ ~~刪除~~

### 清單

#### 有序清單

1. 以數字 1 開頭加上一點 . 和一個空格
2. 就會自動產生從 1. 開始的清單
3. 輸入 `Enter` 換到下一行
4. 所見即所得編輯器也會自動輸入下一個編號

所以你可以全部使用數字 1. ，一樣有相同的清單

1. 以數字 1 開頭加上一點 . 和一個空格
1. 就會自動產生從 1. 開始的清單
1. 輸入 `Enter` 換到下一行
1. 所見即所得編輯器也會自動輸入下一個編號

以數字開頭，就會從那個數字開始產生清單

2. 這是以 2 開頭的清單
1. 所以下一行無論是什麼數字開頭，都會是 3.



1. 如果有兩個清單
1. 即使中間隔著再多個換行
2. 一樣算是同樣的清單

#### 無序清單

+ 在一行的開頭使用 `+` `-` 或是 `*` 建立清單
+ 兩個空白產生第二層清單
  - 這是第二層清單
  - 這是第二行
  * 即使用不同的開頭
  * 仍算是同一層


### 待辦清單

- [ ] 待辦三件事
  - [x] 買些麵包
  - [ ] 刷牙
  - [x] 喝水

### 表格

預設是向左對齊

| 欄位 A | 欄位 B |
| ------- | ------- |
| 出入的樣禮的可拿品這話手念放是    | Lorem ipsum dolor sit amet, consectetur adipiscing elit |

向右和向左對齊

| 欄位 A | 欄位 B |
| ------: | :----- |
| 出入的樣禮的可拿品這話手念放是    | Lorem ipsum dolor sit amet, consectetur adipiscing elit |

置中對齊

| 欄位 A | 欄位 B |
| :-----: | :-----: |
| 出入的樣禮的可拿品這話手念放是    | Lorem ipsum dolor sit amet, consectetur adipiscing elit |

#### 表格跨欄跨列

| >     | >                  |   `>` 大於符號表示向右跨欄    | >           | 跨兩欄 |
| ----- | :---------: | ------------------------------: | -------- | ------- |
| 預設向左對齊 | 置中  |   向右對齊                          |  另外一格 |          |
| ^     |   `^` 向上跨列 |      >                                      | right align | .         |
|        | >           | center align                                    | >             | 2乘2 格  |
| >     | 另一個 2x2 |  隔一格                                     | >           | ^             |
| >     | ^                 |  >                                              |              | 就這樣    |

### 連結

[Google](https://www.google.com.tw/)

[有提示框 tooltops 的連結](https://store.steampowered.com/ "Steam")

自動轉換連結 https://aws.amazon.com


### wiki 條目

- [[條目]] : 兩個中括號包起來的是 wiki 站內連結
- [[實際條目|顯示條目]] : 用一個 `|` 區隔實際和顯示出來的條目
- 方便在文章中提到某個條目時，顯示的是簡稱，但需要指向全名
- 類如[[臺北火車站|北車]]

### 內嵌條目

可以用 include 加上冒號內嵌條目，例如以下的表格可以做爲捷運相關條目的共用目錄

[[include:中高運量鐵路系統]]

點選連結 edit 來新增與修改條目。請注意 include:條目 和 [[條目]] 是不同的條目

### 上傳圖片

- 點選編輯器的圖示 <i class="fa fa-picture-o"></i> ，選好圖片後，會自動上傳，或是拖曳圖片到編輯器也可以
- 上傳時螢幕右上方會有 uploading... 的提示
- 上傳後會新增語法例如

`![Markdown-mark.png](/images/91ffa9b4-7872-4231-9e73-6066b7af09ca)`

![Markdown-mark.png](/images/91ffa9b4-7872-4231-9e73-6066b7af09ca)

如果執行網址 [http://你的網址/images/91ffa9b4-7872-4231-9e73-6066b7af09ca](/images/91ffa9b4-7872-4231-9e73-6066b7af09ca) ，會顯示圖片

### 引用區塊

> 引用區塊第一層
>> 第二層
> > > `>` 之間用空白隔開也可以
>>>> 如果按下 `Enter` 換行
>>>>> 編輯器會自動加上上一行的 `>`

### 程式碼

在文章中加上程式碼是這樣  `print('this is code');`

#### 區塊

```
console.log('hello world');
```

加上 syntax highlighting 和行數

``` javascript
console.log('hello world');
```

### 表情符號

可以輸入 :smile: 表情符號

[表情符號列表](https://github.com/ikatyang/emoji-cheat-sheet)

### 目錄

如果頁面有標題，會自動產生目錄。輸入用中括號包起來的 notoc，指明不產生目錄

### 內嵌 YouTube

<iframe width="560" height="315" src="https://www.youtube.com/embed/NSTte2gLBl4" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

