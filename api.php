<?php




try {
    $user = 'root';
    $pass = '';
    $dbh = new PDO('mysql:host=localhost;dbname=beer-casus', $user, $pass);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

function getBeerData($dbh)
{
    $sql = 'SELECT beers.id, beers.name, beers.brewer, beer_ratings.rating, beer_ratings.note 
            FROM beers, beer_ratings
            LEFT JOIN beer_ratings ON beers.id = beer_ratings.beer_id
            ORDER BY beers.id';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $result;
}

echo json_encode(getBeerData($dbh));