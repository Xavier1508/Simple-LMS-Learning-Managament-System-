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
            ->addDirective(Directive::CONNECT, [
                Keyword::SELF,
                'https://cloudflareinsights.com',
                'https://*.cloudflare.com',
            ])
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, [
                Keyword::SELF,
                'data:',
                'https://placehold.co',
                '*', // Mengizinkan gambar dari mana saja (Penting untuk upload user)
            ])
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            ->addDirective(Directive::SCRIPT, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE, // Wajib untuk Livewire/Alpine
                Keyword::UNSAFE_EVAL,   // Wajib untuk Alpine.js
                'https://static.cloudflareinsights.com',
                'https://*.cloudflare.com',
            ])
            ->addDirective(Directive::STYLE, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE, // Wajib untuk atribut style="" di HTML
            ])
            ->addDirective(Directive::FONT, [
                Keyword::SELF,
                'data:',
            ]);
    }
}
