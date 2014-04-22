<?php
	$path = "../cgi-bin/tmp";
	$files = scandir($path);
	$log = fopen("./log.log", "a");
	foreach ( $files as $file ) {
		$del = false;
		fwrite($log, '\n\nFile name: '.$file.'\n');								echo '<br><br>File name: '.$file.'<br>';
		if ( substr($file, 0, 1) == '.' || substr($file, 0, 5) != 'sess_' )
		{
			fwrite($log, '\t\tIgnoring: \''.$path.'/'.$file.'\'\n');			echo '__________Ignoring: \''.$path.'/'.$file.'\'<br>';
			continue;		
		}
		
		$handle = fopen($path.'/'.$file,'r');
		if ($handle) {
			while ( ($buffer = fgets($handle)) !== false )
			{
				fwrite($log, '\t\t'.$buffer.'\n');								echo '__________'.$buffer.'<br>';
				$vals = explode(';', $buffer);
				foreach ( $vals as $v )
				{
					if ( substr($v, 0, 2) == 'bd' )
					{
						$t = substr($v, 5);
						fwrite($log, '\t\t\t\t'.((time()-$t)).'\n');	echo '____________________'.((time()-$t)).' = '.((time()-$t)/3600).' hours'.'<br>';
						if ( time()-$t > 604800 )
							$del = true;
					}
				}
			}
		}
		fclose($handle);
		
		if ( $del ) 
		{
			unlink($path.'/'.$file);
			fwrite($log, '\t\tDeleting: \''.$path.'/'.$file.'\'\n');			echo '__________Deleting: \''.$path.'/'.$file.'\'<br>';
		}
		else
		{
			fwrite($log, '\t\tIgnoring: \''.$path.'/'.$file.'\'\n');			echo '__________Ignoring: \''.$path.'/'.$file.'\'<br>';
		}
	}
	fclose($log);
?>