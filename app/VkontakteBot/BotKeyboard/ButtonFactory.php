<?php
namespace App\VkontakteBot\BotKeyboard;
//TODO сделать это реальной фабрикой
class ButtonFactory
{

    public static function create(string $payload, string $label, string $colorType): array
    {
        //TODO проверку длины payload и тип т.е что там json
    $button['action']['type'] = 'text';
    $button['action']['payload'] = '{"button":"'.$payload.'"}';
    $button['action']['label'] = $label;
    $button['color']= $colorType;
    return $button;
    }
}