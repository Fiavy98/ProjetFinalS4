<h2>Configuration frais</h2>


<table border="1">

<tr>

<th>Operation</th>
<th>Minimum</th>
<th>Maximum</th>
<th>Frais</th>

</tr>



<?php foreach($frais as $f): ?>

<tr>

<td>
<?= $f['libele'] ?>
</td>


<td>
<?= $f['min'] ?>
</td>


<td>
<?= $f['max'] ?>
</td>


<td>
<?= $f['valeur'] ?>
</td>


</tr>


<?php endforeach; ?>


</table>