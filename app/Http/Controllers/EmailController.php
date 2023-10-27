<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmailSendRequest;
use App\Jobs\SendEmailJob;
use App\Utilities\Contracts\ElasticsearchHelperInterface;
use App\Utilities\Contracts\RedisHelperInterface;
use Illuminate\Support\Facades\Redis;

class EmailController extends Controller
{
    // TODO: finish implementing send method
    public function send(EmailSendRequest $request): void
    {
        $messageBody = $request->messageBody;
        $messageSubject = $request->messageSubject;
        $toEmailAddress = $request->toEmailAddress;

        dispatch(new SendEmailJob($messageBody, $messageSubject, $toEmailAddress));

        /** @var ElasticsearchHelperInterface $elasticsearchHelper */
        $elasticsearchHelper = app()->make(ElasticsearchHelperInterface::class);
        // TODO: Create implementation for storeEmail and uncomment the following line
        $res = $elasticsearchHelper->storeEmail($messageBody, $messageSubject, $toEmailAddress);

        /** @var RedisHelperInterface $redisHelper */
        $redisHelper = app()->make(RedisHelperInterface::class);
        // TODO: Create implementation for storeRecentMessage and uncomment the following line
        $redisHelper->storeRecentMessage($res['_id'], $messageSubject, $toEmailAddress);
    }

    //  TODO - BONUS: implement list method
    public function list()
    {
        /** @var ElasticsearchHelperInterface $elasticsearchHelper */
        $elasticsearchHelper = app()->make(ElasticsearchHelperInterface::class);

        $data = [];
        $res = $elasticsearchHelper->listEmails();

        if (count($res)) {
            $emails = $res['hits']['hits'];
            if (count($emails)) {
                foreach ($emails as $email) {
                    $data[] = [
                        'id' => $email['_id'],
                        'index' => $email['_index'],
                        'email' => $email['_source']['email'],
                        'subject' => $email['_source']['subject'],
                        'body' => $email['_source']['message'],
                    ];
                }
            }
        }

        return response()->json($data, 200);
    }
}
