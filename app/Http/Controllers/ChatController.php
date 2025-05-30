<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Client;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function show()
    {
        return view('chat.form');
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        $userMessage = $request->message;

        try {
            $client = new Client([
                'api_key' => config('services.openai.api_key'),
            ]);

            $systemPrompt = "Bạn là trợ lý thông minh của Rosa Spa...";

            $response = $client->chat()->create([
                'model' => config('services.openai.model', 'gpt-3.5-turbo'),
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);

            $reply = $response->choices[0]->message->content;

            session()->flash('user', $userMessage);
            session()->flash('reply', $reply);

            return redirect()->back();

        } catch (\Exception $e) {
            Log::error('OpenAI Error: ' . $e->getMessage());
            session()->flash('user', $userMessage);
            session()->flash('reply', 'Xin lỗi, tôi đang gặp sự cố kỹ thuật. Vui lòng thử lại sau hoặc liên hệ Rosa Spa.');
            return redirect()->back();
        }
    }
}
