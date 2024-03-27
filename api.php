<?php
try {
    $user = 'root';
    $pass = '';
    $dbh = new PDO('mysql:host=localhost;dbname=beer-casus', $user, $pass);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

function showBeers($dbh)
{
    $userID = $_GET["user_id"];
    $sql = 'SELECT beers.id, beers.name, beers.brewer, beer_ratings.rating, beer_ratings.note 
            FROM beer_ratings
            LEFT JOIN beers ON beers.id = beer_ratings.beer_id
            WHERE beer_ratings.user_id = :userID
            ORDER BY beer_ratings.created_at';
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':userID', $_GET["user_id"]);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return json_encode($result);
}

echo showBeers($dbh);   