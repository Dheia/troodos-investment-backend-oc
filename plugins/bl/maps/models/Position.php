<?php

namespace BL\Maps\Models;

use Model;

/**
 * Attribute Model
 */
class Position extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_maps_positions';

    public $translatable = ['title', 'description'];

    public $timestamps = false;

    public $implement = ['RainLab.Translate.Behaviors.TranslatableModel'];

    public $morphTo = [
        'model' => []
    ];
}
