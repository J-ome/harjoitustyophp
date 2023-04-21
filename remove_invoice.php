<?php

require "functions.php";

try{
$dbcon = createDbConnection();

$invoice_item_id = 1; //Tähän id jonka haluaa poistaa, testattu poistamalla ID 1.

$sql = "DELETE FROM invoice_items WHERE InvoiceId=?";

$statement = $dbcon->prepare($sql);
$statement->bindParam(1, $invoice_item_id);
$statement->execute();


} catch (PDOException $e) {
    echo $e->getMessage();
}
