<?php
namespace App\VkontakteBot\BotKeyboard;

class Keyboard
{

    private $keyboard = [];
    private $oneTime = false;

    public function addRow(array $buttonRow): self
    {
        $this->keyboard['buttons'][] = $buttonRow;

        return $this;
    }

    public function setOneTime(bool $value): self
    {
        $this->oneTime = $value;

        return $this;
    }

    public function getKeyboard(): array
    {
        return $this->keyboard;
    }
}