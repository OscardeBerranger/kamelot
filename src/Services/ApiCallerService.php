<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiCallerService
{
    public function __construct(
        private HttpClientInterface $client,
    ){}

    public function callerCitation(){
        $response = $this->client->request(
            'GET',
            'https://kaamelott.chaudie.re/api/random'
        );
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;
    }
}