<?php

require "functions.php";
require "headers.php";

try {
    $dbcon = createDbConnection();

    $body = file_get_contents("php://input");
    $data = json_decode($body);

    $artist_name = $data->artist_name;
    $album_name = $data->album_name;
    $tracks = $data->tracks;

    // Insert new artist
    $statement = $dbcon->prepare("INSERT INTO artists (Name) VALUES (?)");
    $statement->execute([$artist_name]);
    $artist_id = $dbcon->lastInsertId();

    // Insert new album
    $statement = $dbcon->prepare("INSERT INTO albums (Title, ArtistId) VALUES (?, ?)");
    $statement->execute([$album_name, $artist_id]);
    $album_id = $dbcon->lastInsertId();

    // Insert new tracks
    $statement = $dbcon->prepare("INSERT INTO tracks (Name, AlbumId, MediaTypeId) VALUES (?, ?, 1)");
    foreach ($tracks as $track) {
        $statement->execute([$track, $album_id]);
    }

} catch (PDOException $e) {
    echo $e->getMessage();
}
