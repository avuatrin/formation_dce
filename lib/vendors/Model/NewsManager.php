<?php
namespace Model;
 
use \OCFram\Manager;
use \Entity\News;
 
abstract class NewsManager extends Manager
{
  /**
   * Méthode permettant d'ajouter une news.
   * @param $news News La news à ajouter
   * @return void
   */
  abstract protected function add(News $news);
 
  /**
   * Méthode permettant d'enregistrer une news.
   * @param $news News la news à enregistrer
   * @see self::add()
   * @see self::modify()
   * @return void
   */
  public function save(News $news)
  {
    if ($news->isValid())
    {
      $news->isNew() ? $this->add($news) : $this->modify($news);
      $this->saveTags($news);
    }
    else
    {
      throw new \RuntimeException('La news doit être validée pour être enregistrée');
    }
  }
 
  /**
   * Méthode renvoyant le nombre de news total.
   * @return int
   */
  abstract public function count();
 
  /**
   * Méthode permettant de supprimer une news.
   * @param $id int L'identifiant de la news à supprimer
   * @return void
   */
  abstract public function delete($id);
 
  /**
   * Méthode retournant une liste de news demandée.
   * @param $debut int La première news à sélectionner
   * @param $limite int Le nombre de news à sélectionner
   * @return array La liste des news. Chaque entrée est une instance de News.
   */
  abstract public function getList($debut = -1, $limite = -1);
 
  /**
   * Méthode retournant une news précise.
   * @param $id int L'identifiant de la news à récupérer
   * @param $bddMode bool true si on doit retourner les clés étrangères, false pour un affichage complet
   * @return News La news demandée
   */
  abstract public function getUnique($id, $bddMode);
 
  /**
   * Méthode permettant de modifier une news.
   * @param $news news la news à modifier
   * @return void
   */
  abstract protected function modify(News $news);

  /** Retourne le nb de news postés par un membre
   * @param $member int id of member
   * @return int
   */
  abstract public function countByMember($member);

  abstract public function saveTags(News $news);

  abstract protected function deleteTags($id);

  abstract public function getTags(News $news);
}