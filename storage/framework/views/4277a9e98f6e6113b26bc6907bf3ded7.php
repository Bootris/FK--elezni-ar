<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['kicker' => null, 'title', 'text' => null, 'href' => null, 'link' => null, 'dark' => false]));

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

foreach (array_filter((['kicker' => null, 'title', 'text' => null, 'href' => null, 'link' => null, 'dark' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<div <?php echo e($attributes->merge(['class' => 'reveal flex flex-wrap items-end justify-between gap-6 border-b-2 pb-6 ' . ($dark ? 'border-white/15' : 'border-navy-900')])); ?>>
    <div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($kicker): ?>
            <p class="kicker flex items-center gap-2 <?php echo e($dark ? 'text-gold-400' : 'text-red-600'); ?>">
                <span class="h-1.5 w-1.5 rounded-full <?php echo e($dark ? 'bg-gold-400' : 'bg-red-500'); ?>" aria-hidden="true"></span><?php echo e($kicker); ?>

            </p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <h2 class="display mt-2 text-4xl sm:text-5xl lg:text-6xl <?php echo e($dark ? 'text-white' : 'text-navy-900'); ?>"><?php echo e($title); ?></h2>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($text): ?>
            <p class="mt-3 max-w-xl <?php echo e($dark ? 'text-navy-200' : 'text-ink-600'); ?>"><?php echo e($text); ?></p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($href && $link): ?>
        <a href="<?php echo e($href); ?>" class="inline-flex shrink-0 items-center gap-2 font-display text-base font-bold uppercase tracking-wide transition hover:gap-3 <?php echo e($dark ? 'text-gold-400' : 'text-red-600'); ?>">
            <?php echo e($link); ?>

            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M3 10h13M12 5l5 5-5 5" /></svg>
        </a>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH /home/user/FK--elezni-ar/resources/views/components/section-heading.blade.php ENDPATH**/ ?>