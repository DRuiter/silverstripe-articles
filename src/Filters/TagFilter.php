<?php

namespace TheWebmen\Articles\Filters;

use SilverStripe\ORM\DataList;
use TheWebmen\Articles\Interfaces\FilterInterface;
use TheWebmen\Articles\Models\Tag;

final class TagFilter implements FilterInterface
{
    public function apply(array $items, DataList $dataList): DataList
    {
        $tag = $this->getActiveItems($items);

        if (!$tag) {
            return $dataList;
        }

        return $dataList->filter('Tags.ID', $tag->ID);
    }

    public function getActiveItems(array $items)
    {
        if (count($items) > 1) {
            return Tag::get()->filter('Slug', $items);
        }

        return Tag::get()->filter('Slug', $items[0])->first();
    }
}
