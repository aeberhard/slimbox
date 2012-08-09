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

	unset($rxa_slimbox); 
	include('config.inc.php');
	
	if (!isset($rxa_slimbox['name'])) {
		echo '<font color="#cc0000"><strong>Fehler! Eventuell wurde die Datei config.inc.php nicht gefunden!</strong></font>';
		return;
	}

	// Dateien aus dem Ordner files/slimbox löschen
	if (isset($rxa_slimbox['filesdir']) and ($rxa_slimbox['filesdir']<>'') and ($rxa_slimbox['name']<>'') ) {
		if ($dh = opendir($rxa_slimbox['filesdir'])) {
			while ($el = readdir($dh)) {
				$path = $rxa_slimbox['filesdir'].'/'.$el;
				if ($el != '.' && $el != '..' && is_file($path)) {
					@unlink($path);
				}
			}
		}
	}
	@closedir($dh);
	@rmdir($rxa_slimbox['filesdir']);	
	
	// Evtl Ausgabe einer Meldung
	// De-Installation nicht erfolgreich
	if ( $rxa_slimbox['meldung']<>'' ) {
		$REX['ADDON']['installmsg'][$rxa_slimbox['name']] = '<br /><br />'.$rxa_slimbox['meldung'].'<br /><br />';
		$REX['ADDON']['install'][$rxa_slimbox['name']] = 1;
	// De-Installation erfolgreich
	} else {
		$REX['ADDON']['install'][$rxa_slimbox['name']] = 0;
	}
?>