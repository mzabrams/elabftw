<?php
/**
 * @author Nicolas CARPi <nicolas.carpi@curie.fr>
 * @copyright 2012 Nicolas CARPi
 * @see https://www.elabftw.net Official website
 * @license AGPL-3.0
 * @package elabftw
 */
declare(strict_types=1);

namespace Elabftw\Models;

use Elabftw\Elabftw\Db;
use Elabftw\Interfaces\CrudInterface;
use Elabftw\Traits\SortableTrait;

/**
 * A category is a status for experiments and item type for db item
 */
abstract class AbstractCategory implements CrudInterface
{
    use SortableTrait;

    /** @var Db $Db SQL Database */
    protected $Db;

    /**
     * Get the color of an item type
     *
     * @param int $id ID of the category
     * @return string
     */
    abstract public function readColor(int $id): string;

    /**
     * Count all items of this type
     *
     * @param int $id of the type
     * @return int
     */
    abstract protected function countItems(int $id): int;
}
