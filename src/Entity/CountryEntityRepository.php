<?php

namespace TMCms\Modules\Countries\Entity;

use TMCms\Orm\EntityRepository;

/**
 * Class CountryRepository
 * @package TMCms\Modules\Countries
 *
 * @method setWhereActive(bool $flag)
 */
class CountryEntityRepository extends EntityRepository {
    protected $db_table = 'm_countries';
    protected $translation_fields = ['title'];
    protected $table_structure = [
        'fields' => [
            'icon' => [
                'type' => 'varchar',
            ],
            'lng' => [
                'type' => 'char',
                'length' => 2,
            ],
            'title' => [
                'type' => 'translation',
            ],
            'active' => [
                'type' => 'bool',
            ],
        ],
    ];
}