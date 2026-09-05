
<?php $locale = app()->getLocale(); ?>

<section class="mx-auto max-w-[100rem] px-6 py-16 lg:py-20">
    <div class="reveal-group grid gap-6 lg:grid-cols-[1.4fr_1fr]">
        <a href="<?php echo e(route('youth.index', $locale)); ?>#upis" class="group relative overflow-hidden rounded-sm bg-red-500 p-8 text-white transition hover:bg-red-600 sm:p-12">
            <span class="wordmark-ghost absolute -bottom-6 -right-4 text-[9rem]" style="-webkit-text-stroke-color: rgba(255,255,255,0.15);" aria-hidden="true">upis</span>
            <p class="kicker text-white/80"><?php echo e(__('club.home.enroll_kicker')); ?></p>
            <h2 class="display mt-3 max-w-xl text-4xl sm:text-5xl"><?php echo e(__('club.home.enroll_title')); ?></h2>
            <p class="mt-4 max-w-md text-white/85"><?php echo e(__('club.home.enroll_text')); ?></p>
            <span class="btn btn-sm mt-8 bg-white text-red-600 transition group-hover:gap-3"><?php echo e(__('club.cta.enroll_long')); ?> <span aria-hidden="true">→</span></span>
        </a>

        <a href="<?php echo e(route('support.index', $locale)); ?>" class="group relative overflow-hidden rounded-sm bg-navy-900 bg-pitch p-8 text-white transition hover:bg-navy-800 sm:p-12">
            <p class="kicker text-gold-400"><?php echo e(__('club.home.support_kicker')); ?></p>
            <h2 class="display mt-3 text-4xl sm:text-5xl"><?php echo e(__('club.home.support_title')); ?></h2>
            <p class="mt-4 max-w-md text-navy-200"><?php echo e(__('club.home.support_text')); ?></p>
            <span class="btn btn-gold btn-sm mt-8 transition group-hover:gap-3"><?php echo e(__('club.cta.support_long')); ?> <span aria-hidden="true">→</span></span>
        </a>
    </div>
</section>
<?php /**PATH /home/user/FK--elezni-ar/resources/views/components/cta-band.blade.php ENDPATH**/ ?>