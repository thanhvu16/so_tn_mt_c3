<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\DonViRepository;
use App\Repositories\HomeRepository;
use App\Repositories\MessageRepository;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->middleware('guest')->except('logout');
        $this->messageRepository = $messageRepository;
    }

    public function index()
    {
        return response()->json([
            'status' => SUCCESS,
            'message' => 'OK',
            'data' => $this->messageRepository->getMessage()
        ]);

    }
}
