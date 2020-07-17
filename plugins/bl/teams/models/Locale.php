<?php

namespace BL\Teams\Models;

use October\Rain\Exception\ApplicationException;
use Model;
use Backend\Facades\BackendAuth;
use BL\Teams\Scopes\TeamScope;
use Illuminate\Support\Facades\Lang;
use RainLab\Translate\Models\Attribute;

/**
 * Model
 */
class Locale extends Model
{
    use \October\Rain\Database\Traits\Sortable;
    use \October\Rain\Database\Traits\Validation;

    protected static function boot()
    {
        parent::boot();
        //Ensures whenever team are retreived, only teams that the current user is a member of are retreived
        static::addGlobalScope(new TeamScope);
    }

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'rainlab_translate_locales';

    protected $fillable = ['code', 'name', 'is_default', 'is_enabled', 'sort_order', 'team_id'];


    public $belongsTo = [
        'team' => 'BL\Teams\Models\Team'
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'code' => 'required'
    ];

    public function available_locales()
    {
        return config('locales.available_locales');
    }

    public function beforeSave()
    {
        if (BackendAuth::check()) {
            $user = BackendAuth::getUser();
            if ($user->team_id) {
                if (
                    //Only if this locale is not already in use add it
                    self::where(['team_id' => $user->team_id, 'code' => $this->code])
                    ->count() < 1
                ) {
                    $this->name = config('locales.available_locales.' . $this->code);
                    $this->team_id = $user->team_id;
                    $this->is_enabled = 1;
                    //If this is default language, before save set other language's of team as non-default
                    if ($this->is_default == 1) {
                        self::where('team_id', $user->team_id)->update(['is_default' => 0]);
                    }
                } else {
                    throw new ApplicationException(Lang::get('bl.teams::lang.plugin.languageInUseError'));
                    return false;
                }
            } else {
                // UPD: disabled to be able to create a first team
//                throw new ApplicationException(Lang::get('bl.teams::lang.plugin.languageError'));
//                return false;
            }
        } else {
            throw new ApplicationException(Lang::get('bl.teams::lang.plugin.languageError'));
            return false;
        }
    }

    public function beforeDelete()
    {
        $user = BackendAuth::getUser();
        if ($user) {
            if (self::where('team_id', $user->team_id)->count() < 2) {
                throw new ApplicationException(Lang::get('bl.teams::lang.locales.cannotDeleteMinimumOne'));
                return false;
                exit;
            }
        } else {
            throw new ApplicationException(Lang::get('bl.teams::lang.locales.cannotDeleteGeneral'));
            return false;
            exit;
        }
    }

    public function afterDelete()
    {
        //If language that was delete was team's default language, another language must be set as the default language
        if ($this->is_default == 1) {
            $new_default_language = self::where('team_id', BackendAuth::getUser()->team_id)->first();
            $new_default_language->is_default = 1;
            $new_default_language->save();
        }
        Attribute::where(['locale' => $this->code, 'team_id' => BackendAuth::getUser()->team_id])->delete();
    }
}
