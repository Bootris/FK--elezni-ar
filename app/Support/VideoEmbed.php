<?php

namespace App\Support;

/**
 * Turns the links people actually copy out of YouTube and Vimeo into an
 * embeddable iframe src: a normal video, a live broadcast (youtube.com/live/…)
 * or a channel's permanent "whatever we are streaming right now" URL.
 */
class VideoEmbed
{
    /** YouTube channel IDs are always UC + 22 characters. */
    private const CHANNEL_ID = '(UC[\w-]{22})';

    public static function url(?string $url): ?string
    {
        if (blank($url)) {
            return null;
        }

        $url = trim($url);

        // Checked first: a channel URL also contains segments the video patterns would chew on.
        if ($channel = static::channelId($url)) {
            return "https://www.youtube-nocookie.com/embed/live_stream?channel={$channel}";
        }

        if (preg_match('~(?:youtube\.com/(?:watch\?v=|shorts/|embed/|live/)|youtu\.be/)([\w-]{11})~', $url, $m)) {
            return "https://www.youtube-nocookie.com/embed/{$m[1]}";
        }

        if (preg_match('~vimeo\.com/(?:video/)?(\d+)~', $url, $m)) {
            return "https://player.vimeo.com/video/{$m[1]}";
        }

        return null;
    }

    /** The channel ID out of a /channel/UC…/live URL, or a bare channel ID pasted on its own. */
    public static function channelId(string $url): ?string
    {
        $url = trim($url);

        if (preg_match('~youtube\.com/channel/' . self::CHANNEL_ID . '~', $url, $m)) {
            return $m[1];
        }

        if (preg_match('~^' . self::CHANNEL_ID . '$~', $url, $m)) {
            return $m[1];
        }

        return null;
    }

    /**
     * A youtube.com/@handle/live URL cannot be embedded — YouTube's live_stream
     * player resolves channels by ID only, and a handle is not one.
     */
    public static function isHandleLiveUrl(?string $url): bool
    {
        return (bool) preg_match('~youtube\.com/@[\w.-]+(?:/live)?/?$~', trim((string) $url));
    }
}
