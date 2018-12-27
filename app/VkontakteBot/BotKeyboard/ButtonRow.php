<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 24.12.18
 * Time: 5:55
 */

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