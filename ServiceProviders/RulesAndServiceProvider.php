<?php

namespace Flute\Modules\RulesAndFaq\ServiceProviders;

use Flute\Core\Support\ModuleServiceProvider;
use Flute\Modules\RulesAndFaq\ServiceProviders\Extensions\RoutesExtension;
use Flute\Modules\RulesAndFaq\ServiceProviders\Extensions\AdminExtension;
use Flute\Modules\RulesAndFaq\Widgets\FaqWidget;
use Flute\Modules\RulesAndFaq\Widgets\RulesWidget;

class RulesAndServiceProvider extends ModuleServiceProvider
{
    public array $extensions = [
        AdminExtension::class,
        RoutesExtension::class
    ];

    public function boot(\DI\Container $container): void
    {
        $this->loadEntities();
        $this->loadTranslations();

        widgets()->register(new RulesWidget);
        widgets()->register(new FaqWidget);
    }

    public function register(\DI\Container $container): void
    {
    }
}