<?php
// include_once('./scripts/php/languages/lang.en.php');
// $email = 'phoenis_rebirth@magical.universe.com';

// echo preg_replace('/{email}/', $email, $lang['REG_EMAIL_EX']);

$from['MATCH'] = 'bookings@futsal-time.com';
$type = 'MATCH';
$arenaName = 'Nuevo Campo';
$from = array($from[$type] => $arenaName);
var_dump($from);

echo '<BR><BR><BR>';

foreach ( $_SERVER as $k=>$v)
	echo "<BR>".$k."=>".$v;

echo '<BR><BR><BR>';

$var = json_decode('{"4":["2","3"],"5":["1","2"],"6":["1","2","3"]}', true);

print_r($var);
	
	
	
echo crypt('12345678', '$6$rounds=8888$'.'10'.'manager'.'$');
echo "<BR>";
echo crypt('12345678', '$6$rounds=8888$'.'11'.'manager'.'$');
echo "<BR>";
echo crypt('12345678', '$6$rounds=8888$'.'12'.'manager'.'$');
echo "<BR>";
echo crypt('12345678', '$6$rounds=8888$'.'13'.'manager'.'$');
echo "<BR>";


// $6$rounds=8888$10manager$FREnGf.odeEoxtRcWEcVW.vwVEJuCfdkB3H7XtdTHYhRP4ciOGWZPl3oJtDZPUTazKJWMcrQ7F.q3q3EW9i5t0
// $6$rounds=8888$11manager$GCoSb.hkpYgoMNJo/x.dAJbO38WgpG2FqcWrlXo/VdzC9lh7z/7kb9FNmMjeD9ZUQNA6nGh4nstYAbPLmyJdC.
// $6$rounds=8888$12manager$P44jz9VkatzZ.7JOXMulOEdKhOhNDDAcBBDZ2aNYMHSKsvF.NDBOgvuEdJ2pSZnW/DmTitjuLhU4y99w/Phaa0
// $6$rounds=8888$13manager$sZbbRJsqul2ImN4TsU.A/livAKvkyjd7mDg/Zzf1jkj0GQ6lbo7EztT6K7NstFIWY6E4i2AyUT7IpdNL/Kp4h1


// GREEK
// ======
// part_main_information.php
// misc.php


?>