<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['member']));

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

foreach (array_filter((['member']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $photoUrl = $member->photo ? Illuminate\Support\Facades\Storage::disk('public')->url($member->photo) : null;
    $initials = collect(explode(' ', trim($member->name)))->filter()->map(fn ($p) => mb_substr($p, 0, 1))->take(2)->implode('');
?>

<article <?php echo e($attributes->merge(['class' => 'flex items-center gap-4 rounded-sm border border-surface-300 bg-white p-4 transition hover:-translate-y-0.5 hover:shadow-lg'])); ?>>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($photoUrl): ?>
        <img src="<?php echo e($photoUrl); ?>" alt="<?php echo e($member->name); ?>" class="h-16 w-16 shrink-0 rounded-sm object-cover" loading="lazy">
    <?php else: ?>
        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-sm bg-navy-800 font-display text-xl font-bold text-gold-400"><?php echo e($initials); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <div class="min-w-0">
        <h3 class="display text-xl text-navy-900"><?php echo e($member->name); ?></h3>
        <p class="mt-0.5 text-sm font-semibold text-red-600"><?php echo e($member->role); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($member->selection): ?> · <?php echo e($member->selection->name); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></p>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($member->licence): ?>
            <p class="mt-0.5 text-xs uppercase tracking-wider text-ink-400"><?php echo e(__('club.team.licence')); ?> <?php echo e($member->licence); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</article>
<?php /**PATH /home/user/FK--elezni-ar/resources/views/components/staff-card.blade.php ENDPATH**/ ?>