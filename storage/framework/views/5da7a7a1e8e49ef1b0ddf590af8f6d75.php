<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['player']));

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

foreach (array_filter((['player']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $photoUrl = $player->photo ? Illuminate\Support\Facades\Storage::disk('public')->url($player->photo) : null;
    $parts = collect(explode(' ', trim($player->name)))->filter();
    $first = $parts->first();
    $last = $parts->slice(1)->implode(' ');
?>

<article <?php echo e($attributes->merge(['class' => 'player-card group flex h-full flex-col rounded-sm text-white'])); ?>>
    <span class="number-ghost"><?php echo e($player->shirt_number ?? ''); ?></span>

    <div class="relative aspect-[4/5] overflow-hidden">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($photoUrl): ?>
            <img src="<?php echo e($photoUrl); ?>" alt="<?php echo e($player->name); ?>" class="h-full w-full object-cover object-top" loading="lazy">
        <?php else: ?>
            <div class="silhouette h-full w-full"></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="absolute inset-x-0 bottom-0 h-1/2 bg-gradient-to-t from-navy-950 to-transparent"></div>

        <div class="absolute left-4 top-4 flex flex-col gap-1.5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($player->is_captain): ?>
                <span class="rounded-sm bg-gold-500 px-2 py-0.5 font-display text-[10.5px] font-bold uppercase tracking-[0.14em] text-navy-950"><?php echo e(__('club.team.captain')); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($player->from_academy): ?>
                <span class="rounded-sm bg-red-500 px-2 py-0.5 font-display text-[10.5px] font-bold uppercase tracking-[0.14em] text-white"><?php echo e(__('club.team.academy_badge')); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    <div class="relative -mt-14 flex flex-1 flex-col p-5">
        <div class="flex items-end gap-2.5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($player->shirt_number): ?>
                <span class="score shrink-0 text-[2.6rem] text-gold-400"><?php echo e($player->shirt_number); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="min-w-0 pb-1 leading-none">
                <span class="block truncate font-display text-sm font-semibold uppercase tracking-wide text-navy-200"><?php echo e($first); ?></span>
                <span class="display block truncate text-xl"><?php echo e($last ?: '&nbsp;'); ?></span>
            </div>
        </div>
        <div class="mt-4 flex items-center justify-between border-t border-white/10 pt-3 text-[11.5px] font-semibold uppercase tracking-[0.12em] text-navy-200">
            <span><?php echo e($player->position_label); ?></span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($player->age): ?>
                <span><?php echo e($player->age); ?> <?php echo e(__('club.team.age')); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
</article>
<?php /**PATH /home/user/FK--elezni-ar/resources/views/components/player-card.blade.php ENDPATH**/ ?>