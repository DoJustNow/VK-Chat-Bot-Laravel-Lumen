# VK-Chat-Bot-Laravel-Lumen
## Обработка запросов от VK
Обработка запросов от VК происходит методом `execute(Request $request)` в контроллере [app/Http/Controllers/VkApiCallbackController.php](https://github.com/DoJustNow/VK-Chat-Bot-Laravel-Lumen/blob/master/app/Http/Controllers/VkApiCallbackController.php "Код").
<br>Метод `sendOk()` отправляет на любой запрос от VK ответ `ok` со статусом `200 OK` для того чтобы VK не спамил в случае неудачи.<br>
Обработка сообщений отправляемых боту ([app/VkontakteBot/MessageNewHandler.php](https://github.com/DoJustNow/VK-Chat-Bot-Laravel-Lumen/blob/master/app/VkontakteBot/MessageNewHandler.php "Код")): 
```php
    if ($request->type === 'message_new') {
            MessageNewHandler::handle($request);
        }
```
В кэше хранится шаг (состояние) диалога с ботом, т.е текущяя стадия:
```php
$dialogStep = Cache::remember("dialog_step_$userId", 5,
            function () use ($userId) {
                return 'start';
            });
```
В зависимости от шага отправляется определенный ответ (исходящее сообщение) на запрос (входящее сообщение):
```php
 switch ($dialogStep) {
            case 'start':
                $actionResponse->start();
                break;
            case 'faq':
                $actionResponse->faqClick();
                break;
            ...
            default:
                $actionResponse->defaultResponse();
        }
 ```
## Ответ на запрос от VK
Ответы бота прописаны в методах класса `ActionResponse` ([app/VkontakteBot/Response/ActionResponse.php](https://github.com/DoJustNow/VK-Chat-Bot-Laravel-Lumen/blob/master/app/VkontakteBot/Response/ActionResponse.php "Код")).
## Создание клавиатуры бота
Для упращения создания json-строки, содержащей клавиатуру бота согласно [структуре данных](https://vk.com/dev/bots_docs_3?f=4.2.%2B%D0%A1%D1%82%D1%80%D1%83%D0%BA%D1%82%D1%83%D1%80%D0%B0%2B%D0%B4%D0%B0%D0%BD%D0%BD%D1%8B%D1%85), разработаны классы для создания кнопок, рядов кнопок, клавиатуры боты в целом. Расположены в каталоге [app/VkontakteBot/BotKeyboard](https://github.com/DoJustNow/VK-Chat-Bot-Laravel-Lumen/tree/master/app/VkontakteBot/BotKeyboard "Каталог").
#### class `Button`
<ins>`array`</ins> `create(array $payload, string $label, string $colorType)` 
<br>Cоздание массива json-строка которого является представлением кнопки.
#### class `ButtonRowFactory`
<ins>`ButtonRow`</ins> `createRow()`<br>
Содание обьекта класса `ButtonRow`.
#### class `ButtonRow`
<ins>`self`</ins> `addButton(array $button)`<br>
Добавление кнопки в кнопочный ряд.<br>
<ins>`array`</ins> `getRow()`<br>
Получение массива ряда кнопок.
#### class `KeyboardFactory`
<ins>`Keyboard`</ins> `createKeyboard()`<br>
Создание объекта класса `Keyboard`.
#### class `Keyboard`
<ins>`self`</ins> `addRow(array $buttonRow)`<br>
Добавление ряда кнопок в клавиатуру бота.<br>
<ins>`self`</ins> `setOneTime(bool $value)`<br>
Установление значения one_time для клавиатуры.<br>
<ins>`string`</ins> `getKeyboardJson()`<br>
Возврат json-строки представления клавиатуры бота.

Простейший пример создания и отправки клавиатуры бота:
```php
        $btnFaq       = Button::create(['button' => 'faq'], 'FAQ❔', 'primary');
        $btnAbout     = Button::create(['button' => 'about'], 'О нас🥇', 'primary');
        $btnMoneyBack = Button::create(['button' => 'reviews'], 'Отзывы🗣', 'primary');
        $btnStock     = Button::create(['button' => 'stock'], 'Акции🎊', 'positive');
        $btnRow1 = ButtonRowFactory::createRow()
                                   ->addButton($btnFaq)
                                   ->addButton($btnAbout)
                                   ->getRow();
        $btnRow2 = ButtonRowFactory::createRow()
                                   ->addButton($btnMoneyBack)
                                   ->getRow();
        $btnRow3 = ButtonRowFactory::createRow()
                                   ->addButton($btnStock)
                                   ->getRow();
        $kb = KeyboardFactory::createKeyboard()
                             ->addRow($btnRow1)
                             ->addRow($btnRow2)
                             ->addRow($btnRow3)
                             ->setOneTime(false)
                             ->getKeyboardJson();
       ...       
       $params = [
            ...
            'keyboard'  => $kb,
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
 ```
Результат будет иметь следующий вид:<br>
![image](https://user-images.githubusercontent.com/39625189/50545513-2b3a2700-0c27-11e9-8469-b8618206fef9.png)

## Пример работы 
<p align="center">
<img src ="https://user-images.githubusercontent.com/39625189/50546714-69dadc00-0c3d-11e9-905b-b209b07a572c.gif">
</p>

