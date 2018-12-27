<?php
namespace App\VkontakteBot\BotKeyboard;

class KeyboardFactory
{

    public static function createKeyboard()
    {
        return new Keyboard();
    }

}