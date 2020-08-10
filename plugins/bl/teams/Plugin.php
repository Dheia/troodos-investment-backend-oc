<?php

namespace BL\Teams;

use Backend\Facades\BackendAuth;
use RainLab\Translate\Models\Attribute;
use System\Classes\PluginBase;
use Backend\Models\User;
use System\Models\File;
use RainLab\Translate\Models\Locale;
use BL\Teams\Scopes\TeamScope;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
        return [
            'BL\Teams\Components\TeamMemberLogin' => 'teammemberlogin',
            'BL\Teams\Components\TeamRegister' => 'teamregister',
        ];
    }

    public function registerSettings()
    {
    }

    public function registerFormWidgets()
    {
        return [
            'BL\Teams\FormWidgets\UserProvider' => [
                'label' => 'User Provider',
                'code' => 'userprovider'
            ],
            'BL\Teams\FormWidgets\TeamProvider' => [
                'label' => 'Team Provider',
                'code' => 'teamprovider'
            ]
        ];
    }

    public function boot()
    {
        User::extend(function ($model) {
            $model->hasOne['team_owner'] = ['bl\Teams\Models\Team'];
        });
        User::extend(function ($model) {
            $model->belongsTo['active_team'] = [
                'bl\Teams\Models\Team',
                'key' => 'team_id'
            ];
        });
        User::extend(function ($model) {
            $model->belongsToMany['member_of_teams'] = [
                'bl\Teams\Models\Team',
                'table' => 'bl_teams_team_user'
            ];
        });
        File::extend(function ($model) {
            $model->belongsTo['team'] = [
                'bl\Teams\Models\Team',
                'key' => 'team_id'
            ];
        });
        File::extend(function ($model) {
            $model->bindEvent('model.beforeSave', function () use ($model) {
                if (BackendAuth::check()) {
                    $model->team_id = BackendAuth::getUser()->team_id;
                }
            });
        });
        Attribute::extend(function ($model) {
            $model->belongsTo['team'] = [
                'bl\Teams\Models\Team',
                'key' => 'team_id'
            ];
        });
        Locale::extend(function ($model) {
            $model->addGlobalScope(new TeamScope);
        });
    }
}
