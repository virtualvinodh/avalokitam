(Get-Content .\src\mixin\LinkMixin.js).replace('http://www.avalokitam.com', 'http://localhost') | Set-Content .\src\mixin\LinkMixin.js

(Get-Content .\src\layouts\MyLayout.vue).replace('@import', '/* @import') | Set-Content .\src\layouts\MyLayout.vue

(Get-Content .\src\layouts\MyLayout.vue).replace("tamil');", "tamil'); */") | Set-Content .\src\layouts\MyLayout.vue

(Get-Content .\src\layouts\MyLayout.vue).replace('https://cdn.jsdelivr.net/gh/virtualvinodh/aksharamukha/aksharamukha-front/src/statics/e-VatteluttuOT.ttf', '../statics/e-VatteluttuOT.ttf') | Set-Content .\src\layouts\MyLayout.vue

quasar build

cd ./dist

docker build -t virtualvinodh/avalokitam .

docker push virtualvinodh/avalokitam
