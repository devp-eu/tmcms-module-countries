<?php

namespace TMCms\Modules\Countries;

use TMCms\Modules\Countries\Entity\CountryEntityRepository;
use TMCms\Modules\IModule;
use TMCms\Traits\singletonInstanceTrait;

class ModuleCountries implements IModule {
    use singletonInstanceTrait;

    public static $tables = [
        'countries' => 'm_countries'
    ];

    public static function getCountryPairs($only_active = false)
    {
        $countries = new CountryEntityRepository();
        if ($only_active) {
            $countries->setWhereActive(true);
        }
        $countries->addOrderByField('title');

        return $countries->getPairs('title');
    }
}