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

	// Name des Addons und Pfade
	unset($rxa_slimbox);
	$rxa_slimbox['name'] = 'slimbox';

	$REX['ADDON']['version'][$rxa_slimbox['name']] = '1.5';
	$REX['ADDON']['author'][$rxa_slimbox['name']] = 'Andreas Eberhard';

	$rxa_slimbox['path'] = $REX['INCLUDE_PATH'].'/addons/'.$rxa_slimbox['name'];
	$rxa_slimbox['basedir'] = dirname(__FILE__);
	$rxa_slimbox['lang_path'] = $REX['INCLUDE_PATH']. '/addons/'. $rxa_slimbox['name'] .'/lang';
	$rxa_slimbox['sourcedir'] = $REX['INCLUDE_PATH']. '/addons/'. $rxa_slimbox['name'] .'/'. $rxa_slimbox['name'];
	$rxa_slimbox['filesdir'] = $REX['HTDOCS_PATH'].'files/'.$rxa_slimbox['name'];
	$rxa_slimbox['meldung'] = '';
	$rxa_slimbox['rexversion'] = isset($REX['VERSION']) ? $REX['VERSION'] . $REX['SUBVERSION'] : $REX['version'] . $REX['subversion'];

/**
 * --------------------------------------------------------------------
 * Nur im Backend
 * --------------------------------------------------------------------
 */
	if (!$REX['GG']) {
		// Sprachobjekt anlegen
		$rxa_slimbox['i18n'] = new i18n($REX['LANG'],$rxa_slimbox['lang_path']);

		// Anlegen eines Navigationspunktes im REDAXO Hauptmenu
		$REX['ADDON']['page'][$rxa_slimbox['name']] = $rxa_slimbox['name'];
		// Namensgebung f�r den Navigationspunkt
		$REX['ADDON']['name'][$rxa_slimbox['name']] = $rxa_slimbox['i18n']->msg('menu_link');

		// Berechtigung f�r das Addon
		$REX['ADDON']['perm'][$rxa_slimbox['name']] = $rxa_slimbox['name'].'[]';
		// Berechtigung in die Benutzerverwaltung einf�gen
		$REX['PERM'][] = $rxa_slimbox['name'].'[]';		
	}

/**
 * --------------------------------------------------------------------
 * Outputfilter f�r das Frontend
 * --------------------------------------------------------------------
 */
	if ($REX['GG'])
	{
		rex_register_extension('OUTPUT_FILTER', 'slimbox_opf');

		// Pr�fen ob die aktuelle Kategorie mit der Auswahl �bereinstimmt
		function slimbox_check_cat($acat, $aart, $subcats, $slimbox_cats)
		{

			// pr�fen ob Kategorien ausgew�hlt
			if (!is_array($slimbox_cats)) return false;

			// aktuelle Kategorie in den ausgew�hlten dabei?
			if (in_array($acat, $slimbox_cats)) return true;

			// Pr�fen ob Parent der aktuellen Kategorie ausgew�hlt wurde
			if ( ($acat > 0) and ($subcats == 1) )
			{
				$cat = OOCategory::getCategoryById($acat);
				while($cat = $cat->getParent())
				{
					if (in_array($cat->_id, $slimbox_cats)) return true;
				}
			}

			// evtl. noch Root-Artikel pr�fen
			if (strstr(implode('',$slimbox_cats), 'r'))
			{
				if (in_array($aart.'r', $slimbox_cats)) return true;
			}

			// ansonsten keine Ausgabe!
			return false;
		}

      // Output-Filter
		function slimbox_opf($params)
		{
			global $REX, $REX_ARTICLE;
			global $rxa_slimbox;

			$content = $params['subject'];
			
			if ( !strstr($content,'</head>') or !file_exists($rxa_slimbox['path'].'/'.$rxa_slimbox['name'].'.ini')
			 or ( strstr($content,'<script type="text/javascript" src="files/slimbox/slimbox.js"></script>') and strstr($content,'<link rel="stylesheet" href="files/slimbox/slimbox.css" type="text/css" media="screen" />') ) ) {
				return $content;
			}

   		// Einstellungen aus ini-Datei laden
			if (($lines = file($rxa_slimbox['path'].'/'.$rxa_slimbox['name'].'.ini')) === FALSE) {
				return $content;
			} else {
				$va = explode(',', trim($lines[0]));
				$allcats = trim($va[0]);
				$subcats = trim($va[1]);
				$slimbox_cats = array();
				$slimbox_cats = unserialize(trim($lines[1]));
			}

			// aktuellen Artikel ermitteln
			$artid = isset($_GET['article_id']) ? $_GET['article_id']+0 : 0;
			if ($artid==0) {
				$artid = $REX_ARTICLE->getValue('article_id')+0;
			}
			if ($artid==0) { $artid = $REX['START_ARTICLE_ID']; }

			if (!$artid) { return $content; }

			$article = OOArticle::getArticleById($artid);
			if (!$article) { return $content; }

			// aktuelle Kategorie ermitteln
			if ( in_array($rxa_slimbox['rexversion'], array('3.11')) ) {
				$acat = $article->getCategoryId();
			}
			if ( in_array($rxa_slimbox['rexversion'], array('32', '40', '41', '42')) ) {
				$cat = $article->getCategory();
				if ($cat) {
					$acat = $cat->getId();
				}
			}
			// Wenn keine Kategorie ermittelt wurde auf -1 setzen f�r Pr�fung in slimbox_check_cat, Pr�fung auf Artikel im Root
			if (!$acat) { $acat = -1; }

         // Array anlegen falls keine Kategorien ausgew�hlt wurden
			if (!is_array($slimbox_cats)){
				$slimbox_cats = array();
			}

			// Code f�r Slimbox im head-Bereich ausgeben
			if ( ($allcats==1) or (slimbox_check_cat($acat, $artid, $subcats, $slimbox_cats) == true) )
			{
				$rxa_slimbox['output'] = '	<!-- Addon Slimbox '.$REX['ADDON']['version'][$rxa_slimbox['name']].' -->'."\n";
				$rxa_slimbox['output'] .= '	<script type="text/javascript" src="files/slimbox/mootools.js"></script>'."\n";
				$rxa_slimbox['output'] .= '	<script type="text/javascript" src="files/slimbox/slimbox.js"></script>'."\n";
				$rxa_slimbox['output'] .= '	<link rel="stylesheet" href="files/slimbox/slimbox.css" type="text/css" media="screen" />'."\n";
				$content = str_replace('</head>', $rxa_slimbox['output'].'</head>', $content);
			}
			return $content;
		}

	}
?>