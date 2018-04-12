<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Stack;
use Illuminate\Support\Facades\Route;

class CategoryController extends Controller
{
    //used to create table that will appear in view
    var $table;

    //Go through Category tree recursively and make table as we move on
    function showRecursive()
    {
        $this->initializeTable();
        //start moving through all categories, starting by elements that have no parent elements
        $this->showRecursively(0, 0);
        $this->table .= "</table>";
        if (isset($_GET['m']))
            return view("showCategories", ["table" => html_entity_decode($this->table), "message" => $this->getMessage(), "response" => $_GET['m']]);
        else
            return view("showCategories", ["table" => html_entity_decode($this->table)]);
    }

    //Go through Category tree iteratively and make table as we move on
    function showIterative()
    {
        $this->initializeTable();
        //start moving through all categories, starting by elements that have no parent elements
        $this->showIteratively();
        $this->table .= "</table>";
        if (isset($_GET['m']))
            return view("showCategories", ["table" => html_entity_decode($this->table), "message" => $this->getMessage(), "response" => $_GET['m']]);
        else
            return view("showCategories", ["table" => html_entity_decode($this->table)]);
    }




    //go through tree of categories recursively (Depth first search)
    //$parent - Category that called this function - one function call displays all subcategories of this category
    //$depth - how many parents does this category have (used for indenting - better readability to see parent of each subcategory
    function showRecursively($parent, $depth)
    {
        $categories = \App\Category::all()->where('parent', $parent); //get all subcategories of given parent
        foreach ($categories as $category) {
            $this->table .= "<tr>"; //every category/subcategory has its own row
            $this->indent($depth);
            $this->table .= "<td>$category->name";
            $this->appendButton($category->id);
            $this->appendForm($category->id, "recursion");
            $this->table .= "</td></tr>";
            $this->showRecursively($category->id, $depth + 1); //go deeper and look for more subcategories.
        }
    }

    //go through tree of categories iteratively (Depth first search)
    function showIteratively()
    {
        $stack = new Stack(); //create a stack
        $visited = array();   //array to keep track of what categories were visited.
        $categories = \App\Category::all()->where('parent', 0); //get all categories that has no parents
        $depth = 0;
        foreach ($categories as $category) {
            $category->depth = 0;
            $stack->push($category);    //push all these parents to the stack.
        }
        while (!$stack->isEmpty()) //go through if there is any elements still needed to be visited
        {
            $category = $stack->pop();
            if (in_array($category->id, $visited) == false)  //check if category was visited, if not -output it
            {
                $this->table .= "<tr>";
                $this->indent($category->depth);
                $this->table .= "<td>$category->name";
                $this->appendButton($category->id);
                $this->appendForm($category->id, "iteration");
                $this->table .= "</td></tr>";
                array_push($visited, $category->id);  //add element to visited array
            }
            $categories = \App\Category::all()->where('parent', $category->id); //get subcategories to push
            foreach ($categories as $category) //go through children of popped element.
            {
                if (in_array($category->id, $visited) == false) { //if its not yet visited add to need to visit stack
                    $stack->push($category);    //push all these parents to the stack.
                }
            }
        }
    }

    //create indenting for better readability
    public function indent($depth)
    {
        for ($i = 0; $i < $depth; $i++) {
            $this->table .= "<td></td>";
        }
    }

//adds a new form for category
    public function appendForm($parentId, $route)
    {
        $this->table .= "
        <form method='post' action=" . action('CategoryController@addNewCategory') . " id='form$parentId'>
            <input type='text' placeholder='Name' name='name'>
            <input type='hidden' name='parent' value='$parentId'>
            <input type='submit' value='Add'>
            <input type='hidden' name='route' value='" . $route . "'>
        </form>
        ";
    }

//creates button to show/hide form
    public function appendButton($parentId)
    {
        $this->table .= "<button id='button$parentId' onclick='createForm($parentId)'>Add category</button>";
    }

//add new category to list of categories
    public function addNewCategory(Request $request)
    {
        $_GET['message'] = "good";
        //get inputs from post
        $name = $request->input('name');
        $parent = $request->input('parent');
        if (strlen($name) > 0) //check if name entered
        {
            $parent_element = \App\Category::all()->where('id', $parent)->first(); //due to errors of indenting in iterational view
            //of tree we keep track of indenting in database
            $category = new Category();
            $category->name = $name;
            if ($parent != 0)
                $category->parent = $parent;
            if ($parent != 0) //if element has parent use its indent - if not depth is zero
                $category->depth = $parent_element->depth + 1;
            else
                $category->depth = 0;
            $category->save();  //push new category to database
            if ($request->input('route') == 'recursion') //get which page called the function and return user to it
                return redirect("recursive/?m=true");
            else
                return redirect('iterative/?m=true');
        }
        if ($request->input('route') == 'recursion') //get which page called the function and return user to it
            return redirect("recursive/?m=false");
        else
            return redirect('iterative/?m=false');
    }

//checks if there are any response from adding element. If there is give needed message
    function getMessage()
    {
        if (isset($_GET['m'])) {
            if ($_GET['m'] == 'true')
                return "Category was successfully added to the list.";
            else
                return "Category was not added to the list (Please enter name for the new category).";
        } else return '';
    }

//creates first elements for table form and button for element creation without parents and table beginning tags.
    function initializeTable()
    {
        //form and button for elements without parents
        $this->appendButton(0);
        $this->appendForm(0, "recursion");
        $this->table .= "<table>";
    }
}
