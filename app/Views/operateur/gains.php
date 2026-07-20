<h2>Historique des gains</h2>


<table border="1">


<tr>
<th>Date</th>
<th>Operation</th>
<th>Gain</th>
</tr>


<?php foreach($gains as $g): ?>


<tr>

<td>
<?= $g['dateheure'] ?>
</td>


<td>
<?= $g['libele'] ?>
</td>


<td>
<?= $g['valeur'] ?>
</td>


</tr>


<?php endforeach; ?>


</table>