# 合併最新進度
git merge master
# 安裝/更新透過 npm 使用的 library
npm install
# 把 resources 的 css, js, 圖片部署到 public
npm run production
git add --all
git commit -m 'deploy to heroku'
# push 本機的 heroku 分支到遠端 heroku 的 master 分支
git push heroku heroku:master
# 資料庫 migration and seeding
heroku run php artisan migrate --seed
