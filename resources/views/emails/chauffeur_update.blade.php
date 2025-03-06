<!DOCTYPE html>
<html lang="fr" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="x-apple-disable-message-reformatting" />
    <title></title>

    <link href="https://fonts.googleapis.com/css?family=Roboto:400,600" rel="stylesheet" type="text/css" />
    <!-- Web Font / @font-face : BEGIN -->
    <!--[if mso]>
      <style>
        * {
          font-family: "Roboto", sans-serif !important;
        }
      </style>
    <![endif]-->

    <!--[if !mso]>
      <link
        href="https://fonts.googleapis.com/css?family=Roboto:400,600"
        rel="stylesheet"
        type="text/css"
      />
    <![endif]-->

    <!-- Web Font / @font-face : END -->

    <!-- CSS Reset : BEGIN -->

    <style>
        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            font-family: "Roboto", sans-serif !important;
            font-size: 14px;
            margin-bottom: 10px;
            line-height: 24px;
            color: #8094ae;
            font-weight: 400;
        }

        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
            margin: 0;
            padding: 0;
        }

        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }

        table table table {
            table-layout: auto;
        }

        a {
            text-decoration: none;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }
    </style>
</head>

<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f5f6fa;">
    <center style="width: 100%; background-color: #f5f6fa">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#f5f6fa">
            <tr>
                <td style="padding: 40px 0">
                    <table style="width: 100%; max-width: 620px; margin: 0 auto">
                        <tbody>
                            <tr>
                                <td style="text-align: center; padding-bottom: 25px">
                                    <h5 style="margin-bottom: 24px; color: #526484; font-size: 20px; font-weight: 400; line-height: 28px;">
                                        <strong>Alphaciment </strong>
                                    </h5>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 style="padding-top:10px;padding-bottom:5px;padding-left: 137px;">Voici le détails des anciens informations du chauffeur </h3>

                    <table style="width: 100%; max-width: 950px; margin: 0 auto; background-color: #ffffff;">
                        <tbody>
                            <tr>
                                <td style="padding: 30px 30px 20px">
                                   
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <thead>
                                            <tr>
                                                <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Chauffeur</th>
                                                <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Transporteur</th>
                                                <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">RIFD</th>
                                                <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">RFID physique</th>
                                                <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Numero badge</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        {{-- Assurez-vous que la structure des données est correcte --}}

                                        @foreach($chauffeur_updates as $chauffeur_update)
                                            <tr>
                                                <td style="border: 1px solid #ccc; padding: 8px;">{{ $chauffeur_update['chauffeur']['nom'] }}</td>
                                                <td style="border: 1px solid #ccc; padding: 8px;">{{ $chauffeur_update['chauffeur']['related_transporteur']['nom'] }}</td>
                                                <td style="border: 1px solid #ccc; padding: 8px;">{{ $chauffeur_update['chauffeur']['rfid'] }}</td>
                                                <td style="border: 1px solid #ccc; padding: 8px;">{{ $chauffeur_update['chauffeur']['rfid_physique'] }}</td>
                                                <td style="border: 1px solid #ccc; padding: 8px;">{{ $chauffeur_update['numero_badge'] }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <h3 style="padding-top:10px;padding-bottom:5px;padding-left: 137px;">Voici le détails du mise à jour sur l'informations du chauffeur </h3>

                    <table style="width: 100%; max-width: 950px; margin: 0 auto; background-color: #ffffff;">
                        <tbody>
                            <tr>
                                <td style="padding: 30px 30px 20px">
                                   
                                    <table style="width: 100%; border-collapse: collapse;">
                                        <thead>
                                            <tr>
                                                <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Chauffeur</th>
                                                <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Transporteur</th>
                                                <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">RIFD</th>
                                                <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">RFID physique</th>
                                                <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Numero badge</th>
                                                <th style="border: 1px solid #ccc; padding: 8px; text-align: left;">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        {{-- Assurez-vous que la structure des données est correcte --}}

                                        @foreach($chauffeur_updates as $chauffeur_update)
                                            <tr>
                                                <td style="border: 1px solid #ccc; padding: 8px;">{{ $chauffeur_update['nom'] }}</td>
                                                <td style="border: 1px solid #ccc; padding: 8px;">{{ $chauffeur_update['transporteur']['nom'] }}</td>
                                                <td style="border: 1px solid #ccc; padding: 8px;">{{ $chauffeur_update['rfid'] }}</td>
                                                <td style="border: 1px solid #ccc; padding: 8px;">{{ $chauffeur_update['rfid_physique'] }}</td>
                                                <td style="border: 1px solid #ccc; padding: 8px;">{{ $chauffeur_update['numero_badge'] }}</td>
                                                <td style="border: 1px solid #ccc; padding: 8px;">{{ $chauffeur_update['chauffeur_update_type']['name'] }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <table style="width: 100%; max-width: 620px; margin: 0 auto">
                        <tbody>
                            <tr>
                                <td style="text-align: center; padding: 25px 20px 0">
                                    <p style="font-size: 13px">
                                        Copyright © <script>`${new Date().getFullYear()}`</script> Alphaciment {{ env('ZONE') }}. Tous droits réservés.
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>

</html>