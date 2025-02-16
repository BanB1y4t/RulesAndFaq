<?php

namespace Flute\Modules\RulesAndFaq;

use Flute\Core\Database\Entities\NavbarItem;
use Flute\Core\Database\Entities\Permission;
use Flute\Core\Modules\ModuleInformation;
use Flute\Modules\RulesAndFaq\Widgets\FaqWidget;
use Flute\Modules\RulesAndFaq\Widgets\RulesWidget;

class Installer extends \Flute\Core\Support\AbstractModuleInstaller
{
    public function getNavItem(): ?NavbarItem
    {
        $navItem = new NavbarItem;
        $navItem->icon = 'ph ph-notebook';
        $navItem->title = 'Rules And Faq';
        $navItem->url = '/rulesandfaq/';

        return $navItem;
    }

    public function install(\Flute\Core\Modules\ModuleInformation &$module): bool
    {
        $permission = rep(Permission::class)->findOne([
            'name' => 'admin.rulesandfaq'
        ]);

        if (!$permission) {
            $permission = new Permission;
            $permission->name = 'admin.rulesandfaq';
            $permission->desc = 'rulesandfaq.description';

            transaction($permission)->run();
        }

        return true;
    }

    public function uninstall(\Flute\Core\Modules\ModuleInformation &$module): bool
    {
        $permission = rep(Permission::class)->findOne([
           'name' => 'admin.rulesandfaq'
        ]);

        if ($permission) {
            transaction($permission, 'delete')->run();
        }

        widgets()->unregister(RulesWidget::class);
        widgets()->unregister(FaqWidget::class);

        return true;
    }
}