<?php

namespace Tests\Unit;

use App\Support\VideoEmbed;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class VideoEmbedTest extends TestCase
{
    public static function youtubeUrls(): array
    {
        return [
            'watch' => ['https://www.youtube.com/watch?v=jNQXAC9IVRw', 'https://www.youtube-nocookie.com/embed/jNQXAC9IVRw'],
            'short link' => ['https://youtu.be/jNQXAC9IVRw', 'https://www.youtube-nocookie.com/embed/jNQXAC9IVRw'],
            'shorts' => ['https://www.youtube.com/shorts/jNQXAC9IVRw', 'https://www.youtube-nocookie.com/embed/jNQXAC9IVRw'],
            'live broadcast' => ['https://www.youtube.com/live/jNQXAC9IVRw', 'https://www.youtube-nocookie.com/embed/jNQXAC9IVRw'],
            'live broadcast with tracking params' => ['https://www.youtube.com/live/jNQXAC9IVRw?si=Xy_9', 'https://www.youtube-nocookie.com/embed/jNQXAC9IVRw'],
            'channel live' => ['https://www.youtube.com/channel/UCabcdefghijklmnopqrstuv/live', 'https://www.youtube-nocookie.com/embed/live_stream?channel=UCabcdefghijklmnopqrstuv'],
            'bare channel id' => ['UCabcdefghijklmnopqrstuv', 'https://www.youtube-nocookie.com/embed/live_stream?channel=UCabcdefghijklmnopqrstuv'],
            'vimeo' => ['https://vimeo.com/76979871', 'https://player.vimeo.com/video/76979871'],
        ];
    }

    #[DataProvider('youtubeUrls')]
    public function test_it_builds_an_embed_src(string $input, string $expected): void
    {
        $this->assertSame($expected, VideoEmbed::url($input));
    }

    public function test_it_returns_null_for_links_it_cannot_embed(): void
    {
        $this->assertNull(VideoEmbed::url(null));
        $this->assertNull(VideoEmbed::url(''));
        $this->assertNull(VideoEmbed::url('https://example.com/video.mp4'));
        // YouTube's live_stream player resolves channels by ID, never by handle.
        $this->assertNull(VideoEmbed::url('https://www.youtube.com/@FKZeleznicarNis/live'));
    }

    public function test_it_flags_handle_urls_so_the_admin_can_explain_them(): void
    {
        $this->assertTrue(VideoEmbed::isHandleLiveUrl('https://www.youtube.com/@FKZeleznicarNis/live'));
        $this->assertTrue(VideoEmbed::isHandleLiveUrl('https://www.youtube.com/@FKZeleznicarNis'));
        $this->assertFalse(VideoEmbed::isHandleLiveUrl('https://www.youtube.com/live/jNQXAC9IVRw'));
    }
}
