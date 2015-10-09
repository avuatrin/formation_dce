<?php
namespace OCFram;

class SelectField extends Field
{
    protected $options = [];

    public function buildWidget()
    {
        $widget = '';

        if (!empty($this->errorMessage)) {
            $widget .= $this->errorMessage . '<br />';
        }

        $widget .= '<label>' . $this->label . '</label><select name="' . $this->name . '">';

        foreach($this->options as $option)
            $widget .= '<option  value="' . htmlspecialchars($option) . '">'.htmlspecialchars($option).'</option>';


        return $widget .= ' </select>';
    }

    public function setOptions($options)
    {
        $this->options[] = $options;
    }


}