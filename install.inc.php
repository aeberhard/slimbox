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
		$REX['ADDON']['install'][$rxa_slimbox['name']] = 0;
		return;
	}

   // Gültige REDAXO-Version abfragen
	if ( !in_array($rxa_slimbox['rexversion'], array('3.11', '32', '40', '41', '42')) ) {
		echo '<font color="#cc0000"><strong>Fehler! Ung&uuml;ltige REDAXO-Version - '.$rxa_slimbox['rexversion'].'</strong></font>';
		$REX['ADDON']['installmsg'][$rxa_slimbox['name']] = '<br /><br /><font color="#cc0000"><strong>Fehler! Ung&uuml;ltige REDAXO-Version - '.$rxa_slimbox['rexversion'].'</strong></font>';
		$REX['ADDON']['install'][$rxa_slimbox['name']] = 0;
		return;
	}
	
	// Verzeichnis files/slimbox anlegen
	if ( !@is_dir($rxa_slimbox['filesdir']) ) {
		if ( !@mkdir($rxa_slimbox['filesdir']) ) {
			$rxa_slimbox['meldung'] .= $rxa_slimbox['i18n']->msg('error_createdir', $rxa_slimbox['filesdir']);
		}
	}

	// Dateien ins Verzeichnis files/slimbox kopieren
	if ($dh = opendir($rxa_slimbox['sourcedir'])) {
		while ($el = readdir($dh)) {
			$rxa_slimbox['file'] = $rxa_slimbox['sourcedir'].'/'.$el;
			if ($el != '.' && $el != '..' && is_file($rxa_slimbox['file'])) {
				if ( !@copy($rxa_slimbox['file'], $rxa_slimbox['filesdir'].'/'.$el) ) {
					$rxa_slimbox['meldung'] .= $rxa_slimbox['i18n']->msg('error_copyfile', $el, $REX['HTDOCS_PATH'].'files/'.$rxa_slimbox['name'].'/');
				}
			}
		}
	} else {
		$rxa_slimbox['meldung'] .= $rxa_slimbox['i18n']->msg('error_readdir',$rxa_slimbox['sourcedir']);
	}
	
	// Evtl Ausgabe einer Meldung
	// $rxa_slimbox['meldung'] = 'Das Addon wurde nicht installiert, weil...';
	if ( $rxa_slimbox['meldung']<>'' ) {
		$REX['ADDON']['installmsg'][$rxa_slimbox['name']] = '<br /><br />'.$rxa_slimbox['meldung'].'<br /><br />';
		$REX['ADDON']['install'][$rxa_slimbox['name']] = 0;
	} else {
	// Installation erfolgreich
		$REX['ADDON']['install'][$rxa_slimbox['name']] = 1;
	}
?>