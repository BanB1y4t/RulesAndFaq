<?php

namespace Flute\Modules\RulesAndFaq\database\Entities;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;

/**
 * @Entity()
 */
class RafSettings
{
    /** @Column(type="primary") */
    public $id;

    /** @Column(type="string", unique=true) */
    public string $key;

    /** @Column(type="string") */
    public string $value;
}