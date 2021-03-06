<?php
namespace OCFram;

class StringField extends Field
{
  protected $maxLength,
      $type;

  public function buildWidget()
  {
    $widget = '<label  name="' . $this->name.'"';
    if (!empty($this->errorMessage)) {
      $widget .= 'class="falseField"';
    }
    $widget .= '>' . $this->label . '</label><input name="' . $this->name . '"';

    if (!empty($this->value)) {
      $widget .= ' value="' . htmlspecialchars($this->value) . '"';
    }

    if(!empty($this->type))
      $widget .= ' type="' . htmlspecialchars($this->type) . '"';
    else
      $widget .= 'type = "text"';

    if (!empty($this->maxLength)) {
      $widget .= ' maxlength="' . $this->maxLength . '"';
    }

    $widget .= ' />';

    if (!empty($this->errorMessage)) {
      $widget .= '<p name="error' . $this->name.'" class="errorMessage">'.$this->errorMessage.'</p>';
    }else
      $widget .= '<p name="error' . $this->name.'"></p>';
    return $widget;
  }

  public function setMaxLength($maxLength)
  {
    $maxLength = (int)$maxLength;

    if ($maxLength > 0) {
      $this->maxLength = $maxLength;
    } else {
      throw new \RuntimeException('La longueur maximale doit être un nombre supérieur à 0');
    }
  }

  Public function setType($type = 'text'){  $this->type = $type;  }


}