<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova poruka sa sajta</title>
</head>
<body style="margin:0;padding:0;background-color:#eef0f4;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="560" cellpadding="0" cellspacing="0"
                    style="max-width:560px;width:100%;background:#ffffff;border-radius:12px;overflow:hidden;border:1px solid #d9dde6;">
                    <tr>
                        <td style="background:#1b3157;padding:24px 32px;">
                            <p style="margin:0;color:#c8a04e;font-size:12px;letter-spacing:2px;text-transform:uppercase;">
                                {{ config('app.name') }}
                            </p>
                            <h1 style="margin:8px 0 0;color:#ffffff;font-size:20px;font-weight:600;">
                                Nova poruka sa sajta
                            </h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px;color:#33414f;font-size:14px;line-height:1.7;">
                            <p style="margin:0 0 4px;"><strong>Ime:</strong> {{ $contactMessage->name }}</p>
                            <p style="margin:0 0 4px;"><strong>Email:</strong>
                                <a href="mailto:{{ $contactMessage->email }}" style="color:#1b3157;">{{ $contactMessage->email }}</a>
                            </p>
                            @if ($contactMessage->phone)
                                <p style="margin:0 0 4px;"><strong>Telefon:</strong> {{ $contactMessage->phone }}</p>
                            @endif
                            @if ($contactMessage->subject)
                                <p style="margin:0 0 4px;"><strong>Tema:</strong> {{ $contactMessage->subject }}</p>
                            @endif
                            <div style="margin-top:20px;padding:16px 20px;background:#fbf9f4;border-left:3px solid #c8a04e;border-radius:6px;white-space:pre-line;">{{ $contactMessage->message }}</div>
                            <p style="margin:24px 0 0;font-size:12px;color:#7c8894;">
                                Poruka je sačuvana i u administraciji sajta (Inbox).
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
