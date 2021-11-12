# Установка
1. Устанавливаем backend (инструкция в папке backend)
2. Устанавливаем frontend (инструкция в папке frontend)
3. Устанавливаем demo:

Для возможности просмотра demo необходимо в frontend сгенерировать новый js файл с актуальным ключем
- Убедитесь, что в env.local параметр VUE_APP_IS_TEST равен 0, если будет 1, то заказы сохранены не будут.
- Убедитесь, что в env.local у вас установлены корректные VUE_APP_BACKEND_URL 
и VUE_APP_API_KEY (который был сгенерирован на backend) 

Далее проделайте следующие действия д*л*я каждого города:
- Убедитесь, что в VUE_APP_CITY у вас установлен нужный город
- Соберите проект, для этого в папке frontend вызовите команду npm run build
- Переместите файл frontend/dist/js/app.js в demo/нужная_папка/movies/app.js

Где нужная_папка - одна из трех папок (one, two, three)

# Замечания
1. После совершения заказа ***<u>отсутствует кнопка возврата в начало</u>***, т.к. на изначальном макете данной кнопки 
не было.
Т.к. возможно так и должно быть, добавлять я её не стал. Разумеется, при необходимости, её можно добавить без проблем.
Сейчас же, что бы совершить новый заказ необходимо обновить страницу.
2. ***<u>Для тестирования было бы правильнее создавать отдельный API ключ</u>***, через который не сохраняются заказы, 
а не использовать
is_test переменную, но такой вариант менее удобен для быстрого переключения между тестами, и реальными заказами,
так что, был выбран более удобный для МЕНЯ вариант. В реальном проекте я бы использовал разные API ключи.
3. ***<u>Тестирование может провалиться, если</u>***, к примеру, будет установленно, что сделать заказ на текущий день,
плюс 10 дней, а ***<u>в этот день будут выкуплены все билеты</u>***. Можно использовать большее кол-во дней, 
чтобы избежать этого, или прибавлять по одному дню, пока не дойдет до свободного, но правильнее было бы смотреть 
на загруженность, и определять от неё правильный подход, т.к. писать тест со всеми проверками займет достаточно много
времени, и если заказы обычно делают на 1-2 дня вперед, а тесты запускаются руками и подобное можно отследить, 
то это не имеет какого-либо смысла
4. ***<u>Дизайн не был реализован</u>***, т.к. цель задания была в другом