<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\ReplyRequest;
use App\Models\Reply;
use App\Models\Topic;
use App\Transformers\ReplyTransformer;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function store(ReplyRequest $request, Topic $topic, Reply $reply)
    {
        $data = $request->all();
        $data['topic_id'] = $topic->id;
        $data['user_id'] = $this->user()->id;
        $reply->fill($data)->save();

        return $this->response->item($reply, new ReplyTransformer())->setStatusCode(201);
    }
}
