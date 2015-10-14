<?php
namespace OCFram;

class SelectField extends Field
{
    protected $options = [];

    public function buildWidget()
    {
        $widget = '<label  name="' . $this->name .'"';
        if (!empty($this->errorMessage)) {
            $widget .= 'class="falseField"';
        }
        $widget .= '>' .  $this->label . '</label><select name="' . $this->name . '" required>';

        foreach($this->options as $id => $option) {
            $widget .= '<option  value="' . $id . '"';
            if (!empty($this->value)) {
                $widget .= $this->value == $id ? 'selected' : '';
            }
            $widget .= '>' . htmlspecialchars($option) . '</option>';
        }

        $widget .= ' </select>';

        if (!empty($this->errorMessage))
        {
            $widget .= '<p name="error' . $this->name.'" class="errorMessage">'.$this->errorMessage.'</p>';
        }else
            $widget .= '<p name="error' . $this->name.'"></p>';
        return $widget;
    }

    public function setOptions($options)
    {
        $this->options = $options;
    }


}