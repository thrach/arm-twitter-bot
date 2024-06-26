<?php

namespace App\Integrations;

use Google\Cloud\Language\Annotation;
use Google\Cloud\Language\LanguageClient;

class GoogleSentimentAnalyzer
{
    protected LanguageClient $client;

    public function __construct()
    {
        $authKeyFile = config('services.google.sentiment.auth_key_file');

        $this->client = new LanguageClient([
            'keyFilePath' => storage_path("keys/{$authKeyFile}"),
        ]);
    }

    public function forText(string $text): array
    {
        return $this->client->analyzeSentiment($text)->info();
    }
}
