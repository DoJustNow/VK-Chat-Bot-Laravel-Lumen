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
    private $botStandartMessages;

    public function __construct(
        Request $request,
        VKApiClient $vkApiClient,
        String $accessToken
    ) {
        $this->botStandartMessages    = config('bot_messages');
        $this->botStandartAttachments = config('bot_vk_media_attachments');
        $this->botButtonLabels        = config('bot_button_names');
        $this->request                = $request;
        $this->vkApiClient            = $vkApiClient;
        $this->accessToken            = $accessToken;
    }

    public function start()
    {
        $btnFaq       = ButtonFactory::create(['button' => 'faq'], $this->botButtonLabels['faq'], 'primary');
        $btnAbout     = ButtonFactory::create(['button' => 'about'], $this->botButtonLabels['about'], 'primary');
        $btnMoneyBack = ButtonFactory::create(['button' => 'reviews'], $this->botButtonLabels['reviews'], 'primary');
        $btnStock     = ButtonFactory::create(['button' => 'stock'], $this->botButtonLabels['stock'], 'positive');

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
                             ->getKeyboard();

        $params = [
            'user_id'   => $this->request->object['from_id'],
            'random_id' => rand(0, 2 ** 31),
            'message'   => $this->botStandartMessages['start_message'],
            'keyboard'  => json_encode($kb, JSON_UNESCAPED_UNICODE),
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function faqClick()
    {
        $userId = $this->request->object['from_id'];

        Cache::put("dialog_step_$userId", 'faq', 5);

        $btnFaqBuy = ButtonFactory::create(['button' => 'faq_buy'], $this->botButtonLabels['faq_buy'], 'primary');
        $btnFaqPayment = ButtonFactory::create(['button' => 'faq_payment'], $this->botButtonLabels['faq_payment'], 'primary');
        $btnFaqDelivery = ButtonFactory::create(['button' => 'faq_delivery'], $this->botButtonLabels['faq_delivery'], 'primary');
        $btnFaqMoneyBack = ButtonFactory::create(['button' => 'faq_money_back'], $this->botButtonLabels['faq_money_back'], 'primary');
        $btnBackToStart = ButtonFactory::create(['button' => 'start'], $this->botButtonLabels['start'], 'negative');

        $btnRow1 = ButtonRowFactory::createRow()
                                   ->addButton($btnFaqBuy)
                                   ->addButton($btnFaqPayment)
                                   ->addButton($btnFaqDelivery)
                                   ->addButton($btnFaqMoneyBack)
                                   ->getRow();

        $btnRow2 = ButtonRowFactory::createRow()
                                   ->addButton($btnBackToStart)
                                   ->getRow();

        $kb = KeyboardFactory::createKeyboard()
                             ->addRow($btnRow1)
                             ->addRow($btnRow2)
                             ->setOneTime(true)
                             ->getKeyboard();

        $params = [
            'user_id'   => $userId,
            'random_id' => rand(0, 2 ** 31),
            'message'   => $this->botStandartMessages['faq_message'],
            'keyboard'  => json_encode($kb, JSON_UNESCAPED_UNICODE),
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function faqBuyClick()
    {
        $params = [
            'user_id'    => $this->request->object['from_id'],
            'random_id'  => rand(0, 2 ** 31),
            'message'    => $this->botStandartMessages['faq_buy_message'],
            'attachment' => $this->botStandartAttachments['faq_buy_attachment'],
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function faqPaymentClick()
    {
        $params = [
            'user_id'    => $this->request->object['from_id'],
            'random_id'  => rand(0, 2 ** 31),
            'message'    => $this->botStandartMessages['faq_payment_message'],
            'attachment' => $this->botStandartAttachments['faq_payment_attachment'],
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function faqDeliveryClick()
    {

        $params = [
            'user_id'    => $this->request->object['from_id'],
            'random_id'  => rand(0, 2 ** 31),
            'message'    => $this->botStandartMessages['faq_delivery_message'],
            'attachment' => $this->botStandartAttachments['faq_delivery_attachment'],
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function faqMoneyBackClick()
    {
        $params = [
            'user_id'    => $this->request->object['from_id'],
            'random_id'  => rand(0, 2 ** 31),
            'message'    => $this->botStandartMessages['faq_money_back_message'],
            'attachment' => $this->botStandartAttachments['faq_money_back_attachment'],
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function aboutClick()
    {
        $userId = $this->request->object['from_id'];

        Cache::put("dialog_step_$userId", 'about', 5);

        $btnAboutShop = ButtonFactory::create(['button' => 'about_shop'], $this->botButtonLabels['about_shop'], 'primary');
        $btnAboutWorkers = ButtonFactory::create(['button' => 'about_workers'], $this->botButtonLabels['about_workers'], 'primary');
        $btnBackToStart = ButtonFactory::create(['button' => 'start'], $this->botButtonLabels['start'], 'negative');

        $btnRow1 = ButtonRowFactory::createRow()
                                   ->addButton($btnAboutShop)
                                   ->addButton($btnAboutWorkers)
                                   ->getRow();

        $btnRow2 = ButtonRowFactory::createRow()
                                   ->addButton($btnBackToStart)
                                   ->getRow();

        $kb = KeyboardFactory::createKeyboard()
                             ->addRow($btnRow1)
                             ->addRow($btnRow2)
                             ->setOneTime(true)
                             ->getKeyboard();

        $params = [
            'user_id'   => $userId,
            'random_id' => rand(0, 2 ** 31),
            'message'   => $this->botStandartMessages['about_message'],
            'keyboard'  => json_encode($kb, JSON_UNESCAPED_UNICODE),
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function aboutShopClick()
    {
        $params = [
            'user_id'   => $this->request->object['from_id'],
            'random_id' => rand(0, 2 ** 31),
            'message'   => $this->botStandartMessages['about_shop_message'],
            'lat'       => config('bot_map_coordinates.main_shop.lat'),
            'long'      => config('bot_map_coordinates.main_shop.long'),
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function aboutWorkersClick()
    {
        $params = [
            'user_id'    => $this->request->object['from_id'],
            'random_id'  => rand(0, 2 ** 31),
            'message'    => $this->botStandartMessages['about_workers_message'],
            'attachment' => $this->botStandartAttachments['about_workers_attachment'],
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function reviewsClick()
    {
        $userId = $this->request->object['from_id'];

        Cache::put("dialog_step_$userId", 'reviews', 5);

        $params = [
            'user_id'   => $userId,
            'random_id' => rand(0, 2 ** 31),
            'message'   => $this->botStandartMessages['reviews_message'],
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function stockClick()
    {
        $userId = $this->request->object['from_id'];

        Cache::put("dialog_step_$userId", 'stock', 5);

        $btnStock1 = ButtonFactory::create(['button' => 'stock_1'], $this->botButtonLabels['stock_1'], 'primary');
        $btnStock2 = ButtonFactory::create(['button' => 'stock_2'], $this->botButtonLabels['stock_2'], 'primary');
        $btnStock3 = ButtonFactory::create(['button' => 'stock_3'], $this->botButtonLabels['stock_3'], 'primary');
        $btnStock4 = ButtonFactory::create(['button' => 'stock_4'], $this->botButtonLabels['stock_4'], 'primary');
        $btnBackToStart = ButtonFactory::create(['button' => 'start'], $this->botButtonLabels['start'], 'negative');

        $btnRow1 = ButtonRowFactory::createRow()
                                   ->addButton($btnStock1)
                                   ->addButton($btnStock2)
                                   ->getRow();

        $btnRow2 = ButtonRowFactory::createRow()
                                   ->addButton($btnStock3)
                                   ->addButton($btnStock4)
                                   ->getRow();

        $btnRow3 = ButtonRowFactory::createRow()
                                   ->addButton($btnBackToStart)
                                   ->getRow();

        $kb = KeyboardFactory::createKeyboard()
                             ->addRow($btnRow1)
                             ->addRow($btnRow2)
                             ->addRow($btnRow3)
                             ->setOneTime(true)
                             ->getKeyboard();

        $params = [
            'user_id'   => $userId,
            'random_id' => rand(0, 2 ** 31),
            'message'   => $this->botStandartMessages['stock_message'],
            'keyboard'  => json_encode($kb, JSON_UNESCAPED_UNICODE),
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function stock1Click()
    {
        $params = [
            'user_id'   => $this->request->object['from_id'],
            'random_id' => rand(0, 2 ** 31),
            'message'   => $this->botStandartMessages['stock_1_message'],
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function stock2Click()
    {
        $params = [
            'user_id'   => $this->request->object['from_id'],
            'random_id' => rand(0, 2 ** 31),
            'message'   => $this->botStandartMessages['stock_2_message'],
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function stock3Click()
    {
        $params = [
            'user_id'   => $this->request->object['from_id'],
            'random_id' => rand(0, 2 ** 31),
            'message'   => $this->botStandartMessages['stock_3_message'],
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function stock4Click()
    {
        $userId = $this->request->object['from_id'];

        Cache::put("dialog_step_$userId", 'stock_4_bonus_code_entry', 5);

        $params = [
            'user_id'   => $userId,
            'random_id' => rand(0, 2 ** 31),
            'message'   => $this->botStandartMessages['stock_4_message'],
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function checkBonusCode()
    {
        $bonusCodes = [
            'bonus10' => '👌Ваш бонус -10% от стоимости товара при покупке.',
            'bonus30' => '👌Ваш бонус -30% на покупку свыше 10 000 попугаев.',
        ];

        $userBonusCode = strtolower($this->request->object['text']);

        $message = array_key_exists($userBonusCode, $bonusCodes)
            ? $bonusCodes[$userBonusCode]
            : $this->botStandartMessages['stock_4_check_bonus_fail_message'];

        $params = [
            'user_id'   => $this->request->object['from_id'],
            'random_id' => rand(0, 2 ** 31),
            'message'   => $message,
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }

    public function defaultResponse()
    {
        $params = [
            'user_id'   => $this->request->object['from_id'],
            'random_id' => rand(0, 2 ** 31),
            'message'   => $this->botStandartMessages['default_message'],
        ];

        $this->vkApiClient->messages()->send($this->accessToken, $params);
    }
}