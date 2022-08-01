<?php namespace Visiosoft\CustomfieldsModule;

use Anomaly\Streams\Platform\Addon\Plugin\Plugin;
use Visiosoft\CatsModule\Category\Contract\CategoryRepositoryInterface;
use Visiosoft\CustomfieldsModule\CustomField\Command\getCustomFields;
use Visiosoft\CustomfieldsModule\CustomField\Contract\CustomFieldRepositoryInterface;
use Visiosoft\CustomfieldsModule\Parent\Contract\ParentRepositoryInterface;

class CustomfieldsModulePlugin extends Plugin
{

    public $repository;
    public $parentRepository;
    public $categoryRepository;

    public function __construct(CustomFieldRepositoryInterface $repository, ParentRepositoryInterface $parentRepository,
                                CategoryRepositoryInterface $categoryRepository)
    {
        $this->repository = $repository;
        $this->parentRepository = $parentRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                'getSelectedCategoriesByCustomfieldId',
                function ($id) {

                    if ($categories = $this->parentRepository->findAllBy('cf_id', $id)) {
                        $categories = $categories->pluck('cat_id')->all();

                        $new_categories = array();

                        foreach ($categories as $category) {
                            if ($category = $this->categoryRepository->find($category)) {
                                $parents = $this->categoryRepository->getParentCategoryById($category->getId());
                                krsort($parents);
                                $name = "";
                                foreach ($parents as $key => $parent) {
                                    $name .= ($key == 0) ? $parent->name . '' : $parent->name . ' > ';
                                }
                                $new_categories[] = [$category->getId() => $name];
                            }
                        }
                        return json_encode($new_categories);
                    }
                }
            ),
            new \Twig_SimpleFunction(
                'getCustomField',
                function ($slug, $placeholder = 'visiosoft.module.customfields::field.select.name', $view = true) {
                    if (!is_null($cf = $this->dispatch(new getCustomFields($slug, $placeholder, $view)))) {
                        return $cf;
                    }
                    return null;
                }
            ),
            new \Twig_SimpleFunction(
                'unset',
                function ($variable, $array, $withValue = false) {
                    if ($withValue) {
                        if (($key = array_search($variable, $array)) !== false) {
                            unset($array[$key]);
                        }
                    } else {
                        unset($array[$variable]);
                    }
                    return $array;
                }
            ),
            new \Twig_SimpleFunction(
                'getValueWithSlugAndAd',
                function ($slug, $ad_id, $get_value = false) {
                    if (!is_null($cf = $this->repository->getAdValueByCustomFieldSlug($slug, $ad_id, $get_value))) {
                        return $cf;
                    }
                    return null;
                }
            ),
        ];
    }
}
