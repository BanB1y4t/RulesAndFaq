<?php

namespace Flute\Modules\RulesAndFaq\ServiceProviders\Extensions;

use Flute\Core\Admin\Builders\AdminSidebarBuilder;
use Flute\Modules\RulesAndFaq\Services\RulesAndFaqService;

class AdminExtension implements \Flute\Core\Contracts\ModuleExtensionInterface
{
    public function register(): void
    {
        AdminSidebarBuilder::add('additional', [
            'title' => 'rulesandfaq.admin.menu.title',
            'icon' => 'ph-notebook',
            'permission' => 'admin.rulesandfaq',
            'items' => [
                [
                    'title' => 'rulesandfaq.admin.menu.rules',
                    'url' => '/admin/rulesandfaq/rules/list'
                ],
                [
                    'title' => 'rulesandfaq.admin.menu.faq',
                    'url' => '/admin/rulesandfaq/faq/list'
                ],
                [
                    'title' => 'rulesandfaq.admin.menu.settings',
                    'url' => '/admin/rulesandfaq/settings'
                ],
            ]
        ]);
    }
}