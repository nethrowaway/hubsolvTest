<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends baseController
{

    public function index(Category $categoryModel)
    {
        // get category data
        $categories = $categoryModel->all();

        return $this->output($categories);
    }

}