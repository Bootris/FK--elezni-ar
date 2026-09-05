<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['post', 'large' => false]));

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

foreach (array_filter((['post', 'large' => false]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $isVideo = (bool) $post->video_embed_url;
    $film = ['film', 'film film-red', 'film film-gold'][$post->id % 3];
    $imageUrl = $post->featured_image
        ? Illuminate\Support\Facades\Storage::disk('public')->url($post->featured_image)
        : null;
?>

<article <?php echo e($attributes->merge(['class' => 'group flex h-full flex-col'])); ?>>
    <a href="<?php echo e(route('news.show', $post->slug)); ?>" class="relative block overflow-hidden rounded-sm <?php echo e($large ? 'aspect-[16/10]' : 'aspect-[3/2]'); ?>">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($imageUrl): ?>
            <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($post->title); ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
        <?php else: ?>
            <span class="<?php echo e($film); ?> absolute inset-0 transition duration-500 group-hover:scale-105"></span>
            <img src="<?php echo e(asset('images/logo.gif')); ?>" alt="" class="absolute left-1/2 top-1/2 z-[1] h-16 w-16 -translate-x-1/2 -translate-y-1/2 opacity-25" aria-hidden="true">
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->category): ?>
            <span class="absolute left-3 top-3 z-10 rounded-sm bg-navy-950/80 px-2.5 py-1 font-display text-[11px] font-bold uppercase tracking-[0.14em] text-white backdrop-blur"><?php echo e($post->category->name); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isVideo): ?>
            <span class="play-ring absolute left-1/2 top-1/2 z-10 h-12 w-12 -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </a>

    <div class="mt-4 flex items-center gap-3 text-[12px] font-semibold uppercase tracking-[0.12em] text-ink-400">
        <time datetime="<?php echo e($post->published_at?->toDateString()); ?>"><?php echo e($post->published_at?->format('d.m.Y.')); ?></time>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isVideo): ?>
            <span class="text-red-600"><?php echo e(__('club.news.video_badge')); ?></span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <h3 class="display mt-2 text-[1.65rem] leading-[0.95] text-navy-900 transition group-hover:text-red-600 <?php echo e($large ? 'sm:text-4xl' : ''); ?>">
        <a href="<?php echo e(route('news.show', $post->slug)); ?>"><?php echo e($post->title); ?></a>
    </h3>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($large && $post->excerpt): ?>
        <p class="mt-3 line-clamp-3 text-ink-600"><?php echo e($post->excerpt); ?></p>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</article>
<?php /**PATH /home/user/FK--elezni-ar/resources/views/components/post-card.blade.php ENDPATH**/ ?>