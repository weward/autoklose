<?php

namespace App\Utilities\Services;

use Elasticsearch;
use Ramsey\Uuid\Uuid;

class ElasticSearchService
{
    public function storeEmail(string $messageBody, string $messageSubject, string $toEmailAddress): mixed
    {
        $id = Uuid::uuid4()->toString();

        $data = [
            'body' => [
                'message'   => $messageBody,
                'subject'   => $messageSubject,
                'email'     => $toEmailAddress,
            ],
            'index' => "{$id}_index",
            'type' => 'email_type',
            'id' => $id,
        ];

        return Elasticsearch::index($data);
    }

    public function listEmails(): mixed
    {
        $params = [
            'body'  => [
                'query' => [
                    'match' => [
                        '_type' => 'email_type'
                    ]
                ]
            ]
        ];

        return Elasticsearch::search($params);
    }

}
