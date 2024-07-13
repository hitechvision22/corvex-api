<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Email Template</title>
</head>

<style>
    @media screen and (max-width: 600px) {
      .content {
          width: 100% !important;
          display: block !important;
          padding: 10px !important;
      }
      .header, .body, .footer {
          padding: 20px !important;
      }
    }
  </style>

<body style="font-family: 'Poppins', Arial, sans-serif">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="padding: 20px;">
                <table class="content" width="80%" border="0" cellspacing="0" cellpadding="0"
                    style="border-collapse: collapse; border: 1px solid #cccccc;">
                    <!-- Header -->
                    <tr>
                        <td class="header"
                            style="background-color: #00A3FE; padding: 40px; text-align: center; color: white; font-size: 24px;">
                            Annulation de reservation
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="body" style="padding: 40px; text-align: left; font-size: 16px; line-height: 1.6;">
                            Salut,Mr/Mme {{ $client->name }} <br>
                            votre reservation concernant le trajet <strong>{{ $trajet->date_depart }}</strong> 
                             a <strong>{{ $trajet->heure_depart }}</strong>  a ete annuele et la politique d'annulation de reservation vous sera appliquer, penser a verifier votre porte feuille sur notre plateforme</strong>
                            <br><br>
                            veuillez ouvrir l'application ou le site pour en prendre acte et discuter avec le client
                        </td>
                    </tr>

                   
                    <tr>
                        <td style="padding: 0px 40px 0px 40px; text-align: center;">
                            <!-- CTA Button -->
                            <table cellspacing="0" cellpadding="0" style="margin: auto;">
                                <tr>
                                    <td align="center"
                                        style="background-color: #00A3FE; padding: 10px 20px; border-radius: 5px;">
                                        <a href="https://www.yourwebsite.com" target="_blank"
                                            style="color: #ffffff; text-decoration: none; font-weight: bold;">Book a
                                            ouvrir l'application</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td class="body" style="padding: 40px; text-align: left; font-size: 16px; line-height: 1.6;">
                            veuillez ne pas repondre a cet email  
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td class="footer"
                            style="background-color: #333333; padding: 40px; text-align: center; color: white; font-size: 14px;">
                            Copyright &copy; 2024 | Covex
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
