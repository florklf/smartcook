<?php
namespace App\Http\Controllers;

use App\Conversations\AIConversation;
use App\Services\OpenAIService;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\Messages\Incoming\Answer;
use Illuminate\Support\Facades\Redis;

class BotManController extends Controller
{
    protected $systemPrompt = "You're a Michelin-starred chef with 15 years' experience in the field, including several international culinary competitions. You respond in the language in which you are addressed.";

    protected array $messages;

    protected OpenAIService $openAIService;

    /**
     * Place your BotMan logic here.
     */
    public function handle()
    {
        $this->openAIService = new OpenAiService();

        /** @var \BotMan\BotMan\BotMan */
        $botman = app('botman');
   
        $botman->hears('{message}', function($botman, $message) {
            if ($cachedMessages = Redis::get('messages')) {
                $this->messages = json_decode($cachedMessages);
            }
            $this->messages[] = ['role' => 'user', 'content' => $message];

            $response = $this->openAIService->getClient()->chat()->create([
                'model' => 'gpt-3.5-turbo',
                'messages' => array_merge([['role' => 'system', 'content' => $this->systemPrompt]], $this->messages),
            ]);
            $this->messages[] = $response['choices'][0]['message'];
            Redis::set('messages', json_encode($this->messages));
            Redis::expire('messages', 60 * 10);
            $botman->reply($this->messages[array_key_last($this->messages)]['content']);
        });
   
        $botman->listen();
    }
}