```
npm i
cp .env.example .env.local
```
Далее необходимо заполнить .env
Город, возможные города: Rostov, Taganrog, Moscow
```
VUE_APP_CITY=Taganrog
```
Ключ, который мы сгенерировали на backend
```
VUE_APP_API_KEY=YiL2x3O3CETQtHisISnOtVaLiDKeYsrg2vOAKBRVHw7h5yyI
```
Адрес нашего backend (обратите внимания, на конце должен быть /)
```
VUE_APP_BACKEND_URL=http://127.0.0.1/
```
Режим тестирования, где 1 - заказы не будут созданы (тест), 0 - заказы будут созданы
```
VUE_APP_IS_TEST=1
```
Для запуска локального сервера используйте команду
```
npm run serve
```
Для запуска тестирования
```
npm run test
```
Если необходимо собрать проект
```
npm run build
```