<?php

// Google Translate API endpoint
$apiEndpoint = 'https://translation.googleapis.com/language/translate/v2';

// Your Google Translate API key
$apiKey = 'AIzaSyCTT6T91u-XQPHzCdqIXKnzZl-_O75dC_8';

// Source language code
$sourceLanguage = 'en';

// Target language code
$targetLanguage = 'ru';

// Text to translate
$text = 'Hello, world!';
$messages = require_once('resources/lang/ru/messages.php');
foreach ($messages as $key => $value) {
    $text = $value;
    $url = $apiEndpoint . '?key=' . $apiKey . '&q=' . urlencode($text) . '&source=' . $sourceLanguage . '&target=' . $targetLanguage;

    $response = file_get_contents($url);
    $data = json_decode($response, true);
    if ($data && isset($data['data']['translations'][0]['translatedText'])) {
        $translatedText = $data['data']['translations'][0]['translatedText'];
        $translations[$key] = $value;
        file_put_contents('result.php', '<?php return ' . var_export($translations, true) . ';' . PHP_EOL);
    } else {
        echo 'Translation failed.';
    }
}
