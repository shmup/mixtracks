<?php

$directory = "tracks";
$cacheFile = "metadata_cache.json";

function listen($path, $text)
{
    return "<a href=\"$path\">$text</a>";
}

function renderTrack($fileName, $metadata)
{
    if (empty($metadata["title"])) {
        return "\t<li>" . removeLeadingTrackNumber($fileName) . "</li>";
    }

    $title = clean($metadata["title"]);
    $artist = clean($metadata["artist"]);
    $album = clean($metadata["album"]);
    $year = clean($metadata["year"]);

    return "\t<li>" .
        listen("tracks/$fileName", $title) .
        " by $artist from $album ($year)</li>\r\n";
}

function renderTemplate($trackHtml)
{
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Mixtracks</title>
  <link rel="icon" href="data:image/png;base64,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h8eBgYE3f39/IISEhBt/f38EAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACAAAAC/AAAAIAAAAAQAAAAzAAAAxwAAAOMAAADbDg4OJISEhBt/f38gf39/IH9/fyB/f38ghISEG39/fwQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIAAAAMMAAAA7AAAANwAAAMsAAAD7AAAA/wAAAN8AAAAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgAAAA2wAAAOMAAADjAAAA+wAAAPsAAADjAAAAwwAAABsAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACAAAADfAAAA/wAAAPsAAADjAAAAxwAAADcAAAAbAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAIAAAAN8AAAD7AAAAywAAADcAAAAbAAAABAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAgAAAA3wAAAOMAAAA3AAAABAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA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AAAAAAAAAAAAAAAAAAAAAAAAAAAANAAAAMAAAACbAAAAQQAAAD0AAACXAAAA7wAAAP0AAAD/AAAA/wAAAPcAAAB/AAAADAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAANAAAAMUAAADlAAAAzQAAAMwAAADlAAAA/AAAAP4AAAD5AAAA8wAAAOsAAAB5AAAACwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAANAAAAMcAAAD/AAAA/wAAAP8AAAD/AAAA/wAAAPsAAAC/AAAAhQAAAHsAAAA/AAAABQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAANAAAAMcAAAD/AAAA/wAAAP0AAADjAAAAyQAAAMEAAABnAAAAEAAAAAcAAAADAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAANAAAAMcAAAD/AAAA/AAAAO8AAACTAAAAPAAAADIAAAAZAAAAAgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAANAAAAMcAAAD/AAAA5QAAAJcAAAA/AAAABQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAANAAAAMcAAAD/AAAAzAAAAD0AAAADAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA==" />
  <style>
  #tracks { margin-top: 0; }
  a { cursor: pointer; }
  .track-nav {
    display: flex;
  }
  .track-item {
    margin-right: 10px;
  }
  </style>
</head>
<body>
  <div class="track-nav">
    <p class="track-item">
      <a onClick="playPrevTrack()">Prev</a>
    </p>
    <p class="track-item">
      <a onClick="playNextTrack()">Next</a>
    </p>
    <p class="track-item">
      <a onClick="shuffleTracks()">Shuffle</a>
    </p>
  </div>
  $trackHtml
  <audio id="player" class="hidden"></audio>
<script>
const audioPlayer = document.getElementById('player');
let trackElements = document.querySelectorAll('#tracks li a');

function playTrack(src) {
    console.debug('Playing track:', src);
    audioPlayer.src = src;
    audioPlayer.play();
}
function getCurrentTrackIndex() {
    let playerSrc = document.getElementById('player').src;
    const decodedPlayerSrc = decodeURIComponent(playerSrc.split('/').pop());

    const index = Array.from(trackElements).findIndex(el => {
        const decodedHref = decodeURIComponent(el.getAttribute('href').split('/').pop());
        return decodedHref === decodedPlayerSrc;
    });

    return index >= 0 ? index : 0;
}

function playTrackByOffset(offset) {
    let currentIndex = getCurrentTrackIndex();

    let newIndex = (currentIndex === -1) 
        ? (offset > 0 ? 0 : trackElements.length - 1) 
        : (currentIndex + offset + trackElements.length) % trackElements.length;

    playTrack(trackElements[newIndex].getAttribute('href'));
}

function shuffleTracks() {
    const trackList = document.getElementById('tracks');
    const tracks = Array.from(trackList.children);
    
    // Fisher-Yates shuffle
    for (let i = tracks.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [tracks[i], tracks[j]] = [tracks[j], tracks[i]];
    }

    trackList.innerHTML = tracks.map(track => track.outerHTML).join('');

    trackElements = document.querySelectorAll('#tracks li a');

    playTrack(trackElements[0].getAttribute('href'));
}

function playNextTrack() {
    playTrackByOffset(1);
}

function playPrevTrack() {
    playTrackByOffset(-1);
}

audioPlayer.addEventListener('ended', playNextTrack);

document.addEventListener("DOMContentLoaded", function() {
    const trackLinks = document.querySelectorAll("#tracks a");

    trackLinks.forEach(link => {
        link.addEventListener("click", function(event) {
            event.preventDefault();
            playTrack(link.href);
        });
    });
});

</script>
</body>
</html>
HTML;
}

function removeLeadingTrackNumber($track)
{
    return preg_replace("/^\d{2} - /", "", $track);
}

function clean($string)
{
    return htmlspecialchars($string, ENT_QUOTES, "UTF-8");
}

function getMP3Metadata($filePath)
{
    $metadata = [];

    if (!file_exists($filePath)) {
        return $metadata;
    }

    $file = fopen($filePath, "rb");
    if (!$file) {
        return $metadata;
    }

    fseek($file, -128, SEEK_END);
    $tag = fread($file, 128);
    fclose($file);

    if (substr($tag, 0, 3) == "TAG") {
        $metadata["title"] = trim(substr($tag, 3, 30));
        $metadata["artist"] = trim(substr($tag, 33, 30));
        $metadata["album"] = trim(substr($tag, 63, 30));
        $metadata["year"] = trim(substr($tag, 93, 4));
        $metadata["comment"] = trim(substr($tag, 97, 30));
        $metadata["genre"] = ord(substr($tag, 127, 1));
    }

    return $metadata;
}

function saveMetadataCache($metadata)
{
    global $cacheFile;
    file_put_contents($cacheFile, json_encode($metadata));
}

function loadMetadataCache()
{
    global $cacheFile;
    if (file_exists($cacheFile)) {
        return json_decode(file_get_contents($cacheFile), true);
    }
    return null;
}

function generateAndCacheMetadata($directory)
{
    $metadata = [];
    $files = array_diff(scandir($directory), [".", ".."]);
    foreach ($files as $file) {
        $filePath = $directory . DIRECTORY_SEPARATOR . $file;
        $metadata[$file] = getMP3Metadata($filePath);
    }
    saveMetadataCache($metadata);
    return $metadata;
}

function getCachedOrGenerateMetadata($directory)
{
    $cachedMetadata = loadMetadataCache();
    if ($cachedMetadata !== null) {
        return $cachedMetadata;
    }
    return generateAndCacheMetadata($directory);
}

if (is_dir($directory)) {
    $metadata = getCachedOrGenerateMetadata($directory);
    $trackHtml = "<ol id='tracks'>\r\n";
    foreach ($metadata as $file => $data) {
        $trackHtml .= renderTrack($file, $data);
    }
    $trackHtml .= "\r\n</ol>";
    echo renderTemplate($trackHtml);
} else {
    echo renderTemplate("no tracks");
}

?>
