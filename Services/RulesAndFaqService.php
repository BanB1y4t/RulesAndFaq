<?php

namespace Flute\Modules\RulesAndFaq\Services;

use Flute\Modules\RulesAndFaq\database\Entities\FaqEntry;
use Flute\Modules\RulesAndFaq\database\Entities\FaqEntryBlock;
use Flute\Modules\RulesAndFaq\database\Entities\RafSettings;
use Flute\Modules\RulesAndFaq\database\Entities\RulesEntry;
use Flute\Modules\RulesAndFaq\database\Entities\RulesEntryBlock;
use Flute\Core\Page\PageEditorParser;
use Nette\Utils\Json;
use Nette\Utils\JsonException;

class RulesAndFaqService
{
    public function find($rulesId, $faqId)
    {
        if ($rulesId){
            $rulesEntry = rep(RulesEntry::class)
                ->select()
                ->load('blocks')
                ->where(['rules_id' => $rulesId])
                ->fetchOne();

            if (!$rulesEntry) {
                throw new \Exception(__('rulesandfaq.not_found_rules'));
            }

            return $rulesEntry;

        } elseif ($faqId) {
            $faqEntry = rep(FaqEntry::class)
                ->select()
                ->load('blocks')
                ->where(['faq_id' => $faqId])
                ->fetchOne();

            if (!$faqEntry) {
                throw new \Exception(__('rulesandfaq.not_found_faq'));
            }

            return $faqEntry;
        }

        throw new \Exception(__('rulesandfaq.no_identifiers_provided'));
    }

    public function parseBlocks(?RulesEntry $rulesEntry, ?FaqEntry $faqEntry)
    {
        if ($rulesEntry){
            try {
                /** @var PageEditorParser $parser */
                $parser = app(PageEditorParser::class);

                $blocks = Json::decode($rulesEntry->blocks->json ?? '[]', Json::FORCE_ARRAY);

                return $parser->parse($blocks);
            } catch (JsonException $e) {
                return null;
            }
        } elseif ($faqEntry) {
            try {
                /** @var PageEditorParser $parser */
                $parser = app(PageEditorParser::class);

                $blocks = Json::decode($faqEntry->blocks->json ?? '[]', Json::FORCE_ARRAY);

                return $parser->parse($blocks);
            } catch (JsonException $e) {
                return null;
            }
        }

        throw new \Exception(__('rulesandfaq.no_parse_all'));
    }

    public function getSetting(string $key): ?string
    {
        // Получаем настройку по ключу
        $setting = rep(RafSettings::class)->findOne(['key' => $key]);
        return $setting ? $setting->value : null;
    }

    public function saveSetting(string $key, string $value): void
    {
        // Ищем настройку по ключу
        $setting = rep(RafSettings::class)->findOne(['key' => $key]);

        if (!$setting) {
            // Если настройка не найдена, создаем новую
            $setting = new RafSettings();
            $setting->key = $key;
        }

        // Устанавливаем значение
        $setting->value = $value;

        // Сохраняем настройку в базу данных
        transaction($setting)->run();
    }
}