<?php
namespace Model;
 
use \OCFram\Manager;
use \Entity\Comment;
 
abstract class CommentsManager extends Manager
{
  /**
   * Méthode permettant d'ajouter un commentaire.
   * @param $comment Le commentaire à ajouter
   * @return void
   */
  abstract protected function add(Comment $comment);
 
  /**
   * Méthode permettant de supprimer un commentaire.
   * @param $id int L'identifiant du commentaire à supprimer
   * @param $user int L'identifiant du commentaire à supprimer
   * @return void
   */
  abstract public function delete($id, $user);
 
  /**
   * Méthode permettant de supprimer tous les commentaires liés à une news
   * @param $news L'identifiant de la news dont les commentaires doivent être supprimés
   * @return void
   */
  abstract public function deleteFromNews($news);
 
  /**
   * Méthode permettant d'enregistrer un commentaire.
   * @param $comment Comment Le commentaire à enregistrer
   * @return void
   */
  public function save(Comment $comment)
  {
    if ($comment->isValid())
    {
      $comment->isNew() ? $this->add($comment) : $this->modify($comment);
    }
    else
    {
      throw new \RuntimeException('Le commentaire doit être validé pour être enregistré');
    }
  }
 
  /**
   * Méthode permettant de récupérer une liste de commentaires.
   * @param $news La news sur laquelle on veut récupérer les commentaires
   * @param $debut int La première news à sélectionner
   * @param $limite int Le nombre de news à sélectionner
   * @return array
   */
  abstract public function getListOf($news, $debut, $limite);
 
  /**
   * Méthode permettant de modifier un commentaire.
   * @param $comment Comment Le commentaire à modifier
   * @return void
   */
  abstract protected function modify(Comment $comment);
 
  /**
   * Méthode permettant d'obtenir un commentaire spécifique.
   * @param $id L'identifiant du commentaire
   * @return Comment
   */
  abstract public function get($id);

  /** Retourne le nb de messages postés par un membre
   * @param $member int id of member
   * @return int
   */
  abstract public function countByMember($member);
}