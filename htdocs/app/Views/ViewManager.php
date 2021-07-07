<?php

declare(strict_types=1);

namespace App\Views;

use Jenssegers\Blade\Blade;

class ViewManager
{
    protected static Blade $blade;

    /**
     * ViewManager constructor.
     * @param Blade $blade
     */
    public function __construct(Blade $blade)
    {
        self::$blade = $blade;
    }

    /**
     * Returns an object of blade
     *
     * @return Blade
     */
    public static function getViewObject() : Blade
    {
        return self::$blade;
    }
}