<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <div>
        <div>
            <p>Salut Mr/Mme {{ $user->name }}, votre publication de covoiturage concernant le voyage
                <strong>
                    {{ $trajet->ville_depart . ' (' . $trajet->point_rencontre . ') ' }}
                </strong>
                vers
                <strong>
                    {{ $trajet->ville_destination . ' (' . $trajet->point_destination . ') ' }}
                </strong>
                vient d'etre active
            </p>
        </div>
    </div>
</body>

</html>
