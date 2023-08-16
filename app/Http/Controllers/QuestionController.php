<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;


class QuestionController extends Controller
{
    public function index()
    {
      // 仮のデータ。実際にはデータベース等から問題のリストを取得
    $questions = [
        ['id' => 1, 'title' => 'Question 1'],
        ['id' => 2, 'title' => 'Question 2'],
        // 必要なだけ追加
    ];
    return view('questions.index', compact('questions'));
    }

    public function create()
    {
        return view('questions.create');
    }

    public function show($id)
    {
        return view('questions.show', compact('id'));
    }

    public function checkAnswer($id)
    {
        return view('questions.check', compact('id'));
    }

    public function showQuestionForm()
    {
        return view('questions.question_form');
    }

    public function showHintForm()
    {
            return view('questions.hint_form');
    }

    public function edit($id)
    {
            return view('questions.edit', compact('id'));
    }

    public function detail($id)
    {
         // ログインしていなければログインページへリダイレクト
        if (Auth::guest()) {
            return redirect()->route('login');
        }
        // 仮のデータ。実際にはデータベース等から問題の詳細を取得
        $questionDetail = [
            'id' => $id,
            'title' => 'Question ' . $id,
            'content' => 'This is a detail of question ' . $id,
            // 必要なだけ追加
        ];

            return view('questions.detail', compact('questionDetail'));
    }

    public function generateQuestion(Request $request)
    {
        $genre = $request->input('genre');
        $difficulty = $request->input('difficulty');
    
        // OpenAI APIのエンドポイントとパラメータを設定
        $endpoint = "https://api.openai.com/v1/chat/completions";
        $prompt = "あなたは、水平思考ゲームのゲームマスターです。今から問題を作成していただきたいです。";
        $content = "Create a {$difficulty} question about {$genre}.";
    
        $client = new Client();
    
        $response = $client->post($endpoint, [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'model' => "gpt-3.5-turbo",
                "messages" => [
                    [
                        "role" => "system",
                        "content" => $prompt
                    ],
                    [
                        "role" => "user",
                        "content" => $content
                    ]
                ]
            ]
        ]);
    
        $data = json_decode($response->getBody(), true);
        //dd($data['choices'][0]['message']['content']);
        // dd($data); 
        $questionContent = $data['choices'][0]['message']['content'] ?? 'Failed to generate question';
    
        return redirect()->route('questions.create')->with('question', $questionContent);
    }
    public function showGenerated()
{
    $question = session('question', 'No question generated');
    return view('questions.generated', compact('question'));
}
    }
