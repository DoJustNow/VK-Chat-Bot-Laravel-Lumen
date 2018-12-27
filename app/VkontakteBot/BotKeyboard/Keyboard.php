<?php
namespace App\VkontakteBot\BotKeyboard;

class Keyboard
{

    private $keyboard = [];
    private $oneTime = false;

    public function addRow(array $buttonRow)
    {
        $this->keyboard['buttons'][] = $buttonRow;

        return $this;
    }

    public function setOneTime(bool $value)
    {
        $this->oneTime = $value;

        return $this;
    }

    public function getKeyboard()
    {
        return $this->keyboard;
    }
}