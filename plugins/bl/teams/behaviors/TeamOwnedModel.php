<?php

namespace BL\Teams\Behaviors;

use RainLab\Translate\Models\Attribute;
use Backend\Facades\BackendAuth;
use October\Rain\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use October\Rain\Extension\ExtensionBase;
use BL\Teams\Models\Team;
use BL\Teams\Scopes\TeamScope;

/**
 * Base class for model behaviors.
 *
 * @package bl\guides
 * @author Christos Symeou
 */
class TeamOwnedModel extends ExtensionBase
{

    protected $parent;

    public function __construct($parent)
    {
        $parent::addGlobalScope(new TeamScope);
        $this->parent = $parent;
        $this->parent->belongsTo['team'] = ['BL\Teams\Models\Team'];
        $model = $this->parent;
        $this->parent->bindEvent('model.beforeSave', function () use ($model) {
            $model->team_id = BackendAuth::getUser()->team_id;
        });
        $this->parent->bindEvent('model.afterSave', function () use ($model) {
            $attribute = Attribute::where(['model_type' => get_class($model), 'model_id' => $model->id])->first();
            if ($attribute) {
                $attributes = $attribute->getAttributes();
                unset($attributes['updated_at']);
                $attribute->setRawAttributes($attributes, true);

                DB::table('rainlab_translate_attributes')->where(['model_type' => get_class($model), 'model_id' => $model->id])->update(['team_id' => BackendAuth::getUser()->team_id]);
            }
        });
        $this->parent->bindEvent('model.beforeDelete', function () use ($model) {
            DB::table('rainlab_translate_attributes')->where(['model_id' => $model->id, 'model_type' => get_class($model)])->delete();
        });
    }
}
