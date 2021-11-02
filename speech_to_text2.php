<?php

require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;



if (isset($_POST['speech_to_text'])) {

    // upload file
    $target_dir = "uploads/";
    $audioFile = $target_dir . basename($_FILES["audio_file"]["name"]);
    move_uploaded_file($_FILES["audio_file"]["tmp_name"], $audioFile);


    // convert to text from here
    $KEY_FILE_LOCATION = __DIR__ . '/speech-319913-8b5137cf26f2.json';

    $authConfig = [
        'credentials' => $KEY_FILE_LOCATION
        // 'projectId' => 'speech-319913',
    ];

    // change these variables if necessary
    $encoding = AudioEncoding::LINEAR16;
    $sampleRateHertz = 32000;
    $languageCode = 'en-US';

    // $audioFile = 'test.mp3';

    // get contents of a file into a string
    $content = file_get_contents($audioFile);

    // set string as audio content
    $audio = (new RecognitionAudio())->setContent($content);

    // set config
    $config = (new RecognitionConfig())
        ->setEncoding($encoding)
        ->setSampleRateHertz($sampleRateHertz)
        ->setLanguageCode($languageCode);
        // ->setEnableAutomaticPunctuation(true);


    // create the speech client
    $client = new SpeechClient($authConfig);

    // create the asyncronous recognize operation
    $operation = $client->longRunningRecognize($config, $audio);
    $operation->pollUntilComplete();

    $text_result;
    if ($operation->operationSucceeded()) {
        $response = $operation->getResult();

        // each result is for a consecutive portion of the audio. iterate
        // through them to get the transcripts for the entire audio file.
        $results = $response->getResults();

        // var_dump($results);
        
        foreach ($response->getResults() as $result) {
            $alternatives = $result->getAlternatives();
            $mostLikely = $alternatives[0];
            $transcript = $mostLikely->getTranscript();
            $confidence = $mostLikely->getConfidence();
            // echo('Transcript: ' . $transcript);
            // echo('Confidence: ' . $confidence);

            $text_result = $transcript;
        }
    } else {
        var_dump($operation->getError());
    }

}
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>Hello, world!</title>
  </head>
  <body>
    <div class="container mt-5">
        <form method="post"  enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" class="form-control" name="audio_file" id="text" placeholder="Select audio file" required/>
            </div>
            <div class="form-group">
                <select class="form-control" name="lang" id="lang" placeholder="select language">
                    <option value="en-US">English</option>
                    <option value="af-ZA">Afrikaans</option>
                    <option value="ar-DZ">Arabic</option>
                    <option value="zh">Chinese</option>
                    <option value="nl-NL">Dutch</option>
                    <option value="ru-RU">Russian</option>
                    <option value="es-AR">Spanish</option>
                    <option value="sv-SE">Swedish</option>
                    <!-- <option value=""></option>
                    <option value=""></option>
                    <option value=""></option>
                    <option value=""></option> -->
                </select>
            </div>

            <div class="form-group">
                <button name="speech_to_text">Start</button>
            </div>

            <div class="form-group">
                <p><?php if (isset($_POST['speech_to_text']) && !isset($text_result)) echo 'no result'; ?></p>
                <textarea class="form-control"><?php if (isset($text_result) && $text_result) echo $text_result; ?></textarea>
            </div>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>