<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['match']));

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

foreach (array_filter((['match']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php $finished = $match->is_finished; ?>

<div class="grid grid-cols-[4.5rem_1fr_auto] items-center gap-4 border-b border-surface-300 py-4 sm:grid-cols-[6rem_1fr_auto_auto]">
    <div class="leading-tight">
        <span class="display block text-2xl text-navy-900"><?php echo e($match->kickoff_at->format('d.m')); ?></span>
        <span class="text-[11px] uppercase tracking-wider text-ink-400"><?php echo e($finished ? $match->kickoff_at->format('Y.') : $match->kickoff_at->format('H:i')); ?></span>
    </div>
    <div class="min-w-0">
        <div class="flex flex-wrap items-center gap-x-2 font-display text-lg font-bold uppercase leading-tight text-navy-900 sm:text-xl">
            <span class="<?php echo e($match->is_home ? '' : 'text-ink-600'); ?>"><?php echo e($match->home_team); ?></span>
            <span class="text-ink-400">–</span>
            <span class="<?php echo e($match->is_home ? 'text-ink-600' : ''); ?>"><?php echo e($match->away_team); ?></span>
        </div>
        <p class="mt-0.5 truncate text-xs text-ink-400"><?php echo e($match->competition); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($match->round): ?> · <?php echo e($match->round); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($match->venue): ?> · <?php echo e($match->venue); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></p>
    </div>
    <div class="text-right">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($finished): ?>
            <span class="score text-2xl text-navy-900"><?php echo e($match->score_line); ?></span>
        <?php else: ?>
            <span class="rounded-sm border border-surface-300 px-2 py-1 font-display text-[11px] font-bold uppercase tracking-wider text-ink-600"><?php echo e($match->is_home ? __('club.match.home') : __('club.match.away')); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <div class="hidden w-14 text-right sm:block">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($finished && $match->outcome): ?>
            <span class="form-pill form-<?php echo e($match->outcome); ?>"><?php echo e($match->outcome); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($match->report): ?>
            <a href="<?php echo e(route('news.show', $match->report->slug)); ?>" class="ml-1 text-[11px] font-bold uppercase tracking-wider text-red-600 hover:underline"><?php echo e(__('club.match.report')); ?></a>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH /home/user/FK--elezni-ar/resources/views/components/match-row.blade.php ENDPATH**/ ?>