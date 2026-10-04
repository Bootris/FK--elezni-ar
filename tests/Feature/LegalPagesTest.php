<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LegalPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_legal_pages_render_in_both_languages_and_show_the_operator(): void
    {
        $this->withoutVite();
        Setting::set('legal_name', 'FK Test d.o.o.');
        Setting::set('legal_id', '123456789');

        foreach (['sr', 'en'] as $locale) {
            foreach (['politika-privatnosti', 'uslovi-koriscenja'] as $page) {
                $this->get("/{$locale}/{$page}")
                    ->assertOk()
                    ->assertSee('FK Test d.o.o.')
                    ->assertSee('123456789');
            }
        }
    }

    public function test_footer_links_to_both_documents_and_sitemap_lists_them(): void
    {
        $this->withoutVite();

        $this->get('/sr')
            ->assertSee('/sr/politika-privatnosti', false)
            ->assertSee('/sr/uslovi-koriscenja', false);
        $this->get('/sitemap.xml')->assertSee('/sr/politika-privatnosti', false);
    }
}
