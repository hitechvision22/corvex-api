<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notification de Débit de Compte</title>
  <style>
    body {
      font-family: 'Helvetica Neue', Arial, sans-serif;
      background-color: #ffffff;
      color: #333;
      margin: 0;
      padding: 0;
      -webkit-font-smoothing: antialiased;
      -moz-osx-font-smoothing: grayscale;
    }
    .email-container {
      max-width: 600px;
      margin: 20px auto;
      background-color: #ffffff;
      border-radius: 10px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      border: 1px solid #e0e0e0;
    }
    .header {
      text-align: center;
      padding: 40px 20px;
      background-color: #007bff;
      color: #ffffff;
    }
    .header img {
      max-width: 150px;
    }
    .header h1 {
      margin: 10px 0 0;
      font-size: 28px;
      font-weight: 500;
    }
    .content {
      padding: 40px 20px;
      font-size: 16px;
      line-height: 1.8;
      color: #333333;
    }
    .content h2 {
      font-size: 22px;
      color: #007bff;
    }
    .content p {
      margin: 15px 0;
    }
    .content a.button {
      display: inline-block;
      background-color: #007bff;
      color: #ffffff;
      padding: 12px 25px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: bold;
      margin-top: 20px;
      transition: background-color 0.3s;
    }
    .content a.button:hover {
      background-color: #0056b3;
    }
    .footer {
      background-color: #f7f7f7;
      color: #6c757d;
      text-align: center;
      padding: 20px;
      font-size: 14px;
      border-top: 1px solid #e0e0e0;
    }
    .footer p {
      margin: 0;
    }
    .footer a {
      color: #007bff;
      text-decoration: none;
    }
    .footer a:hover {
      text-decoration: underline;
    }
    @media (max-width: 600px) {
      .content, .footer {
        padding: 20px 15px;
      }
      .header, .content h2 {
        font-size: 18px;
      }
      .content a.button {
        padding: 10px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="email-container">
    <div class="header">
      <img src="logo-url.png" alt="Logo de l'Entreprise">
      <h1>Covoiturage Express</h1>
    </div>
    <div class="content">
      <h2>Bonjour {{ $admin->name }},</h2>
      <p>Nous souhaitons vous informer que votre compte a été débité suite à un voyage <strong>{{ $trajet->ville_depart }} -> <h5>{{ $trajet->point_rencontre }}</h5>  vers {{ $trajet->ville_destination }} -> {{ $trajet->point_destination }} </strong> récemment réalisé sur notre plateforme.</p>
      <p>Le montant de <strong>{{ $transaction->montant }} FCFA</strong> a été retiré de votre solde. Nous vous remercions pour votre gestion efficace des voyages sur notre site.</p>
      {{-- <a href="https://www.example.com/mon-compte" class="button">Voir mon compte</a> --}}
      <p>Pour toute question ou besoin d'assistance, n'hésitez pas à nous contacter.</p>
      <p>Cordialement,<br>L'équipe COVEX</p>
    </div>
    <div class="footer">
      <p>Vous recevez cet e-mail parce que vous êtes un administrateur sur notre plateforme.</p>
      <p><a href="#">Contactez-nous</a> pour plus d'informations.</p>
    </div>
  </div>
</body>
</html>
