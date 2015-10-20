<?php
namespace OCFram;
 
class Page extends ApplicationComponent
{
  protected $contentFile;
  protected $returnType;
  protected $vars = [];

  const CONTENT_MAIL = 2;
  const CONTENT_JSON = "JSON";
  const CONTENT_HTML = "";
 
  public function addVar($var, $value)
  {
    if (!is_string($var) || is_numeric($var) || empty($var))
    {
      throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractères non nulle');
    }
 
    $this->vars[$var] = $value;
  }
 
  public function getGeneratedPage()
  {
    extract($this->vars);

    //récupération de la vue dans le cas ou l'on a du html à retourner
    if(!file_exists($this->contentFile)) {
        throw new \RuntimeException('La vue spécifiée n\'existe pas');
    }else{
        ob_start();
        require $this->contentFile;
        $content = ob_get_clean();
    }

    ob_start();
      require __DIR__.'/../../App/'.$this->app->name().'/Templates/'.$this->returnType.'Layout.php';
    return ob_get_clean();
  }
 
  public function setContentFile($contentFile)
  {
    if (!is_string($contentFile) || empty($contentFile))
    {
      throw new \InvalidArgumentException('La vue spécifiée est invalide');
    }
 
    $this->contentFile = $contentFile;
  }

  public function setReturnType($returnTypeConst){
      $this->returnType = $returnTypeConst;
  }

}