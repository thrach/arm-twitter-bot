<?php

namespace App\Facades;

use App\Integrations\GoogleSentimentAnalyzer;
use Google\Cloud\Language\Annotation;
use Illuminate\Support\Facades\Facade;

/**
 * @method static array forText(string $text)
 */
class SentimentAnalyse extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return GoogleSentimentAnalyzer::class;
    }
}
