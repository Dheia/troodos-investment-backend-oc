<?php

namespace BL\Teams\Models;

use October\Rain\Exception\ApplicationException;
use Model;
use Backend\Facades\BackendAuth;
use Illuminate\Support\Facades\DB;
use BL\Teams\Scopes\MemberScope;
use October\Rain\Database\Traits\Purgeable;
use Illuminate\Support\Facades\Lang;
use BL\Teams\Models\Locale;
use BL\Teams\Models\TeamMember;

/**
 * Model
 */
class Team extends Model
{
    use \October\Rain\Database\Traits\Sortable;
    use \October\Rain\Database\Traits\Validation;
    use Purgeable;

    protected static function boot()
    {
        parent::boot();
        //Ensures whenever team are retreived, only teams that the current user is a member of are retreived
        static::addGlobalScope(new MemberScope);
    }

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_teams_teams';

    protected $fillable = ['name', 'address', 'email', 'user_id'];

    protected $purgeable = ['active_team'];

    public $belongsTo = [
        'owner' => 'Backend\Models\User'
    ];

    public $hasMany = [
        'users_where_active' => [
            'Backend\Models\User'
        ],
        'locales' => [
            'BL\Teams\Models\Locale'
        ]
    ];

    public $belongsToMany = [
        'members' => [
            'Backend\Models\User',
            'table' => 'bl_teams_team_user'
        ]
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|max:64',
        'email' => 'email',
        'address' => 'between:2,256|',
        'user_id' => 'required',
    ];

    public function afterCreate()
    {
        $this->members()->attach(BackendAuth::getUser(), ['role' => 'owner']);
        $locale = new Locale;
        $locale->code = config('locales.default_locale')['code'];
        $locale->name = config('locales.default_locale')['name'];
        $locale->is_default = config('locales.default_locale')['is_default'];
        $locale->is_enabled = config('locales.default_locale')['is_enabled'];
        $locale->sort_order = config('locales.default_locale')['sort_order'];
        $locale->team_id = $this->id;
        $locale->save();
    }

    public function afterSave()
    {
        if ($this->getOriginalPurgeValue('active_team') == '1') {
            $user = BackendAuth::getUser();
            $user->team_id = $this->id;
            $user->save();
        }
    }

    public function beforeDelete()
    {
        $user = BackendAuth::getUser();
        $team_member = DB::table('bl_teams_team_user')
            ->where(['user_id' => $user->id, 'team_id' => $this->id])
            ->first();
        if ($team_member) {
            if ($team_member->role != 'owner') {
                throw new ApplicationException(Lang::get('bl.teams::lang.plugin.cannotDeleteTeamOwner'));
                return false;
                exit;
            } else {
                if ($team_member = DB::table('bl_teams_team_user')
                    ->where(['user_id' => $user->id])
                    ->count() < 2
                ) {
                    throw new ApplicationException(Lang::get('bl.teams::lang.plugin.cannotDeleteTeamBelong'));
                    return false;
                } else {
                    return true;
                }
            }
        } else {
            throw new ApplicationException(Lang::get('bl.teams::lang.plugin.cannotDeleteTeam'));
            return false;
        }
    }

    public function afterDelete()
    {
        //If team that was delete was user's active team, another team must be set as the active team
        $user = BackendAuth::getUser();
        if ($user->team_id == $this->id) {
            $new_active_team = db::table('bl_teams_team_user')->where('user_id', $user->id)->first();
            $user->team_id = $new_active_team->team_id;
            $user->save();
            return true;
        }
    }
}
