<?php

namespace App\Support\Csp\Policies;

use Spatie\Csp\Directive;
use Spatie\Csp\Keyword;
use Spatie\Csp\Policies\Basic;

class LMSPolicy extends Basic
{
    public function configure()
    {
        $this
            ->addDirective(Directive::BASE, Keyword::SELF)
            ->addDirective(Directive::CONNECT, [Keyword::SELF])
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, [Keyword::SELF, 'data:', 'https://placehold.co']) // Izinkan gambar placeholder
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            ->addDirective(Directive::SCRIPT, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE, // Wajib untuk Livewire/AlpineJS v3 tanpa nonce
                Keyword::UNSAFE_EVAL,   // Kadang dibutuhkan AlpineJS
                'https://cdn.jsdelivr.net', // Untuk Lucide Icons / FontAwesome CDN
                'https://cdnjs.cloudflare.com'
            ])
            ->addDirective(Directive::STYLE, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE, // Wajib untuk style yang di-inject Livewire
                'https://cdnjs.cloudflare.com', // FontAwesome CSS
                'https://fonts.googleapis.com'  // Google Fonts
            ])
            ->addDirective(Directive::FONT, [
                Keyword::SELF,
                'https://cdnjs.cloudflare.com',
                'https://fonts.gstatic.com',
                'data:'
            ]);
    }
}
