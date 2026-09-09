<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova prijava za upis</title>
</head>
<body style="margin:0;padding:0;background-color:#eef0f4;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="560" cellpadding="0" cellspacing="0"
                    style="max-width:560px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #d9dde6;">
                    <tr>
                        <td style="background:#1b3157;padding:24px 32px;">
                            <p style="margin:0;color:#c8a04e;font-size:12px;letter-spacing:2px;text-transform:uppercase;">{{ config('app.name') }}</p>
                            <h1 style="margin:8px 0 0;color:#ffffff;font-size:20px;font-weight:600;">Nova prijava za upis u omladinsku školu</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px;color:#2a3242;font-size:14px;line-height:1.7;">
                            <p style="margin:0 0 4px;"><strong>Dete:</strong> {{ $application->child_name }} ({{ $application->birth_year }}. godište)</p>
                            @if ($application->selection)
                                <p style="margin:0 0 4px;"><strong>Željena selekcija:</strong> {{ $application->selection->name }}</p>
                            @endif
                            <p style="margin:0 0 4px;"><strong>Roditelj / staratelj:</strong> {{ $application->parent_name }}</p>
                            <p style="margin:0 0 4px;"><strong>Telefon:</strong> <a href="tel:{{ preg_replace('/[^+\d]/', '', $application->phone) }}" style="color:#1b3157;">{{ $application->phone }}</a></p>
                            @if ($application->email)<p style="margin:0 0 4px;"><strong>Imejl:</strong> <a href="mailto:{{ $application->email }}" style="color:#1b3157;">{{ $application->email }}</a></p>@endif
                            @if ($application->note)
                                <div style="margin-top:20px;padding:16px 20px;background:#f6f7fa;border-left:3px solid #d7262d;border-radius:6px;white-space:pre-line;">{{ $application->note }}</div>
                            @endif
                            <p style="margin:24px 0 0;font-size:12px;color:#6b7383;">Prijava je sačuvana u admin panelu (Prijave → Upis omladinaca).</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
