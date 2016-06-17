<?php

namespace TMCms\Modules\Countries\Entity;

use TMCms\Orm\Entity;

/**
 * Class CountryEntity
 * @package TMCms\Modules\Countries
 *
 * @method int getActive()
 * @method string getTitle()
 */
class CountryEntity extends Entity {
    protected $db_table = 'm_countries';
    protected $translation_fields = ['title'];
}