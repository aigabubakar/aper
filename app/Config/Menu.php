<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Menu extends BaseConfig
{
    /**
     * Sidebar menu configuration.
     * Each menu item can define:
     *  - label
     *  - url
     *  - roles   → array of allowed roles (optional)
     *  - cats    → array of allowed categories (optional)
     *  - children → nested menu items
     */
    public array $items = [
        'profile' => [
            'label' => 'Profile',
            'url'   => 'profile/overview',
            'roles' => ['admin', 'staff', 'hod', 'dean'],
            'children' => [
                'personal' => [
                    'label' => 'Personal',
                    'url'   => 'profile/personal',
                    'roles' => ['admin', 'staff', 'hod', 'dean'],
                ],
                'employment' => [
                    'label' => 'Employment',
                    'url'   => 'profile/employment',
                    'roles' => ['admin', 'staff'],
                    'cats'  => ['academic', 'non_academic', 'junior_non_academic', 'senior_non_academic'],
                ],
                'professional' => [
                    'label' => 'Professional',
                    'url'   => 'profile/professional',
                    'roles' => ['admin', 'staff'],
                    'cats'  => ['academic', 'non_academic'],
                ],
                'print' => [
                    'label' => 'Print Summary',
                    'url'   => 'profile/print-summary',
                    'roles' => ['admin', 'hod', 'dean'],
                ],
            ],
        ],

        // Example of another menu group (optional)
        'appraisals' => [
            'label' => 'Appraisals',
            'url'   => 'appraisals',
            'roles' => ['staff', 'hod', 'dean'],
            'children' => [
                'view' => [
                    'label' => 'View Appraisals',
                    'url'   => 'appraisals/view',
                    'roles' => ['staff', 'hod', 'dean'],
                ],
                'evaluate' => [
                    'label' => 'Evaluate Staff',
                    'url'   => 'appraisals/evaluate',
                    'roles' => ['hod', 'dean'],
                ],
            ],
        ],
    ];
}
