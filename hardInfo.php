<?php
function getMacLinux() {
	$result = explode("\n",`netstat -ie`);
	if(is_array($result)) {
		$hardInfo = array();
		$i = 0;
		foreach($result as $key => $line) {
			if($key > 0) {
				if($line <> "") {
					$macPos = strpos($line, "HWaddr");
					$ipPos = strpos($line, "inet addr:");
					$bacsPos = strpos($line, "Bcast:");
					$afterNameSpacePos = strpos($line, "   ");
					if($macPos !== false) {
						$hardInfo[$i]['name'] = substr($line, 0, $line - strlen(substr($line, $afterNameSpacePos)));
						$hardInfo[$i]['mac'] = strtolower(substr($line, $macPos+7, 17));
						$hardInfo[$i]['ip'] = $key;
						$i++;
					}elseif($bacsPos !== false && $key == $hardInfo[$i-1]['ip']+1) {
						$hardInfo[$i-1]['ip'] = substr($line, $ipPos+10, $line - strlen(substr($line, $bacsPos)) -2);
					}
				}
			}
		}
		return $hardInfo;
	} else {
		return "notfound";
	}
}

function getHDriveId(){
	$id = substr(`echo Max232 | sudo -S hdparm -i /dev/sda | grep -oE 'SerialNo=.*'`, 9);
	if(strlen($id) > 0){
		return $id;
	}else{
		return "notfound";
	}
}

echo getHDriveId();