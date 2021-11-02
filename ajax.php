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
        <a href="speech_to_text1.php">Speech To Text1</a><br>
        <a href="speech_to_text.php">Speech To Text</a><br>
        <a href="text_to_speech.php">Text To Speech</a>

        <button>test</button>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script>

        $(() => {
            var data = {
                        config: {
                            "enableAutomaticPunctuation": true,
                            "encoding": "MP3",
                            "sampleRateHertz": 16000,
                            "languageCode": "en-US",
                        },
                        audio: {
                            content: "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"
                        }
                    };

            $('button').click(() => {
                $.ajax({
                    method: 'post',
                    dataType: "json",
                    "headers": {
                        "content-type": "application/json",
                    },
                    url: 'https://speech.googleapis.com/v1p1beta1/speech:recognize?key=AIzaSyD_aQ2jGFndPRAIlKAV7Bozso8RtCASVY8',
                    data: JSON.stringify(data),
                    success: (result) => {
                        console.log(result);
                    }
                })
            })
        })
    </script>
  </body>
</html>