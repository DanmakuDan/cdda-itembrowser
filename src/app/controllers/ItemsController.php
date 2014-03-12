<?php

class ItemsController extends BaseController 
{
  protected $item;
  protected $recipe;
  protected $material;
  public function __construct(ItemRepositoryInterface $item, RecipeRepositoryInterface $recipe, MaterialRepositoryInterface $material)
  {
    $this->item = $item;
    $this->recipe = $recipe;
    $this->material= $material;
  }
  public function index()
  {
    return View::make('items.index');
  }

  public function search()
  {
    $search = Input::get('q');
    if($search=="")
      return Redirect::to("/");
    $items = $this->item->where($search);
    return View::make('items.search', compact('items', 'search'));
  }

  public function view($id)
  {
    $item = $this->item->find($id);
    if($item->type=="invalid") App::abort(404);
    $recipeRepository = $this->recipe;
    return View::make('items.view', compact('item', 'recipeRepository'));
  }

  public function craft($id)
  {
    $item = $this->item->find($id);
    if($item->type=="invalid") App::abort(404);
    $itemRepository = $this->item;
    return View::make('items.craft', compact('item', 'itemRepository'));
  }

  public function recipes($id, $category="")
  {
    $item = $this->item->find($id);
    if($item->type=="invalid") App::abort(404);
    $categories = $item->toolCategories;
    if($category=="" && $categories)
    {
      $category = $categories[0];
    }
    $recipes = $item->getToolForCategory($category);

    return View::make('items.recipes', compact('item', "category", "recipes", "categories"));
  }

  public function disassemble($id)
  {
    $item = $this->item->find($id);
    if($item->type=="invalid") App::abort(404);
    return View::make('items.disassemble', compact('item'));
  }

  public function sitemap()
  {
    $items = $this->item->where('');
    return View::make('items.sitemap', compact('items'));
  }
}