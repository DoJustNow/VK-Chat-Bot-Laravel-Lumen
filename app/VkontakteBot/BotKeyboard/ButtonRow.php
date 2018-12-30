<?php
namespace App\VkontakteBot\BotKeyboard;

class ButtonRow
{

    private $buttonRow = [];

    public function addButton(array $button): self
    {
        $this->buttonRow[] = $button;

        return $this;
    }

    public function getRow(): array
    {
        return $this->buttonRow;
    }
}