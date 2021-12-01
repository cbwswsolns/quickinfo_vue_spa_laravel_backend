<?php

namespace App\Http\Resources;

use App\Helpers\HtmlHelper;
use Illuminate\Http\Resources\Json\JsonResource;

class InfoResource extends JsonResource
{
    public function toArray($request)
    {
        $infoResource = parent::toArray($request->except('definition'));

        $infoResource['definition'] = (new HtmlHelper())->purifyHTML($this->definition);

        return $infoResource;
    }
}
