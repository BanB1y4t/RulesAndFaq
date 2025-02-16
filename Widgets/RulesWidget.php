<?php

namespace Flute\Modules\RulesAndFaq\Widgets;

use Flute\Core\Widgets\AbstractWidget;
use Flute\Modules\RulesAndFaq\database\Entities\RulesEntry;
use Flute\Modules\RulesAndFaq\Services\RulesAndFaqService;

class RulesWidget extends AbstractWidget
{
    public function __construct()
    {
        $this->setAssets([
            mm('RulesAndFaq', 'Resources/assets/styles/widget/rules.scss'),
            mm('RulesAndFaq', 'Resources/assets/js/widget/rules.js'),
        ]);
    }

    public function render(array $data = []): string
    {
        $rulesAndFaqService = app(RulesAndFaqService::class);
        return render(mm('RulesAndFaq', 'Resources/views/widget/rules'), [
            'rules' => $this->getRulesRAF(),
            'service' => $rulesAndFaqService,
            'rulesTitle' => $rulesAndFaqService->getSetting('rules_title') ?? 'Rules',
        ]);
    }

    public function getName(): string
    {
        return 'RulesAndFaq - Rules';
    }

    public function isLazyLoad(): bool
    {
        return false;
    }

    protected function getRulesRAF()
    {
        return rep(RulesEntry::class)->select()->load('blocks')->orderBy(['position' => 'asc']) ->fetchAll();
    }
}