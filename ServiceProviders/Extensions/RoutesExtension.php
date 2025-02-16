<?php

namespace Flute\Modules\RulesAndFaq\ServiceProviders\Extensions;

use Flute\Core\Admin\Http\Middlewares\HasPermissionMiddleware;
use Flute\Core\Http\Middlewares\UserExistsMiddleware;
use Flute\Core\Router\RouteGroup;
use Flute\Modules\RulesAndFaq\Http\Controllers\API\ApiRulesAndFaqController;
use Flute\Modules\RulesAndFaq\Http\Controllers\Views\RulesAndFaqView;

class RoutesExtension implements \Flute\Core\Contracts\ModuleExtensionInterface
{
    public function register(): void
    {
        router()->group(function (RouteGroup $routeGroup) {
            $routeGroup->get('', [RulesAndFaqView::class, 'index']);
            $routeGroup->get('/', [RulesAndFaqView::class, 'index']);
            $routeGroup->post('/load', [RulesAndFaqView::class, 'loadBlock']);
        }, 'rulesandfaq');

        router()->group(function (RouteGroup $adminRouteGroup){
            $adminRouteGroup->middleware(HasPermissionMiddleware::class);
            $adminRouteGroup->group(function (RouteGroup $rulesAndFaq){
                $rulesAndFaq->group(function (RouteGroup $rulesFaq){
                    $rulesFaq->get('list', [RulesAndFaqView::class, 'listItems']);
                    $rulesFaq->get('add', [RulesAndFaqView::class, 'addItem']);
                    $rulesFaq->get('edit/{itemId}', [RulesAndFaqView::class, 'updateItems']);
                }, '{type}/');
                $rulesAndFaq->get('settings', [RulesAndFaqView::class, 'settingsItems']);
            }, 'rulesandfaq/');

            $adminRouteGroup->group(function (RouteGroup $rulesAndFaq){
                $rulesAndFaq->group(function (RouteGroup $rulesFaq){
                    $rulesFaq->post('add', [ApiRulesAndFaqController::class, 'addItem']);
                    $rulesFaq->put('save-order', [ApiRulesAndFaqController::class, 'saveOrder']);
                    $rulesFaq->put('{itemId}', [ApiRulesAndFaqController::class, 'updateItems']);
                    $rulesFaq->delete('{rules_id}', [ApiRulesAndFaqController::class, 'deleteItem']);
                }, '{type}/');
                $rulesAndFaq->post('settings', [ApiRulesAndFaqController::class, 'saveSettings']);
            }, 'api/rulesandfaq/');
        }, 'admin/');
    }
}