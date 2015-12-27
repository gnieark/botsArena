<article>
<?php
echo '<h2>A props de '.htmlentities($_GET['params']).'</h2><p>Inscrit le '.$theBot['date_inscription'].'</p><p>'.$theBot['description'].'</p>
<p><i><a href="/p/editBot/'.$theBot['id'].'">Si vous êtes le propriétaire de ce bot, vous pouvez le modifier</a></i></p>';
?>
</article>