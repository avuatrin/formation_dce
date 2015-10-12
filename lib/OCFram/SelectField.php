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

        $widget .= '<label>' . $this->label . '</label><select name="' . $this->name . '" required>';

        foreach($this->options as $id => $option) {
            $widget .= '<option  value="' . $id . '"';
            if (!empty($this->value)) {
                $widget .= $this->value == $id ? 'selected' : '';
            }
            $widget .= '>' . htmlspecialchars($option) . '</option>';
        }

        return $widget .= ' </select>';
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }


}