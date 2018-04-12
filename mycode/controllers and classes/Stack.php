<?php
/**
 * Created by PhpStorm.
 * User: gytapa
 * Date: 4/11/2018
 * Time: 3:33 PM
 */

namespace App;

//Class used to display categories iteratively.
//Since it's like depth first search creating a Stack data structure is easier for
//implementation and is easier to read.
//NOTE: since we are not sure how many elements we will have, we dont set maximum stack size
class Stack
{
    private $stack;

    //class constructor - simply initialize the array.
    public function __construct()
    {
        $this->stack = array();
    }

    //add new element to the beginning of the stack
    public function push($element)
    {
        array_unshift($this->stack,$element);
    }

    //take first element from the stack. (return it and remove it from stack).
    public function pop()
    {
        return array_shift($this->stack);
    }

    //return first element from the stacks (keeps it in stack).
    public function top()
    {
        return current($this->stack);
    }

    //check if stack is empty
    public function isEmpty()
    {
        return empty($this->stack);
    }



}