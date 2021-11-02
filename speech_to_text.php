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


    $stturl = "https://speech.googleapis.com/v1p1beta1/speech:recognize?key=AIzaSyD_aQ2jGFndPRAIlKAV7Bozso8RtCASVY8";
    $upload = file_get_contents($audioFile);


    $audio_content = base64_encode($upload);
    $lang = $_POST['lang'];
    $punctuation = isset($_POST['punctuation']) && $_POST['punctuation'] == 'true' ? true : false;

    $data = array(
        "config"    =>  array(
            "enableAutomaticPunctuation" => $punctuation,
            "encoding" => "MP3",
            "sampleRateHertz" => 24000,
            "languageCode" => $lang,
        ),
        "audio"     =>  array(
            "content"       =>  $audio_content,
        )
    );

    $jsonData = json_encode($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $stturl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    

    $response = curl_exec($ch);

    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);


    $response = json_decode($response);

    // var_dump($response);

    $results = $response->results;



    foreach ($results as $result) {
        
        $alternatives = $result->alternatives;
        $alternatives = $alternatives[0];
        $text_result = $alternatives->transcript;
        $confidence = $alternatives->confidence;
    }

    curl_close($ch);
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
            <h2>Please fill all information</h2>
            <div class="form-group">
                <input type="file" class="form-control" name="audio_file" id="text" placeholder="Select audio file" required/>
            </div>
            <div class="form-group">
                <select class="form-control" name="lang" id="lang" placeholder="select language">
                    <option value="en-US">English</option>
                    <option value="af-ZA" <?php if(isset($_POST['lang']) && $_POST['lang'] == 'af-ZA') echo 'selected'?>>Afrikaans</option>
                    <option value="ar-DZ" <?php if(isset($_POST['lang']) && $_POST['lang'] == 'ar-DZ') echo 'selected'?>>Arabic</option>
                    <option value="zh"    <?php if(isset($_POST['lang']) && $_POST['lang'] == 'zh') echo 'selected'?>>Chinese</option>
                    <option value="nl-NL" <?php if(isset($_POST['lang']) && $_POST['lang'] == 'nl-NL') echo 'selected'?>>Dutch</option>
                    <option value="ru-RU" <?php if(isset($_POST['lang']) && $_POST['lang'] == 'ru-RU') echo 'selected'?>>Russian</option>
                    <option value="es-AR" <?php if(isset($_POST['lang']) && $_POST['lang'] == 'es-AR') echo 'selected'?>>Spanish</option>
                    <option value="sv-SE" <?php if(isset($_POST['lang']) && $_POST['lang'] == 'sv-SE') echo 'selected'?>>Swedish</option>
                    <!-- <option value=""></option>
                    <option value=""></option>
                    <option value=""></option>
                    <option value=""></option> -->
                </select>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="true" id="defaultCheck1" name="punctuation">
                <label class="form-check-label" for="defaultCheck1">
                    Punctuation
                </label>
            </div>

            <div class="form-group mt-2">
                <button class="btn btn-success" name="speech_to_text">Start</button>
            </div>

            <div class="form-group">
                <h2>Bellow is result</h2>
                <?php if (isset($confidence) && $confidence) echo '<span>confidence: '.$confidence.'</span><br>'; ?>
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