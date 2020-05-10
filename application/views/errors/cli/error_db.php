<!-- 
	References that helped in the development of this application:
		https://codeigniter.com/user_guide/general/errors.html
		https://lucidar.me/en/codeigniter-3-1-9/how-to-create-a-custom-404-error-page-with-code-ignitor/
-->
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

echo "\nDatabase error: ",
	$heading,
	"\n\n",
	$message,
	"\n\n";