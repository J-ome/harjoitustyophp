<?php
require "inc/functions.php";



try {
    $dbcon = createDbConnection();

    $playlist_id = 1; //tähän id jonka playlistin haluaa hakea

    $sql = "SELECT tracks.Name AS TrackName, albums.Title AS AlbumTitle, tracks.Composer AS ComposerName
            FROM playlists
            JOIN playlist_track ON playlists.playlistId = playlist_track.playlistId
            JOIN tracks ON playlist_track.trackId = tracks.TrackId
            JOIN albums ON tracks.AlbumId = albums.AlbumId
            WHERE playlists.playlistId = :playlist_id";

    $statement = $dbcon->prepare($sql);
    $statement->bindParam(':playlist_id', $playlist_id);
    $statement->execute();

    foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
        echo "<h2>" . $row['TrackName'] ."</h2>". "<br>";
        echo "(" . $row['ComposerName'] .")". "<br><br>";
    }

    
}catch (PDOException $e) {
    echo $e->getMessage();
}


