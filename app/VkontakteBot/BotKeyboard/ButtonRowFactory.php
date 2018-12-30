<?php
namespace App\VkontakteBot\BotKeyboard;

class ButtonRowFactory
{

    public static function createRow(): ButtonRow
    {

        return new ButtonRow();
    }

}