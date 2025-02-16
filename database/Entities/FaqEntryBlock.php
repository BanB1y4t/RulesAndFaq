<?php

namespace Flute\Modules\RulesAndFaq\database\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;

/**
 * @Entity()
 */
class FaqEntryBlock
{
    /** @Column(type="primary") */
    public $id;

    /** @BelongsTo(target="FaqEntry", nullable=false) */
    public $faqEntry;

    /** @Column(type="json") */
    public $json;
}