<?php

namespace App\Facades\OpenAI;

use Illuminate\Support\Facades\Facade;

class OpenAIFacade extends Facade
{
   protected static function getFacadeAccessor()
   {
      return 'openai';
   }
}