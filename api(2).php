<?php
try {
    $user = 'root';
    $pass = '';
    $dbh = new PDO('mysql:host=localhost;dbname=beer-casus', $user, $pass);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

// Functie om beoordelingsgegevens toe te voegen aan de database
// Functie om beoordelingsgegevens toe te voegen of bij te werken in de database
function addOrUpdateRating($dbh, $userId, $beerId, $rating, $note)
{
    // Controleer eerst of er al een beoordeling bestaat voor het specifieke biertje en de gebruiker
    $existingRatingQuery = 'SELECT * FROM beer_ratings WHERE user_id = ? AND beer_id = ?';
    $existingStmt = $dbh->prepare($existingRatingQuery);
    $existingStmt->execute([$userId, $beerId]);
    $existingRating = $existingStmt->fetch(PDO::FETCH_ASSOC);

    if ($existingRating) {
        // Als er al een beoordeling bestaat, werk deze bij
        $updateQuery = 'UPDATE beer_ratings SET rating = ?, note = ? WHERE user_id = ? AND beer_id = ?';
        $updateStmt = $dbh->prepare($updateQuery);
        $success = $updateStmt->execute([$rating, $note, $userId, $beerId]);

        if ($success) {
            return true; // Beoordeling succesvol bijgewerkt
        } else {
            return false; // Er is een fout opgetreden bij het bijwerken van de beoordeling
        }
    } else {
        // Als er nog geen beoordeling bestaat, voeg een nieuwe toe
        $insertQuery = 'INSERT INTO beer_ratings (user_id, beer_id, rating, note) VALUES (?, ?, ?, ?)';
        $insertStmt = $dbh->prepare($insertQuery);
        $success = $insertStmt->execute([$userId, $beerId, $rating, $note]);

        if ($success) {
            return true; // Beoordeling succesvol toegevoegd
        } else {
            return false; // Er is een fout opgetreden bij het toevoegen van de beoordeling
        }
    }
}


// Haal de ontvangen gegevens op vanuit het POST-verzoek
$data = json_decode(file_get_contents("php://input"));

// Controleer of alle vereiste velden zijn ontvangen
if (isset($data->userId, $data->beerId, $data->rating, $data->note)) {
    // Voeg of werk de beoordelingsgegevens bij in de database
    $result = addOrUpdateRating($dbh, $data->userId, $data->beerId, $data->rating, $data->note);
    if ($result) {
        // Stuur een succesvolle JSON-respons terug
        echo json_encode(array("message" => "Rating saved successfully"));
    } else {
        // Stuur een foutmelding JSON-respons terug
        echo json_encode(array("error" => "Failed to save rating"));
    }
} else {
    // Stuur een foutmelding JSON-respons terug als vereiste velden ontbreken
    echo json_encode(array("error" => "Missing required fields"));
}

