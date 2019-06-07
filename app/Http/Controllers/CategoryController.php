<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends baseController
{
    public function __construct(Category $categoryModel)
    {
        $this->categoryModel = $categoryModel;
    }

    public function index()
    {
        // get category data
        $categories = $this->categoryModel->all();

        return $this->output($categories);
    }

}