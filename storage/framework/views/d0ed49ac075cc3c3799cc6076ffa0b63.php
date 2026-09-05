<?php if (isset($component)) { $__componentOriginald956570c5321d7185b887a45463f814f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald956570c5321d7185b887a45463f814f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-layout','data' => ['title' => __('club.video.page_title'),'description' => __('club.video.page_subtitle')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.video.page_title')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.video.page_subtitle'))]); ?>

    <section class="bg-navy-950 bg-pitch text-white">
        <div class="mx-auto max-w-[100rem] px-6 py-14 sm:py-20">
            <p class="kicker flex items-center gap-2 text-gold-400"><span class="live-dot" aria-hidden="true"></span><?php echo e(__('club.video.kicker')); ?></p>
            <h1 class="display mt-3 text-6xl sm:text-8xl"><?php echo e(__('club.video.page_title')); ?></h1>
            <p class="mt-5 max-w-xl text-navy-100"><?php echo e(__('club.video.page_subtitle')); ?></p>
            <div class="mt-6 flex flex-wrap items-center gap-4 text-sm text-navy-200">
                <span><?php echo e($posts->total()); ?> <?php echo e(__('club.video.count')); ?></span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($site['youtube'])): ?>
                    <a href="<?php echo e($site['youtube']); ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm">▶ <?php echo e(__('club.video.youtube')); ?></a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <div class="rails text-white"></div>
    </section>

    <section class="mx-auto max-w-[100rem] px-6 py-14">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($categories->isNotEmpty()): ?>
            <div class="flex flex-wrap items-center gap-2 border-b border-surface-300 pb-8">
                <a href="<?php echo e(route('video.index')); ?>"
                    class="rounded-sm px-4 py-1.5 font-display text-sm font-bold uppercase tracking-wider transition <?php echo e($activeCategory ? 'border border-surface-300 text-ink-600 hover:border-navy-700 hover:text-navy-900' : 'bg-navy-900 text-white'); ?>">
                    <?php echo e(__('club.news.all')); ?>

                </a>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('video.index', ['category' => $category->slug])); ?>"
                        class="rounded-sm px-4 py-1.5 font-display text-sm font-bold uppercase tracking-wider transition <?php echo e($activeCategory === $category->slug ? 'bg-navy-900 text-white' : 'border border-surface-300 text-ink-600 hover:border-navy-700 hover:text-navy-900'); ?>">
                        <?php echo e($category->name); ?> <span class="text-xs opacity-60">(<?php echo e($category->posts_count); ?>)</span>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($posts->isEmpty()): ?>
            <div class="mt-14 rounded-sm border-2 border-dashed border-surface-300 p-16 text-center">
                <img src="<?php echo e(asset('images/logo.gif')); ?>" alt="" class="mx-auto h-14 w-14 opacity-40">
                <p class="mt-4 text-ink-600"><?php echo e(__('club.video.empty')); ?></p>
            </div>
        <?php else: ?>
            <div class="reveal-group mt-12 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $posts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $imageUrl = $post->featured_image ? Illuminate\Support\Facades\Storage::disk('public')->url($post->featured_image) : null;
                        $film = ['film', 'film film-red', 'film film-gold'][$post->id % 3];
                    ?>
                    <article class="group flex h-full flex-col">
                        <a href="<?php echo e(route('news.show', $post->slug)); ?>" class="relative block aspect-video overflow-hidden rounded-sm">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($imageUrl): ?>
                                <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($post->title); ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" loading="lazy">
                            <?php else: ?>
                                <span class="<?php echo e($film); ?> absolute inset-0 transition duration-500 group-hover:scale-105"></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->category): ?>
                                <span class="absolute left-3 top-3 z-10 rounded-sm bg-navy-950/80 px-2.5 py-1 font-display text-[11px] font-bold uppercase tracking-[0.14em] text-white"><?php echo e($post->category->name); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <span class="play-ring absolute left-1/2 top-1/2 z-10 h-16 w-16 -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></span>
                        </a>
                        <div class="mt-4 text-[12px] font-semibold uppercase tracking-[0.12em] text-ink-400"><?php echo e($post->published_at?->format('d.m.Y.')); ?></div>
                        <h3 class="display mt-2 text-[1.65rem] leading-[0.95] text-navy-900 transition group-hover:text-red-600">
                            <a href="<?php echo e(route('news.show', $post->slug)); ?>"><?php echo e($post->title); ?></a>
                        </h3>
                    </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="mt-14">
                <?php echo e($posts->links()); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </section>

    <?php if (isset($component)) { $__componentOriginalb9eda212fea7921b4a03feb1b01f47d7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb9eda212fea7921b4a03feb1b01f47d7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.cta-band','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('cta-band'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb9eda212fea7921b4a03feb1b01f47d7)): ?>
<?php $attributes = $__attributesOriginalb9eda212fea7921b4a03feb1b01f47d7; ?>
<?php unset($__attributesOriginalb9eda212fea7921b4a03feb1b01f47d7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb9eda212fea7921b4a03feb1b01f47d7)): ?>
<?php $component = $__componentOriginalb9eda212fea7921b4a03feb1b01f47d7; ?>
<?php unset($__componentOriginalb9eda212fea7921b4a03feb1b01f47d7); ?>
<?php endif; ?>

 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald956570c5321d7185b887a45463f814f)): ?>
<?php $attributes = $__attributesOriginald956570c5321d7185b887a45463f814f; ?>
<?php unset($__attributesOriginald956570c5321d7185b887a45463f814f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald956570c5321d7185b887a45463f814f)): ?>
<?php $component = $__componentOriginald956570c5321d7185b887a45463f814f; ?>
<?php unset($__componentOriginald956570c5321d7185b887a45463f814f); ?>
<?php endif; ?>
<?php /**PATH /home/user/FK--elezni-ar/resources/views/video/index.blade.php ENDPATH**/ ?>