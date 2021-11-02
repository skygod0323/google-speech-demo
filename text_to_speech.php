<?php

require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;

if (isset($_POST['text_to_speech'])) {

    $text = $_POST['text'];
    $lang = $_POST['lang'];

    $KEY_FILE_LOCATION = __DIR__ . '/speech-319913-8b5137cf26f2.json';

    $config = [
        'credentials' => $KEY_FILE_LOCATION
    ];
    $textToSpeechClient = new TextToSpeechClient($config);

    $input = new SynthesisInput();
    $input->setText($text);
    $voice = new VoiceSelectionParams();
    $voice->setLanguageCode($lang);
    $audioConfig = new AudioConfig();
    $audioConfig->setAudioEncoding(AudioEncoding::MP3);
    $audioConfig->setSpeakingRate($_POST['speed']);
    $audioConfig->setPitch($_POST['pitch']);

    if (isset($_POST['effects_profile_id']) && $_POST['effects_profile_id']) {
        $effectsProfileId = array($_POST['effects_profile_id']);
        $audioConfig->setEffectsProfileId($effectsProfileId);
    }
    


    $resp = $textToSpeechClient->synthesizeSpeech($input, $voice, $audioConfig);
    file_put_contents('test.mp3', $resp->getAudioContent());


    $filepath = "test.mp3";

    // Process download
    if(file_exists($filepath)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        flush(); // Flush system output buffer
        readfile($filepath);
        die();
    } else {
        
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
        <form method="post">
            <div class="form-group row">
                <label for="text" class="col-sm-2 col-form-label">Text</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="text" id="text">How are you</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label for="lang" class="col-sm-2 col-form-label">Language</label>
                <div class="col-sm-10">
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
            </div>

            <div class="form-group row">
                <div class="col-6">
                    <label for="customRange1">Speed</label>
                    <input type="range" name="speed" class="custom-range" id="customRange1" style="width: 100%" min="0.25" max="4.00" step="0.01" value="<?php if(isset($_POST['speed'])) {echo $_POST['speed'];} else {echo '1';} ?>">
                </div>
                <div class="col-6">
                    <label for="customRange1">Pitch</label>
                    <input type="range" name="pitch" class="custom-range" id="customRange1" style="width: 100%" min="-20" max="20" value="<?php if(isset($_POST['pitch'])) echo $_POST['pitch']; ?>">
                </div>
            </div>

            <div class="form-group row">
                <label for="audio_device" class="col-sm-2 col-form-label">Audio Device Profile</label>
                <div class="col-sm-10">
                    <select class="form-control" name="effects_profile_id" id="audio_device" placeholder="">
                        <option value="">Default</option>
                        <option value="wearable-class-device">Smart Watch or Wearable</option>
                        <option value="handset-class-device">Smartphones</option>
                        <option value="headphone-class-device">Earbuds or headphones</option>
                        <option value="small-bluetooth-speaker-class-device">Small home speaker</option>
                        <option value="medium-bluetooth-speaker-class-device">Smart home speaker</option>
                        <option value="large-home-entertainment-class-device">Home entertainment systems or smart TV</option>
                        <option value="large-automotive-class-device">Car speaker</option>
                        <option value="telephony-class-application">Interactive Voice Response</option>
                    </select>
                </div>
            </div>

            <button class="btn btn-success" name="text_to_speech">Start</button>
        </form>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>