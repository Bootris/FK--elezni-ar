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
                        <td style="background:#1b2740;padding:24px 32px;">
                            <p style="margin:0;color:#c8a04e;font-size:12px;letter-spacing:2px;text-transform:uppercase;"><?php echo e(config('app.name')); ?></p>
                            <h1 style="margin:8px 0 0;color:#ffffff;font-size:20px;font-weight:600;">Nova prijava za upis u omladinsku školu</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:28px 32px;color:#2a3242;font-size:14px;line-height:1.7;">
                            <p style="margin:0 0 4px;"><strong>Dete:</strong> <?php echo e($application->child_name); ?> (<?php echo e($application->birth_year); ?>. godište)</p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($application->selection): ?>
                                <p style="margin:0 0 4px;"><strong>Željena selekcija:</strong> <?php echo e($application->selection->name); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p style="margin:0 0 4px;"><strong>Roditelj / staratelj:</strong> <?php echo e($application->parent_name); ?></p>
                            <p style="margin:0 0 4px;"><strong>Telefon:</strong> <a href="tel:<?php echo e(preg_replace('/[^+\d]/', '', $application->phone)); ?>" style="color:#1b2740;"><?php echo e($application->phone); ?></a></p>
                            <p style="margin:0 0 4px;"><strong>Imejl:</strong> <a href="mailto:<?php echo e($application->email); ?>" style="color:#1b2740;"><?php echo e($application->email); ?></a></p>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($application->note): ?>
                                <div style="margin-top:20px;padding:16px 20px;background:#f6f7fa;border-left:3px solid #d7262d;border-radius:6px;white-space:pre-line;"><?php echo e($application->note); ?></div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <p style="margin:24px 0 0;font-size:12px;color:#6b7383;">Prijava je sačuvana u admin panelu (Prijave → Upis omladinaca).</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
<?php /**PATH /home/user/FK--elezni-ar/resources/views/emails/youth_application.blade.php ENDPATH**/ ?>