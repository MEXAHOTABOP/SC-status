<?php
	header('Content-type: image/png;');
	$name   = $_GET['name'];
    $jsonObj= json_decode(file_get_contents("http://gmt.star-conflict.com/pubapi/v1/userinfo.php?nickname={$name}"),true);
    //array(3)
    //{
        //["result"]=>string()
        //["code"]=>int()
        //["data"]=>
        //array(7)
        //{
            //["elo"]=>float()
            //["karma"]=>int()
            //["nickName"]=>string()
            //["prestigeBonus"]=>float()
            //["uid"]=>int()
            //["pvp"]=>
            //array(9)
            //{
                //["gamePlayed"]=>int()
                //["gameWin"]=>int()
                //["totalAssists"]=>int()
                //["totalBattleTime"]=>int()
                //["totalDeath"]=>int()
                //["totalDmgDone"]=>float()
                //["totalHealingDone"]=>float()
                //["totalKill"]=>int()
                //["totalVpDmgDone"]=>float()
            //}
        //["clan"]=>
            //array(2)
            //{
                //["name"]=>string()
                //["tag"]=>string()
            //}
        //}
    //}


    date_default_timezone_set("UTC");
    $force          = (int)($jsonObj['data']['prestigeBonus']*100);
    $time           = date("j.n.Y");
    $winrate        = round($jsonObj['data']['pvp']['gameWin']/($jsonObj['data']['pvp']['gamePlayed']-$jsonObj['data']['pvp']['gameWin']) ,2);
    $killDR         = round($jsonObj['data']['pvp']['totalKill']/$jsonObj['data']['pvp']['totalDeath'] ,2);
    $assistDR       = round($jsonObj['data']['pvp']['totalAssists']/$jsonObj['data']['pvp']['totalDeath'] ,2);
    $killPG         = round($jsonObj['data']['pvp']['totalKill']/$jsonObj['data']['pvp']['gamePlayed'] ,2);
    $supportPG      = round($jsonObj['data']['pvp']['totalAssists']/$jsonObj['data']['pvp']['gamePlayed'] ,2);
    $deathPG        = round($jsonObj['data']['pvp']['totalDeath']/$jsonObj['data']['pvp']['gamePlayed'] ,2);
    $damagePG       = round($jsonObj['data']['pvp']['totalDmgDone']/$jsonObj['data']['pvp']['gamePlayed']);
    $healPG         = round($jsonObj['data']['pvp']['totalHealingDone']/$jsonObj['data']['pvp']['gamePlayed']);
    
    $status = "{$time} {$name}[{$jsonObj['data'][clan][tag]}] 
в среднем: {$killPG} убийств, {$supportPG} помощи, {$deathPG} смертей
winrate {$winrate}, damage/game {$damagePG}, heal/game {$healPG}
kill/death {$killDR}, assists/death {$assistDR}";
    
    $font   = 'DejaVuSansMono.ttf';
    $w      = 400;
    $h      = 70;
    $img    = imagecreatetruecolor($w, $h);
    $white  = imagecolorallocatealpha($img, 255, 255, 255, 127);
    $black  = imagecolorallocatealpha($img, 0, 0, 0, 0);
    
    imagefill($img, 0, 0, $white);
    imagettftext ($img, 10, 0, 0, 12, $black, $font, $status);
    
    imagealphablending($img, false);
    imagesavealpha($img, true);
    imagepng ($img);
    imagedestroy($img);
    //print $status;
    
?>
