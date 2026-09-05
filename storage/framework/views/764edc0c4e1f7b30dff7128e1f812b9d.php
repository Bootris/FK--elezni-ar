<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => null, 'description' => null, 'image' => null]));

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

foreach (array_filter((['title' => null, 'description' => null, 'image' => null]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    use Illuminate\Support\Facades\Cache;
    use Illuminate\Support\Facades\Storage;
    use App\Models\Post;

    $siteName = $site['site_name'] ?? config('app.name');
    $shortName = $site['club_short_name'] ?? 'Železničar';
    $tagline = $site['tagline'] ?? __('club.hero.kicker');
    $pageTitle = $title ? "{$title} — {$siteName}" : ($site['seo_title'] ?? "{$siteName} — {$tagline}");
    $pageDescription = $description ?? ($site['seo_description'] ?? __('club.meta.description'));
    $locale = app()->getLocale();
    $homeUrl = url($locale);
    $logoUrl = !empty($site['logo']) ? Storage::disk('public')->url($site['logo']) : asset('images/logo.gif');
    $ogImage = $image ?? url($logoUrl);

    $navLinks = [
        ['href' => route('news.index'), 'label' => __('club.nav.news')],
        ['href' => route('video.index'), 'label' => __('club.nav.video')],
        ['href' => route('team.index', $locale), 'label' => __('club.nav.first_team')],
        ['href' => route('youth.index', $locale), 'label' => __('club.nav.youth')],
        ['href' => route('support.index', $locale), 'label' => __('club.nav.support')],
        ['href' => route('contact.index', $locale), 'label' => __('club.nav.contact')],
    ];

    $enrolUrl = route('youth.index', $locale) . '#upis';
    $supportUrl = route('support.index', $locale);

    $tickerHeadlines = Cache::remember('site_ticker', 600, fn () =>
        Post::published()->orderByDesc('published_at')->take(6)->pluck('title')->all()
    );

    $socials = array_filter([
        'YouTube' => $site['youtube'] ?? null,
        'Instagram' => $site['instagram'] ?? null,
        'Facebook' => $site['facebook'] ?? null,
        'TikTok' => $site['tiktok'] ?? null,
    ]);

    $isActive = fn (string $href) => str_starts_with(url()->current(), $href);
?>
<!DOCTYPE html>
<html lang="<?php echo e($locale); ?>" class="scroll-pt-24">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($pageTitle); ?></title>
    <meta name="description" content="<?php echo e($pageDescription); ?>">
    <meta name="theme-color" content="#101a2e">
    <meta property="og:title" content="<?php echo e($pageTitle); ?>">
    <meta property="og:description" content="<?php echo e($pageDescription); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(url()->current()); ?>">
    <meta property="og:image" content="<?php echo e($ogImage); ?>">
    <meta property="og:site_name" content="<?php echo e($siteName); ?>">
    <meta name="twitter:card" content="summary_large_image">
    <link rel="canonical" href="<?php echo e(url()->current()); ?>">
    <link rel="icon" href="<?php echo e($logoUrl); ?>">
    <link rel="apple-touch-icon" href="<?php echo e($logoUrl); ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@600;700;800;900&family=Barlow:wght@400;500;600;700&display=swap" rel="stylesheet">

    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

    <script type="application/ld+json">
    <?php echo json_encode([
        '<?php $__contextArgs = [];
if (context()->has($__contextArgs[0])) :
if (isset($value)) { $__contextPrevious[] = $value; }
$value = context()->get($__contextArgs[0]); ?>' => 'https://schema.org',
        '@type' => 'SportsTeam',
        'name' => $siteName,
        'sport' => 'Football',
        'url' => $homeUrl,
        'logo' => url($logoUrl),
        'foundingDate' => $site['founded_year'] ?? '1928',
        'address' => ['@type' => 'PostalAddress', 'addressLocality' => $site['city'] ?? 'Niš', 'addressCountry' => 'RS'],
        'sameAs' => array_values($socials),
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>

    </script>
</head>

<body class="bg-surface-100 font-sans text-ink-900 antialiased">

    
    <div class="hidden bg-navy-950 text-navy-200 lg:block">
        <div class="mx-auto flex h-9 max-w-[100rem] items-center gap-6 px-6 text-[11.5px]">
            <span class="kicker text-[10.5px] text-gold-400"><?php echo e(__('club.footer.founded')); ?> <?php echo e($site['founded_year'] ?? '1928'); ?></span>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($site['stadium'])): ?>
                <span class="text-navy-300"><?php echo e($site['stadium']); ?></span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($tickerHeadlines)): ?>
                <div class="ticker-mask h-9 flex-1 items-center overflow-hidden border-x border-white/10 px-5 flex">
                    <div class="ticker-track gap-10">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = array_merge($tickerHeadlines, $tickerHeadlines); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $headline): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="flex items-center gap-10">
                                <span class="h-1.5 w-1.5 rounded-full bg-red-500" aria-hidden="true"></span>
                                <?php echo e($headline); ?>

                            </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="flex-1"></div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <span class="flex items-center gap-1.5 font-semibold tracking-wide">
                <a href="<?php echo e(url('sr')); ?>" class="<?php echo e($locale === 'sr' ? 'text-white' : 'transition hover:text-white'); ?>">SR</a>
                <span class="text-navy-600">/</span>
                <a href="<?php echo e(url('en')); ?>" class="<?php echo e($locale === 'en' ? 'text-white' : 'transition hover:text-white'); ?>">EN</a>
            </span>
        </div>
    </div>

    
    <header id="site-header" class="sticky top-0 z-40 border-b border-white/10 bg-navy-900/95 text-white backdrop-blur transition-shadow">
        <div class="mx-auto flex h-[4.25rem] max-w-[100rem] items-center gap-5 px-4 sm:px-6 lg:h-20">
            <a href="<?php echo e($homeUrl); ?>" class="flex shrink-0 items-center gap-3">
                <img src="<?php echo e($logoUrl); ?>" alt="<?php echo e($siteName); ?>" class="h-11 w-11 object-contain lg:h-14 lg:w-14" width="56" height="56">
                <span class="hidden flex-col leading-none sm:flex">
                    <span class="font-display text-[1.45rem] font-black uppercase tracking-tight lg:text-[1.65rem]">FK <?php echo e($shortName); ?></span>
                    <span class="kicker mt-1 text-[9.5px] tracking-[0.28em] text-gold-400"><?php echo e($site['city'] ?? 'Niš'); ?> · <?php echo e($site['founded_year'] ?? '1928'); ?></span>
                </span>
            </a>

            <nav class="ml-6 hidden items-center gap-1 lg:flex" aria-label="Main">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $navLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e($link['href']); ?>"
                        class="rounded-sm px-3 py-2 font-display text-[15.5px] font-bold uppercase tracking-wide transition <?php echo e($isActive($link['href']) ? 'text-white after:block after:h-0.5 after:bg-red-500' : 'text-navy-100 hover:text-white'); ?>">
                        <?php echo e($link['label']); ?>

                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </nav>

            <div class="ml-auto flex items-center gap-2 sm:gap-3">
                <a href="<?php echo e($supportUrl); ?>" class="btn btn-outline btn-sm hidden md:inline-flex"><?php echo e(__('club.cta.support')); ?></a>
                <a href="<?php echo e($enrolUrl); ?>" class="btn btn-red btn-sm">
                    <svg class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l2.9 6.3 6.9.8-5.1 4.7 1.4 6.8L12 17.3 5.9 20.6l1.4-6.8L2.2 9.1l6.9-.8z"/></svg>
                    <?php echo e(__('club.cta.enroll')); ?>

                </a>
                <button id="menu-toggle" type="button" aria-expanded="false" aria-controls="mobile-menu"
                    class="rounded-md p-2 text-white transition hover:bg-white/10 lg:hidden">
                    <span class="sr-only">Meni</span>
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" /></svg>
                </button>
            </div>
        </div>
    </header>

    
    <div id="menu-overlay" class="pointer-events-none fixed inset-0 z-40 bg-navy-950/75 opacity-0 transition-opacity duration-300 lg:hidden"></div>
    <div id="mobile-menu"
        class="fixed inset-y-0 right-0 z-50 flex w-80 max-w-[88vw] translate-x-full flex-col bg-navy-950 bg-pitch p-7 text-white transition-transform duration-300 lg:hidden">
        <div class="flex items-center justify-between">
            <span class="flex items-center gap-3">
                <img src="<?php echo e($logoUrl); ?>" alt="" class="h-10 w-10 object-contain">
                <span class="font-display text-xl font-black uppercase">FK <?php echo e($shortName); ?></span>
            </span>
            <button id="menu-close" type="button" class="rounded-md p-2 transition hover:bg-white/10">
                <span class="sr-only">Zatvori</span>
                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="m6 6 12 12M18 6 6 18" /></svg>
            </button>
        </div>

        <nav class="mt-8 flex flex-col" aria-label="Mobile">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $navLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e($link['href']); ?>" class="border-b border-white/10 py-3.5 font-display text-2xl font-bold uppercase tracking-wide transition hover:text-gold-400"><?php echo e($link['label']); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </nav>

        <div class="mt-8 grid gap-3">
            <a href="<?php echo e($enrolUrl); ?>" class="btn btn-red whitespace-normal"><?php echo e(__('club.cta.enroll')); ?></a>
            <a href="<?php echo e($supportUrl); ?>" class="btn btn-outline whitespace-normal"><?php echo e(__('club.cta.support')); ?></a>
        </div>

        <div class="mt-auto flex items-center gap-3 pt-8 text-sm font-semibold text-navy-200">
            <a href="<?php echo e(url('sr')); ?>" class="<?php echo e($locale === 'sr' ? 'text-gold-400' : ''); ?>">Srpski</a>
            <span class="text-navy-600">/</span>
            <a href="<?php echo e(url('en')); ?>" class="<?php echo e($locale === 'en' ? 'text-gold-400' : ''); ?>">English</a>
        </div>
    </div>

    <main>
        <?php echo e($slot); ?>

    </main>

    
    <footer class="relative overflow-hidden bg-navy-950 bg-pitch text-navy-200">
        <div class="mx-auto max-w-[100rem] px-6 pt-16 pb-24 lg:pb-8">
            <div class="wordmark-ghost text-[17vw] sm:text-[14vw]" aria-hidden="true"><?php echo e($shortName); ?></div>

            <div class="mt-10 grid gap-10 border-t border-white/10 pt-12 md:grid-cols-[2fr_1fr_1fr_1fr]">
                <div>
                    <div class="flex items-center gap-3">
                        <img src="<?php echo e($logoUrl); ?>" alt="" class="h-12 w-12 object-contain">
                        <span class="font-display text-2xl font-black uppercase text-white"><?php echo e($siteName); ?></span>
                    </div>
                    <p class="mt-5 max-w-sm text-sm leading-relaxed"><?php echo e(__('club.footer.about')); ?></p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="<?php echo e($enrolUrl); ?>" class="btn btn-red btn-sm"><?php echo e(__('club.cta.enroll')); ?></a>
                        <a href="<?php echo e($supportUrl); ?>" class="btn btn-outline btn-sm"><?php echo e(__('club.cta.support')); ?></a>
                    </div>
                </div>

                <div>
                    <h3 class="kicker text-navy-300"><?php echo e(__('club.footer.sections')); ?></h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $navLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><a href="<?php echo e($link['href']); ?>" class="transition hover:text-gold-400"><?php echo e($link['label']); ?></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>

                <div>
                    <h3 class="kicker text-navy-300"><?php echo e(__('club.footer.club')); ?></h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($site['address'])): ?><li><?php echo e($site['address']); ?></li><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($site['stadium'])): ?><li><?php echo e($site['stadium']); ?></li><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($site['email'])): ?>
                            <li><a href="mailto:<?php echo e($site['email']); ?>" class="transition hover:text-gold-400"><?php echo e($site['email']); ?></a></li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($site['phone'])): ?>
                            <li><a href="tel:<?php echo e(preg_replace('/[^+\d]/', '', $site['phone'])); ?>" class="transition hover:text-gold-400"><?php echo e($site['phone']); ?></a></li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>

                <div>
                    <h3 class="kicker text-navy-300"><?php echo e(__('club.footer.follow')); ?></h3>
                    <ul class="mt-5 space-y-3 text-sm">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $socials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <li><a href="<?php echo e($url); ?>" target="_blank" rel="noopener" class="transition hover:text-gold-400"><?php echo e($label); ?></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <li>YouTube</li>
                            <li>Instagram</li>
                            <li>Facebook</li>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                </div>
            </div>

            <div class="mt-14 flex flex-col items-start justify-between gap-2 border-t border-white/10 pt-6 text-xs text-navy-300 sm:flex-row sm:items-center">
                <p>© <?php echo e(date('Y')); ?> <?php echo e($siteName); ?>. <?php echo e(__('club.footer.rights')); ?></p>
                <p><?php echo e($tagline); ?></p>
            </div>
        </div>
    </footer>

    
    <div class="mobile-cta fixed inset-x-0 bottom-0 z-30 grid grid-cols-2 gap-2 border-t border-white/10 bg-navy-950/95 p-2 backdrop-blur lg:hidden">
        <a href="<?php echo e($supportUrl); ?>" class="btn btn-outline btn-sm"><?php echo e(__('club.cta.support')); ?></a>
        <a href="<?php echo e($enrolUrl); ?>" class="btn btn-red btn-sm"><?php echo e(__('club.cta.enroll')); ?></a>
    </div>

</body>

</html>
<?php /**PATH /home/user/FK--elezni-ar/resources/views/components/site-layout.blade.php ENDPATH**/ ?>