<?php
namespace App\VkontakteBot\Response;

use App\VkontakteBot\BotKeyboard\ButtonFactory;
use App\VkontakteBot\BotKeyboard\ButtonRowFactory;
use App\VkontakteBot\BotKeyboard\KeyboardFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use VK\Client\VKApiClient;

class ActionResponse
{

    private $vkApiClient;
    private $request;
    private $accessToken;

    public function __construct(
        Request $request,
        VKApiClient $vkApiClient,
        String $accessToken
    ) {
        $this->request     = $request;
        $this->vkApiClient = $vkApiClient;
        $this->accessToken = $accessToken;
    }

    public function start()
    {
        $buttonFaq       = ButtonFactory::create(['button'=>'faq'], 'FAQ', 'primary');
        $buttonAbout     = ButtonFactory::create(['button'=>'about'], "О нас", 'primary');
        $buttonMoneyBack = ButtonFactory::create(['button'=>'reviews'], "Отзывы", 'primary');
        $buttonStock     = ButtonFactory::create(['button'=>'stock'], "Акции", 'positive');

        $buttonRow1 = ButtonRowFactory::createRow()
                                      ->addButton($buttonFaq)
                                      ->addButton($buttonAbout)
                                      ->getRow();

        $buttonRow2 = ButtonRowFactory::createRow()
                                      ->addButton($buttonMoneyBack)
                                      ->getRow();

        $buttonRow3 = ButtonRowFactory::createRow()
                                      ->addButton($buttonStock)
                                      ->getRow();

        $kb = KeyboardFactory::createKeyboard()
                             ->addRow($buttonRow1)
                             ->addRow($buttonRow2)
                             ->addRow($buttonRow3)
                             ->setOneTime(false)
                             ->getKeyboard();

        $params = [
            'user_id'   => $this->request->object['from_id'],
            'random_id' => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'   => "🤖 🤖 🤖\n Выбирай",
            'keyboard'  => json_encode($kb, JSON_UNESCAPED_UNICODE),
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function faqClick()
    {
        $userId = $this->request->object['from_id'];
        Cache::put("dialog_step_$userId", 'faq', 5);
        $b1 = ButtonFactory::create(['button'=>'faq_buy'], 'Покупка', 'primary');
        $b2 = ButtonFactory::create(['button'=>'faq_payment'], 'Оплата', 'primary');
        $b3 = ButtonFactory::create(['button'=>'faq_delivery'], 'Доставка', 'primary');
        $b4 = ButtonFactory::create(['button'=>'faq_money_back'], 'Возврат', 'primary');
        $b5 = ButtonFactory::create(['button'=>'start'], '<< Назад', 'negative');

        $btnRow1 = ButtonRowFactory::createRow()
                                   ->addButton($b1)
                                   ->addButton($b2)
                                   ->addButton($b3)
                                   ->addButton($b4)
                                   ->getRow();

        $btnRow2 = ButtonRowFactory::createRow()
                                   ->addButton($b5)
                                   ->getRow();

        $kb = KeyboardFactory::createKeyboard()->addRow($btnRow1)
                             ->addRow($btnRow2)
                             ->setOneTime(true)
                             ->getKeyboard();

        $params = [
            'user_id'   => $userId,//498921857
            'random_id' => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'   => "🤖 🤖 🤖\n FAQ",
            'keyboard'  => json_encode($kb, JSON_UNESCAPED_UNICODE),
        ];
        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function faqBuyClick()
    {
        $params = [
            'user_id'    => $this->request->object['from_id'],//498921857
            'random_id'  => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'    => 'Преобрести товар вы можете там-то. И так-то.',
            'attachment' => 'photo-175591301_456239018',
        ];
        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function faqPaymentClick()
    {
        $params = [
            'user_id'    => $this->request->object['from_id'],//498921857
            'random_id'  => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'    => 'Оплата производится при таких-то условиях и такими-то способами',
            'attachment' => 'photo-175591301_456239019',
        ];
        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function faqDeliveryClick()
    {
        $params = [
            'user_id'    => $this->request->object['from_id'],//498921857
            'random_id'  => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'    => 'Доставка осуществляется на велосипеде',
            'attachment' => 'photo-175591301_456239020',
        ];
        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function faqMoneyBackClick()
    {
        $params = [
            'user_id'    => $this->request->object['from_id'],//498921857
            'random_id'  => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'    => 'Вовзрат по закону',
            'attachment' => 'photo-175591301_456239021',
        ];
        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function aboutClick()
    {
        $userId = $this->request->object['from_id'];
        Cache::put("dialog_step_$userId", 'about', 5);
        $b1 = ButtonFactory::create(['button'=>'about_shop'], 'О магазаине', 'primary');
        $b2 = ButtonFactory::create(['button'=>'about_workers'], 'О работниках', 'primary');
        $b3 = ButtonFactory::create(['button'=>'start'], '<< Назад', 'negative');

        $btnRow1 = ButtonRowFactory::createRow()->addButton($b1)
                                   ->addButton($b2)
                                   ->getRow();

        $btnRow2 = ButtonRowFactory::createRow()->addButton($b3)
                                   ->getRow();

        $kb = KeyboardFactory::createKeyboard()->addRow($btnRow1)
                             ->addRow($btnRow2)
                             ->setOneTime(true)
                             ->getKeyboard();

        $params = [
            'user_id'   => $userId,//498921857
            'random_id' => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'   => "🤖 🤖 🤖\nСасибо за проявленный интерес. Продолжай!",
            'keyboard'  => json_encode($kb, JSON_UNESCAPED_UNICODE),
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);

    }

    public function aboutShopClick()
    {
        $params = [
            'user_id'   => $this->request->object['from_id'],//498921857
            'random_id' => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'   => 'Магазин рассположен в центре мира.',
            'lat'       => 60,
            'long'      => 90,
        ];
        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function aboutWorkersClick()
    {
        $params = [
            'user_id'    => $this->request->object['from_id'],//498921857
            'random_id'  => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'    => 'Коллектив успешных, молодых профессионалов своего дела.',
            'attachment' => 'photo-175591301_456239022',
        ];
        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function reviewsClick()
    {
        $userId = $this->request->object['from_id'];
        Cache::put("dialog_step_$userId", 'reviews', 5);
        $params = [
            'user_id'   => $userId,//498921857
            'random_id' => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'   => "Отзывы можно посмотреть в группе по ссылке: https://vk.com/dialogue_bot",
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function stockClick()
    {
        $userId = $this->request->object['from_id'];
        Cache::put("dialog_step_$userId", 'stock', 5);
        $b1 = ButtonFactory::create(['button'=>'stock_1'], 'Акция 1', 'primary');
        $b2 = ButtonFactory::create(['button'=>'stock_2'], 'Акция 2', 'primary');
        $b3 = ButtonFactory::create(['button'=>'stock_3'], 'Акция 3', 'primary');
        $b4 = ButtonFactory::create(['button'=>'stock_4'], 'Бонус код', 'primary');
        $b5 = ButtonFactory::create(['button'=>'start'], '<< Назад', 'negative');

        $btnRow1 = ButtonRowFactory::createRow()->addButton($b1)
                                   ->addButton($b2)
                                   ->getRow();

        $btnRow2 = ButtonRowFactory::createRow()->addButton($b3)
                                   ->addButton($b4)
                                   ->getRow();

        $btnRow3 = ButtonRowFactory::createRow()
                                   ->addButton($b5)
                                   ->getRow();

        $kb = KeyboardFactory::createKeyboard()->addRow($btnRow1)
                             ->addRow($btnRow2)
                             ->addRow($btnRow3)
                             ->setOneTime(true)
                             ->getKeyboard();

        $params = [
            'user_id'   => $userId,//498921857
            'random_id' => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'   => "🤖 🤖 🤖\n Акции:\n
            1 - ...\n
            2 - ...\n
            3 - ...\n
            4 - ...",
            'keyboard'  => json_encode($kb, JSON_UNESCAPED_UNICODE),
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function stock1Click()
    {
        $params = [
            'user_id'   => $this->request->object['from_id'],//498921857
            'random_id' => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'   => 'Акция 1...',
        ];
        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function stock2Click()
    {
        $params = [
            'user_id'   => $this->request->object['from_id'],//498921857
            'random_id' => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'   => 'Акция 2...',
        ];
        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function stock3Click()
    {
        $params = [
            'user_id'   => $this->request->object['from_id'],//498921857
            'random_id' => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'   => 'Акция 3...',
        ];
        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function stock4Click()
    {
        $userId = $this->request->object['from_id'];
        Cache::put("dialog_step_$userId", 'stock_4_bonus_code_entry', 5);
        $params = [
            'user_id'   => $userId,//498921857
            'random_id' => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'   => 'Введите ваш бонус код (для теста bonus10,bonus30):',
        ];
        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function checkBonusCode()
    {
        $bonusCodes = [
            'bonus10' => 'Ваш бонус -10% от стоимости товара при покупке.',
            'bonus30' => 'Ваш бонус -30% на покупку свыше 10 000 попугаев.',
        ];

        $userBonusCode = strtolower($this->request->object['text']);

        if (array_key_exists($userBonusCode, $bonusCodes)) {
            $message = $bonusCodes[$userBonusCode];
        } else {
            $message
                = "Такого бонус-кода нет. Возможно вы ошиблись при вводе.\nПопробуйте ввести код заново:";
        }

        $params = [
            'user_id'   => $this->request->object['from_id'],//498921857
            'random_id' => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'message'   => $message,
        ];
        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function defaultResponse()
    {
        $params = [
            'user_id'    => $this->request->object['from_id'],//498921857
            'random_id'  => rand(0, 2 ** 31),
            //TODO подгружать из источника
            'attachment' => "photo-175591301_456239017",
        ];
        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }
}