<?php
namespace App\VkontakteBot\BotKeyboard;

class ButtonRow
{

    private $buttonRow = [];

    public function addButton(array $button)
    {
        $this->buttonRow[] = $button;

        return $this;
    }

    public function getRow()
    {
        return $this->buttonRow;
    }
}