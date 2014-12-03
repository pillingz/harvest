<?php

//sets
set_time_limit(0);
error_reporting(E_ALL);

//includes
include_once("config/config.php");
include_once("functions.php");

//constants and variables
$inactive_patterns = array(
	'DH' => array(
		'A keresett oldal nem található!',
	),
	'HASZNALTAUTO' => array(
		'Nincs ilyen kódszámú hirdetés a rendszerben!',
	),
	'INGATLANBAZAR' => array(
		'Az oldal nem található.',
	),
	'INGATLANCOM' => array(
		'A hirdetés jelenleg nem aktív.',
		'A keresett oldal nem található',
		'Nincs a keresésnek megfelelő ingatlan az adatbázisunkban.',
	),
	'INGATLANEGY' => array(
		'<div class="search_box"',
	),
	'INGATLANKA' => array(
		'<h1>Not Found</h1>',
		'<div class="gyorskereso">',
	),
	'INGATLANOK' => array(
		'<div class="container-404-image">',
	),
	'INGATLANTAJOLO' => array(
		'Az oldal nem található',
	),
	'JOFOGAS' => array(
		'<div id="list_container">',
	),
	'OC' => array(
		'A megbízás már nem aktuális, a keresett ingatlan nem található az adatbázisban.',
	),
	'OLX' => array(
		'A hirdetés nem aktív, hasonló hirdetés(eke)t találtunk itt:',
		'Az OLX.hu nem találja a keresett oldalt',
	),
);

$parserFunctions = array(
	//Ingatlanok
	'DH' => 'parser_ingatlan_dh',
	'INGATLANBAZAR' => 'parser_ingatlan_ingatlanbazar',
	'INGATLANCOM' => 'parser_ingatlan_ingatlancom',
	'INGATLANEGY' => 'parser_ingatlan_ingatlanegy',
	'INGATLANKA' => 'parser_ingatlan_ingatlanka',
	'INGATLANOK' => 'parser_ingatlan_ingatlanok',
	'INGATLANTAJOLO' => 'parser_ingatlan_ingatlantajolo',
	'JOFOGAS' => 'parser_ingatlan_jofogas',
	'OC' => 'parser_ingatlan_oc',
	'OLX' => 'parser_ingatlan_olx',
	//Járművek
	'HASZNALTAUTO' => 'parser_jarmu_hasznaltauto',
	'JOFOGAS_JARMU' => 'parser_jarmu_jofogas',
	'OLX_JARMU' => 'parser_jarmu_olx',
	//Apró
	'JOFOGAS_APRO' => 'parser_apro_jofogas',
	'OLX_APRO' => 'parser_apro_olx',
);

$categoryToTable = array(
	'Állás' => 'apro_hirdetesek_allas',
	'Háziállat' => 'apro_hirdetesek_allat',
	'Baba-mama' => 'apro_hirdetesek_baba',
	'Divat' => 'apro_hirdetesek_divat',
	'Gyűjtemény' => 'apro_hirdetesek_gyujtemeny',
	'Ingatlan' => 'apro_hirdetesek_ingatlan',
	'Ingyen' => 'apro_hirdetesek_ingyen',
	'Jármű' => 'apro_hirdetesek_jarmu',
	'Játék' => 'apro_hirdetesek_jatek',
	'Kert' => 'apro_hirdetesek_kert',
	'Könyv' => 'apro_hirdetesek_konyv',
	'Mezőgazdaság' => 'apro_hirdetesek_mezogazdasag',
	'Sport' => 'apro_hirdetesek_sport',
	'Szolgáltatás' => 'apro_hirdetesek_szolgaltatas',
	'Műszaki' => 'apro_hirdetesek_tech',
	'Zene, film' => 'apro_hirdetesek_zene',
);

//A $vehicleProperties értékeit a fronton lévő temp.php segítségével lehet kinyerni
$vehicleProperties = array(
	'Manuális klíma',
	'Automata klíma',
	'Digitális klíma',
	'Digitális kétzónás klíma',
	'Digitális többzónás klíma',
	'Lemeztető',
	'Vászontető',
	'Nyitható keménytető',
	'Harmonikatető',
	'Targatető',
	'Fix üvegtető',
	'Panoráma tető',
	'Fix napfénytető',
	'Nyitható napfénytető',
	'Elhúzható napfénytető',
	'Motoros napfénytető',
	'magyar okmányokkal',
	'külföldi okmányokkal',
	'okmányok nélkül',
	'érvényes okmányokkal',
	'lejárt okmányokkal',
	'forgalomból ideiglenesen kivont',
	'okmányok nélkül',
	'állítható felfüggesztés',
	'fedélzeti komputer',
	'állítható kormány',
	'sperr differenciálmű',
	'fűthető tükör',
	'sportfutómű',
	'centrálzár',
	'kerámia féktárcsák',
	'sportülések',
	'chiptuning',
	'könnyűfém felni',
	'szervokormány',
	'defektjavító készlet',
	'kormányváltó',
	'színezett üveg',
	'EDC (elektronikus lengéscsillapítás vezérlés)',
	'króm felni',
	'távolságtartó tempomat',
	'elektromos ablak elöl',
	'kulcsnélküli indítás',
	'tempomat',
	'elektromos ablak hátul',
	'részecskeszűrő',
	'tolóajtó',
	'elektromos tolótető',
	'riasztó',
	'tolótető (napfénytető)',
	'elektromos tükör',
	'sebességfüggő szervókormány',
	'vonóhorog',
	'full extra',
	'velúr kárpit',
	'faberakás',
	'állófűtés',
	'ajtószervó',
	'garázsajtó távirányító',
	'fűthető ablakmosó fúvókák',
	'allítható combtámasz',
	'középső kartámasz',
	'fűthető kormány',
	'állítható hátsó ülések',
	'masszírozós ülés',
	'fűthető szélvédő',
	'automatikus csomagtér-ajtó',
	'memóriás vezetőülés',
	'fűthető ülés',
	'automatikusan sötétedő belső tükör',
	'multifunkciós kormánykerék',
	'álló helyzeti klíma',
	'automatikusan sötétedő külső tükör',
	'plüss kárpit',
	'hűthető kartámasz',
	'deréktámasz',
	'pótkerék',
	'hűthető kesztyűtartó',
	'dönthető utasülések',
	'távolsági fényszóró asszisztens',
	'üléshűtés/szellőztetés',
	'elektromosan állítható fejtámlák',
	'tolatókamera',
	'bőr belső',
	'elektromosan behajtható külső tükrök',
	'tolatóradar',
	'műbőr-kárpit',
	'elektronikus futómű hangolás',
	'ülésmagasság állítás',
	'függönylégzsák',
	'LED fényszóró',
	'GPS nyomkövető',
	'gyalogos légzsák',
	'xenon fényszóró',
	'guminyomás-ellenőrző rendszer',
	'hátsó oldal légzsák',
	'ABS  (blokkolásgátló)',
	'hátsó fejtámlák',
	'kikapcsolható légzsák',
	'ADS (adaptív lengéscsillapító)',
	'holttér-figyelő rendszer',
	'oldallégzsák',
	'APS (parkolóradar)',
	'indításgátló (immobiliser)',
	'térdlégzsák',
	'ARD (automatikus távolságtartó)',
	'ISOFIX rendszer',
	'utasoldali légzsák',
	'ASR (kipörgésgátló)',
	'lejtmenet asszisztens',
	'vezetőoldali légzsák',
	'beépített gyerekülés',
	'MSR (motorféknyomaték szabályzás)',
	'bi-xenon fényszóró',
	'bukócső',
	'rablásgátló',
	'bukólámpa',
	'csomag rögzítő',
	'sávtartó rendszer',
	'éjjellátó asszisztens',
	'efekttűrő abroncsok',
	'sávváltó asszisztens',
	'fényszóró magasságállítás',
	'EBD/EBV (elektronikus fékerő-elosztó)',
	'sebességváltó zár',
	'fényszórómosó',
	'EDS (elektronikus differenciálzár)',
	'tábla-felismerő funkció',
	'kanyarkövető fényszóró',
	'esőszenzor',
	'visszagurulás-gátló',
	'kiegészítő fényszóró',
	'ESP (menetstabilizátor)',
	'ködlámpa',
	'fékasszisztens',
	'autótelefon',
	'bluetooth-os kihangosító',
	'gyári erősítő',
	'CD-s autórádió',
	'DVB tuner',
	'kormányra szerelhető távirányító',
	'DVD',
	'DVB-T tuner',
	'távirányító',
	'GPS (navigáció)',
	'erősítő kimenet',
	'tetőmonitor',
	'HIFI',
	'FM transzmitter',
	'2 hangszóró',
	'rádió',
	'HDMI bemenet',
	'4 hangszóró',
	'rádiós magnó',
	'iPhone/iPod csatlakozó',
	'5 hangszóró',
	'TV',
	'kihangosító',
	'6 hangszóró',
	'1 DIN',
	'memóriakártya-olvasó',
	'7 hangszóró',
	'2 DIN',
	'merevlemez',
	'8 hangszóró',
	'CD tár',
	'mikrofon bemenet',
	'9 hangszóró',
	'MP3 lejátszás',
	'tolatókamera bemenet',
	'10 hangszóró',
	'MP4 lejátszás',
	'USB csatlakozó',
	'11 hangszóró',
	'WMA lejátszás',
	'érintőkijelző',
	'12 hangszóró',
	'analóg TV tuner',
	'erősítő',
	'mélynyomó',
	'AUX csatlakozó',
	'fejtámlamonitor',
	'garanciális',
	'első tulajdonostól',
	'motorbeszámítás lehetséges',
	'amerikai modell',
	'frissen szervizelt',
	'mozgássérült',
	'azonnal elvihető',
	'garázsban tartott',
	'nem dohányzó',
	'bemutató jármű',
	'hölgy tulajdonostól',
	'rendszeresen karbantartott',
	'jobbkormányos',
	'keveset futott',
	'szervizkönyv',
	'rendelhető',
	'Magyarországon újonnan üzembe helyezett',
	'taxi',
	'autóbeszámítás lehetséges',
	'második tulajdonostól',
	'törzskönyv',
	'Alufelni',
	'ABS (blokkolásgátló)',
	'Centrálzár',
	'Klíma',
	'ASR (kipörgésgátló)',
	'Metálfény',
	'Tempomat',
	'Érvényes magyar forgalmi',
	'Elektromos ablak',
	'Garanciális',
	'Veterán',
);

//database connecting
$dbLink = dbConnect();

/*
//DEBUG START
$argv[1] = 'http://www.jofogas.hu/budapest/18__keruleta__elado_59_m2_es_panel_lakas_18565872.htm';
$argv[2] = 3;
$argv[3] = 'JOFOGAS';
$argv[4] = 'Eladó';
$argv[5] = 'Ingatlan';
$argv[6] = 'Lakás';
$argv[7] = '';
$argv[8] = '';
$argv[9] = 'Audi';
$argv[10] = 'A3';
$argv[11] = '';
//DEBUG END
*/

//get parameters
$row['url'] = $argv[1];
$row['id'] = $argv[2];
$row['forras'] = $argv[3];
$row['tipus'] = $argv[4];
$row['kategoria'] = $argv[5];
$row['alkategoria'] = $argv[6];
$row['varos'] = $argv[7];
$row['kerulet'] = $argv[8];
$row['param1'] = $argv[9];
$row['param2'] = $argv[10];
$row['param3'] = $argv[11];

$table = $categoryToTable[$row['kategoria']];
if(!$table) end_script($row['id'], 2);

//get HTML
$ch = curl_init($row['url']);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$html = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);
if(!$html || $error) end_script($row['id'], 2);

if($row['forras'] == 'JOFOGAS') $html = utf8_encode($html);

//get data from HTML and update record
if($row['forras'] == 'OLX' && $row['kategoria'] == 'Jármű') $key = 'OLX_JARMU';
elseif($row['forras'] == 'JOFOGAS' && $row['kategoria'] == 'Jármű') $key = 'JOFOGAS_JARMU';
elseif($row['forras'] == 'OLX' && $row['kategoria'] != 'Ingatlan' && $row['kategoria'] != 'Jármű') $key = 'OLX_APRO';
elseif($row['forras'] == 'JOFOGAS' && $row['kategoria'] != 'Ingatlan' && $row['kategoria'] != 'Jármű') $key = 'JOFOGAS_APRO';
else $key = $row['forras'];

$function = $parserFunctions[$key];
if($function){
	
	$response = $function($html);
	if($response){
		
		$temp = array(
			"hirdetesKod = '".$row['url']."'",
			"hirdetesForras = '".$row['forras']."'",
			"hirdetesTipus = '".$row['tipus']."'",
			"hirdetesDatum = NOW()",
			"hirdetesKategoria = '".$row['kategoria']."'",
			"hirdetesAlkategoria = '".$row['alkategoria']."'",
			"hirdetesMegye = '".(getCountyFromSettlement($row['varos']))."'",
			"hirdetesVaros = '".$row['varos']."'",
			"hirdetesKerulet = '".$row['kerulet']."'",
			"hirdetesSync = '0'",
		);
		
		if(!$response['hirdetesStatus']) $response['hirdetesStatus'] = 1; 
		
		foreach($response as $k => $v){ 
			if($v) $temp[] = $k." = '".(mysql_real_escape_string($v))."'";
		}
		
		$temp = implode(", ", $temp);
		
		$query = mysql_query("SELECT hirdetesId FROM ".$table." WHERE hirdetesKod = '".$row['url']."'");
		$check = mysql_fetch_assoc($query);
		if($check){
			mysql_query("UPDATE ".$table." SET ".$temp." WHERE hirdetesKod = '".$row['url']."'");
		}
		else{
			mysql_query("INSERT INTO ".$table." SET ".$temp);
		}
	}
}
else{
	
	end_script($row['id'], 2);
}

exit;


//---FUNCTIONS---
function end_script($id, $status) {
	
	mysql_query("UPDATE apro_scheduler SET status = '".$status."' WHERE id = '".$id."'");
	mysql_close($dbLink);
	exit;
}


function parser_ingatlan_dh($html) {
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['DH'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesParam1, szobaszám
	$temp = explode('>Szobák:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$response['hirdetesParam1'] = trim($temp[0]);
	}
	
	//hirdetesParam2, alapterület
	$temp = explode('>Méret:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$temp = trim($temp[0]);
		$response['hirdetesParam2'] = getSizeInterval($temp);
	}
	
	//hirdetesParam5, altípus
	if(ereg('>Ingatlan szerkezete:</div>', $html)) $param = '>Ingatlan szerkezete:</div>';
	if(ereg('>Ház típusa:</div>', $html)) $param = '>Ház típusa:</div>';
	if(ereg('>Telek típusa:</div>', $html)) $param = '>Telek típusa:</div>';
	$temp = explode($param, $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$response['hirdetesParam5'] = trim($temp[0]);
	}
	
	//hirdetesParam6, emelet
	$temp = explode('>Emelet:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$response['hirdetesParam6'] = trim($temp[0]);
	}
	
	//hirdetesParam7, tetőtéri
	//Nincs
	
	//hirdetesParam8, állapot
	$temp = explode('>Ingatlan állapota:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$response['hirdetesParam8'] = trim($temp[0]);
	}
	
	//hirdetesParam9, komfort
	//Nincs
	
	//hirdetesParam10, fűtés
	$temp = explode('>Fütés:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$response['hirdetesParam10'] = trim($temp[0]);
	}
	
	//hirdetesParam11, parkolás
	//Nincs
	
	//hirdetesParam12, kilátás
	$temp = explode('>Nézet:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$response['hirdetesParam12'] = trim($temp[0]);
	}
	
	//hirdetesParam13, lift
	$temp = explode('>Lift:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$response['hirdetesParam13'] = trim($temp[0]);
	}
	
	//hirdetesParam14, telekterület
	$temp = explode('>Telek mérete:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$temp = trim($temp[0]);
		$temp = explode('m', $temp);
		$response['hirdetesParam14'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	//hirdetesParam15, épület szintjei
	$temp = explode('>Belsö szintek száma:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$response['hirdetesParam15'] = trim($temp[0]);
	}
	
	//hirdetesParam16, pince
	$temp = explode('>Pince:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$response['hirdetesParam16'] = trim($temp[0]);
	}
	
	//hirdetesParam17, villany
	$temp = explode('>Villany:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$response['hirdetesParam17'] = trim($temp[0]);
	}
	
	//hirdetesParam18, víz
	$temp = explode('>Víz:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$response['hirdetesParam18'] = trim($temp[0]);
	}
	
	//hirdetesParam19, gáz
	$temp = explode('>Gáz:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$response['hirdetesParam19'] = trim($temp[0]);
	}
	
	//hirdetesParam20, csatorna
	//Nincs
	
	//hirdetesParam21, férőhelyek
	//Nincs
	
	//hirdetesText, leírás
	$temp = explode('<div class="datapage_news_boxs_list">', $html);
	if(count($temp) > 1){
		$temp = explode('itemprop="description">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesText'] = trim($temp);
	}
	
	//hirdetesAr, ár
	$temp = explode('<span itemprop="price">', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesAr'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	return $response; 
}


function parser_ingatlan_ingatlanbazar($html) {
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['INGATLANBAZAR'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesParam1, szobaszám
	$temp = explode('Szobák száma:</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesParam1'] += trim($temp[0]);
	}
	$temp = explode('Félszobák száma:</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesParam1'] += trim($temp[0]);
	}
	
	//hirdetesParam2, alapterület
	$temp = explode('Alapterület(m2):</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesParam2'] = trim($temp[0]);
		$response['hirdetesParam2'] = getSizeInterval($response['hirdetesParam2']);
	}
	
	//hirdetesAlkategoria
	$temp = explode('Hirdetés típusa:</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$temp = explode(',', $temp[0]);
		$temp = trim($temp[1]);
	}
	
	//hirdetesParam5, altípus
	$temp = explode($temp.' típusa:</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesParam5'] = trim($temp[0]);
	}
	
	//hirdetesParam6, emelet
	$temp = explode('Emelet:</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesParam6'] = trim($temp[0]);
	}
	
	//hirdetesParam7, tetőtéri
	$temp = false;
	if(ereg('<dt>Beépíthető tetőtér</dt>', $html)) $response['hirdetesParam7'] = 'beépíthető';
	if(ereg('<dt>Tetőtér beépített</dt>', $html)) $response['hirdetesParam7'] = 'beépített';
	if(ereg('<dt>Tetőtér beépíthető</dt>', $html)) $response['hirdetesParam7'] = 'beépített';
	
	//hirdetesParam8, állapot
	$temp = explode('Ingatlan állapot:</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesParam8'] = trim($temp[0]);
	}
	
	//hirdetesParam9, komfort
	//Nincs
	
	//hirdetesParam10, fűtés
	$temp = explode('Fűtés típusa:</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesParam10'] = trim($temp[0]);
	}
	
	//hirdetesParam11, parkolás
	$temp = explode('Parkolás:</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesParam11'] = trim($temp[0]);
	}
	
	//hirdetesParam12, kilátás
	$temp = explode('Kilátás:</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesParam12'] = trim($temp[0]);
	}
	
	//hirdetesParam13, lift
	$temp = false;
	if(ereg('<dt>Lift</dt>', $html)) $response['hirdetesParam13'] = 'van';
	
	//hirdetesParam14, telekterület
	$temp = explode('Telek terül.(m2):</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$temp = trim($temp[0]);
		$temp = explode('m', $temp);
		$response['hirdetesParam14'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	//hirdetesParam15, épület szintjei
	//Nincs
	
	//hirdetesParam16, pince
	$temp = false;
	if(ereg('<dt>Pince</dt>', $html)) $response['hirdetesParam16'] = 'van';
	
	//hirdetesParam17, villany
	$temp = false;
	if(ereg('<dt>Elektromos áram</dt>', $html)) $response['hirdetesParam17'] = 'van';
	
	//hirdetesParam18, víz
	$temp = false;
	if(ereg('<dt>Vezetékes víz</dt>', $html)) $response['hirdetesParam18'] = 'van';
	if(ereg('<dt>Víz telken belül</dt>', $html)) $response['hirdetesParam18'] = 'telken belül';
	
	//hirdetesParam19, gáz
	$temp = false;
	if(ereg('<dt>Gáz telken belül</dt>', $html)) $response['hirdetesParam19'] = 'telken belül';
	
	//hirdetesParam20, csatorna
	$temp = false;
	if(ereg('<dt>Csatorna</dt>', $html)) $response['hirdetesParam20'] = 'van';
	
	//hirdetesParam21, férőhelyek
	$temp = explode('Férőhelyek száma:</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesParam21'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	//hirdetesText, leírás
	$temp = explode('<div class="description-text">', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesText'] = trim($temp[0]);
		$response['hirdetesText'] = strip_tags($response['hirdetesText']);
	}
	
	//hirdetesAr, ár
	$temp = explode('Ár:</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesAr'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	return $response; 
}


function parser_ingatlan_ingatlancom($html) {
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['INGATLANCOM'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesParam1, szobaszám
	$temp = explode('<h1 class="importantInformation">', $html);
	if(count($temp) > 1){
		$temp = explode('</h1>', $temp[2]);
		$response['hirdetesParam1'] = trim($temp[0]);
	}
	
	//hirdetesParam2, alapterület
	$temp = explode('<h1 class="importantInformation">', $html);
	if(count($temp) > 1){
		$temp = explode('</h1>', $temp[1]);
		$temp = explode(' ', $temp[0]);
		$response['hirdetesParam2'] = trim($temp[0]);
		$response['hirdetesParam2'] = getSizeInterval($response['hirdetesParam2']);
	}
	
	//hirdetesParam5, altípus
	$temp = explode('<th>Típus</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam5'] = trim($temp[0]);
	}
	
	//hirdetesParam6, emelet
	$temp = explode('<th>Emelet</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam6'] = trim($temp[0]);
	}
	
	//hirdetesParam7, tetőtéri
	$temp = explode('<th>Tetőtér</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam7'] = trim($temp[0]);
	}
	
	//hirdetesParam8, állapot
	$temp = explode('<th>Ingatlan állapota</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam8'] = trim($temp[0]);
	}
	
	//hirdetesParam9, komfort
	$temp = explode('<th>Komfort</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam9'] = trim($temp[0]);
	}
	
	//hirdetesParam10, fűtés
	$temp = explode('<th>Fűtés</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam10'] = trim($temp[0]);
	}
	
	//hirdetesParam11, parkolás
	$temp = explode('<th>Parkolás</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam11'] = trim($temp[0]);
	}
	
	//hirdetesParam12, kilátás
	$temp = explode('<th>Kilátás</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam12'] = trim($temp[0]);
	}
	
	//hirdetesParam13, lift
	$temp = explode('<th>Lift</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam13'] = trim($temp[0]);
	}
	
	//hirdetesParam14, telekterület
	$temp = explode('<th>Telekterület</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$temp = trim($temp[0]);
		$temp = explode('m', $temp);
		$response['hirdetesParam14'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	//hirdetesParam15, épület szintjei
	$temp = explode('<th>Épület szintjei</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam15'] = trim($temp[0]);
	}
	
	//hirdetesParam16, pince
	$temp = explode('<th>Pince</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam16'] = trim($temp[0]);
	}
	
	//hirdetesParam17, villany
	$temp = explode('<th>Villany</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam17'] = trim($temp[0]);
	}
	
	//hirdetesParam18, víz
	$temp = explode('<th>Víz</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam18'] = trim($temp[0]);
	}
	
	//hirdetesParam19, gáz
	$temp = explode('<th>Gáz</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam19'] = trim($temp[0]);
	}
	
	//hirdetesParam20, csatorna
	$temp = explode('<th>Csatorna</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam20'] = trim($temp[0]);
	}
	
	//hirdetesParam21, férőhelyek
	$temp = explode('<th>Parkolóhelyek száma</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam21'] = trim($temp[0]);
	}
	
	//hirdetesText, leírás
	$temp = explode('<div id="commentText">', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = trim($temp[0]);
		$response['hirdetesText'] = strip_tags($temp);
	}
	
	//hirdetesAr, ár
	$temp = explode("<h1 class='importantInformation'>", $html);
	if(count($temp) > 1){
		$temp = explode('</h1>', $temp[1]);
		if(ereg('M Ft', $temp[0])){
			$temp = explode(' ', $temp[0]);
			$temp = $temp[0] * 1000000;
		}
		else{
			$temp = explode(' ', $temp[0]);
			$temp = $temp[0];
		}
		$response['hirdetesAr'] = $temp;
	}
	
	return $response; 
}


function parser_ingatlan_ingatlanegy($html) {
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['INGATLANEGY'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesParam1, szobaszám
	$temp = explode('<span class="key">Szobák száma:</span>', $html);
	if(count($temp) > 1){
		$temp = explode('<span class="val">', $temp[1]);
		$temp = explode('</span>', $temp[1]);
		$response['hirdetesParam1'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	//hirdetesParam2, alapterület
	$temp = explode('<span class="key">Alapterület:</span>', $html);
	if(count($temp) > 1){
		$temp = explode('<span class="val">', $temp[1]);
		$temp = explode('</span>', $temp[1]);
		$temp = explode(' ', $temp[0]);
		$response['hirdetesParam2'] = trim($temp[0]);
		$response['hirdetesParam2'] = getSizeInterval($response['hirdetesParam2']);
	}
	
	//hirdetesParam5, altípus
	$temp = explode('<span class="key">Típus:</span>', $html);
	if(count($temp) > 1){
		$temp = explode('<span class="val">', $temp[1]);
		$temp = explode('</span>', $temp[1]);
		$response['hirdetesParam5'] = trim($temp[0]);
	}
	
	//hirdetesParam6, emelet
	$temp = explode('<span class="key">Emelet:</span>', $html);
	if(count($temp) > 1){
		$temp = explode('<span class="val">', $temp[1]);
		$temp = explode('</span>', $temp[1]);
		$response['hirdetesParam6'] = trim($temp[0]);
	}
	
	//hirdetesParam7, tetőtéri
	//Nincs
	
	//hirdetesParam8, állapot
	$temp = explode('<span class="key">Állapot:</span>', $html);
	if(count($temp) > 1){
		$temp = explode('<span class="val">', $temp[1]);
		$temp = explode('</span>', $temp[1]);
		$response['hirdetesParam8'] = trim($temp[0]);
	}
	
	//hirdetesParam9, komfort
	//Nincs
	
	//hirdetesParam10, fűtés
	$temp = explode('<span class="key">Fűtés:</span>', $html);
	if(count($temp) > 1){
		$temp = explode('<span class="val">', $temp[1]);
		$temp = explode('</span>', $temp[1]);
		$response['hirdetesParam10'] = trim($temp[0]);
	}
	
	//hirdetesParam11, parkolás
	//Nincs
	
	//hirdetesParam12, kilátás
	//Nincs
	
	//hirdetesParam13, lift
	//Nincs
	
	//hirdetesParam14, telekterület
	$temp = explode('<span class="key">Telekterület:</span>', $html);
	if(count($temp) > 1){
		$temp = explode('<span class="val">', $temp[1]);
		$temp = explode('</span>', $temp[1]);
		$temp = trim($temp[0]);
		$temp = explode('m', $temp);
		$response['hirdetesParam14'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	//hirdetesParam15, épület szintjei
	//Nincs
	
	//hirdetesParam16, pince
	//Nincs
	
	//hirdetesParam17, villany
	//Nincs
	
	//hirdetesParam18, víz
	//Nincs
	
	//hirdetesParam19, gáz
	//Nincs
	
	//hirdetesParam20, csatorna
	//Nincs
	
	//hirdetesParam21, férőhelyek
	//Nincs
	
	//hirdetesText, leírás
	$temp = explode('<h2>Ingatlan bemutatása</h2>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="text">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesText'] = trim($temp);
	}
	
	//hirdetesAr, ár
	$temp = explode('<div class="pprice">', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		if(ereg('millió', $temp[0])){
			$temp = explode(' ', $temp[0]);
			$temp = $temp[0] * 1000000;
		}
		if(ereg('ezer', $temp[0])){
			$temp = explode(' ', $temp[0]);
			$temp = $temp[0] * 1000;
		}
		$response['hirdetesAr'] = $temp;
	}
	
	return $response; 
}


function parser_ingatlan_ingatlanka($html) {
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['INGATLANKA'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesParam1, szobaszám
	$temp = explode('<td class="adatlaposzlop1">Szobák száma:</td>', $html);
	if(count($temp) > 1){
		$temp = explode('<td class="adatlaposzlop2">', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$temp = trim($temp[0]);
		$temp = explode('+', $temp);
		$response['hirdetesParam1'] = trim($temp[0]) + trim($temp[1]);
	}
	
	//hirdetesParam2, alapterület
	$temp = explode('<td class="adatlaposzlop1">Alapterület:</td>', $html);
	if(count($temp) > 1){
		$temp = explode('<td class="adatlaposzlop2">', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$temp = explode(' ', $temp[0]);
		$response['hirdetesParam2'] = trim($temp[0]);
		$response['hirdetesParam2'] = getSizeInterval($response['hirdetesParam2']);
	}
	
	//hirdetesParam5, altípus
	//Nincs
	
	//hirdetesParam6, emelet
	//Nincs
	
	//hirdetesParam7, tetőtéri
	//Nincs
	
	//hirdetesParam8, állapot
	$temp = explode('<td class="adatlaposzlop1">Állapota:</td>', $html);
	if(count($temp) > 1){
		$temp = explode('<td class="adatlaposzlop2">', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam8'] = trim($temp[0]);
	}
	
	//hirdetesParam9, komfort
	//Nincs
	
	//hirdetesParam10, fűtés
	$temp = explode('<td class="adatlaposzlop1">Fűtés:</td>', $html);
	if(count($temp) > 1){
		$temp = explode('<td class="adatlaposzlop2">', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam10'] = trim($temp[0]);
	}
	
	//hirdetesParam11, parkolás
	$temp = explode('<td class="adatlaposzlop1">Parkolás:</td>', $html);
	if(count($temp) > 1){
		$temp = explode('<td class="adatlaposzlop2">', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam11'] = trim($temp[0]);
	}
	
	//hirdetesParam12, kilátás
	$temp = explode('<td class="adatlaposzlop1">Kilátás:</td>', $html);
	if(count($temp) > 1){
		$temp = explode('<td class="adatlaposzlop2">', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam12'] = trim($temp[0]);
	}
	
	//hirdetesParam13, lift
	//Nincs
	
	//hirdetesParam14, telekterület
	$temp = explode('<td class="adatlaposzlop1">Telekterület:</td>', $html);
	if(count($temp) > 1){
		$temp = explode('<td class="adatlaposzlop2">', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$temp = trim($temp[0]);
		$temp = explode('m', $temp);
		$response['hirdetesParam14'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	//hirdetesParam15, épület szintjei
	$temp = explode('<td class="adatlaposzlop1">Szintek száma:</td>', $html);
	if(count($temp) > 1){
		$temp = explode('<td class="adatlaposzlop2">', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam12'] = trim($temp[0]);
	}
	
	//extrák
	$temp = explode('<td class="adatlaposzlop1">Egyéb extrák:</td>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$extrak = trim($temp[0]);
	}
	
	//hirdetesParam16, pince
	if(ereg('pince', $extrak)) $response['hirdetesParam16'] = 'van';
	
	//hirdetesParam17, villany
	if(ereg('villany', $extrak)) $response['hirdetesParam17'] = 'van';
	
	//hirdetesParam18, víz
	if(ereg('víz', $extrak)) $response['hirdetesParam18'] = 'van';
	
	//hirdetesParam19, gáz
	if(ereg('gáz', $extrak)) $response['hirdetesParam19'] = 'van';
	
	//hirdetesParam20, csatorna
	if(ereg('csatorna', $extrak)) $response['hirdetesParam20'] = 'van';
	
	//hirdetesParam21, férőhelyek
	//Nincs
	
	//hirdetesText, leírás
	$temp = explode('<div class="adatlapleiras">', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesText'] = trim($temp);
	}
	
	//hirdetesAr, ár
	$temp = explode('<td class="adatlaposzlop1">Irányár:</td>', $html);
	if(count($temp) > 1){
		$temp = explode('<td class="adatlaposzlop2">', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesAr'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	return $response; 
}


function parser_ingatlan_ingatlanok($html) {
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['INGATLANOK'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesParam1, szobaszám
	$temp = explode('<div class="label">Egész szobák:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesParam1'] += trim($temp[0]);
	}
	$temp = explode('<div class="label">Fél szobák:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesParam1'] += trim($temp[0]);
	}
	
	//hirdetesParam2, alapterület
	$temp = explode('<h1 class="title">', $html);
	if(count($temp) > 1){
		$temp = explode('</h1>', $temp[1]);
		$temp = explode(',', $temp[0]);
		$temp = preg_replace('/[^0-9]/', '', $temp[count($temp)-1]);
		$response['hirdetesParam2'] = getSizeInterval($temp);
	}
	
	//hirdetesParam5, altípus (sorrend: besorolás, jelleg, építési mód, építőanyag)
	$param = '';
	if(ereg('<div class="label">Besorolás:</div>', $html)) $param = 'Besorolás';
	elseif(ereg('<div class="label">Jelleg:</div>', $html)) $param = 'Jelleg';
	elseif(ereg('<div class="label">Építési mód:</div>', $html)) $param = 'Építési mód';
	elseif(ereg('<div class="label">Építőanyag:</div>', $html)) $param = 'Építőanyag';
	if($param){
		$temp = explode('<div class="label">'.$param.':</div>', $html);
		if(count($temp) > 1){
			$temp = explode('<div class="value">', $temp[1]);
			$temp = explode('</div>', $temp[1]);
			$response['hirdetesParam5'] = trim($temp[0]);
		}
	}
		
	//hirdetesParam6, emelet
	$temp = explode('<div class="label">Emelet:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesParam6'] = trim($temp[0]);
	}
	
	//hirdetesParam7, tetőtéri
	$temp = explode('<div class="label">Beépíthető tetőtér:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesParam7'] = trim($temp[0]);
	}
	
	//hirdetesParam8, állapot
	$temp = explode('<div class="label">Ingatlan állapota:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesParam8'] = trim($temp[0]);
	}
	
	//hirdetesParam9, komfort
	$temp = explode('<div class="label">Komfort fokozat:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesParam9'] = trim($temp[0]);
	}
	
	//hirdetesParam10, fűtés
	$temp = explode('<div class="label">Fűtés jellege:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesParam10'] = trim($temp[0]);
	}
	
	//hirdetesParam11, parkolás
	//Nincs
	
	//hirdetesParam12, kilátás
	//Nincs
	
	//hirdetesParam13, lift
	$temp = explode('<div class="label">Lift:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesParam13'] = trim($temp[0]);
	}
	
	//hirdetesParam14, telekterület
	$temp = explode('<div class="label">Telekterület:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$temp = trim($temp[0]);
		$temp = explode('m', $temp);
		$response['hirdetesParam14'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	//hirdetesParam15, épület szintjei
	$temp = explode('<div class="label">Szintek:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesParam15'] = trim($temp[0]);
	}
	
	//hirdetesParam16, pince
	$temp = explode('<div class="label">Pince:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesParam16'] = trim($temp[0]);
	}
	
	//hirdetesParam17, villany
	$temp = explode('<div class="label">Villany:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesParam17'] = trim($temp[0]);
	}
	
	//hirdetesParam18, víz
	$temp = explode('<div class="label">Víz:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesParam18'] = trim($temp[0]);
	}
	
	//hirdetesParam19, gáz
	$temp = explode('<div class="label">Gáz:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesParam19'] = trim($temp[0]);
	}
	
	//hirdetesParam20, csatorna
	$temp = explode('<div class="label">Csatorna:</div>', $html);
	if(count($temp) > 1){
		$temp = explode('<div class="value">', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesParam20'] = trim($temp[0]);
	}
	
	//hirdetesParam21, férőhelyek
	//Nincs
	
	//hirdetesText, leírás
	$temp = explode('<div class="tabs-content">', $html);
	if(count($temp) > 1){
		$temp = explode('<div', $temp[1]);
		$temp = explode('>', $temp[1]);
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesText'] = trim($temp[0]);
		$response['hirdetesText'] = strip_tags($response['hirdetesText']);
	}
	
	//hirdetesAr, ár
	$temp = explode('<h1 class="title">', $html);
	if(count($temp) > 1){
		$temp = explode('</h1>', $temp[1]);
		$temp = explode(',', $temp[0]);
		$temp = preg_replace('/[^0-9]/', '', $temp[count($temp)-2]);
		$response['hirdetesAr'] = $temp;
	}
	
	return $response; 
}


function parser_ingatlan_ingatlantajolo($html) {
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['INGATLANTAJOLO'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesParam1, szobaszám
	$temp = explode('<dt>Szobák száma</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$temp = explode(' ', $temp[0]);
		$response['hirdetesParam1'] = trim($temp[0]) + trim($temp[1]);
	}
	
	//hirdetesParam2, alapterület
	$temp = explode('<dt>Alapterület</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$temp = explode(' ', $temp[0]);
		$response['hirdetesParam2'] = trim($temp[0]);
		$response['hirdetesParam2'] = getSizeInterval($response['hirdetesParam2']);
	}
	
	//hirdetesParam5, altípus
	$temp = explode('<dt>Típus</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesParam5'] = trim($temp[0]);
	}
		
	//hirdetesParam6, emelet
	$temp = explode('<dt>Emelet</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesParam6'] = trim($temp[0]);
	}
	
	//hirdetesParam7, tetőtéri
	//Nincs
	
	//hirdetesParam8, állapot
	$temp = explode('<dt>Állapot</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesParam8'] = trim($temp[0]);
	}
	
	//hirdetesParam9, komfort
	//Nincs
	
	//hirdetesParam10, fűtés
	$temp = explode('<dt>Fűtés</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$response['hirdetesParam10'] = trim($temp[0]);
	}
	
	//hirdetesParam11, parkolás
	//Nincs
	
	//hirdetesParam12, kilátás
	//Nincs
	
	//hirdetesParam13, lift
	//Nincs
	
	//hirdetesParam14, telekterület
	$temp = explode('<dt>Telekterület</dt>', $html);
	if(count($temp) > 1){
		$temp = explode('<dd>', $temp[1]);
		$temp = explode('</dd>', $temp[1]);
		$temp = trim($temp[0]);
		$temp = explode('m', $temp);
		$response['hirdetesParam14'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	//hirdetesParam15, épület szintjei
	//Nincs
	
	//hirdetesParam16, pince
	//Nincs
	
	//hirdetesParam17, villany
	//Nincs
	
	//hirdetesParam18, víz
	//Nincs
	
	//hirdetesParam19, gáz
	//Nincs
	
	//hirdetesParam20, csatorna
	//Nincs
	
	//hirdetesParam21, férőhelyek
	//Nincs
	
	//hirdetesText, leírás
	$temp = explode('<section id="description">', $html);
	if(count($temp) > 1){
		$temp = explode('</header>', $temp[1]);
		$temp = explode('</section>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesText'] = trim($temp);
	}
	
	//hirdetesAr, ár
	$temp = explode('<span class="tag price">', $html);
	if(count($temp) > 1){
		$temp = explode('</span>', $temp[1]);
		$response['hirdetesAr'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	return $response; 
}


function parser_ingatlan_jofogas($html) {
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['JOFOGAS'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesParam1, szobaszám
	$temp = explode('>Szobák száma:', $html);
	if(count($temp) > 1){
		$temp = explode('<strong>', $temp[1]);
		$temp = explode('</strong>', $temp[1]);
		$response['hirdetesParam1'] = trim($temp[0]);
	}
	
	//hirdetesParam2, alapterület
	$temp = explode('>Méret:', $html);
	if(count($temp) > 1){
		$temp = explode('<strong>', $temp[1]);
		$temp = explode('</strong>', $temp[1]);
		$temp = explode(' ', $temp[0]);
		$response['hirdetesParam2'] = trim($temp[0]);
		$response['hirdetesParam2'] = getSizeInterval($response['hirdetesParam2']);
	}
	
	//hirdetesParam5, altípus
	//Nincs
	
	//hirdetesParam6, emelet
	//Nincs
	
	//hirdetesParam7, tetőtéri
	//Nincs
	
	
	//hirdetesParam8, állapot
	//Nincs
	
	
	//hirdetesParam9, komfort
	//Nincs
	
	
	//hirdetesParam10, fűtés
	//Nincs
	
	
	//hirdetesParam11, parkolás
	//Nincs
	
	
	//hirdetesParam12, kilátás
	//Nincs
	
	
	//hirdetesParam13, lift
	//Nincs
	
	
	//hirdetesParam14, telekterület
	//Nincs
	
	//hirdetesParam15, épület szintjei
	//Nincs
	
	//hirdetesParam16, pince
	//Nincs
	
	//hirdetesParam17, villany
	//Nincs
	
	//hirdetesParam18, víz
	//Nincs
	
	//hirdetesParam19, gáz
	//Nincs
	
	//hirdetesParam20, csatorna
	//Nincs
	
	//hirdetesParam21, férőhelyek
	//Nincs
	
	//hirdetesText, leírás
	$temp = explode('<div class="hirdetes_body">', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesText'] = strip_tags($temp[0]);
		$response['hirdetesText'] = trim($response['hirdetesText']);
	}
	
	//hirdetesAr, ár
	$temp = explode('<span class="adview_menu_price ad_price"', $html);
	if(count($temp) > 1){
		$temp = explode('Ár:', $temp[1]);
		$temp = explode('</span>', $temp[1]);
		$response['hirdetesAr'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	return $response; 
}


function parser_ingatlan_olx($html) {
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['OLX'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesParam1, szobaszám
	$temp = explode('Szobák száma:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$temp = explode(' ', $temp);
		$response['hirdetesParam1'] += trim($temp[0]);
	}
	$temp = explode('Félszobák száma :', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$temp = explode(' ', $temp);
		$response['hirdetesParam1'] += trim($temp[0]);
	}
	
	//hirdetesParam2, alapterület
	$temp = explode('Alapterület:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$temp = explode(' ', $temp);
		$response['hirdetesParam2'] = trim($temp[0]);
		$response['hirdetesParam2'] = getSizeInterval($response['hirdetesParam2']);
	}
	
	//hirdetesParam5, altípus
	$temp = explode('Ingatlan típusa:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesParam5'] = trim($temp);
	}
	
	//hirdetesParam6, emelet
	$temp = explode('Emelet:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesParam6'] = trim($temp);
	}
	
	//hirdetesParam7, tetőtéri
	//Nincs
	
	//hirdetesParam8, állapot
	$temp = explode('Állapot:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesParam8'] = trim($temp);
	}
	
	//hirdetesParam9, komfort
	//Nincs
	
	//hirdetesParam10, fűtés
	$temp = explode('Fűtés:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesParam10'] = trim($temp);
	}
	
	//hirdetesParam11, parkolás
	$temp = explode('Parkolás:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesParam11'] = trim($temp);
	}
	
	//hirdetesParam12, kilátás
	$temp = explode('Kilátás:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesParam12'] = trim($temp);
	}
	
	//hirdetesParam13, lift
	$temp = explode('Lift:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesParam13'] = trim($temp);
	}
	
	//hirdetesParam14, telekterület
	$temp = explode('Telekterület:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$temp = trim($temp);
		$temp = explode('m', $temp);
		$response['hirdetesParam14'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	//hirdetesParam15, épület szintjei
	$temp = explode('Szintek száma: ', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesParam15'] = trim($temp);
	}
	
	//hirdetesParam16, pince
	$temp = explode('Pince: ', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesParam16'] = trim($temp);
	}
	
	//hirdetesParam17, villany
	//Nincs
	
	//hirdetesParam18, víz
	//Nincs
	
	//hirdetesParam19, gáz
	//Nincs
	
	//hirdetesParam20, csatorna
	//Nincs
	
	//hirdetesParam21, férőhelyek
	//Nincs
	
	//hirdetesText, leírás
	$temp = explode('<div class="clr" id="textContent">', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesText'] = trim($temp);
	}
	
	//hirdetesAr, ár
	$temp = explode('<strong class="xxxx-large margintop7 block arranged">', $html);
	if(count($temp) > 1){
		$temp = explode('</strong>', $temp[1]);
		$response['hirdetesAr'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	return $response; 
}


function parser_ingatlan_oc($html) {
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['OC'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesParam1, szobaszám
	$temp = explode('<th>Szobák</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$temp = explode(' ', $temp[0]);
		$response['hirdetesParam1'] = trim($temp[0]);
	}
	
	//hirdetesParam2, alapterület
	$temp = explode('<th>Lakásméret (nettó)</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$temp = explode(' ', $temp[0]);
		$response['hirdetesParam2'] = trim($temp[0]);
		$response['hirdetesParam2'] = getSizeInterval($response['hirdetesParam2']);
	}
	
	//hirdetesParam5, altípus
	$temp = explode('<th>Típus</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam5'] = trim($temp[0]);
	}
	
	//hirdetesParam6, emelet
	$temp = explode('<th>Emelet</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam6'] = trim($temp[0]);
	}
	
	//hirdetesParam7, tetőtéri
	//Nincs
	
	//hirdetesParam8, állapot
	$temp = explode('<th>Állapot</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam8'] = trim($temp[0]);
	}
	
	//hirdetesParam9, komfort
	//Nincs
	
	//hirdetesParam10, fűtés
	$temp = explode('<th>Fűtés</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam10'] = trim($temp[0]);
	}
	
	//hirdetesParam11, parkolás
	//Nincs
	
	//hirdetesParam12, kilátás
	//Nincs
	
	//hirdetesParam13, lift
	//Nincs
	
	//hirdetesParam14, telekterület
	$temp = explode('<th>Telekméret</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$temp = trim($temp[0]);
		$temp = explode('m', $temp);
		$response['hirdetesParam14'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	//hirdetesParam15, épület szintjei
	$temp = explode('<th>lakáson belüli szintszám</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesParam15'] = trim($temp[0]);
	}
	
	//hirdetesParam16, pince
	//Nincs
	
	//hirdetesParam17, villany
	//Nincs
	
	//hirdetesParam18, víz
	//Nincs
	
	//hirdetesParam19, gáz
	//Nincs
	
	//hirdetesParam20, csatorna
	//Nincs
	
	//hirdetesParam21, férőhelyek
	//Nincs
	
	//hirdetesText, leírás
	$temp = explode('<a name="bemutatas"></a>', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesText'] = trim($temp);
	}
	
	//hirdetesAr, ár
	$temp = explode('<th>Ingatlan ár</th>', $html);
	if(count($temp) > 1){
		$temp = explode('<td>', $temp[1]);
		$temp = explode('</td>', $temp[1]);
		$response['hirdetesAr'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	return $response; 
}


function parser_jarmu_hasznaltauto($html) {
	
	global $vehicleProperties;
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['HASZNALTAUTO'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesParam1, típus
	//Nem kell, mert a lista adja.
	
	//hirdetesParam2, modell
	//Nem kell, mert a lista adja.
	
	//hirdetesParam3, üzemanyag
	$temp = explode('Üzemanyag:', $html);
	if(count($temp) > 1){
		$temp = explode('<strong>', $temp[1]);
		$temp = explode('</strong>', $temp[1]);
		$response['hirdetesParam3'] = trim($temp[0]);
	}
	
	//hirdetesParam4, évjárat
	$temp = explode('Évjárat:', $html);
	if(count($temp) > 1){
		$temp = explode('<strong>', $temp[1]);
		$temp = explode('</strong>', $temp[1]);
		$temp = explode('/', $temp[0]);
		$response['hirdetesParam4'] = trim($temp[0]);
	}
	
	//hirdetesParam5, hengerűrtartalom
	$temp = explode('Hengerűrtartalom:', $html);
	if(count($temp) > 1){
		$temp = explode('<strong>', $temp[1]);
		$temp = explode('</strong>', $temp[1]);
		$temp = explode(' ', $temp[0]);
		$response['hirdetesParam5'] = trim($temp[0]);
	}
	
	//hirdetesParam6, futott km
	$temp = explode('Futott km:', $html);
	if(count($temp) > 1){
		$temp = explode('<strong>', $temp[1]);
		$temp = explode('</strong>', $temp[1]);
		$response['hirdetesParam6'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	//hirdetesParam7, kivitel
	$temp = explode('Kivitel:', $html);
	if(count($temp) > 1){
		$temp = explode('<strong>', $temp[1]);
		$temp = explode('</strong>', $temp[1]);
		$response['hirdetesParam7'] = trim($temp[0]);
	}
	
	//hirdetesParam8, állapot
	$temp = explode('Állapot:', $html);
	if(count($temp) > 1){
		$temp = explode('<strong>', $temp[1]);
		$temp = explode('</strong>', $temp[1]);
		$response['hirdetesParam8'] = trim($temp[0]);
	}
	
	//hirdetesText, leírás
	$temp = explode('<p class="alcim">Leírás</p>', $html);
	if(count($temp) > 1){
		$temp = explode('>', $temp[1]);
		$temp = explode('<', $temp[1]);
		$response['hirdetesText'] = trim($temp[0]);
		$response['hirdetesText'] = strip_tags($response['hirdetesText']);
	}
	
	//hirdetesHash, hash
	foreach($vehicleProperties as $k => $v){
		if(eregi($v, $html)) $hirdetesHash[] = $v;
	}
	if($hirdetesHash) $response['hirdetesHash'] = '#'.implode('#', $hirdetesHash).'#';
	
	return $response; 
}


function parser_jarmu_jofogas($html) {
	
	global $vehicleProperties;
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['JOFOGAS'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesParam1, típus
	//Nem kell, mert a lista adja.
	
	//hirdetesParam2, modell
	//Nem kell, mert a lista adja.
	
	//hirdetesParam3, üzemanyag
	$temp = explode('Üzemanyag:', $html);
	if(count($temp) > 1){
		$temp = explode('<strong>', $temp[1]);
		$temp = explode('</strong>', $temp[1]);
		$response['hirdetesParam3'] = trim($temp[0]);
	}
	
	//hirdetesParam4, évjárat
	$temp = explode('Gyártási év:', $html);
	if(count($temp) > 1){
		$temp = explode('<strong>', $temp[1]);
		$temp = explode('</strong>', $temp[1]);
		$temp = explode('/', $temp[0]);
		$response['hirdetesParam4'] = trim($temp[0]);
	}
	
	//hirdetesParam5, hengerűrtartalom
	//Nincs
	
	//hirdetesParam6, futott km
	$temp = explode('Futott km:', $html);
	if(count($temp) > 1){
		$temp = explode('<strong>', $temp[1]);
		$temp = explode('</strong>', $temp[1]);
		$response['hirdetesParam6'] = preg_replace('/[^0-9]/', '', $temp[0]);
	}
	
	//hirdetesParam7, kivitel
	//Nincs
	
	//hirdetesParam8, állapot
	//Nincs
	
	//hirdetesText, leírás
	$temp = explode('<div class="hirdetes_body">', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesText'] = strip_tags($temp[0]);
		$response['hirdetesText'] = trim($response['hirdetesText']);
	}
	
	//hirdetesHash, hash
	foreach($vehicleProperties as $k => $v){
		if(eregi($v, $html)) $hirdetesHash[] = $v;
	}
	if($hirdetesHash) $response['hirdetesHash'] = '#'.implode('#', $hirdetesHash).'#';
	
	return $response; 
}


function parser_jarmu_olx($html) {
	
	global $vehicleProperties;
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['OLX'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesParam1, típus
	//Nem kell, mert a lista adja.
	
	//hirdetesParam2, modell
	//Nem kell, mert a lista adja.
	
	//hirdetesParam3, üzemanyag
	$temp = explode('Üzemanyag:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesParam3'] = trim($temp);
	}
	
	//hirdetesParam4, évjárat
	$temp = explode('Gyártási év:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesParam4'] = trim($temp);
	}
	
	//hirdetesParam5, hengerűrtartalom
	//Nincs
	
	//hirdetesParam6, futott km
	$temp = explode('Futott:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesParam6'] = preg_replace('/[^0-9]/', '', $temp);
	}
	
	//hirdetesParam7, kivitel
	$temp = explode('Kivitel:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesParam7'] = trim($temp);
	}
	
	//hirdetesParam8, állapot
	$temp = explode('Állapot:', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesParam8'] = trim($temp);
	}
	
	//hirdetesText, leírás
	$temp = explode('<div class="clr" id="textContent">', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesText'] = trim($temp);
	}
	
	//hirdetesHash, hash
	foreach($vehicleProperties as $k => $v){
		if(eregi($v, $html)) $hirdetesHash[] = $v;
	}
	if($hirdetesHash) $response['hirdetesHash'] = '#'.implode('#', $hirdetesHash).'#';
	
	return $response; 
}


function parser_apro_jofogas($html) {
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['JOFOGAS'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesText, leírás
	$temp = explode('<div class="hirdetes_body">', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$response['hirdetesText'] = strip_tags($temp[0]);
		$response['hirdetesText'] = trim($response['hirdetesText']);
	}
	
	return $response; 
}


function parser_apro_olx($html) {
	
	$response = array();
	
	//hirdetesStatus, státusz
	foreach($inactive_patterns['OLX'] as $pattern){
		if(ereg($pattern, $html)){ 
			$response['hirdetesStatus'] = 0;
			return $response;
		}
	}
	
	//hirdetesText, leírás
	$temp = explode('<div class="clr" id="textContent">', $html);
	if(count($temp) > 1){
		$temp = explode('</div>', $temp[1]);
		$temp = strip_tags($temp[0]);
		$response['hirdetesText'] = trim($temp);
	}
	
	return $response; 
}

?>