<?php

namespace Flute\Modules\RulesAndFaq\database\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasOne;

/**
 * @Entity()
 */
class RulesEntry
{
    /** @Column(type="primary", name="rules_id") */
    public $rulesId;

    /** @Column(type="string") */
    public string $rule;

    /** @HasOne(target="RulesEntryBlock") */
    public $blocks;

    /** @Column(type="integer", name="position", default=1) */
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
