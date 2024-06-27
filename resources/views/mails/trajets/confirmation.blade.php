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
          padding: 10px !important;
      }
    }
  </style>

<body style="font-family: 'Poppins', Arial, sans-serif">
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center" style="padding: 20px;">
                <table class="content" width="600" border="0" cellspacing="0" cellpadding="0"
                    style="border-collapse: collapse; border: 1px solid #cccccc;">
                    <!-- Header -->
                    <tr>
                        <td class="header"
                            style="background-color: #422AFB; padding: 40px; text-align: center; color: white; font-size: 24px;">
                            Covoiturage Express
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td class="body" style="padding: 40px; text-align: left; ">
                           Salut, {{ $client->name }}
                            <br>
                            <p>vous venez de validez la finalisation de votre trajet sur covoiturage express</p>
                            <br>
                            <p>merci à vous</p>
                        </td>
                    </tr>
 
                    <tr>
                        <td class="body" style="padding-bottom: 10px; text-align: center; font-size: 16px; line-height: 1.6;">
                            ces informations sont confidentielles | Veuillez ne pas repondre a cet email
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td class="footer"
                            style="background-color: #422AFB; padding: 40px; text-align: center; color: white; font-size: 14px;">
                            Copyright &copy; 2024 | Covex
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
