<?php
/**
 * --------------------------------------------------------------------
 *
 * Redaxo Addon: Slimbox
 * Version: 1.5, 19.03.2008
 *
 * Autor: Andreas Eberhard, andreas.eberhard@gmail.com
 *        http://rex.andreaseberhard.de
 *
 * Verwendet wird das Script von Christophe Beyls
 * http://www.digitalia.be/software/slimbox/
 *
 * --------------------------------------------------------------------
 */
?>

<table border="0" width="770" class="rex-table">
  <tr>
    <td class="grey" style="padding:10px;">
<strong>Modul-Input:</strong><br /><br />
<textarea cols="50" rows="12" style="width:80%;">
<?php 
	echo htmlspecialchars(file_get_contents($rxa_slimbox['path'].'/modul-input.txt'));
?>
</textarea>
    </td>
  </tr>
  <tr>
    <td class="grey" style="padding:10px;">
<strong>Modul-Output:</strong><br /><br />
<textarea cols="50" rows="12" style="width:80%;">
<?php 
	echo htmlspecialchars(file_get_contents($rxa_slimbox['path'].'/modul-output.txt'));
?>
</textarea>
    </td>
  </tr>
</table>