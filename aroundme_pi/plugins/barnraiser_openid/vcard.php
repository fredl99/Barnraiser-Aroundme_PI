<?php

// -----------------------------------------------------------------------
// This file is part of AROUNDMe
// 
// Copyright (C) 2003-2008 Barnraiser
// http://www.barnraiser.org/
// info@barnraiser.org
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; see the file COPYING.txt.  If not, see
// <http://www.gnu.org/licenses/>
// -----------------------------------------------------------------------

/*
$vcard = <<<EOF
begin:vcard
fn:Drs. Vos-Vis Dieuwer
n:Vos-Vis;Dieuwer;;Drs
adr:;Molenstraat 9;Wageningen;Gelderland;6701 DM;Nederland
tel:+31-317-416738
version:3.0
end:vcard
EOF;
*/
include '../../core/config/aroundme_core.config.php';
include '../../core/class/Storage.class.php';
define("AM_DATA_PATH", dirname(__FILE__) . '/../../' . $core_config['data']['dir']);

$card_transforms = array();
$card_transforms['fullname'] = 'FN';
$card_transforms['address'] = 'ADR';
$card_transforms['dob'] = 'BDAY';


$am_core = new Storage($core_config);

$identity = $am_core->getData(AM_DATA_PATH . 'identity.data.php', 1);


$vcard = "BEGIN:vcard\n";
$vcard .= "VERSION:3.0\n";

foreach($card_transforms as $key => $val) {
	if (isset($identity['level'][$key]) && $identity['level'][$key] == 0) {
		if (isset($identity[$key])) {
			if (!empty($identity[$key])) {
				$vcard .= $val . ":" . strip_tags($identity[$key]) . "\n";
			}
		}
	}
}

$vcard .= "URL:" . $core_config['openid_account'] . "\n";
$vcard .= "END:vcard\n";

header("Content-type: text/directory");
header("Content-Disposition: attachment; filename=vcard.vcf");
header("Pragma: public");
print $vcard;

?>

