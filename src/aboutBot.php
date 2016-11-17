<article>
<?php
#- BEGIN LICENSE BLOCK ---------------------------------------
#
# This file is part of botsArena.
#
# Copyright (C) Gnieark et contributeurs
# Licensed under the GPL version 3.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/gpl-3.0-standalone.html
#
# -- END LICENSE BLOCK -----------------------------------------

echo '<h2>A props de '.htmlentities($_GET['params']).'</h2><p>Inscrit le '.$theBot['date_inscription'].'</p><p>'.$theBot['description'].'</p>
<p><i><a href="/p/editBot/'.$theBot['id'].'">Si vous êtes le propriétaire de ce bot, vous pouvez le modifier</a></i></p>';
?>
</article>