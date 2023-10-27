<?php

namespace App\Utilities\Contracts;

interface ElasticsearchHelperInterface {
    /**
     * Store the email's message body, subject and to address inside elasticsearch.
     *
     * @param  string  $messageBody
     * @param  string  $messageSubject
     * @param  string  $toEmailAddress
     * @return mixed - Return the id of the record inserted into Elasticsearch
     */
    public function storeEmail(string $messageBody, string $messageSubject, string $toEmailAddress): mixed;

    /**
     * List emails from elasticsearch.
     *
     * @param  int     $page
     * @param  int     $perPage
     * @return mixed - Return the list of emails fetched from Elasticsearch
     */
    public function listEmails(): mixed;
}
