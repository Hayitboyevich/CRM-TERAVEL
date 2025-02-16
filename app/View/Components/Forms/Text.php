<?php

namespace App\View\Components\Forms;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Text extends Component
{

    public $fieldLabel;
    public $fieldRequired;
    public $fieldPlaceholder;
    public $fieldValue;
    public $fieldName;
    public $fieldId;
    public $fieldDisabled;
    public $fieldHelp;
    public $fieldReadOnly;
    public $popover;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($fieldLabel, $fieldName, $fieldId, $fieldDisabled = false, ?bool $fieldRequired = false,
                                $fieldPlaceholder = null, $fieldValue = null, $fieldHelp = null, $fieldReadOnly = false,
                                $popover = null)
    {
        $this->fieldLabel = $fieldLabel;
        $this->fieldRequired = $fieldRequired;
        $this->fieldPlaceholder = $fieldPlaceholder;
        $this->fieldValue = $fieldValue;
        $this->fieldName = $fieldName;
        $this->fieldDisabled = $fieldDisabled;
        $this->fieldId = $fieldId;

        $this->fieldHelp = $fieldHelp;
        $this->fieldReadOnly = $fieldReadOnly;
        $this->popover = $popover;

    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return View|string
     */
    public function render()
    {
        return view('components.forms.text');
    }

}
