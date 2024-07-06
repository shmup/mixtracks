<?php

$directory = "tracks";
$cacheFile = "metadata_cache.json";

$styles = <<<HTML
  <style>
    a {
      cursor: pointer;
    }
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    .highlighted {
      background-color: yellow;
    }
    #tracks {
      margin-top: 0;
    }
    .track-item {
      background: #e4e4e4;
      border: 2px solid #d4d0c8;
      box-shadow: 2px 2px 5px #808080, -2px -2px 5px #ffffff;
      margin-right: 10px;
      padding: 2px 4px;
    }
    .track-item:active {
      -webkit-tap-highlight-color: transparent;
      background: #c0c0c0;
      border: 2px solid #d4d0c8;
      box-shadow: inset 2px 2px 5px #808080, inset -2px -2px 5px #ffffff;
    }
    .track-item:hover {
      background: pink;
      border: 2px solid #ffb6c1;
      box-shadow: 2px 2px 5px #ffb6c1, -2px -2px 5px #ffffff;
    }
    .track-nav {
      background-color: rgba(255, 255, 255, 0);
      display: none;
      opacity: 97%;
      padding: 8px;
      position: -webkit-sticky;
      position: sticky;
      top: 0;
      user-select: none;
      z-index: 1000;
    }
  </style>
HTML;

$javascript = <<<HTML
<script>
const audioPlayer = document.getElementById('player');
let trackElements = document.querySelectorAll('#tracks li a');

document.querySelector('.track-nav').style.display = 'flex';

function updateTrackElements() {
    trackElements = document.querySelectorAll('#tracks li a');
}

function highlight(element) {
    document.querySelectorAll('.highlighted').forEach(el => el.classList.remove('highlighted'));
    element.parentElement.classList.add('highlighted');
}

function getCurrentTrackIndex() {
    const playerSrc = decodeURIComponent(audioPlayer.src.split('/').pop());
    return Array.from(trackElements).findIndex(el => decodeURIComponent(el.getAttribute('href').split('/').pop()) === playerSrc);
}

function playTrack(src) {
    console.debug('Playing track:', src);
    audioPlayer.src = src;
    audioPlayer.play();
}

function playTrackByOffset(offset) {
    const currentIndex = getCurrentTrackIndex();
    const newIndex = currentIndex === -1 
        ? (offset === -1 ? trackElements.length - 1 : 0) 
        : (currentIndex + offset + trackElements.length) % trackElements.length;
    playTrack(trackElements[newIndex].getAttribute('href'));
}

function shuffleTracks() {
    const trackList = document.getElementById('tracks');
    const tracks = Array.from(trackList.children);
    for (let i = tracks.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [tracks[i], tracks[j]] = [tracks[j], tracks[i]];
    }
    trackList.innerHTML = '';
    tracks.forEach(track => trackList.appendChild(track));
    updateTrackElements();
    playTrack(trackElements[0].getAttribute('href'));
}

audioPlayer.addEventListener('ended', () => playTrackByOffset(1));
audioPlayer.addEventListener('pause', () => document.title = 'Mixtracks');
audioPlayer.addEventListener('play', () => {
    highlight(trackElements[getCurrentTrackIndex()]);
    document.title = trackElements[getCurrentTrackIndex()].textContent;
    document.querySelector('.highlighted').scrollIntoView({ behavior: 'smooth', block: 'center' });
});

document.body.addEventListener("click", function(event) {
    if (event.target.tagName === 'A' && event.target.closest('#tracks')) {
        event.preventDefault();
        playTrack(event.target.href);
    }
});

document.querySelector('.track-nav').addEventListener('click', function(event) {
    if (event.target.classList.contains('track-item')) {
        const action = event.target.textContent.toLowerCase();
        if (action === 'prev') playTrackByOffset(-1);
        if (action === 'next') playTrackByOffset(1);
        if (action === 'shuffle') shuffleTracks();
        if (action === 'stop') {
            audioPlayer.pause();
            audioPlayer.currentTime = 0;
            document.querySelectorAll('.highlighted').forEach(el => el.classList.remove('highlighted'));
        }
    }
});
</script>
HTML;

function listen($path, $text)
{
    return "<a href=\"$path\">$text</a>";
}

function renderTrack($fileName, $metadata)
{
    $cleanFileName = removeLeadingTrackNumber($fileName);
    $title = clean($metadata["title"] ?? $cleanFileName);
    $artist = clean($metadata["artist"] ?? "");
    $album = clean($metadata["album"] ?? "");
    $year = clean($metadata["year"] ?? "");

    return "\t<li>" .
        listen("tracks/$fileName", $title) .
        " by $artist from $album ($year)</li>\r\n";
}

function renderTemplate($trackHtml, $styles, $javascript)
{
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="icon" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAACD0lEQVRYR72XDZaCMAyE5WRwM+FmcDI3E5gaQtKWXV3e8wFSO18nP8XhkR+v49FQGfPnR7XJCQCRr0H0AnClHwe5C/BxkBYAn9twfDQkvQAQ/UpO3AGg/QAZ5nn2ruhz+f5WnvjBVashKh+IPJ7PuZTgtq3lel3f1z0wFuAlx2MYTkx6Y1cL8YeMk5EhgG0M0zQ1k/YEAHrzI002XXVZrRgEwE4Aqh9zhqE5Afi2pnZD/BKoPVJ0wYbAz4H7cRzp7AUizQGIgxyfS2jEgV4AiG/blkKkGbtb/1TLL6HpBLDiy7Jo8iKs1qUQoIjr8GBIB4AXh5NYiIdIAZiMk1h4giiF+q4EnwOZOENqXbgAYPWuEtQxgKwSy/16L695UUvLgd/VxBlKWxXdAB7C2FkA9gYleSMHYk7b/dmG4RYAIeYgoWyX1HFHzKPzrx3gUqNksgBRCH2omAehA60VfB2gZh83G19O/+YAkgydLUvClv10txqCzAGKs9Ts1qtlKXAs1VN9uptqEupERy9g3eLsxe2co/QFNCNtWnLUIPzOmHZCm4gtcYzFRGsHRBcAXcC5Jk4X4IAHiJyI3guqu2GvOB3YRd+h8BC3AOgCIWpJRQciAEJkb0XNN9hsc7L285qTeReO3bT5SpYuki+lWY3DAR8G1yfShTYdsFQRCMULwHnG5vzNAYkt4Z+SY+ytOX8AprGpMIQBWMcAAAAASUVORK5CYII=" />
  <title>Mixtracks</title>
  $styles
</head>
<body>
  <div class="track-nav">
      <a class="track-item">Prev</a>
      <a class="track-item">Next</a>
      <a class="track-item">Shuffle</a>
      <a class="track-item">Stop</a>
  </div>
  $trackHtml
  <audio id="player" class="hidden"></audio>
  $javascript
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

function saveMetadataCache($metadata, $cacheFile)
{
    file_put_contents($cacheFile, json_encode($metadata));
}

function loadMetadataCache($cacheFile)
{
    return file_exists($cacheFile)
        ? json_decode(file_get_contents($cacheFile), true)
        : null;
}

function getCachedOrGenerateMetadata($directory, $cacheFile)
{
    $cachedMetadata = loadMetadataCache($cacheFile);
    if ($cachedMetadata !== null) {
        return $cachedMetadata;
    }

    $metadata = [];
    $files = array_diff(scandir($directory), [".", ".."]);
    foreach ($files as $file) {
        $filePath = $directory . DIRECTORY_SEPARATOR . $file;
        $metadata[$file] = getMP3Metadata($filePath);
    }
    saveMetadataCache($metadata, $cacheFile);
    return $metadata;
}

if (is_dir($directory)) {
    $metadata = getCachedOrGenerateMetadata($directory, $cacheFile);
    $trackHtml =
        "<ol id='tracks'>\r\n" .
        implode(
            "",
            array_map(
                fn($file, $data) => renderTrack($file, $data),
                array_keys($metadata),
                $metadata
            )
        ) .
        "\r\n</ol>";
    echo renderTemplate($trackHtml, $styles, $javascript);
} else {
    echo renderTemplate("no tracks", $styles, $javascript);
}
?>
