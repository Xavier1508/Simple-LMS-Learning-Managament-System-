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
                'https://www.google.com',
                'https://www.gstatic.com',
            ])
            ->addDirective(Directive::DEFAULT, Keyword::SELF)
            ->addDirective(Directive::FORM_ACTION, Keyword::SELF)
            ->addDirective(Directive::IMG, [
                Keyword::SELF,
                'data:',
                'https://placehold.co',
                '*',
            ])
            ->addDirective(Directive::MEDIA, Keyword::SELF)
            ->addDirective(Directive::OBJECT, Keyword::NONE)
            ->addDirective(Directive::SCRIPT, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE,
                Keyword::UNSAFE_EVAL,
                'https://static.cloudflareinsights.com',
                'https://*.cloudflare.com',
                'https://cdn.jsdelivr.net',
                'https://unpkg.com',
                'https://www.google.com',
                'https://www.gstatic.com',
            ])
            ->addDirective(Directive::STYLE, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE,
                'https://cdn.jsdelivr.net',
                'https://fonts.googleapis.com',
            ])
            ->addDirective(Directive::FONT, [
                Keyword::SELF,
                'data:',
                'https://fonts.gstatic.com',
            ])
            ->addDirective(Directive::FRAME, [
                Keyword::SELF,
                'https://www.google.com',
                'https://recaptcha.google.com',
            ]);
    }
}
