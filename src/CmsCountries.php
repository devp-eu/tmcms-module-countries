<?php

namespace TMCms\Modules\Countries;

use TMCms\Admin\Messages;
use TMCms\HTML\BreadCrumbs;
use TMCms\HTML\Cms\CmsFormHelper;
use TMCms\HTML\Cms\CmsTable;
use TMCms\HTML\Cms\Column\ColumnActive;
use TMCms\HTML\Cms\Column\ColumnData;
use TMCms\HTML\Cms\Column\ColumnDelete;
use TMCms\HTML\Cms\Column\ColumnEdit;
use TMCms\HTML\Cms\Columns;
use TMCms\Log\App;
use TMCms\Modules\Countries\Entity\CountryEntity;
use TMCms\Modules\Countries\Entity\CountryEntityRepository;
use TMCms\Routing\Languages;

class CmsCountries {
    public function _default()
    {
        $breadcrumbs = BreadCrumbs::getInstance()
            ->addCrumb(ucfirst(P))
            ->addCrumb('All Countries')
        ;

        $countries = new CountryEntityRepository();

        $table = CmsTable::getInstance()
            ->addData($countries)
            ->addColumn(ColumnData::getInstance('title')
                ->enableOrderableColumn()
                ->multilng(true)
            )
            ->addColumn(ColumnData::getInstance('lng'))
            ->addColumn(ColumnEdit::getInstance('edit')
                ->href('?p=' . P . '&do=edit&id={%id%}')
                ->width('1%')
                ->value('Edit')
            )
            ->addColumn(ColumnActive::getInstance('active')
                ->href('?p=' . P . '&do=_active&id={%id%}')
                ->enableOrderableColumn()
                ->ajax(true)
            )
            ->addColumn(ColumnDelete::getInstance('delete')
                ->href('?p=' . P . '&do=_delete&id={%id%}')
            )
        ;

        $columns = Columns::getInstance()
            ->add($breadcrumbs)
            ->add('<a class="btn btn-success" href="?p=' . P . '&do=add">Add Country</a>', ['align' => 'right'])
        ;

        echo $columns;
        echo $table;
    }

    public function __countries_form($data = NULL)
    {
        $form_array = [
            'data' => $data,
            'action' => '?p=' . P . '&do=_add',
            'button' => 'Add',
            'fields' => [
                'title' => [
                    'multilng' => true
                ],
                'lng' => [
                    'title' => __('Default language'),
                    'type' => 'select',
                    'options' => Languages::getPairs()
                ],
            ]
        ];

        return CmsFormHelper::outputForm(ModuleCountries::$tables['countries'],
            $form_array
        );
    }
    
    public function add()
    {
        echo BreadCrumbs::getInstance()
            ->addCrumb(ucfirst(P))
            ->addCrumb('Add Country')
        ;

        echo self::__countries_form();
    }
    
    public function edit()
    {
        $id = abs((int)$_GET['id']);
        if (!$id) return;

        $country = new CountryEntity($id);

        echo BreadCrumbs::getInstance()
            ->addCrumb(ucfirst(P), '?p='. P)
            ->addCrumb('Edit Country')
            ->addCrumb($country->getTitle())
        ;

        echo self::__countries_form($country)
            ->setAction('?p=' . P . '&do=_edit&id=' . $id)
            ->setSubmitButton('Update');
    }

    public function _add()
    {
        $country = new CountryEntity;
        $country->loadDataFromArray($_POST);
        $country->save();

        App::add('Country "' . $country->getTitle() . '" added');

        Messages::sendMessage('Country added');

        go('?p='. P .'&highlight='. $country->getId());
    }

    public function _edit()
    {
        $id = abs((int)$_GET['id']);
        if (!$id) return;

        $country = new CountryEntity($id);
        $country->loadDataFromArray($_POST);
        $country->save();

        App::add('Country "' . $country->getTitle() . '" edited');

        Messages::sendMessage('Country updated');

        go('?p='. P .'&highlight='. $country->getId());
    }

    public function _active()
    {
        $id = abs((int)$_GET['id']);
        if (!$id) return;

        $country = new CountryEntity($id);
        $country->flipBoolValue('active');
        $country->save();

        App::add('Country "' . $country->getTitle() . '" ' . ($country->getActive() ? '' : 'de') . 'activated');

        Messages::sendMessage('Country updated');

        if (IS_AJAX_REQUEST) {
            die('1');
        }
        back();
    }


    public function _delete()
    {
        $id = abs((int)$_GET['id']);
        if (!$id) return;

        $country = new CountryEntity($id);
        $country->deleteObject();

        App::add('Country "' . $country->getTitle() . '" deleted');

        Messages::sendMessage('Country deleted');

        back();
    }
}