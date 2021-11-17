<?php

namespace TheWebmen\Articles\Models;

use SilverStripe\CMS\Controllers\CMSPageEditController;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\Parsers\URLSegmentFilter;
use TheWebmen\Articles\ElementalGrid\ElementArticles;
use TheWebmen\Articles\Pages\ArticlePage;
use TheWebmen\Articles\Pages\ArticlesPage;

class Author extends DataObject
{
    /**
     * @var string
     */
    private static $table_name = 'Webmen_Author';

    /**
     * @var string
     */
    private static $singular_name = 'Author';

    /**
     * @var string
     */
    private static $plural_name = 'Authors';

    /**
     * @var string
     */
    private static $icon_class = 'font-icon-block-user';

    /**
     * @var array
     */
    private static $summary_fields = [
        'Title' => 'Name',
    ];

    /**
     * @var array
     */
    private static $db = [
        'Title' => 'Varchar(255)',
        'Slug' => 'Varchar(255)',
        'Function' => 'Varchar',
        'Bio' => 'HTMLText',
        'Phone' => 'Varchar',
        'Email' => 'Varchar',
        'FacebookURL' => 'Varchar',
        'TwitterURL' => 'Varchar',
        'LinkedInURL' => 'Varchar',
    ];

    /**
     * @var array
     */
    private static $has_one = [
        'ArticlesPage' => ArticlesPage::class,
    ];

    /**
     * @var array
     */
    private static $many_many = [
        'ElementArticles' => ElementArticles::class,
    ];

    public function getCMSFields(): FieldList
    {
        $fields = parent::getCMSFields();

        $fields->removeByName(
            [
                'ArticlesPageID',
                'Slug',
                'Function',
                'Bio',
                'Phone',
                'Email',
                'FacebookURL',
                'TwitterURL',
                'LinkedInURL',
            ]
        );

        $fields->renameField('Title', 'Name');

        $fields->addFieldsToTab(
            'Root.Main',
            [
                TextField::create('Function', 'Function'),
                HTMLEditorField::create('Bio', 'Bio')->setRows(5),
                HeaderField::create('', 'Contact details'),
                TextField::create('Phone', 'Phone'),
                TextField::create('Email', 'E-mailaddress'),
                HeaderField::create('', 'Social media'),
                TextField::create('FacebookURL', 'Facebook URL'),
                TextField::create('TwitterURL', 'Twitter URL'),
                TextField::create('LinkedInURL', 'LinkedIn URL'),
            ]
        );

        return $fields;
    }

    /**
     * This sets the ArticlesPageID in case the Author is created within
     * an article {@see ArticlePage}, in stead of via the article overview page {@see ArticlesPage}
     */
    protected function onBeforeWrite(): void
    {
        $currentPageID = CMSPageEditController::curr()->currentPageID();
        $currentPage = \Page::get_by_id(ArticlePage::class, $currentPageID);

        if ($currentPage) {
            $this->ArticlesPageID = $currentPage->ParentID;
        }

        $this->Slug = URLSegmentFilter::create()->filter($this->Title);

        parent::onBeforeWrite();
    }
}
