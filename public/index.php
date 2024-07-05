<?php
require_once "/usr/src/php/getID3/getid3/getid3.php";

$directory = "tracks";

function removeLeadingTrackNumber($track)
{
    return preg_replace("/^\d{2} - /", "", $track);
}

function clean($string)
{
    return htmlspecialchars($string);
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

function renderTrack($file, $metadata)
{
    $track = clean(removeLeadingTrackNumber($file));
    $title = clean($metadata["title"]);
    $artist = clean($metadata["artist"]);
    $album = clean($metadata["album"]);
    $year = clean($metadata["year"]);

    return <<<HTML
<li>
    $track - $title by $artist ($album, $year)
</li>
HTML;
}

if (is_dir($directory)) {
    $files = scandir($directory);
    echo "<ol>";
    foreach ($files as $file) {
        if ($file == "." || $file == "..") {
            continue;
        }
        $filePath = $directory . DIRECTORY_SEPARATOR . $file;
        $metadata = getTrackMetadata($filePath);
        echo renderTrack($file, $metadata);
    }
    echo "</ol>";
} else {
    echo "no tracks";
}
?>
