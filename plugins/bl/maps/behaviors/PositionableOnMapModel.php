<?php namespace BL\Maps\Behaviors;

use BL\Maps\Models\Position;
use Illuminate\Support\Facades\DB;
use October\Rain\Extension\ExtensionBase;
use October\Rain\Html\Helper as HtmlHelper;

class PositionableOnMapModel extends ExtensionBase
{
    protected $model;

    /**
     * @var array Data store for positionable attributes.
     */
    protected $positionableAttributes = [];

    /**
     * @var string Active mapId for positions.
     */
    protected $positionableContext;

    /**
     * {@inheritDoc}
     */
    protected $requiredProperties = ['positionable'];

    public function __construct($model)
    {
        $this->model = $model;

        $model->morphMany['positions'] = [
            'BL\Maps\Models\Position',
            'name' => 'model'
        ];

        $model->bindEvent('model.beforeDelete', function () use ($model) {
            DB::table('infopoint.bl_maps_positions')->where(['model_id' => $model->id, 'model_type' => get_class($model)])->delete();
        });

        $this->model->bindEvent('model.saveInternal', function () {
            $this->syncPositionableAttributes();
        });
    }

    public function initPositionableContext($mapId)
    {
        $this->positionableContext = $mapId;
    }

    /**
     * Checks if this model has positionable attributes.
     * @return true
     */
    public function hasPositionableAttributes()
    {
        return is_array($this->model->positionable) &&
            count($this->model->positionable) > 0;
    }

    public function getPositionableAttributes()
    {
        $positionable = [
            'title',
            'description',
            'external_url',
            'coord_x',
            'coord_y'
        ];

        return $positionable;
    }

    public function syncPositionableAttributes()
    {
        $knownMaps = array_keys($this->positionableAttributes);
        foreach ($knownMaps as $mapId) {
            $this->storePositionableData($mapId);
        }


        /*
         * Restore positionable values to models originals
         */
        $original = $this->model->getOriginal();
        $attributes = $this->model->getAttributes();
        $positionable = $this->getPositionableAttributes();
        $originalValues = array_intersect_key($original, array_flip($positionable));
        $this->model->attributes = array_merge($attributes, $originalValues);
    }

    /**
     * Saves the position data in the join table.
     * @param string $locale
     * @return void
     */
    public function storePositionableData($mapId = null)
    {
        if (!$mapId) {
            $mapId = $this->positionableContext;
        }

        /*
         * Model doesn't exist yet, defer this logic in memory
         */
        if (!$this->model->exists) {
            $this->model->bindEventOnce('model.afterCreate', function () use ($mapId) {
                $this->storePositionableData($mapId);
            });

            return;
        }

        /**
         * @event model.position.resolveComputedFields
         * Resolve computed fields before saving
         *
         * Example usage:
         *
         * Override Model's __construct method
         *
         * public function __construct(array $attributes = [])
         * {
         *     parent::__construct($attributes);
         *
         *     $this->bindEvent('model.position.resolveComputedFields', function ($locale) {
         *         return [
         *             'content_html' =>
         *                 self::formatHtml($this->asExtension('PositionableModel')
         *                     ->getAttributePositioned('content', $locale))
         *         ];
         *     });
         * }
         *
         */
        $computedFields = $this->model->fireEvent('model.position.resolveComputedFields', [$mapId], true);
        if (is_array($computedFields)) {
            $this->positionableAttributes[$mapId] = array_merge($this->positionableAttributes[$mapId], $computedFields);
        }

        return $this->storePositionableBasicData($mapId);
    }

    /**
     * Saves the basic position data in the join table.
     * @param string $locale
     * @return void
     */
    protected function storePositionableBasicData($mapId = null)
    {
        $marker = Position::where('map_id', $mapId)
            ->where('model_id', $this->model->getKey())
            ->where('model_type', $this->model->getMorphClass())->first();

        if (!$marker) {
            $marker = new Position();
            $marker->map_id = $mapId;
            $marker->model_id = $this->model->getKey();
            $marker->model_type =  $this->getClass();
        }

        $marker->title = isset($this->positionableAttributes[$mapId]['title']) ?
                $this->positionableAttributes[$mapId]['title'] :
                null;
        $marker->description = isset($this->positionableAttributes[$mapId]['description']) ?
                $this->positionableAttributes[$mapId]['description'] :
                null;
        $marker->external_url = isset($this->positionableAttributes[$mapId]['external_url']) ?
                $this->positionableAttributes[$mapId]['external_url'] :
                null;
        $marker->coord_x = isset($this->positionableAttributes[$mapId]['coord_x']) ?
                $this->positionableAttributes[$mapId]['coord_x'] :
                null;
        $marker->coord_y = isset($this->positionableAttributes[$mapId]['coord_y']) ?
                $this->positionableAttributes[$mapId]['coord_y'] :
                null;

        $marker->save();

        // Also update translatable attributes for translatable fields
        if (isset($this->positionableAttributes[$mapId]['translations'])) {
            $translations = $this->positionableAttributes[$mapId]['translations'];
            if ($translations) {
                foreach ($translations['title'] as $translation) {
                    $marker->setAttributeTranslated('title', $translation['value'], $translation['locale']);
                }
                foreach ($translations['description'] as $translation) {
                    $marker->setAttributeTranslated('description', $translation['value'], $translation['locale']);
                }
                $marker->syncTranslatableAttributes();
                $marker->save();
            }
        }
        return $marker->id;
    }

    public function deletePositionableData($mapId) {
        $marker = Position::where('map_id', $mapId)
            ->where('model_id', $this->model->getKey())
            ->where('model_type', $this->model->getMorphClass())->first();
        $markerId = $marker->id;
        if ($marker) {
            DB::table('rainlab_translate_attributes')->where(['model_id' => $marker->getKey(), 'model_type' => $marker->getMorphClass()])->delete();
            $marker->delete();
        }
        return $markerId;
    }

    /**
     * Loads the position data from the join table.
     * @param string $locale
     * @return array
     */
    public function loadPositionableData($mapId = null)
    {
        if (!$mapId) {
            $mapId = $this->positionableContext;
        }

        if (!$this->model->exists) {
            return $this->positionableAttributes[$mapId] = [];
        }

        $obj = $this->model->positions->first(function ($value, $key) use ($mapId) {
            return $value->attributes['map_id'] === $mapId;
        });

        $result = $obj ? [
            'title' => $obj->title,
            'description' => $obj->description,
            'external_url' => $obj->external_url,
            'coord_x' => $obj->coord_x,
            'coord_y' => $obj->coord_y,
        ] : [];

        return $this->positionableAttributes[$mapId] = $result;
    }

    /**
     * Sets a position attribute value.
     * @param string $key Attribute
     * @param string $value Value related to position
     * @return string        Position value
     */
    public function setAttributePositionable($key, $value, $mapId = null)
    {
        if ($mapId == null) {
            $mapId = $this->positionableContext;
        }


        if (!array_key_exists($mapId, $this->positionableAttributes)) {
            $this->loadPositionableData($mapId);
        }

        return $this->setAttributeFromData($this->positionableAttributes[$mapId], $key, $value);
    }

    /**
     * Sets an attribute from a model/array with nesting support.
     * @param mixed $data
     * @param string $attribute
     * @return mixed
     */
    protected function setAttributeFromData(&$data, $attribute, $value)
    {
        $keyArray = HtmlHelper::nameToArray($attribute);

        array_set($data, implode('.', $keyArray), $value);

        return $value;
    }

    /**
     * Returns the class name of the model. Takes any
     * custom morphMap aliases into account.
     *
     * @return string
     */
    protected function getClass()
    {
        return $this->model->getMorphClass();
    }
}
