<p style="text-align: center">Il y a actuellement <?= $nombreNews ?> news. En voici la liste :</p>

<table>
  <tr><th>Auteur</th><th>Titre</th><th>Date d'ajout</th><th>Dernière modification</th><th>Action</th></tr>
<?php
foreach ($listeNews as $news)
{
  echo
    '<tr>
      <td>', $news['auteur'], '</td>
      <td>', $news['titre'], ' </td>
      <td> le ', $news['dateAjout']->format('d/m/Y à H\hi'), '</td>
      <td>', ($news['dateAjout'] == $news['dateModif'] ? '-' : 'le '.$news['dateModif']->format('d/m/Y à H\hi')), '</td>
      <td><a href="'.$this->app()->router()->getUrl('update', 'News', [$news['id']]).'"><img src="/images/update.png" alt="Modifier" /></a>
      <a href="'.$this->app()->router()->getUrl('delete', 'News', [$news['id']]).'"><img src="/images/delete.png" alt="Supprimer" /></a></td>
      </tr>',
  "\n";
}
?>
</table>