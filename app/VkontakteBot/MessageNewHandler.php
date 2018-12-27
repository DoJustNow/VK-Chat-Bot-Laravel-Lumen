<?php
namespace App\VkontakteBot;

use App\VkontakteBot\Response\ActionResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use VK\Client\VKApiClient;

abstract class MessageNewHandler implements RequestTypeHandlerInterface
{

    public static function handle(Request $request)
    {
        //Определяем на каком этапе диалог с юзверем
        $userId     = $request->object['from_id'];
        $dialogStep = Cache::remember("dialog_step_$userId", 5,
            function () use ($userId) {
                return 'start';
            });
        if (isset($request->object['payload'])) {
            $dialogStep = json_decode($request->object['payload'])->button;
        }
        //Класс выполняющий ответ на запрос
        $actionResponse = new ActionResponse($request, new VKApiClient('5.92'),
            env('VK_SECRET_KEY_GROUP'));

        //Переходим к тому действию что соответствует шагу
        switch ($dialogStep) {
            case 'start':
                $actionResponse->start();
                break;
            case 'faq':
                $actionResponse->faqClick();
                break;
            //Уровень
            case 'faq_buy':
                $actionResponse->faqBuyClick();
                break;
            case 'faq_payment':
                $actionResponse->faqPaymentClick();
                break;
            case 'faq_delivery':
                $actionResponse->faqDeliveryClick();
                break;
            case 'faq_money_back':
                $actionResponse->faqMoneyBackClick();
                break;
            case 'about':
                $actionResponse->aboutClick();
                break;
            case 'about_shop':
                $actionResponse->aboutShopClick();
                break;
            case 'about_workers':
                $actionResponse->aboutWorkersClick();
                break;
            case 'stock':
                $actionResponse->stockClick();
                break;
            case 'reviews':
                $actionResponse->reviewsClick();
                break;
            //Уровень 2
            case 'stock_1':
                $actionResponse->stock1Click();
                break;
            case 'stock_2':
                $actionResponse->stock2Click();
                break;
            case 'stock_3':
                $actionResponse->stock3Click();
                break;
            case 'stock_4':
                $actionResponse->stock4Click();
                break;
            case 'stock_4_bonus_code_entry':
                $actionResponse->checkBonusCode();
                break;
            default:
                $actionResponse->defaultResponse();
        }

    }
}