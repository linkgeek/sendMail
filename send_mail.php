<?php

	$count = 0;
	while(true){
		$count++;
		echo $count ."\r\n";
		file_put_contents("./test_result.txt", $count ."\r\n",FILE_APPEND);
		if($count>=10){
			break;
		}
		sleep(3);
	}
	echo "done";