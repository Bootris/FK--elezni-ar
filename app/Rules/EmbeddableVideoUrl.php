<?php

namespace App\Rules;

use App\Support\VideoEmbed;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Rejects video links the site cannot embed at save time, instead of letting the
 * post save cleanly and then silently render no player.
 */
class EmbeddableVideoUrl implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (blank($value)) {
            return;
        }

        if (VideoEmbed::isHandleLiveUrl((string) $value)) {
            $fail('Link sa @imenom kanala se ne može ugraditi. Na YouTube-u otvori kanal, uzmi ID kanala (počinje sa UC…) i nalepi ga ovde, ili link oblika youtube.com/channel/UC…/live');

            return;
        }

        if (! VideoEmbed::url((string) $value)) {
            $fail('Link nije prepoznat. Podržani su YouTube (watch?v=…, /live/…, youtu.be/…, /shorts/…, /channel/UC…/live) i Vimeo linkovi.');
        }
    }
}
