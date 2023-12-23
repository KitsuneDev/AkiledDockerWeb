<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <button onclick="startRecordAudio()">Grabar audio</button>
    <button onclick="stopRecordAudio()">Parar de grabar audio</button>
    <div id="reproducir" style="display: none;">
        <button onclick="listenAudio()">Escuchar nota de voz</button>
    </div>

    <script>
        var mediaRecorder = null;
        var audioChunks = null;

        function startRecordAudio(){
            navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                mediaRecorder = new MediaRecorder(stream);
                    mediaRecorder.start();

                audioChunks = [];
                    mediaRecorder.addEventListener("dataavailable", event => {
                    audioChunks.push(event.data);
                });

                mediaRecorder.addEventListener("stop", () => {
                    document.getElementById("reproducir").style.display = "block";
                    sendAudioToServer()
                });
            });
        }

        function stopRecordAudio(){
            mediaRecorder.stop();
        }

        function sendAudioToServer(){
            var formData = new FormData();
            formData.append('audio', new Blob(audioChunks));

            fetch("https://int.kubbo.city/audio", {
                method: "POST",
                body: formData
            }).then(function(response){
                response.text().then(function(text){
                    console.log(text)
                })
            });
        }

        function listenAudio(){
            const audioBlob = new Blob(audioChunks);
            const audio = new Audio(audioUrl);
            audio.play();
        }

    </script>
</body>
</html>
