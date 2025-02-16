<?php

namespace Flute\Modules\RulesAndFaq\Http\Controllers\API;

use Flute\Core\Admin\Http\Middlewares\HasPermissionMiddleware;
use Flute\Core\Http\Middlewares\CSRFMiddleware;
use Flute\Core\Support\AbstractController;
use Flute\Core\Support\FluteRequest;
use Flute\Modules\RulesAndFaq\database\Entities\FaqEntry;
use Flute\Modules\RulesAndFaq\database\Entities\FaqEntryBlock;
use Flute\Modules\RulesAndFaq\Services\RulesAndFaqService;
use Flute\Modules\RulesAndFaq\database\Entities\RulesEntry;
use Flute\Modules\RulesAndFaq\database\Entities\RulesEntryBlock;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class ApiRulesAndFaqController extends AbstractController
{
    protected $rulesAndFaqService;

    public function deleteItem(FluteRequest $fluteRequest)
    {
        $itemId = $fluteRequest->input('itemId');
        $type = $fluteRequest->input('type');

        if (!$itemId || !$type || !in_array($type, ['rules', 'faq'])) {
            return $this->error('Invalid request parameters', 400);
        }

        // Выбираем репозиторий в зависимости от типа
        $entityClass = ($type === 'rules') ? RulesEntry::class : FaqEntry::class;

        // Получаем элемент из базы данных
        $item = rep($entityClass)->select()->where([$type . '_id' => $itemId])->fetchOne();

        if (!$item) {
            return $this->error('Item not found', 404);
        }

        try {
            // Удаляем элемент
            transaction($item, 'delete')->run();

            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function getItemRules(string $rulesId)
    {
        $list = rep(RulesEntry::class)->findByPK((int) $rulesId);

        if (!$list)
            return $this->error(__('rules.admin.empty'), 404);

        return $list;

    }

    public function addItem(FluteRequest $fluteRequest)
    {
        $type = $fluteRequest->input('type');
        $data = $fluteRequest->input();

        if (!$type || !in_array($type, ['rules', 'faq'])) {
            return $this->error('Invalid type', 400);
        }

        // Определяем класс сущности и блока на основе типа
        $entityClass = ($type === 'rules') ? RulesEntry::class : FaqEntry::class;
        $blockClass = ($type === 'rules') ? RulesEntryBlock::class : FaqEntryBlock::class;

        try {
            $item = new $entityClass();

            // Заполняем поля сущности
            if ($type === 'rules') {
                $item->rule = $data['rule'] ?? null;
            } elseif ($type === 'faq') {
                $item->question = $data['question'] ?? null;
            }

            // Добавляем блоки, если они переданы
            if (!empty($data['blocks'])) {
                $block = new $blockClass();
                $block->json = $data['blocks'];
                $block->item = $item;

                $item->blocks = $block;
            }

            // Сохраняем транзакцию
            transaction($item)->run();

            // Редирект на список
            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }


    public function saveOrder(FluteRequest $fluteRequest)
    {
        $order = $fluteRequest->input("order");
        $type = $fluteRequest->input("type");

        if (!$order) {
            return $this->error('Order is empty', 404);
        }

        if (!$type || !in_array($type, ["rules", "faq"])) {
            return $this->error('Invalid type', 400);
        }

        foreach ($order as $value) {
            // Выбираем репозиторий и поле ID на основе типа
            $repository = ($type === "rules") ? RulesEntry::class : FaqEntry::class;
            $idField = ($type === "rules") ? 'rulesId' : 'faqId';

            // Получаем элемент из базы данных по соответствующему ID
            $item = rep($repository)->select()->where([$idField => (int) $value[$idField]])->fetchOne();

            if ($item) {
                // Обновляем позицию
                $item->position = $value['position'];

                transaction($item)->run();
            } else {
                return $this->error('Item not found with ID ' . $value[$idField], 404);
            }
        }

        return $this->success();
    }

    public function updateItems(FluteRequest $fluteRequest)
    {
        $type = $fluteRequest->attributes->get('type');
        $data = $fluteRequest->input();

        if (!$type || !in_array($type, ['rules', 'faq'])) {
            return $this->error('Invalid type', 400);
        }

        // Определяем класс сущности и блока на основе типа
        $entityClass = ($type === 'rules') ? RulesEntry::class : FaqEntry::class;
        $blockClass = ($type === 'rules') ? RulesEntryBlock::class : FaqEntryBlock::class;
        $idField = ($type === 'rules') ? 'rules_id' : 'faq_id';

        try {
            // Проверяем наличие ID для существующей записи
            $itemId = $data['id'] ?? null;

            if (!$itemId) {
                return $this->error('Item ID is required', 400);
            }

            $item = rep($entityClass)->select()->where([$idField => $itemId])->fetchOne();

            if (!$item) {
                return $this->error('Item not found', 404);
            }

            // Обновляем поля сущности
            if ($type === 'rules') {
                $item->rule = $data['name'] ?? $item->rule;
            } elseif ($type === 'faq') {
                $item->question = $data['name'] ?? $item->question;
            }

            // Обновляем блоки, если они переданы
            if (!empty($data['blocks'])) {
                // Проверяем, существует ли уже блок
                $block = rep($blockClass)->select()->where(['id' => $itemId])->fetchOne();

                if (!$block) {
                    $block = new $blockClass();
                    $block->item = $item;
                }

                $block->json = $data['blocks'];
                $item->blocks = $block;
            }

            // Сохраняем изменения
            transaction($item)->run();

            return $this->success();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function saveSettings(FluteRequest $fluteRequest, RulesAndFaqService $rulesAndFaqService): Response
    {
        try {
            // Получаем данные из запроса
            $rulesTitle = $fluteRequest->input('rules_title', 'Rules');
            $faqTitle = $fluteRequest->input('faq_title', 'FAQ');

            // Сохраняем настройки через сервис
            $rulesAndFaqService->saveSetting('rules_title', $rulesTitle);
            $rulesAndFaqService->saveSetting('faq_title', $faqTitle);

            // Возвращаем успешный ответ
            return $this->success();
        } catch (\Exception $e) {
            // В случае ошибки, возвращаем ошибку с сообщением
            return $this->error($e->getMessage());
        }
    }
}