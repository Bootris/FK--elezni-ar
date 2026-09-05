<?php
    $rows = array_filter([
        'account_holder' => $site['account_holder'] ?? null,
        'bank' => $site['bank_name'] ?? null,
        'account_number' => $site['bank_account'] ?? null,
        'purpose' => $site['payment_purpose'] ?? null,
        'code' => $site['payment_code'] ?? null,
        'model' => $site['payment_model'] ?? null,
        'reference' => $site['payment_reference'] ?? null,
        'iban' => $site['iban'] ?? null,
        'swift' => $site['swift'] ?? null,
    ]);
    $locale = app()->getLocale();
?>

<?php if (isset($component)) { $__componentOriginald956570c5321d7185b887a45463f814f = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald956570c5321d7185b887a45463f814f = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-layout','data' => ['title' => __('club.support.page_title'),'description' => $site['support_intro'] ?? __('club.support.subtitle')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(__('club.support.page_title')),'description' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($site['support_intro'] ?? __('club.support.subtitle'))]); ?>

    <section class="relative overflow-hidden bg-navy-950 bg-pitch bg-spot text-white">
        <div class="wordmark-ghost absolute -bottom-8 -right-6 text-[20vw]" aria-hidden="true"><?php echo e($site['founded_year'] ?? '1928'); ?></div>
        <div class="relative mx-auto max-w-[100rem] px-6 py-14 sm:py-20">
            <p class="kicker text-gold-400"><?php echo e(__('club.support.eyebrow')); ?></p>
            <h1 class="display mt-3 max-w-4xl text-5xl text-balance sm:text-7xl lg:text-8xl"><?php echo e(__('club.support.title')); ?></h1>
            <p class="mt-5 max-w-2xl text-lg text-navy-100"><?php echo e($site['support_intro'] ?? __('club.support.subtitle')); ?></p>
        </div>
        <div class="rails text-white"></div>
    </section>

    <section class="mx-auto max-w-[100rem] px-6 py-16 lg:py-24">
        <div class="grid gap-12 lg:grid-cols-[1.2fr_1fr] lg:gap-20">
            
            <div class="reveal">
                <p class="kicker text-red-600"><?php echo e(__('club.support.how_title')); ?></p>
                <p class="mt-3 max-w-xl text-ink-600"><?php echo e(__('club.support.how_text')); ?></p>

                <div class="mt-8 overflow-hidden rounded-sm border-2 border-navy-900 bg-white">
                    <div class="flex items-center justify-between bg-navy-900 px-5 py-3 text-white">
                        <span class="display text-2xl"><?php echo e($site['site_name'] ?? 'FK Železničar'); ?></span>
                        <img src="<?php echo e(asset('images/logo.gif')); ?>" alt="" class="h-9 w-9">
                    </div>
                    <dl class="divide-y divide-surface-300">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="grid grid-cols-[8rem_1fr_auto] items-center gap-3 px-5 py-3.5 sm:grid-cols-[11rem_1fr_auto]">
                                <dt class="text-[11px] font-bold uppercase tracking-[0.14em] text-ink-400"><?php echo e(__('club.support.' . $key)); ?></dt>
                                <dd class="<?php echo e($key === 'account_number' ? 'score text-2xl text-navy-900 sm:text-3xl' : 'font-semibold text-ink-900'); ?>"><?php echo e($value); ?></dd>
                                <dd>
                                    <button type="button" data-copy="<?php echo e($value); ?>" data-copied="<?php echo e(__('club.support.copied')); ?>"
                                        class="rounded-sm border border-surface-300 px-2.5 py-1 font-display text-[11px] font-bold uppercase tracking-wider text-ink-600 transition hover:border-navy-900 hover:text-navy-900 [&.is-copied]:border-green-600 [&.is-copied]:text-green-700">
                                        <span data-copy-label><?php echo e(__('club.support.copy')); ?></span>
                                    </button>
                                </dd>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </dl>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($site['support_note'])): ?>
                    <p class="mt-4 text-sm text-ink-600"><?php echo e($site['support_note']); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <p class="display mt-8 text-3xl text-red-600"><?php echo e(__('club.support.thanks')); ?></p>
            </div>

            
            <div class="reveal">
                <p class="kicker text-red-600"><?php echo e(__('club.support.where_title')); ?></p>
                <ul class="mt-6 space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = __('club.support.where'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => [$whereTitle, $whereText]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="flex gap-5 rounded-sm border border-surface-300 bg-white p-5">
                            <span class="score text-4xl text-gold-500"><?php echo e(str_pad($i + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                            <div>
                                <h3 class="display text-2xl text-navy-900"><?php echo e($whereTitle); ?></h3>
                                <p class="mt-1 text-sm text-ink-600"><?php echo e($whereText); ?></p>
                            </div>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>

                <div class="mt-8 rounded-sm bg-navy-950 bg-pitch p-6 text-white">
                    <h3 class="display text-2xl"><?php echo e(__('club.support.other_title')); ?></h3>
                    <p class="mt-2 text-sm text-navy-200"><?php echo e(__('club.support.other_text')); ?></p>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($site['email'])): ?><a href="mailto:<?php echo e($site['email']); ?>" class="btn btn-gold btn-sm"><?php echo e($site['email']); ?></a><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <a href="<?php echo e(route('contact.index', $locale)); ?>" class="btn btn-outline btn-sm"><?php echo e(__('club.nav.contact')); ?></a>
                    </div>
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
<?php /**PATH /home/user/FK--elezni-ar/resources/views/support/index.blade.php ENDPATH**/ ?>