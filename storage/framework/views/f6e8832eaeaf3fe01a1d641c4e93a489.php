<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['match', 'label' => null, 'countdown' => false, 'dark' => true]));

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

foreach (array_filter((['match', 'label' => null, 'countdown' => false, 'dark' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $club = $site['club_short_name'] ?? 'Železničar';
    $finished = $match->is_finished;
    $isLive = $match->status === 'live';
?>

<div <?php echo e($attributes->merge(['class' => 'relative overflow-hidden rounded-sm p-6 ' . ($dark ? 'bg-navy-800 bg-pitch text-white' : 'border border-surface-300 bg-white text-navy-900')])); ?>>
    <div class="flex items-center justify-between gap-3 text-[11px] font-semibold uppercase tracking-[0.16em] <?php echo e($dark ? 'text-navy-200' : 'text-ink-400'); ?>">
        <span class="flex items-center gap-2 <?php echo e($dark ? 'text-gold-400' : 'text-red-600'); ?>">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isLive): ?><span class="live-dot" aria-hidden="true"></span><?php echo e(__('club.match.live')); ?><?php else: ?><?php echo e($label ?? $match->competition); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </span>
        <span><?php echo e($match->competition); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($match->round): ?> · <?php echo e($match->round); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></span>
    </div>

    <div class="mt-5 grid grid-cols-[1fr_auto_1fr] items-center gap-3">
        <div class="text-right">
            <span class="display block text-2xl leading-none sm:text-3xl <?php echo e($match->is_home ? ($dark ? 'text-white' : 'text-navy-900') : ($dark ? 'text-navy-100' : 'text-ink-600')); ?>"><?php echo e($match->home_team); ?></span>
            <span class="mt-1 block text-[11px] uppercase tracking-wider <?php echo e($dark ? 'text-navy-300' : 'text-ink-400'); ?>"><?php echo e(__('club.match.home')); ?></span>
        </div>

        <div class="px-2 text-center">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($finished || $isLive): ?>
                <span class="score text-5xl sm:text-6xl"><?php echo e($match->home_score ?? 0); ?>:<?php echo e($match->away_score ?? 0); ?></span>
            <?php else: ?>
                <span class="score text-4xl sm:text-5xl"><?php echo e($match->kickoff_at->format('H:i')); ?></span>
                <span class="mt-1 block text-[11px] font-semibold uppercase tracking-wider <?php echo e($dark ? 'text-navy-300' : 'text-ink-400'); ?>"><?php echo e($match->kickoff_at->format('D d.m.')); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div>
            <span class="display block text-2xl leading-none sm:text-3xl <?php echo e(!$match->is_home ? ($dark ? 'text-white' : 'text-navy-900') : ($dark ? 'text-navy-100' : 'text-ink-600')); ?>"><?php echo e($match->away_team); ?></span>
            <span class="mt-1 block text-[11px] uppercase tracking-wider <?php echo e($dark ? 'text-navy-300' : 'text-ink-400'); ?>"><?php echo e(__('club.match.away')); ?></span>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($countdown && !$finished && !$isLive && $match->kickoff_at->isFuture()): ?>
        <div class="mt-5 grid grid-cols-4 gap-2 border-t border-white/10 pt-4" data-countdown="<?php echo e($match->kickoff_at->toIso8601String()); ?>">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['d' => 'd', 'h' => 'h', 'm' => 'min', 's' => 'sec']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $unit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="text-center">
                    <span class="score block text-2xl" data-cd="<?php echo e($key); ?>">00</span>
                    <span class="text-[10px] uppercase tracking-wider <?php echo e($dark ? 'text-navy-300' : 'text-ink-400'); ?>"><?php echo e($unit); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <div class="mt-4 flex flex-wrap items-center justify-between gap-2 text-xs <?php echo e($dark ? 'text-navy-200' : 'text-ink-600'); ?>">
        <span><?php echo e($match->kickoff_at->format('d.m.Y.')); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($match->venue): ?> · <?php echo e($match->venue); ?><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></span>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($finished && $match->outcome): ?>
            <span class="form-pill form-<?php echo e($match->outcome); ?>"><?php echo e($match->outcome); ?></span>
        <?php elseif($match->status === 'postponed'): ?>
            <span class="font-semibold uppercase tracking-wider text-red-500"><?php echo e(__('club.match.postponed')); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH /home/user/FK--elezni-ar/resources/views/components/match-card.blade.php ENDPATH**/ ?>