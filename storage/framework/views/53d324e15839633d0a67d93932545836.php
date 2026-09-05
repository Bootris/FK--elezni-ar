<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['rows', 'compact' => false, 'dark' => false]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['rows', 'compact' => false, 'dark' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $cols = __('club.match.cols');
    $textMuted = $dark ? 'text-navy-300' : 'text-ink-400';
?>

<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rows->isEmpty()): ?>
    <p class="text-sm <?php echo e($textMuted); ?>"><?php echo e(__('club.match.no_table')); ?></p>
<?php else: ?>
    <div class="overflow-x-auto">
        <table class="w-full text-sm <?php echo e($dark ? 'text-navy-100' : 'text-ink-700'); ?>">
            <thead>
                <tr class="text-[10.5px] font-bold uppercase tracking-[0.14em] <?php echo e($textMuted); ?>">
                    <th class="py-2 pr-2 text-left font-bold"><?php echo e($cols['pos']); ?></th>
                    <th class="py-2 pr-2 text-left font-bold"><?php echo e($cols['team']); ?></th>
                    <th class="py-2 px-2 text-center font-bold"><?php echo e($cols['p']); ?></th>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($compact)): ?>
                        <th class="py-2 px-2 text-center font-bold"><?php echo e($cols['w']); ?></th>
                        <th class="py-2 px-2 text-center font-bold"><?php echo e($cols['d']); ?></th>
                        <th class="py-2 px-2 text-center font-bold"><?php echo e($cols['l']); ?></th>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <th class="py-2 px-2 text-center font-bold"><?php echo e($cols['gd']); ?></th>
                    <th class="py-2 pl-2 text-right font-bold"><?php echo e($cols['pts']); ?></th>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($compact)): ?>
                        <th class="hidden py-2 pl-4 text-right font-bold sm:table-cell"><?php echo e($cols['form']); ?></th>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="border-t <?php echo e($dark ? 'border-white/10' : 'border-surface-300'); ?> <?php echo e($row->is_club ? ($dark ? 'bg-white/10 font-bold text-white' : 'bg-gold-100 font-bold text-navy-900') : ''); ?>">
                        <td class="py-2.5 pr-2 font-display text-base font-bold">
                            <span class="<?php echo e($row->is_club ? 'text-red-500' : ''); ?>"><?php echo e($row->position); ?></span>
                        </td>
                        <td class="py-2.5 pr-2 whitespace-nowrap"><?php echo e($row->team); ?></td>
                        <td class="py-2.5 px-2 text-center tabular-nums"><?php echo e($row->played); ?></td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($compact)): ?>
                            <td class="py-2.5 px-2 text-center tabular-nums"><?php echo e($row->won); ?></td>
                            <td class="py-2.5 px-2 text-center tabular-nums"><?php echo e($row->drawn); ?></td>
                            <td class="py-2.5 px-2 text-center tabular-nums"><?php echo e($row->lost); ?></td>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <td class="py-2.5 px-2 text-center tabular-nums"><?php echo e($row->goal_difference > 0 ? '+' : ''); ?><?php echo e($row->goal_difference); ?></td>
                        <td class="py-2.5 pl-2 text-right font-display text-lg font-black tabular-nums"><?php echo e($row->points); ?></td>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (! ($compact)): ?>
                            <td class="hidden py-2.5 pl-4 text-right sm:table-cell">
                                <span class="inline-flex gap-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = str_split((string) $row->form); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $r): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($r, ['W', 'D', 'L'])): ?><span class="form-pill form-<?php echo e($r); ?>"><?php echo e($r); ?></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </span>
                            </td>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH /home/user/FK--elezni-ar/resources/views/components/standings-table.blade.php ENDPATH**/ ?>