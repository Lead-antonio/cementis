<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Chauffeurs non fixes</title>
</head>
<body>
    <h2>Liste des chauffeurs non fixe</h2>
    <p>Voici la liste des chauffeurs qui sont toujours non fixes :</p>
    <table border="1">
        <thead>
            <tr>
                <th>Transporteur</th>
                <th>RFID</th>
                <th>Nom</th>
                <th>Date de création</th>
            </tr>
        </thead>
        <tbody>
            @foreach($drivers as $driver)
                <tr>
                    <td>{{ $driver->related_transporteur->nom }}</td>
                    <td>{{ $driver->rfid }}</td>
                    <td>{{ $driver->nom }}</td>
                    <td>{{ $driver->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
