<?php

namespace App\View\Components;

use Illuminate\View\Component;

class GridForm extends Component
{
    public $gridOptions;
    public $actionMethod;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($gridOptions)
    {
        $this->actionMethod = request()->route()->getActionMethod();
        $this->gridOptions = $gridOptions;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.grid-form');
    }
}
