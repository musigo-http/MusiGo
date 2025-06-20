<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faire un don</title>
</head>
<body>
    <h1>Faire un don</h1>
    <form action="process_donation.php" method="POST" id="donation-form">
        <label for="amount">Montant (€) :</label>
        <input type="number" name="amount" id="amount" min="1" required>
        
        <button type="submit">Faire un don</button>
    </form>
</body>
</html>