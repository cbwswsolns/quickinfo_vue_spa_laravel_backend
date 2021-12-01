<?php

namespace App\Helpers;

use Purifier;

class HtmlHelper
{
    /**
     * Purify HTML
     *
     * @param HTML text $html [HTML text data]
     *
     * @return void
     */
    public function purifyHTML($html)
    {
        return Purifier::clean($html, array('Attr.AllowedFrameTargets' => ['_target' => true]));
    }
}
