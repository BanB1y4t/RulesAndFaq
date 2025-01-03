<?php

namespace Flute\Modules\RulesAndFaq\database\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasOne;

/**
 * @Entity()
 */
class FaqEntry
{
    /** @Column(type="primary", name="faq_id") */
    public $faqId;

    /** @Column(type="string") */
    public string $question;

    /** @HasOne(target="FaqEntryBlock") */
    public $blocks;

    /** @Column(type="integer", default=1) */
    public $position = 1;

    /**
     * @Column(type="timestamp", default="CURRENT_TIMESTAMP")
     */
    public $created_at;

    public function __construct()
    {
        $this->created_at = new \DateTime();
    }
}