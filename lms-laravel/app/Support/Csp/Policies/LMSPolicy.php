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
                'https://cdn.jsdelivr.net',
                'https://cdnjs.cloudflare.com',
                'https://static.cloudflareinsights.com',
                'https://cloudflareinsights.com',
            ])
            ->addDirective(Directive::STYLE, [
                Keyword::SELF,
                Keyword::UNSAFE_INLINE,
                'https://cdnjs.cloudflare.com',
                'https://fonts.googleapis.com',
            ])
            ->addDirective(Directive::FONT, [
                Keyword::SELF,
                'https://cdnjs.cloudflare.com',
                'https://fonts.gstatic.com',
                'data:',
            ]);
    }
}
