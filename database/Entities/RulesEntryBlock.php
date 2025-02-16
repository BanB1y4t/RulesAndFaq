<?php

namespace Flute\Modules\RulesAndFaq\database\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\BelongsTo;

/**
 * @Entity()
 */
class RulesEntryBlock
{
    /** @Column(type="primary") */
    public $id;

    /** @BelongsTo(target="RulesEntry", nullable=false) */
    public $rulesEntry;

    /** @Column(type="json") */
    public $json;
}