<?php return [
    'plugin' => [
        'name' => 'Teams',
        'description' => 'Plugin by Blupath for own projects, to create backend teams that have limited access to the backend',
        'cannotDeleteTeam' => 'You don\'t have the right to delete this team.',
        'cannotDeleteTeamBelong' => 'You must be a member of at least one team.',
        'cannotDeleteTeamOwner' => 'You can only delete teams that you are the owner of.',
        'cannotDeleteTeamMemberOwner' => 'You cannot remove a team\'s owner.',
        'cannotDeleteTeamMemberUnlessOwner' => 'You must be the owner of a team to be able to remove other users.',
        'cannotDeleteTeamGeneral' => 'Something went wrong when trying to remove this team member.',
        'languageInUseError' => 'You are already using this language',
        'languageError' => 'Something went wrong, you cannot add this language',
        'userNotFound' => 'No user was found matching these credentials.'
    ],
    'teams' => [
        'names' => 'Teams',
        'name' => 'Team',
        'label' => 'Team name',
        'active' => 'Is currently active team',
        'owner' => 'Team owner: You',
        'role' => 'Your role',
        'email' => 'Main contact email',
        'address' => 'Adress of organization',
        'active_team' => 'Make this the currently active team',
        'custom_domain_only' => 'Prevent access to your guide from the default infopoint domain and only allow access from the custom domain you have defined. This will only work after we have reviewed your custom domain request.',
        'request_domain' => 'Domain you would like to make your team\'s content such as your guides and online portal accessible from.',
        'request_domain_comment' => 'In order for your domain to work you will need to do the following: a) inform your web administrator that they need to add A records to point from your selected domain to xxx.xxx.xxx.xxx. b) contact us at c.symeou@blupath.co.uk and inform us of the domain you want to enable. We will review your request and inform you when the changes have taken effect.'
    ],
    'locales' => [
        'names' => 'Languages',
        'name' => 'Language',
        'label' => 'Language',
        'new' => 'New suppprted language',
        'update' => 'Update supported language',
        'field' => 'Select a supported language',
        'comment' => 'The languages you select will allow you to define ',
        'code' => 'Code',
        'default' => 'Is default language',
        'default_label' => 'Make this the default language',
        'cannotDeleteMinimumOne' => 'You must have at least one active language',
        'cannotDeleteGeneral' => 'Something appears to have gone wrong',
    ],
    'teammembers' => [
        'names' => 'Team members',
        'name' => 'Team member',
        'user' => 'User',
        'role' => 'Role',
        'email' => 'Email: If user with this email already exists, they will be added to the team',
        'firstname' => 'First name',
        'lastname' => 'Last name',
        'password1' => 'Password'
    ],
    'options' => [
        'Y' => 'Yes',
        'N' => 'No'
    ],
    'tabs' => [
        'newUser' => 'New user details',
        'basic' => 'Basic details',
        'domain' => 'Team domain details'
    ]
];
