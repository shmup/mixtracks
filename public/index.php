<?php
$directory = "tracks";

function removeLeadingTrackNumber($track)
{
    return preg_replace("/^\d{2} - /", "", $track);
}

if (is_dir($directory)) {
    $files = scandir($directory);
    echo "<ol>";
    foreach ($files as $file) {
        if ($file !== "." && $file !== "..") {
            echo "<li>" .
                htmlspecialchars(removeLeadingTrackNumber($file)) .
                "</li>";
        }
    }
    echo "</ol>";
} else {
    echo "no tracks";
}
?>
