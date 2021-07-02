<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Badge extends Component
{
    public $type;
    public $show;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($show, $type)
    {
        $this->type = $type;
        $this->show = $show;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.badge');
    }
}
