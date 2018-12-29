<?php

namespace App\Http\Controllers;

use App\VkontakteBot\MessageNewHandler;
use Illuminate\Http\Request;

class VkApiCallbackController extends Controller
{

    public function execute(Request $request)
    {
        //Если секрет не совпадает финиш
        if ($request->secret !== env('VK_SECRET_KEY_CALLBACK')) {
            return;
        }
        //Верификация сервера
        if ($request->type === 'confirmation') {
            return env('VK_SECRET_INIT_KEY');
        }
        //Отправка 'ok' на любой запрос от VK
        $this->sendOK();
        //Обрабатываем поступившее сообщение
        if ($request->type === 'message_new') {
            MessageNewHandler::handle($request);
        }
    }

    private function sendOK()
    {
        echo 'ok';
        $response_length = ob_get_length();
        if (is_callable('fastcgi_finish_request')) {
            session_write_close();
            fastcgi_finish_request();

            return;
        }
        ignore_user_abort(true);
        ob_start();
        header('HTTP/1.1 200 OK');
        header('Content-Encoding: none');
        header('Content-Length: ' . $response_length);
        header('Connection: close');
        ob_end_flush();
        ob_flush();
        flush();
    }
}
