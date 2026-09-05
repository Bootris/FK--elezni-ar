<?php
    use Illuminate\Support\Facades\Storage;
    $locale = app()->getLocale();
    $heroImage = !empty($site['hero_image']) ? Storage::disk('public')->url($site['hero_image']) : null;
    $shortName = $site['club_short_name'] ?? 'Železničar';
?>

<?php if (isset($component)) { $__componentOriginald956570c5321d7185b887a45463f814f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald956570c5321d7185b887a45463f814f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>

    
    <section class="relative overflow-hidden bg-navy-950 text-white">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($heroImage): ?>
            <img src="<?php echo e($heroImage); ?>" alt="" class="absolute inset-0 h-full w-full object-cover opacity-40" aria-hidden="true">
            <div class="absolute inset-0 bg-gradient-to-r from-navy-950 via-navy-950/85 to-navy-950/40"></div>
        <?php else: ?>
            <div class="absolute inset-0 bg-pitch bg-spot"></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <div class="wordmark-ghost absolute -bottom-10 -left-4 text-[26vw] sm:text-[20vw]" aria-hidden="true"><?php echo e($shortName); ?></div>

        <div class="relative mx-auto grid max-w-[100rem] items-center gap-12 px-6 pt-14 pb-16 lg:grid-cols-[1.15fr_.85fr] lg:gap-16 lg:py-24">
            <div class="reveal is-visible">
                <p class="kicker flex items-center gap-2 text-gold-400">
                    <svg class="h-3.5 w-3.5 text-red-500" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.9 6.3 6.9.8-5.1 4.7 1.4 6.8L12 17.3 5.9 20.6l1.4-6.8L2.2 9.1l6.9-.8z"/></svg>
                    <?php echo e($site['hero_kicker'] ?? __('club.hero.kicker')); ?>

                </p>
                <h1 class="display mt-5 text-[3.4rem] text-balance sm:text-7xl lg:text-[6.5rem]"><?php echo e($site['hero_title'] ?? __('club.hero.title')); ?></h1>
                <p class="mt-6 max-w-xl text-lg leading-relaxed text-navy-100"><?php echo e($site['hero_subtitle'] ?? __('club.hero.subtitle')); ?></p>
                <div class="mt-9 flex flex-wrap gap-3">
                    <a href="<?php echo e(route('youth.index', $locale)); ?>#upis" class="btn btn-red whitespace-normal"><?php echo e(__('club.cta.enroll_long')); ?></a>
                    <a href="<?php echo e(route('support.index', $locale)); ?>" class="btn btn-outline whitespace-normal"><?php echo e(__('club.cta.support')); ?></a>
                </div>
                <div class="mt-10 flex flex-wrap gap-x-10 gap-y-4 border-t border-white/10 pt-6">
                    <div><span class="score block text-4xl text-gold-400"><?php echo e($site['founded_year'] ?? '1928'); ?>.</span><span class="text-xs uppercase tracking-wider text-navy-300"><?php echo e(__('club.footer.founded')); ?></span></div>
                    <div><span class="score block text-4xl"><?php echo e($selections->count()); ?></span><span class="text-xs uppercase tracking-wider text-navy-300"><?php echo e(__('club.home.youth_selections')); ?></span></div>
                    <div><span class="score block text-4xl"><?php echo e($playerCount); ?></span><span class="text-xs uppercase tracking-wider text-navy-300"><?php echo e(__('club.home.team_players')); ?></span></div>
                </div>
            </div>

            
            <div class="reveal is-visible grid gap-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($nextMatch): ?>
                    <?php if (isset($component)) { $__componentOriginal73db34eb66297c5425e9558ed1755d11 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal73db34eb66297c5425e9558ed1755d11 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.match-card','data' => ['match' => $nextMatch,'label' => __('club.hero.next_match'),'countdown' => true,'class' => 'border border-white/10 shadow-2xl']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('match-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['match' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($nextMatch),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.hero.next_match')),'countdown' => true,'class' => 'border border-white/10 shadow-2xl']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal73db34eb66297c5425e9558ed1755d11)): ?>
<?php $attributes = $__attributesOriginal73db34eb66297c5425e9558ed1755d11; ?>
<?php unset($__attributesOriginal73db34eb66297c5425e9558ed1755d11); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal73db34eb66297c5425e9558ed1755d11)): ?>
<?php $component = $__componentOriginal73db34eb66297c5425e9558ed1755d11; ?>
<?php unset($__componentOriginal73db34eb66297c5425e9558ed1755d11); ?>
<?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastMatch): ?>
                    <?php if (isset($component)) { $__componentOriginal73db34eb66297c5425e9558ed1755d11 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal73db34eb66297c5425e9558ed1755d11 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.match-card','data' => ['match' => $lastMatch,'label' => __('club.hero.last_result'),'class' => 'border border-white/10 !bg-navy-900/70 backdrop-blur']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('match-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['match' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($lastMatch),'label' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.hero.last_result')),'class' => 'border border-white/10 !bg-navy-900/70 backdrop-blur']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal73db34eb66297c5425e9558ed1755d11)): ?>
<?php $attributes = $__attributesOriginal73db34eb66297c5425e9558ed1755d11; ?>
<?php unset($__attributesOriginal73db34eb66297c5425e9558ed1755d11); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal73db34eb66297c5425e9558ed1755d11)): ?>
<?php $component = $__componentOriginal73db34eb66297c5425e9558ed1755d11; ?>
<?php unset($__componentOriginal73db34eb66297c5425e9558ed1755d11); ?>
<?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$nextMatch && !$lastMatch): ?>
                    <div class="rounded-sm border border-dashed border-white/20 p-8 text-center text-navy-200"><?php echo e(__('club.hero.no_match')); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($standings->isNotEmpty()): ?>
                    <a href="<?php echo e(route('team.index', $locale)); ?>#tabela" class="flex items-center justify-between rounded-sm border border-white/10 bg-navy-900/70 px-5 py-3 text-sm backdrop-blur transition hover:border-gold-400/50">
                        <span class="font-display text-base font-bold uppercase tracking-wide"><?php echo e($site['league_name'] ?? __('club.match.table')); ?></span>
                        <?php $us = $standings->firstWhere('is_club', true); ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($us): ?>
                            <span class="text-navy-200"><?php echo e($us->position); ?>. · <?php echo e($us->points); ?> <?php echo e(__('club.match.cols.pts')); ?></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <span class="text-gold-400"><?php echo e(__('club.match.full_table')); ?> →</span>
                    </a>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <div class="rails text-white"></div>
    </section>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featured || $latestPosts->isNotEmpty()): ?>
        <section class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
            <?php if (isset($component)) { $__componentOriginal755230460fd16c04121658d92fbf99f7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal755230460fd16c04121658d92fbf99f7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-heading','data' => ['kicker' => __('club.home.latest_kicker'),'title' => __('club.home.latest_title'),'href' => route('news.index'),'link' => __('club.cta.all_news')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['kicker' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.home.latest_kicker')),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.home.latest_title')),'href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('news.index')),'link' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.cta.all_news'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal755230460fd16c04121658d92fbf99f7)): ?>
<?php $attributes = $__attributesOriginal755230460fd16c04121658d92fbf99f7; ?>
<?php unset($__attributesOriginal755230460fd16c04121658d92fbf99f7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal755230460fd16c04121658d92fbf99f7)): ?>
<?php $component = $__componentOriginal755230460fd16c04121658d92fbf99f7; ?>
<?php unset($__componentOriginal755230460fd16c04121658d92fbf99f7); ?>
<?php endif; ?>

            <div class="reveal-group mt-12 grid gap-x-8 gap-y-12 lg:grid-cols-[1.5fr_1fr]">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($featured): ?>
                    <?php if (isset($component)) { $__componentOriginal14b498b52c33a1421ff8895e4557790f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14b498b52c33a1421ff8895e4557790f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.post-card','data' => ['post' => $featured,'large' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('post-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['post' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($featured),'large' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14b498b52c33a1421ff8895e4557790f)): ?>
<?php $attributes = $__attributesOriginal14b498b52c33a1421ff8895e4557790f; ?>
<?php unset($__attributesOriginal14b498b52c33a1421ff8895e4557790f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14b498b52c33a1421ff8895e4557790f)): ?>
<?php $component = $__componentOriginal14b498b52c33a1421ff8895e4557790f; ?>
<?php unset($__componentOriginal14b498b52c33a1421ff8895e4557790f); ?>
<?php endif; ?>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $latestPosts->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (isset($component)) { $__componentOriginal14b498b52c33a1421ff8895e4557790f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14b498b52c33a1421ff8895e4557790f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.post-card','data' => ['post' => $post]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('post-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['post' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14b498b52c33a1421ff8895e4557790f)): ?>
<?php $attributes = $__attributesOriginal14b498b52c33a1421ff8895e4557790f; ?>
<?php unset($__attributesOriginal14b498b52c33a1421ff8895e4557790f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14b498b52c33a1421ff8895e4557790f)): ?>
<?php $component = $__componentOriginal14b498b52c33a1421ff8895e4557790f; ?>
<?php unset($__componentOriginal14b498b52c33a1421ff8895e4557790f); ?>
<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($latestPosts->count() > 2): ?>
                <div class="reveal-group mt-12 grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $latestPosts->slice(2, 4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if (isset($component)) { $__componentOriginal14b498b52c33a1421ff8895e4557790f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal14b498b52c33a1421ff8895e4557790f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.post-card','data' => ['post' => $post]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('post-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['post' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($post)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal14b498b52c33a1421ff8895e4557790f)): ?>
<?php $attributes = $__attributesOriginal14b498b52c33a1421ff8895e4557790f; ?>
<?php unset($__attributesOriginal14b498b52c33a1421ff8895e4557790f); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal14b498b52c33a1421ff8895e4557790f)): ?>
<?php $component = $__componentOriginal14b498b52c33a1421ff8895e4557790f; ?>
<?php unset($__componentOriginal14b498b52c33a1421ff8895e4557790f); ?>
<?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($videoPosts->isNotEmpty()): ?>
        <section class="slant-top bg-navy-950 bg-pitch text-white">
            <div class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
                <?php if (isset($component)) { $__componentOriginal755230460fd16c04121658d92fbf99f7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal755230460fd16c04121658d92fbf99f7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-heading','data' => ['kicker' => __('club.home.video_kicker'),'title' => __('club.home.video_title'),'href' => route('video.index'),'link' => __('club.cta.all_videos'),'dark' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['kicker' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.home.video_kicker')),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.home.video_title')),'href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('video.index')),'link' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.cta.all_videos')),'dark' => true]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal755230460fd16c04121658d92fbf99f7)): ?>
<?php $attributes = $__attributesOriginal755230460fd16c04121658d92fbf99f7; ?>
<?php unset($__attributesOriginal755230460fd16c04121658d92fbf99f7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal755230460fd16c04121658d92fbf99f7)): ?>
<?php $component = $__componentOriginal755230460fd16c04121658d92fbf99f7; ?>
<?php unset($__componentOriginal755230460fd16c04121658d92fbf99f7); ?>
<?php endif; ?>

                <div class="reveal-group mt-12 grid gap-8 lg:grid-cols-[1.6fr_1fr]">
                    <?php $lead = $videoPosts->first(); ?>
                    <a href="<?php echo e(route('news.show', $lead->slug)); ?>" class="group relative block aspect-video overflow-hidden rounded-sm border border-white/10">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lead->featured_image): ?>
                            <img src="<?php echo e(Storage::disk('public')->url($lead->featured_image)); ?>" alt="<?php echo e($lead->title); ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
                        <?php else: ?>
                            <span class="film absolute inset-0"></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-navy-950/20 to-transparent"></div>
                        <span class="play-ring absolute left-1/2 top-1/2 z-10 h-20 w-20 -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></span>
                        <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lead->category): ?><span class="kicker text-gold-400"><?php echo e($lead->category->name); ?></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <h3 class="display mt-2 text-3xl sm:text-4xl"><?php echo e($lead->title); ?></h3>
                        </div>
                    </a>
                    <div class="grid gap-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $videoPosts->slice(1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('news.show', $post->slug)); ?>" class="group grid grid-cols-[7rem_1fr] items-center gap-4 rounded-sm border border-white/10 bg-navy-900/60 p-3 transition hover:border-gold-400/50">
                                <span class="relative block aspect-video overflow-hidden rounded-sm">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($post->featured_image): ?>
                                        <img src="<?php echo e(Storage::disk('public')->url($post->featured_image)); ?>" alt="" class="h-full w-full object-cover">
                                    <?php else: ?>
                                        <span class="film absolute inset-0"></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <span class="play-ring absolute left-1/2 top-1/2 h-8 w-8 -translate-x-1/2 -translate-y-1/2" aria-hidden="true"></span>
                                </span>
                                <span>
                                    <span class="block text-[11px] uppercase tracking-wider text-navy-300"><?php echo e($post->published_at?->format('d.m.Y.')); ?></span>
                                    <span class="display mt-1 block text-xl leading-none transition group-hover:text-gold-400"><?php echo e($post->title); ?></span>
                                </span>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($site['youtube'])): ?>
                            <a href="<?php echo e($site['youtube']); ?>" target="_blank" rel="noopener" class="btn btn-outline btn-sm mt-auto justify-start">▶ <?php echo e(__('club.video.youtube')); ?></a>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <section class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
        <?php if (isset($component)) { $__componentOriginal755230460fd16c04121658d92fbf99f7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal755230460fd16c04121658d92fbf99f7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.section-heading','data' => ['kicker' => __('club.home.team_kicker'),'title' => __('club.home.team_title'),'text' => __('club.home.team_text'),'href' => route('team.index', $locale),'link' => __('club.home.team_link')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('section-heading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['kicker' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.home.team_kicker')),'title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.home.team_title')),'text' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.home.team_text')),'href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('team.index', $locale)),'link' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.home.team_link'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal755230460fd16c04121658d92fbf99f7)): ?>
<?php $attributes = $__attributesOriginal755230460fd16c04121658d92fbf99f7; ?>
<?php unset($__attributesOriginal755230460fd16c04121658d92fbf99f7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal755230460fd16c04121658d92fbf99f7)): ?>
<?php $component = $__componentOriginal755230460fd16c04121658d92fbf99f7; ?>
<?php unset($__componentOriginal755230460fd16c04121658d92fbf99f7); ?>
<?php endif; ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($players->isNotEmpty()): ?>
            <div class="reveal-group mt-12 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $players; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $player): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginal73802c2e27be07b48ae9aa6967cb7e73 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal73802c2e27be07b48ae9aa6967cb7e73 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.player-card','data' => ['player' => $player]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('player-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['player' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($player)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal73802c2e27be07b48ae9aa6967cb7e73)): ?>
<?php $attributes = $__attributesOriginal73802c2e27be07b48ae9aa6967cb7e73; ?>
<?php unset($__attributesOriginal73802c2e27be07b48ae9aa6967cb7e73); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal73802c2e27be07b48ae9aa6967cb7e73)): ?>
<?php $component = $__componentOriginal73802c2e27be07b48ae9aa6967cb7e73; ?>
<?php unset($__componentOriginal73802c2e27be07b48ae9aa6967cb7e73); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="reveal mt-10 flex flex-wrap items-center gap-x-10 gap-y-4 rounded-sm bg-surface-200 px-6 py-5">
            <div><span class="score text-4xl text-navy-900"><?php echo e($playerCount); ?></span> <span class="ml-2 text-sm text-ink-600"><?php echo e(__('club.home.team_players')); ?></span></div>
            <div><span class="score text-4xl text-red-600"><?php echo e($academyCount); ?></span> <span class="ml-2 text-sm text-ink-600"><?php echo e(__('club.home.team_academy')); ?></span></div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($standings->isNotEmpty() && ($us = $standings->firstWhere('is_club', true))): ?>
                <div><span class="score text-4xl text-navy-900"><?php echo e($us->position); ?>.</span> <span class="ml-2 text-sm text-ink-600"><?php echo e($site['league_name'] ?? __('club.match.table')); ?></span></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <a href="<?php echo e(route('team.index', $locale)); ?>" class="btn btn-outline-dark btn-sm ml-auto"><?php echo e(__('club.nav.first_team')); ?></a>
        </div>
    </section>

    
    <section class="slant-top bg-navy-900 bg-pitch text-white">
        <div class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
            <div class="grid gap-12 lg:grid-cols-[1fr_1.3fr] lg:gap-20">
                <div class="reveal">
                    <p class="kicker flex items-center gap-2 text-gold-400"><span class="h-1.5 w-1.5 rounded-full bg-gold-400"></span><?php echo e(__('club.home.youth_kicker')); ?></p>
                    <h2 class="display mt-3 text-5xl sm:text-6xl lg:text-7xl"><?php echo e(__('club.home.youth_title')); ?></h2>
                    <p class="mt-5 max-w-lg text-navy-100"><?php echo e($site['youth_intro'] ?? ''); ?></p>
                    <div class="mt-8 grid grid-cols-3 gap-4 border-t border-white/10 pt-6">
                        <div><span class="score block text-4xl text-gold-400"><?php echo e($selections->count()); ?></span><span class="text-xs uppercase tracking-wider text-navy-300"><?php echo e(__('club.home.youth_selections')); ?></span></div>
                        <div><span class="score block text-4xl"><?php echo e($coachCount); ?></span><span class="text-xs uppercase tracking-wider text-navy-300"><?php echo e(__('club.home.youth_coaches')); ?></span></div>
                        <div><span class="display block text-2xl leading-tight"><?php echo e($site['youth_age_range'] ?? '5–19'); ?></span><span class="text-xs uppercase tracking-wider text-navy-300"><?php echo e(__('club.home.youth_age')); ?></span></div>
                    </div>
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="<?php echo e(route('youth.index', $locale)); ?>#upis" class="btn btn-red"><?php echo e(__('club.cta.enroll')); ?></a>
                        <a href="<?php echo e(route('youth.index', $locale)); ?>" class="btn btn-outline"><?php echo e(__('club.home.youth_link')); ?></a>
                    </div>
                </div>

                <div class="reveal-group grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $selections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $selection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('youth.index', $locale)); ?>#<?php echo e($selection->slug); ?>" class="group flex aspect-square flex-col justify-between rounded-sm border border-white/10 bg-navy-800/70 p-4 transition hover:border-gold-400/60 hover:bg-navy-800">
                            <span class="text-[11px] uppercase tracking-wider text-navy-300"><?php echo e($selection->birth_years); ?></span>
                            <span>
                                <span class="display block text-3xl leading-none transition group-hover:text-gold-400"><?php echo e($selection->name); ?></span>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selection->accepting_applications): ?>
                                    <span class="mt-2 inline-block rounded-sm bg-red-500 px-1.5 py-0.5 text-[9.5px] font-bold uppercase tracking-wider"><?php echo e(__('club.youth.open')); ?></span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </span>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
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

    
    <?php $socials = array_filter(['YouTube' => $site['youtube'] ?? null, 'Instagram' => $site['instagram'] ?? null, 'Facebook' => $site['facebook'] ?? null, 'TikTok' => $site['tiktok'] ?? null]); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($socials): ?>
        <section class="border-t border-surface-300 bg-white">
            <div class="mx-auto flex max-w-[100rem] flex-wrap items-center justify-between gap-6 px-6 py-10">
                <h2 class="display text-3xl text-navy-900"><?php echo e(__('club.home.social_title')); ?></h2>
                <div class="flex flex-wrap gap-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $socials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($url); ?>" target="_blank" rel="noopener" class="btn btn-outline-dark btn-sm"><?php echo e($label); ?></a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </section>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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
<?php /**PATH /home/user/FK--elezni-ar/resources/views/home.blade.php ENDPATH**/ ?>