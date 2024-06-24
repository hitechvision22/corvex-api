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
            <p>
                bienvenue sur corvex, Nous sommes ravis de vous compter parmi nos membres.
                <br>
            <div>
                <p>vous avez creer un compte {{ $user->type == 0 ? 'client' : 'chauffeur' }}</p>
            </div>
            </p>
        </div>
    </div>
</body>

</html>
