<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputSearch extends Component
{
    public $searchOptions;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($searchOptions)
    {
        $this->searchOptions = $searchOptions;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.input-search');
    }
}
