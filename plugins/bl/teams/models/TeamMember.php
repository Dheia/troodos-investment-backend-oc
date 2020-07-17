<?php

namespace BL\Teams\Models;

use October\Rain\Exception\ApplicationException;
use Backend\Facades\BackendAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Model;
use October\Rain\Database\Traits\Purgeable;
use BL\Teams\Models\Team;
use BL\Teams\Scopes\TeamScope;
use Backend\Models\User;


/**
 * Model
 */
class TeamMember extends Model
{
    use \October\Rain\Database\Traits\Sortable;
    use \October\Rain\Database\Traits\Validation;
    use Purgeable;

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new TeamScope);
    }

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bl_teams_team_user';

    protected $purgeable = ['firstname', 'lastname', 'email', 'password1', 'password2'];
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    public $belongsTo = [
        'user' => 'Backend\Models\User',
        'team' => 'cs\Backendteams\Models\Team'
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'email' => 'required|email',
        'firstname' => 'required',
        'lastname' => 'required',
        'password1' => 'required|min:6',
    ];

    public function beforeSave()
    {
        //Only if managing team member from backend while logged in
        if (BackendAuth::check()) {
            $user = BackendAuth::getUser();
            $team = Team::where('id', $user->team_id)->first();

            $email = $this->getOriginalPurgeValue('email');
            $firstname = $this->getOriginalPurgeValue('firstname');
            $lastname = $this->getOriginalPurgeValue('lastname');
            $password = $this->getOriginalPurgeValue('password1');

            //If there is a user with this email
            $new_user = User::where('email', $email)->first();
            if ($new_user) {
                //Check if there already exists such a team member
                if (self::where('user_id', $new_user->id)->where('team_id', $team->id)->count() > 0) {
                    return false;
                } else {
                    $team->members()->attach($new_user, ['role' => 'Editor']);
                    return false;
                }
            } else {
                $new_user = BackendAuth::register([
                    'first_name' => $firstname,
                    'last_name' => $lastname,
                    'login' => $email,
                    'email' => $email,
                    'password' => $password,
                    'password_confirmation' => $password,
                    'is_activated' => 1
                ]);
                $new_user->team_id = $team->id;
                $new_user->role_id = 3;
                $new_user->save();
                $team->members()->attach($new_user, ['role' => 'Editor']);
                return false;
            }
        }
    }

    public function beforeDelete()
    {
        if (BackendAuth::check()) {
            if ($this->role == 'owner') {
                throw new ApplicationException(Lang::get('bl.teams::lang.plugin.cannotDeleteTeamMemberOwner'));
                return false;
            } else {
                $user = BackendAuth::getUser();
                $team_member = DB::table('bl_teams_team_user')
                    ->where(['user_id' => $user->id, 'team_id' => $this->team_id])
                    ->first();
                if ($team_member) {
                    if ($team_member->role != 'owner') {
                        throw new ApplicationException(Lang::get('bl.teams::lang.plugin.cannotDeleteTeamMemberUnlessOwner'));
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    throw new ApplicationException(Lang::get('bl.teams::lang.plugin.cannotDeleteTeamGeneral'));
                    return false;
                }
            }
        }
    }
}
