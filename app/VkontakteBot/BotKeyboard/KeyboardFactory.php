<?php
namespace App\VkontakteBot\BotKeyboard;

class KeyboardFactory
{

    public static function createKeyboard(): Keyboard
    {
        return new Keyboard();
    }

}