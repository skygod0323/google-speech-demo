<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;

$KEY_FILE_LOCATION = __DIR__ . '/speech-319913-8b5137cf26f2.json';

$authConfig = [
    'credentials' => $KEY_FILE_LOCATION
    // 'projectId' => 'speech-319913',
];

// change these variables if necessary
$encoding = AudioEncoding::FLAC;
$sampleRateHertz = 32000;
$languageCode = 'en-US';

?>
