<?php

namespace App\Transformers;

use App\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Category $category)
    {
      return [
          'identifier' => (int)$category->id,
          'catname' => (string)$category->name,
          'detail' => (string)$category->description,
          'createdDate' => (string)$category->created_at,
          'updatedDate' => (string)$category->updated_at,
          'deletedDate' => isset($category->deleted_at) ? (string) $category->deleted_at : null,
      ];
    }
}