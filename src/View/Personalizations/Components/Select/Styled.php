<?php

namespace TallStackUi\View\Personalizations\Components\Select;

use TallStackUi\View\Components\Select\Styled as Component;
use TallStackUi\View\Personalizations\Contracts\Personalizable;
use TallStackUi\View\Personalizations\PersonalizationResource;

/**
 * @internal This class is not meant to be used directly.
 */
class Styled extends PersonalizationResource implements Personalizable
{
    protected function component(): string
    {
        return Component::class;
    }
}