<?php

return [


    'title' => 'FUNDACION 3',
    'title_prefix' => '',
    'title_postfix' => '',



    'use_ico_only' => false,
    'use_full_favicon' => false,


    'google_fonts' => [
        'allowed' => true,
    ],



    'logo' => '',
    'logo_img' => 'img/logo2.png',
    'logo_img_class' => '',

    'preloader' => [
        'enabled' => false,
        'mode' => 'fullscreen',
        'img' => [
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],



    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,



    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,



    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',



    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-light-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',



    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,



    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'light',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',



    'use_route_url' => false,
    'dashboard_url' => 'dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,



    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',



    'menu' => [
        // Navbar items:
        [
            'type' => 'navbar-search',
            'text' => 'search',
            'topnav_right' => true,
        ],
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],

        // Sidebar items:
        [
            'type' => 'sidebar-menu-search',
            'text' => 'search',
        ],
        [
            'text' => 'blog',
            'url' => 'admin/blog',
            'can' => 'manage-blog',
        ],

       // === Módulo: Módulo de usuarios y perfiles (color: azul) ===
[
    'text' => 'Usuarios',
    'url'  => 'users',
    'icon' => 'fas fa-users',
    'can' => 'users.verlista',
    'label' => 'USU',
    'label_color' => 'primary',
],
[
    'text' => 'Voluntarios',
    'url'  => 'volunteers',
    'icon' => 'fas fa-user-friends',
    'can' => 'volunteers.verlista',
    'label' => 'USU',
    'label_color' => 'primary',
],
[   
    'text' => 'Donantes',
    'url'  => 'donantes',
    'icon' => 'fas fa-user-plus',
    'can' => 'donantes.ver',
    'label' => 'USU',
    'label_color' => 'primary',
],
[
    'text' => 'Verificaciones de Voluntarios',
    'url'  => 'volunteer-verifications',
    'icon' => 'fas fa-check-circle',
    'can' => 'volunteer-verifications.verlista',
    'label' => 'USU',
    'label_color' => 'primary',
],

// === Módulo: Roles y permisos (color: naranja) ===
[
    'text' => 'Roles de sistema',
    'url'  => 'roles',
    'icon' => 'fas fa-user-shield',
    'can' => 'roles.verlista',
    'label' => 'ROL',
    'label_color' => 'warning',
],

// === Módulo: Campañas y eventos (color: verde) ===
[
    'text' => 'Campañas',
    'url'  => 'campaigns',
    'icon' => 'fas fa-bullhorn',
    'can' => 'campaigns.verlista',
    'label' => 'CMP',
    'label_color' => 'success',
],
[
    'text' => 'Eventos',
    'url'  => 'events',
    'icon' => 'fas fa-calendar-alt',
    'can' => 'events.verlista',
    'label' => 'CMP',
    'label_color' => 'success',
],
[
    'text' => 'Ubicaciones de Eventos',
    'url'  => 'event-locations',
    'icon' => 'fas fa-map-marker-alt',
    'can' => 'event-locations.verlista',
    'label' => 'CMP',
    'label_color' => 'success',
],
[
    'text' => 'Participantes de Eventos',
    'url'  => 'event-participants',
    'icon' => 'fas fa-users',
    'can' => 'event-participants.verlista',
    'label' => 'CMP',
    'label_color' => 'success',
],
[
    'text' => 'Asignaciones de Personal',
    'url'  => 'staff-assignments',
    'icon' => 'fas fa-user-cog',
    'can' => 'staff-assignments.verlista',
    'label' => 'CMP',
    'label_color' => 'success',
],
[
    'text' => 'Finanzas de Campañas',
    'url'  => 'campaign-finances',
    'icon' => 'fas fa-wallet',
    'can' => 'campaign-finances.verlista',
    'label' => 'CMP',
    'label_color' => 'success',
],

// === Módulo: Donaciones (color: rojo) ===
[
    'text' => 'Donaciones',
    'url'  => 'donations',
    'icon' => 'fas fa-box',
    'can' => 'donations.verlista',
    'label' => 'DON',
    'label_color' => 'danger',
],
[
    'text' => 'Donaciones Entrantes',
    'url'  => 'donations-incoming',
    'icon' => 'fas fa-box-open',
    'can' => 'donations-incoming.verlista',
    'label' => 'DON',
    'label_color' => 'danger',
],
[
    'text' => 'Artículos de Donación',
    'url'  => 'donation-items',
    'icon' => 'fas fa-gift',
    'can' => 'donation-items.verlista',
    'label' => 'DON',
    'label_color' => 'danger',
],
[
    'text' => 'Tipos de Donación',
    'url'  => 'donation-types',
    'icon' => 'fas fa-cogs',
    'can' => 'donation-types.verlista',
    'label' => 'DON',
    'label_color' => 'danger',
],
[
    'text' => 'Donaciones en Efectivo',
    'url'  => 'donations-cashes', 
    'icon' => 'fas fa-money-bill-wave',
    'can' => 'donations-cash.verlista',
    'label' => 'DON',
    'label_color' => 'danger',
],
[
    'text' => 'Donantes',
    'url'  => 'donantes',
    'icon' => 'fas fa-user-plus',
    'can' => 'donantes.verlista',
    'label' => 'DON',
    'label_color' => 'danger',
],
[
    'text' => 'Donantes Externos',
    'url'  => 'external-donors',
    'icon' => 'fas fa-handshake',
    'can' => 'external-donors.verlista',
    'label' => 'DON',
    'label_color' => 'danger',
],
[
    'text' => 'Solicitudes de Donación',
    'url'  => 'donation-requests',
    'icon' => 'fas fa-hand-holding-heart',
    'can' => 'donation-requests.verlista',
    'label' => 'DON',
    'label_color' => 'danger',
],
[
    'text' => 'Descripciones de Solicitudes',
    'url'  => 'donation-request-descriptions',
    'icon' => 'fas fa-list-alt',
    'can' => 'donation-request-descriptions.verlista',
    'label' => 'DON',
    'label_color' => 'danger',
],

// === Módulo: Tareas (color: cian) ===
[
    'text' => 'Tareas',
    'url'  => 'tasks',
    'icon' => 'fas fa-tasks',
    'can'  => 'tasks.verlista',
    'label' => 'TAR',
    'label_color' => 'info',
],
[
    'text' => 'Asignaciones de Tareas',
    'url'  => 'task-assignments',
    'icon' => 'fas fa-user-tag',
    'can' => 'task-assignments.verlista',
    'label' => 'TAR',
    'label_color' => 'info',
],

// === Módulo: Evaluación (color: indigo) ===
[
    'text' => 'Criterios de Evaluación',
    'url'  => 'evaluation-criteria',
    'icon' => 'fas fa-chart-line',
    'can' => 'evaluation-criteria.verlista',
    'label' => 'EVA',
    'label_color' => 'indigo',
],
[
    'text' => 'Indicadores de Impacto',
    'url'  => 'impact-indicators',
    'icon' => 'fas fa-lightbulb',
    'can' => 'impact-indicators.verlista',
    'label' => 'EVA',
    'label_color' => 'indigo',
],
[
    'text' => 'Acciones Recomendadas',
    'url'  => 'agent-actions',
    'icon' => 'fas fa-robot',
    'can' => 'agent-actions.verlista',
    'label' => 'EVA',
    'label_color' => 'indigo',
],

// === Módulo: Finanzas (color: gris oscuro) ===
[
    'text' => 'Cuentas Financieras',
    'url'  => 'financial-accounts',
    'icon' => 'fas fa-university',
    'can' => 'financial-accounts.verlista',
    'label' => 'FIN',
    'label_color' => 'dark',
],
[
    'text' => 'Transacciones',
    'url'  => 'transactions',
    'icon' => 'fas fa-exchange-alt',
    'can' => 'transactions.verlista',
    'label' => 'FIN',
    'label_color' => 'dark',
],


        ['header' => 'account_settings'],
        [
            'text' => 'profile',
            'url' => 'admin/settings',
            'icon' => 'fas fa-fw fa-user',
        ],
        [
            'text' => 'change_password',
            'url' => 'admin/settings',
            'icon' => 'fas fa-fw fa-lock',
        ],
        [
            'text' => 'multilevel',
            'icon' => 'fas fa-fw fa-share',
            'submenu' => [
                [
                    'text' => 'level_one',
                    'url' => '#',
                ],
                [
                    'text' => 'level_one',
                    'url' => '#',
                    'submenu' => [
                        [
                            'text' => 'level_two',
                            'url' => '#',
                        ],
                        [
                            'text' => 'level_two',
                            'url' => '#',
                            'submenu' => [
                                [
                                    'text' => 'level_three',
                                    'url' => '#',
                                ],
                                [
                                    'text' => 'level_three',
                                    'url' => '#',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'text' => 'level_one',
                    'url' => '#',
                ],
            ],
        ],
],


    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],


    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],


    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],


    'livewire' => false,
];
