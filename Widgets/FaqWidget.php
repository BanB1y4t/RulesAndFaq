<?php

namespace Flute\Modules\RulesAndFaq\Widgets;

use Flute\Core\Widgets\AbstractWidget;
use Flute\Modules\RulesAndFaq\database\Entities\FaqEntry;
use Flute\Modules\RulesAndFaq\Services\RulesAndFaqService;

class FaqWidget extends AbstractWidget
{
    public function __construct()
    {
        $this->setAssets([
            mm('RulesAndFaq', 'Resources/assets/styles/widget/faq.scss'),
            mm('RulesAndFaq', 'Resources/assets/js/widget/faq.js'),
        ]);
    }

    public function render(array $data = []): string
    {
        $rulesAndFaqService = app(RulesAndFaqService::class);
        return render(mm('RulesAndFaq', 'Resources/views/widget/faq'), [
            'question' => $this->getFaqRAF(),
            'service' => $rulesAndFaqService,
            'faqTitle' => $rulesAndFaqService->getSetting('faq_title') ?? 'FAQ',
        ]);
    }

    public function getName(): string
    {
        return 'RulesAndFaq - Faq';
    }

    public function isLazyLoad(): bool
    {
        return false;
    }

    protected function getFaqRAF()
    {
        return rep(FaqEntry::class)->select()->load('blocks')->orderBy(['position' => 'asc']) ->fetchAll();
    }
}