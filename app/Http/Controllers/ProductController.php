<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Product;
use App\Helpers\CategoryHierarchy;

class ProductController extends Controller {

	public function index(CategoryHierarchy $hierarchy)
  {
    /*We're referencing the Facade directly in the 
     *controller for this example but you will  
     *probably want to take advantage of the
     *new method injection features built
     *into Laravel 5 and use a product
     *repository.
    */
    $products = Product::getAllProducts();
    $hierarchy->setupItems($products);
    return $hierarchy->render();
  }
  
}
