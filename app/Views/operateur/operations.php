<h2>Liste des opérations</h2>


<table border="1">

<tr>
<th>Client</th>
<th>Numéro</th>
<th>Type</th>
<th>Montant</th>
<th>Frais</th>
<th>Date</th>
</tr>


<?php foreach($operations as $op): ?>

<tr>

<td>
<?= $op['client'] ?>
</td>


<td>
<?= $op['num'] ?>
</td>


<td>
<?= $op['libele'] ?>
</td>


<td>
<?= $op['valeur'] ?>
</td>


<td>
<?= $op['frais'] ?? 0 ?>
</td>


<td>
<?= $op['dateheure'] ?>
</td>


</tr>


<?php endforeach; ?>


</table>