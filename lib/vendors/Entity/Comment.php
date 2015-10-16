<?php
namespace Entity;
 
use JsonSerializable;
use \OCFram\Entity;
 
class Comment extends Entity implements JsonSerializable
{
  protected $news,
          $auteur,
          $contenu,
          $date,
          $email;
 
  const AUTEUR_INVALIDE = 1;
  const CONTENU_INVALIDE = 2;
 
  public function isValid()
  {
    return !(empty($this->auteur) || empty($this->contenu));
  }
 
  public function setNews($news)
  {
    $this->news = (int) $news;
  }
 
  public function setAuteur($auteur)
  {
    if (!is_string($auteur) || empty($auteur))
    {
      $this->erreurs[] = self::AUTEUR_INVALIDE;
    }
 
    $this->auteur = $auteur;
  }
 
  public function setContenu($contenu)
  {
    if (!is_string($contenu) || empty($contenu))
    {
      $this->erreurs[] = self::CONTENU_INVALIDE;
    }
 
    $this->contenu = $contenu;
  }
 
  public function setDate(\DateTime $date)
  {
    $this->date = $date;
  }

  public function setEmail($email)
  {
    $this->email = $email;
  }
 
  public function news()
  {
    return $this->news;
  }
 
  public function auteur()
  {
    return $this->auteur;
  }
 
  public function contenu()
  {
    return $this->contenu;
  }
 
  public function date()
  {
    return $this->date;
  }

  public function email()
  {
    return $this->email;
  }

  public function jsonSerialize() {
    return array(
        'id' => $this->id(),
        'date' => $this->date()->format('d/m/Y à H\hi'),
        'email' => $this->email(),
        'auteur' => $this->auteur(),
        'contenu' => $this->contenu(),
    );
  }
}