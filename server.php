<?php
class Server{
	public function serveRequest($action, $method) {
		switch($action) {
			case 'read':
				$this->getArticle($method);
				break;
			case 'save':
				$this->saveArticle($method);
				break;
			default:
				header('HTTP/1.1 404 Not Found');
		}
	}

	public function getArticle($method) {
		if ($method != 'GET') {
			header('HTTP/1.1 405 Method Not Allowed');
			header('Allow: GET');
			return;
		}
		$this->fibonacci(34);
		$phpinput = file_get_contents("php://memory");
		if( !$phpinput) {
			$article = fopen("Latest_plane_crash.html", "r") or die("Unable to open file!");
			$contents = fread($article,filesize("Latest_plane_crash.html"));
			$cached_article = fopen("php://memory", "w") or die("Unable to open memory");
			fwrite($cached_article, $contents);
			fclose($article);
			fclose($cached_article);
			echo $contents;
			return;
		}
		echo $phpinput;
	}

	public function fibonacci($n)
	{
		$fib = [0,1];
		for($i=1; $i<$n; $i++)
		{
			$fib[] = $fib[$i] + $fib[$i-1];
		}
		return $fib[$i-1];
	}

	public function saveArticle($method) {
		if ($method != 'POST') {

		}
		switch ($method){
			case 'POST':
				$article = fopen("Latest_plane_crash.html", "w") or die("Unable to open file!");
				$current_hash = md5_file('Latest_plane_crash.html');
				if ($current_hash != $_POST['hash']) {
					//TODO redirect here
					echo "you need to download your file again as it has been changed";
				}
				$contents = $_POST['contents'];
				fwrite($article, $contents);
				$cached_article = fopen("php://memory", "w") or die("Unable to open memory");
				fwrite($cached_article, $contents);
				fclose($article);
				fclose($cached_article);
				break;
			case 'GET':
				$article = fopen("Latest_plane_crash.html", "r") or die("Unable to open file!");
				$contents = fread($article,filesize("Latest_plane_crash.html"));
				$hash = md5_file('Latest_plane_crash.html');
				$obj = new stdClass();
				$obj->contents = $contents;
				$obj->hash = $hash;
				echo json_encode($obj);
				break;
			default:
				header('HTTP/1.1 405 Method Not Allowed');
				header('Allow: POST, GET');
				return;
		}
	}
}