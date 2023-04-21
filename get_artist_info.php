<?php

require "functions.php";
require "headers.php";

$artist_id = 3; //id jolla voi hakea artistia ja hänen kappaleita, en saanut toimimaan $_GET
//$_GET["ArtistId"];


try {
    $dbcon = createDbConnection();

    $statement = $dbcon->prepare("
    SELECT artists.Name AS artist_name, albums.Title AS album_name, tracks.Name AS song_name
    FROM artists
    JOIN albums ON artists.ArtistId = albums.ArtistId
    JOIN tracks ON albums.AlbumId = tracks.AlbumId
    WHERE artists.ArtistId = :artist_id");
    
    $statement->bindParam(':artist_id', $artist_id, PDO::PARAM_INT);
    $statement->execute();

    $rows = $statement->fetchAll(PDO::FETCH_ASSOC);

    $albums = array();
    foreach ($rows as $row) {
        $album_name = $row['album_name'];
        if (!isset($albums[$album_name])) {
            $albums[$album_name] = array(
                "title" => $album_name,
                "tracks" => array()
            );
        }
        $albums[$album_name]["tracks"][] = $row["song_name"];
    }

    $json = array(
        "artist" => $rows[0]["artist_name"],
        "albums" => array_values($albums)
    );
    $json = json_encode($json);

    echo $json;

} catch (PDOException $e) {
echo "Database error: " . $e->getMessage();
} catch (Exception $e) {
echo "Error: " . $e->getMessage();
}
