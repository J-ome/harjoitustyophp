<?php

require "functions.php";
$dbcon = createDbConnection();

$artist_id = $_GET["ArtistId"]; //executeen se ID jolla haluaa poistaa tiedot

try {
    $dbcon->beginTransaction();
    
    $statement1 = $dbcon->prepare("DELETE FROM playlist_track WHERE TrackId IN (SELECT TrackId FROM tracks WHERE AlbumId IN (SELECT AlbumId FROM albums WHERE ArtistId = ?))");
    $statement1->execute([2]);

    $statement2 = $dbcon->prepare("DELETE FROM invoice_items WHERE TrackId IN (SELECT TrackId FROM tracks WHERE AlbumId IN (SELECT AlbumId FROM albums WHERE ArtistId = ?))");
    $statement2->execute([2]);

    $statement3 = $dbcon->prepare("DELETE FROM tracks WHERE AlbumId IN (SELECT AlbumId FROM albums WHERE ArtistId = ?)");
    $statement3->execute([2]);

    $statement4 = $dbcon->prepare("DELETE FROM albums WHERE ArtistId = ?");
    $statement4->execute([2]);

    $statement5 = $dbcon->prepare("DELETE FROM artists WHERE ArtistId = ?");
    $statement5->execute([2]);


    $dbcon->commit();
} catch (PDOException $e) {

    $dbcon->rollBack();

    echo $e->getMessage();
}


/*Tiedostossa on parametreina artist_id. Poista kyseinen artisti ja kaikki siihen liittyvät
tiedot kannasta transaktiona. Huom! Tutki kannan rakennetta ja poista
riippuvuudet oikeassa järjestyksessä.(artists/albums/tracks/invoice_items). */