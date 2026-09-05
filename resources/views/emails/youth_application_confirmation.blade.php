<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Primili smo prijavu</title>
</head>
<body style="margin:0;padding:0;background-color:#eef0f4;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="560" cellpadding="0" cellspacing="0"
                    style="max-width:560px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #d9dde6;">
                    <tr>
                        <td style="background:#1b2740;padding:24px 32px;">
                            <p style="margin:0;color:#c8a04e;font-size:12px;letter-spacing:2px;text-transform:uppercase;">{{ $site['site_name'] ?? config('app.name') }}</p>
                            <h1 style="margin:8px 0 0;color:#ffffff;font-size:20px;font-weight:600;">Hvala na prijavi, {{ $application->parent_name }}!</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px;color:#2a3242;font-size:14px;line-height:1.7;">
                            <p style="margin:0 0 12px;">Primili smo prijavu za <strong>{{ $application->child_name }}</strong> ({{ $application->birth_year }}. godište). Naš trener će vas pozvati na broj {{ $application->phone }} i dogovoriti prvi, probni trening.</p>
                            <p style="margin:0 0 12px;">Za prvi trening nije potrebna oprema — dovoljne su patike i dobra volja.</p>
                            @if (!empty($site['youth_phone']))
                                <p style="margin:0;">Ako želite, pozovite nas i vi: <a href="tel:{{ preg_replace('/[^+\d]/', '', $site['youth_phone']) }}" style="color:#1b2740;">{{ $site['youth_phone'] }}</a></p>
                            @endif
                            <p style="margin:24px 0 0;font-size:12px;color:#6b7383;">Vidimo se na terenu.<br>{{ $site['site_name'] ?? config('app.name') }}</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
