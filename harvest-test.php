<?php

if($_GET['token'] != 'harvest-test') exit;

/*
//---DH--- (OK)
$script = 'harvest-dh-ingatlan.php';
$argv[1] = 'http://dh.hu/elado-ingatlan/uzlethelyiseg/hajdu-bihar-megye/30-40-m2?autoscroll=1&page=1';
$argv[2] = 'DH';
$argv[3] = 'Eladó';
$argv[4] = 'Ingatlan';
$argv[5] = 'Iroda, üzlethelyiség';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '30-40';
$argv[10] = '';
$argv[11] = '1';

//---INGATLANBAZAR--- (OK)
$script = 'harvest-ingatlanbazar-ingatlan.php';
$argv[1] = 'http://www.ingatlanbazar.hu/HU/ingatlan-Somogy-megye-Lakas-Elado-7-10-15/0?t[19][min]=90&t[19][max]=100&t[19][id]=19&displayAdvanced=1';
$argv[2] = 'INGATLANBAZAR';
$argv[3] = 'Eladó';
$argv[4] = 'Ingatlan';
$argv[5] = 'Lakás';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '90-100';
$argv[10] = '';
$argv[11] = '12';

//---INGATLANCOM--- (OK)
$script = 'harvest-ingatlancom-ingatlan.php';
$argv[1] = 'http://ingatlan.com/lista/kiado+lakas+t:Budapest+175-200-m2+xi-ker?page=1';
$argv[2] = 'INGATLANCOM';
$argv[3] = 'Kiadó';
$argv[4] = 'Ingatlan';
$argv[5] = 'Lakás';
$argv[6] = 'Budapest';
$argv[7] = '11';
$argv[8] = '';
$argv[9] = '175-200';
$argv[10] = '';
$argv[11] = '24';

//---INGATLANEGY--- (OK)
$script = 'harvest-ingatlanegy-ingatlan.php';
$argv[1] = 'http://ingatlanegy.hu/kereses/SZ/elado-uzlethelyiseg/t:125;150/lp1/';
$argv[2] = 'INGATLANEGY';
$argv[3] = 'Eladó';
$argv[4] = 'Ingatlan';
$argv[5] = 'Iroda, üzlethelyiség';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '125-150';
$argv[10] = '';
$argv[11] = '40';

//---INGATLANKA--- (OK)
$script = 'harvest-ingatlanka-ingatlan.php';
$argv[1] = 'http://www.ingatlanka.hu/ingatlankereses.php?r_kategoria=1&r_tipus=2&r_terulet=5&r_nmtol=70&r_nmig=80&Input=KeresÃ©s&rkereses=i&oldal=1';
$argv[2] = 'INGATLANKA';
$argv[3] = 'Eladó';
$argv[4] = 'Ingatlan';
$argv[5] = 'Lakás';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '70-80';
$argv[10] = '';
$argv[11] = '51';

//---INGATLANOK--- (OK)
$script = 'harvest-ingatlanok-ingatlan.php';
$argv[1] = 'http://ingatlanok.hu/kiado/lakas/hajdu-bihar-megye:35nm-tol;50nm-ig?record=0&num=50';
$argv[2] = 'INGATLANOK';
$argv[3] = 'Kiadó';
$argv[4] = 'Ingatlan';
$argv[5] = 'Lakás';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '35-50';
$argv[10] = '';
$argv[11] = '50';
*/

/*
//---INGATLANTAJOLO--- (Átalakult az oldal, újra kell generálni az URL-eket és a harvest-et is)
$script = 'harvest-ingatlantajolo-ingatlan.php';
$argv[1] = 'http://www.ingatlantajolo.hu/baranya-megye+kiado+lakas+alapterulet-30-100?page=2';
$argv[2] = 'INGATLANTAJOLO';
$argv[3] = 'Kiadó';
$argv[4] = 'Ingatlan';
$argv[5] = 'Lakás';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '30-100';
$argv[10] = '';
$argv[11] = '69';
*/

/*
//---JOFOGAS-INGATLAN--- (OK)
$script = 'harvest-jofogas-ingatlan.php';
$argv[1] = 'http://www.jofogas.hu/jasz-nagykun-szolnok/lakas?st=u&ros=3&roe=3&o=1';
$argv[2] = 'JOFOGAS';
$argv[3] = 'Kiadó';
$argv[4] = 'Ingatlan';
$argv[5] = 'Lakás';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '3';
$argv[9] = '';
$argv[10] = '';
$argv[11] = '77';

//---OC--- (OK)
$script = 'harvest-oc-ingatlan.php';
$argv[1] = 'http://www.oc.hu/ingatlan/kereses?megye[]=KomEszt&ertekesites=elado&jelleg[]=lakoingatlan&alapmin=100&alapmax=125&s=KeresÃ©s&databaseListLimit=20&databaseListStart=4';
$argv[2] = 'OC';
$argv[3] = 'Eladó';
$argv[4] = 'Ingatlan';
$argv[5] = 'Lakás';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '100-125';
$argv[10] = '';
$argv[11] = '84';

//---OLX-INGATLAN--- (OK)
$script = 'harvest-olx-ingatlan.php';
$argv[1] = 'http://olx.hu/ingatlan/haz/elado/szabolcs-szatmar-bereg/?&search[filter_float_area%3Afrom]=125&search[filter_float_area%3Ato]=150&page=1';
$argv[2] = 'OLX';
$argv[3] = 'Eladó';
$argv[4] = 'Ingatlan';
$argv[5] = 'Ház';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '125-150';
$argv[10] = '';
$argv[11] = '95';

//---HASZNALTAUTO--- (OK)
$script = 'harvest-hasznaltauto-jarmu.php';
$argv[1] = 'http://www.hasznaltauto.hu/auto/mercedes-benz/e_220/page2';
$argv[2] = 'HASZNALTAUTO';
$argv[3] = 'Eladó';
$argv[4] = 'Jármű';
$argv[5] = 'Autó';
$argv[6] = '';
$argv[7] = '';
$argv[8] = 'Mercedes-benz';
$argv[9] = 'E 220';
$argv[10] = '';
$argv[11] = '245';

//---JOFOGAS-JARMU--- (OK)
$script = 'harvest-jofogas-jarmu.php';
$argv[1] = 'http://www.jofogas.hu/magyarorszag/auto/peugeot/607/benzin?o=1';
$argv[2] = 'JOFOGAS';
$argv[3] = 'Eladó';
$argv[4] = 'Jármű';
$argv[5] = 'Autó';
$argv[6] = '';
$argv[7] = '';
$argv[8] = 'Peugeot';
$argv[9] = '607';
$argv[10] = '';
$argv[11] = '260';

//---OLX-JARMU--- (OK)
$script = 'harvest-olx-jarmu.php';
$argv[1] = 'http://olx.hu/jarmu/auto/mercedes/clk-200/?search[filter_enum_petrol][0]=petrol';
$argv[2] = 'OLX';
$argv[3] = 'Eladó';
$argv[4] = 'Jármű';
$argv[5] = 'Autó';
$argv[6] = '';
$argv[7] = '';
$argv[8] = 'Mercedes';
$argv[9] = 'CLK 200';
$argv[10] = 'Benzin';
$argv[11] = '262';

//---JOFOGAS-ALLAS--- (OK)
$script = 'harvest-jofogas-allas.php';
$argv[1] = 'http://www.jofogas.hu/magyarorszag/allas-munka/adminisztracio-irodai-munka?o=2';
$argv[2] = 'JOFOGAS';
$argv[3] = '';
$argv[4] = 'Állás';
$argv[5] = 'Adminisztráció / Irodai munka';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '';
$argv[10] = '';
$argv[11] = '265';

//---OLX-ALLAS--- (OK)
$script = 'harvest-olx-allas.php';
$argv[1] = 'http://olx.hu/allas/adminisztracio-irodai-munka/?page=2';
$argv[2] = 'OLX';
$argv[3] = '';
$argv[4] = 'Állás';
$argv[5] = 'Adminisztráció / Irodai munka';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '';
$argv[10] = '';
$argv[11] = '267';

//---PROFESSION--- (OK)
$script = 'harvest-profession-allas.php';
$argv[1] = 'http://www.profession.hu/allasok/adminisztracio-irodai-munka/2,1';
$argv[2] = 'PROFESSION';
$argv[3] = '';
$argv[4] = 'Állás';
$argv[5] = 'Adminisztráció / Irodai munka';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '';
$argv[10] = '';
$argv[11] = '269';

//---JOFOGAS-ALLAT--- (OK)
$script = 'harvest-jofogas-allat.php';
$argv[1] = 'http://www.jofogas.hu/magyarorszag/haziallat?o=2';
$argv[2] = 'JOFOGAS';
$argv[3] = 'Háziállat';
$argv[4] = 'Háziállat';
$argv[5] = '';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '';
$argv[10] = '';
$argv[11] = '271';

//---JOFOGAS-BABA--- (OK)
$script = 'harvest-jofogas-baba.php';
$argv[1] = 'http://www.jofogas.hu/magyarorszag/baba-mama?o=2';
$argv[2] = 'JOFOGAS';
$argv[3] = 'Baba-mama';
$argv[4] = 'Baba-mama';
$argv[5] = '';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '';
$argv[10] = '';
$argv[11] = '273';

//---JOFOGAS-DIVAT--- (OK)
$script = 'harvest-jofogas-divat.php';
$argv[1] = 'http://www.jofogas.hu/magyarorszag/ruhak-kiegeszitok?o=2';
$argv[2] = 'JOFOGAS';
$argv[3] = 'Divat';
$argv[4] = 'Divat';
$argv[5] = '';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '';
$argv[10] = '';
$argv[11] = '277';

//---OLX-ALLAT--- (OK)
$script = 'harvest-olx-allat.php';
$argv[1] = 'http://olx.hu/haziallat/?page=2';
$argv[2] = 'OLX';
$argv[3] = 'Háziállat';
$argv[4] = 'Háziállat';
$argv[5] = '';
$argv[6] = '';
$argv[7] = '';
$argv[8] = '';
$argv[9] = '';
$argv[10] = '';
$argv[11] = '301';
*/

include_once($script);

?>