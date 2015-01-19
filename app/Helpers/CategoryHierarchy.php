<?php

namespace App\Helpers;

/**
 * Class CategoryHierarchy
 * @package App\Helpers
 */
class CategoryHierarchy {

    /**
     * @var $items
     * @private
     */
    private $items;

    /**
     * @var string $inputName The name attribute that is applied to checkboxes created in the lists.
     */
    protected $inputName = 'hierarchy-checkboxes';

    /**
     * @param array $items Collection of items that a hierarchical list will be generated for.
     */
    public function setupItems($items)
    {
        $this->items = $items;
    }

    /**
     * Generate the HTML list from an array of hierarchical data.
     *
     * @return string
     * @public
     */
    public function render()
    {
        return $this->htmlFromArray($this->createItemArray(), true);
    }

    /**
     * Convert a collection of data with parent_id / id data into
     * a nested array that allows for easy traversal and list
     * production.
     *
     * @return array $result The nested array of data.
     */
    private function createItemArray()
    {
        $result = array();
        foreach($this->items as $item) {
            if ($item->parent_id == 0) {
                $newItem = array();
                $newItem['name'] = $item->name;
                $newItem['children'] = $this->itemWithChildren($item);
                $newItem['parent_id'] = $item->parent_id;
                $newItem['id'] = $item->id;
                array_push($result, $newItem);
            }
        }
        return $result;
    }

    /**
     * Find the individual child nodes item has. From the example documentation,
     * if the $item passed to this method was 'Italian', the result from this
     * would assign a 'Pasta' and 'Pizza' node to the $result array.
     *
     * @param array $item The item for which children will be found.
     * @return array $result
     */
    private function childrenOf($item) {
        $result = array();
        foreach($this->items as $i) {
            if ($i->parent_id == $item->id) {
                $result[] = $i;
            }
        }
        return $result;
    }

    /**
     * Build an array of child nodes that are nested to given item.
     * (I.e.any elements that have a parent_id of the item that
     * is passed into the method).
     *
     * @param array $item
     * @return array $result
     */
    private function itemWithChildren($item) {
        $result = array();
        $children = $this->childrenOf($item);
        foreach ($children as $child) {
            $newItem = array();
            $newItem['name'] = $child->name;
            $newItem['children'] = $this->itemWithChildren($child);
            $newItem['id'] = $child->id;
            $newItem['parent_id'] = $child->parent_id;
            array_push($result, $newItem);
        }
        return $result;
    }

    /**
     * Generate the HTML list (<ul>) representation of the hierarchical array.
     *
     * @param array $hierarchicalArray
     * @param bool $addCheckboxes Should we append checkboxes to the list items?
     * @param string $listClass We can append classes to the <ul> tags
     * @return string $html
     */
    private function htmlFromArray($hierarchicalArray, $addCheckboxes = false, $listClass = 'list-unstyled') {
        $html = '';
        foreach($hierarchicalArray as $item) {
            $html .= "<ul class='".$listClass."'>";
            $html .= "<li>".($addCheckboxes ? '<input type="checkbox" name="'.$this->inputName.'[]" value="'.$item['id'].'" '.(isset($item['checked']) && ($item['checked']=true) ? 'checked' : '').'> ' : '') . $item['name']."</li>";
            if(count($item['children']) > 0) {
                $html .= $this->htmlFromArray($item['children'], $addCheckboxes, $listClass);
            }
            $html .= "</ul>";
        }
        return $html;
    }

}
