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
    $sql = 
    'SELECT beers.id, beers.name, beers.brewer, beer_ratings.rating, 
    AVG(rating) AS rating 
    FROM beers LEFT JOIN beer_ratings 
    ON beers.id = beer_ratings.beer_id
    ';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return json_encode($result);
}

echo showBeers($dbh);   