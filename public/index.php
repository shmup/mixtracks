<?php

$directory = "tracks";

function removeLeadingTrackNumber($track)
{
    return preg_replace("/^\d{2} - /", "", $track);
}

function clean($string)
{
    return htmlspecialchars($string);
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

function getTrackMetadata($filePath)
{
    $getID3 = new getID3();
    $fileInfo = $getID3->analyze($filePath);
    getid3_lib::CopyTagsToComments($fileInfo);

    return [
        "title" => $fileInfo["comments_html"]["title"][0] ?? "Unknown Title",
        "artist" => $fileInfo["comments_html"]["artist"][0] ?? "Unknown Artist",
        "album" => $fileInfo["comments_html"]["album"][0] ?? "Unknown Album",
        "year" => $fileInfo["comments_html"]["year"][0] ?? "Unknown Year",
    ];
}

function renderTrack($fileName, $metadata)
{
    if (empty($metadata["title"])) {
        return '\t<li>' . removeLeadingTrackNumber($fileName) . "</li>";
    }

    $title = clean($metadata["title"]);
    $artist = clean($metadata["artist"]);
    $album = clean($metadata["album"]);
    $year = clean($metadata["year"]);

    return <<<HTML
\t<li>$title by $artist ($album, $year)</li>\r\n
HTML;
}

function renderTemplate($trackHtml)
{
    return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width" />
  <title></title>
  <style></style>
</head>
<body>
  $trackHtml
  <script></script>
</body>
</html>
HTML;
}

if (is_dir($directory)) {
    $files = scandir($directory);
    $trackHtml = "<ol>\r\n";
    foreach ($files as $file) {
        if ($file == "." || $file == "..") {
            continue;
        }
        $filePath = $directory . DIRECTORY_SEPARATOR . $file;
        $metadata = getMP3Metadata($filePath);
        $trackHtml .= renderTrack($file, $metadata);
    }
    $trackHtml .= "\r\n</ol>";
    echo renderTemplate($trackHtml);
} else {
    echo renderTemplate("no tracks");
}
?>
