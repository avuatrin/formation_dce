<?php
namespace OCFram;

class TextField extends Field
{
  protected $cols;
  protected $rows;
  
  public function buildWidget()
  {
    $widget = '<label  name="' . $this->name.'"';
    if (!empty($this->errorMessage)) {
      $widget .= 'class="falseField"';
    }
    $widget .= '>' . $this->label.'</label><textarea name="'.$this->name.'"';
    
    if (!empty($this->cols))
    {
      $widget .= ' cols="'.$this->cols.'"';
    }
    
    if (!empty($this->rows))
    {
      $widget .= ' rows="'.$this->rows.'"';
    }
    
    $widget .= '>';
    
    if (!empty($this->value))
    {
      $widget .= htmlspecialchars($this->value);
    }

    $widget.='</textarea>';

    if (!empty($this->errorMessage))
    {
      $widget .= '<p name="error' . $this->name.'" class="errorMessage">'.$this->errorMessage.'</p>';
    }
    else
      $widget .= '<p name="error' . $this->name.'"></p>';

    return $widget;
  }
  
  public function setCols($cols)
  {
    $cols = (int) $cols;
    
    if ($cols > 0)
    {
      $this->cols = $cols;
    }
  }
  
  public function setRows($rows)
  {
    $rows = (int) $rows;
    
    if ($rows > 0)
    {
      $this->rows = $rows;
    }
  }
}