<?php

namespace App\Services;

use Illuminate\Support\Facades\Facade;
use OpenAI;

class OpenAIService extends Facade
{
  static public function getClient()
  {
    $apiKey = getenv('OPENAI_API_KEY');
    $client = OpenAI::client($apiKey);
    return $client;
  }
}
