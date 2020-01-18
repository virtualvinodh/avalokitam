(Get-Content .\src\mixin\LinkMixin.js).replace('http://localhost', 'http://www.avalokitam.com') | Set-Content .\src\mixin\LinkMixin.js

(Get-Content .\src\layouts\MyLayout.vue).replace('/* @import', '@import') | Set-Content .\src\layouts\MyLayout.vue
(Get-Content .\src\layouts\MyLayout.vue).replace( "tamil'); */", "tamil');") | Set-Content .\src\layouts\MyLayout.vue

(Get-Content .\src\layouts\MyLayout.vue).replace('../statics/e-VatteluttuOT.ttf', 'https://cdn.jsdelivr.net/gh/virtualvinodh/aksharamukha/aksharamukha-front/src/statics/e-VatteluttuOT.ttf') | Set-Content .\src\layouts\MyLayout.vue

quasar build

cd dist

gcloud app deploy --project=avalokitam --quiet

cd ..
