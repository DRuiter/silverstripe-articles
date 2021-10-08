<?php

namespace TheWebmen\Articles\Traits;

use SilverStripe\CMS\Controllers\CMSPageEditController;
use SilverStripe\Forms\FieldList;
use SilverStripe\View\Parsers\URLSegmentFilter;
use TheWebmen\Articles\Pages\ArticlePage;
use TheWebmen\Articles\Pages\ArticlesPage;

trait ArticleRelationObjectTrait
{
    /**
     * @var array
     */
    private static $db = [
        'Title' => 'Varchar(255)',
        'Slug' => 'Varchar(255)',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'ArticlesPage' => ArticlesPage::class,
    ];

    /***
     * @return FieldList
     */
    public function getCMSFields()
    {
        $fields = parent::getCMSFields();

        $fields->removeByName('ArticlesPageID');

        $fields->renameField('Title', 'Name');

        return $fields;
    }
}
