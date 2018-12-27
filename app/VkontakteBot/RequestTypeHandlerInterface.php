<?php
namespace App\VkontakteBot;

use Illuminate\Http\Request;

interface RequestTypeHandlerInterface
{

    public static function handle(Request $request);
}