<?php
require_once "/usr/src/php/getID3/getid3/getid3.php";

$directory = "tracks";

function removeLeadingTrackNumber($track)
{
    return preg_replace("/^\d{2} - /", "", $track);
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

if (is_dir($directory)) {
    $files = scandir($directory);
    echo "<ol>";
    foreach ($files as $file) {
        if ($file !== "." && $file !== "..") {
            $filePath = $directory . DIRECTORY_SEPARATOR . $file;
            $metadata = getTrackMetadata($filePath);
            echo "<li>" .
                htmlspecialchars(removeLeadingTrackNumber($file)) .
                " - " .
                htmlspecialchars($metadata["title"]) .
                " by " .
                htmlspecialchars($metadata["artist"]) .
                " (" .
                htmlspecialchars($metadata["album"]) .
                ", " .
                htmlspecialchars($metadata["year"]) .
                ")" .
                "</li>";
        }
    }
    echo "</ol>";
} else {
    echo "no tracks";
}
?>
