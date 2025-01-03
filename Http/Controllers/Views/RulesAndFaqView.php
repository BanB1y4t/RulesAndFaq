<?php

namespace Flute\Modules\RulesAndFaq\Http\Controllers\Views;

use Flute\Core\Support\AbstractController;
use Flute\Core\Support\FluteRequest;
use Flute\Modules\RulesAndFaq\Services\RulesAndFaqService;
use Flute\Modules\RulesAndFaq\database\Entities\FaqEntry;
use Flute\Modules\RulesAndFaq\database\Entities\RulesEntry;

class RulesAndFaqView extends AbstractController
{
    protected RulesAndFaqService $rulesAndFaqService;

    public function __construct(RulesAndFaqService $rulesAndFaqService)
    {
        $this->rulesAndFaqService = $rulesAndFaqService;
    }

    public function index(FluteRequest $fluteRequest, RulesAndFaqService $rulesAndFaqService)
    {
        $rule = rep(RulesEntry::class)->select()->orderBy(['position' => 'asc'])->fetchAll();
        $question = rep(FaqEntry::class)->select()->orderBy(['position' => 'asc'])->fetchAll();

        return view(mm('RulesAndFaq', 'Resources/views/browse'), [
            'rule' => $rule,
            'question' => $question,
        ]);
    }

    public function loadBlock(FluteRequest $fluteRequest, RulesAndFaqService $rulesAndFaqService)
    {
        $faqId = $fluteRequest->request->get('faq_id');
        $rulesId = $fluteRequest->request->get('rules_id');

        $block = null;
        $title = null;

        if ($faqId) {
            $faqEntry = $rulesAndFaqService->find(null, $faqId);
            $block = $rulesAndFaqService->parseBlocks(null, $faqEntry);
            $title = $faqEntry->question;
        } elseif ($rulesId) {
            $rulesEntry = $rulesAndFaqService->find($rulesId, null);
            $block = $rulesAndFaqService->parseBlocks($rulesEntry, null);
            $title = $rulesEntry->rule;
        }

        return response()->json([
            'success' => true,
            'block' => $block,
            'title' => $title,
        ]);
    }

    public function listItems(FluteRequest $fluteRequest)
    {
        $type = $fluteRequest->attributes->get('type');

        if (!$type || !in_array($type, ['rules', 'faq'])) {
            return $this->error('Invalid type ' . print_r($type), 404);
        }

        // Загружаем данные в зависимости от типа
        if ($type == 'rules') {
            $rules = rep(RulesEntry::class)->select()->orderBy(['position' => 'asc'])->fetchAll();
        } else {
            $faq = rep(FaqEntry::class)->select()->orderBy(['position' => 'asc'])->fetchAll();
        }

        // Отправляем данные в представление
        return view(mm('RulesAndFaq', 'Resources/views/admin/list'), [
            'faq' => $faq,
            'rules' =>$rules,
            'type' => $type,
        ]);
    }


    public function addItem(FluteRequest $fluteRequest)
    {
        $type = $fluteRequest->attributes->get('type');

        if (!$type || !in_array($type, ['rules', 'faq'])) {
            return $this->error('Invalid type ' . print_r($type), 404);
        }

        return view(mm('RulesAndFaq', 'Resources/views/admin/add'), [
            'type' => $type,
        ]);
    }


    public function updateItems(FluteRequest $fluteRequest)
    {
        $type = $fluteRequest->attributes->get('type');
        if (!$type || !in_array($type, ['rules', 'faq'])) {
            return $this->error('Invalid type ' . print_r($type), 404);
        }

        $id = $fluteRequest->attributes->get('itemId');


        if ($type == 'rules') {
            $item = rep(RulesEntry::class)->select()->where(['rulesId' => (int) $id])->load('blocks')->fetchOne();
        } else {
            $item = rep(FaqEntry::class)->select()->where(['faqId' => (int) $id])->load('blocks')->fetchOne();
        }

        if (!$item) {
            return $this->error(__('rulesandfaq.not_found_item', ['type' => $type]), 404);
        }

        return view(mm('RulesAndFaq', 'Resources/views/admin/edit'), [
            'item' => $item,
            'type' => $type,
        ]);
    }

    public function settingsItems(FluteRequest $fluteRequest, RulesAndFaqService $rulesAndFaqService)
    {
        // Загружаем текущие значения настроек
        $rulesTitle = $rulesAndFaqService->getSetting('rules_title') ?? 'Rules';
        $faqTitle = $rulesAndFaqService->getSetting('faq_title') ?? 'FAQ';

        // Передаем значения в представление
        return view(mm('RulesAndFaq', 'Resources/views/admin/settings'), [
            'rulesTitle' => $rulesTitle,
            'faqTitle' => $faqTitle
        ]);
    }
}
