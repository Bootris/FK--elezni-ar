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
                            <p style="margin:0;color:#c8a04e;font-size:12px;letter-spacing:2px;text-transform:uppercase;"><?php echo e($site['site_name'] ?? config('app.name')); ?></p>
                            <h1 style="margin:8px 0 0;color:#ffffff;font-size:20px;font-weight:600;">Hvala na prijavi, <?php echo e($application->parent_name); ?>!</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px;color:#2a3242;font-size:14px;line-height:1.7;">
                            <p style="margin:0 0 12px;">Primili smo prijavu za <strong><?php echo e($application->child_name); ?></strong> (<?php echo e($application->birth_year); ?>. godište). Naš trener će vas pozvati na broj <?php echo e($application->phone); ?> i dogovoriti prvi, probni trening.</p>
                            <p style="margin:0 0 12px;">Za prvi trening nije potrebna oprema — dovoljne su patike i dobra volja.</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($site['youth_phone'])): ?>
                                <p style="margin:0;">Ako želite, pozovite nas i vi: <a href="tel:<?php echo e(preg_replace('/[^+\d]/', '', $site['youth_phone'])); ?>" style="color:#1b2740;"><?php echo e($site['youth_phone']); ?></a></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p style="margin:24px 0 0;font-size:12px;color:#6b7383;">Vidimo se na terenu.<br><?php echo e($site['site_name'] ?? config('app.name')); ?></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
<?php /**PATH /home/user/FK--elezni-ar/resources/views/emails/youth_application_confirmation.blade.php ENDPATH**/ ?>