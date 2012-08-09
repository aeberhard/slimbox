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

	include('config.inc.php');
	if (!isset($rxa_slimbox['name'])) {
		echo '<font color="#cc0000"><strong>Fehler! Eventuell wurde die Datei config.inc.php nicht gefunden!</strong></font>';
		return;
	}
		
	echo $rxa_slimbox['i18n']->msg('text_help_title');
	$i=1;
	while ($rxa_slimbox['i18n']->msg('text_help_'.$i)<>'[translate:text_help_'.$i.']') {
		echo $rxa_slimbox['i18n']->msg('text_help_'.$i);
		$i++;
		if ($i>10) { break; }
	}
?>
