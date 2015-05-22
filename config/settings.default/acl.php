<?php

return [
	'ACL' => [
		'Roles' => [
			'root' => [
				'label' => __d('Users', 'Root'),
				'can' => [
                    //Frontend
					'Users.Profile',
					'Users.Profile.ChangeCredentials',
					'Analytics.DoNotTrack',
                    //Backend
					'Backend.Access',
					'Backend.Users.List',
					'Backend.Users.Add',
					'Backend.Users.Edit',
					'Backend.Users.ChangeCredentials',
					'Backend.Users.Delete',
				],
			],
			'admin' =>  [
				'label' => __d('Users', 'Admin'),
				'can' => [
                    //Frontend
					'Users.Profile',
					'Users.Profile.ChangeCredentials',
                    //Backend
					'Backend.Access',
					'Backend.Users.List',
					'Backend.Users.Add',
					'Backend.Users.Edit',
					'Backend.Users.ChangeCredentials',
					'Backend.Users.Delete',
				],
			],
			'identified' =>  [
				'label' => __d('Users', 'Identified User'),
				'can' => [
                    //Frontend
					'Users.Profile',
					'Users.Profile.ChangeCredentials',
                    //Backend
				],
			],
			'anonymous' =>  [
				'label' => __d('Users', 'Anonymous'),
				'can' => [
                    //Frontend
					'Users.Login',
					'Users.Register',
                    //Backend
				],
			],
			'banned' =>  [
				'label' => __d('Users', 'Banned'),
				'can' => [
                    //Frontend
                    //Backend
				],
			],
		],
        'Defaults' => [
            'anonymous' => 'anonymous',
            'register' => 'identified',
        ],
        'Auth' => [
            'everysession' => [
                //Frontend
                'Users.Profile.ChangeCredentials',
                //Backend
                'Backend.Users.Delete',
                'Backend.Users.ChangeCredentials',
            ],
            'everytime' => [
                //Frontend
                //Backend
            ],
        ],
	],
];
