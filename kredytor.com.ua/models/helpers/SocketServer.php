<?php

class SocketServer {
	/*
     * Стек действий для отправки клиенту в текущем запросе.
     */
    static private $actions;
	static private $sockets_path = 'protected/sockets/';
   
    /*
     * Основная функция сервера.
     * Запускает на выполнение переданное клиентом действие.
     */
    static function Run() {
		$files_sock = glob(self::$sockets_path.'*');
        if ( is_array($files_sock) ) {
			foreach ( $files_sock as $sock) {
				if (filesize($sock)> 2048) {
				   unlink($sock);
				}
			}
		}
        $action = 'action'.$_POST['action'];
        if (is_callable('self::'.$action)) {
            self::$action();
            self::Send();
        }
    }
   
    /*
     * Эта функция пишет действие в сокеты.
     * Если передан параметр $self, то исключает указанный в этом параметре сокет.
     */
    static function AddToSock($action, $params = '', $self =null) {
        foreach (glob(self::$sockets_path.'*') as $sock) {
            if ( $self && strpos($sock, $self) !== false) {
               continue;
            }
            $f = fopen($sock,'a+b');
			if ( !$f ) continue;
            flock($f, LOCK_EX);
			$response = array(
				'action'	=> $action,
				'params'	=> $params,
			);
            fwrite($f, json_encode($response)."\r\n");
            fclose($f);
        }
    }
   
    /*
     * Эта функция добавляет действие в стек для отправки в текущем запросе.
     */
    static function AddToSend($action, $params = '') {
		$response = array(
			'action'	=> $action,
			'params'	=> $params,
		);
        self::$actions[] = json_encode($response);
    }
   
    /*
     * Отправка стека действий на выполнение клиенту.
     */
    static function Send() {
        if (self::$actions) {
			$response = array('actions'=>self::$actions);
			exit(json_encode($response));
            //exit('{actions:['.implode(', ', self::$actions).']}');
        }
    }
   
    /*
     * Действие.
     * Соединение с сервером.
     * Создает сокет и отправляет его идентификатор клиенту.
     */
    static function actionConnect() {
        $sock = md5(microtime().rand(1, 1000));
        fclose(fopen(self::$sockets_path.$sock, 'a+b'));
		chmod(self::$sockets_path.$sock, 0777);
        //self::AddToSock('Print', array('message'=>'Clientconnected.'), $sock);
        self::AddToSend('Connect', array('sock'=>$sock));
    }
   
    /*
     * Действие.
     * Отсоединение от сервера.
     * Удаляет сокет.
     */
    static function actionDisconnect() {
        $sock = $_POST['sock'];
        unlink(self::$sockets_path.$sock);
       // self::AddToSock('Print', array('message'=>'Client disconnected.'));
        self::AddToSend('Disconnect');
    }
   
    /*
     * Действие.
     * Отправляет введенные данные всем клиентам.
     */
    static function actionSend() {
        $sock = $_POST['sock'];
        $data = htmlspecialchars(trim($_POST['data']), ENT_QUOTES);
        if (strlen($data)) {
           self::AddToSock('Print', array('message'=>$data), $sock);
           self::AddToSend('Print', array('message'=>$data));
        }
    }
   
    /*
     * Действие.
     * Слушает сокет до момента когда в нем появятся данные или же до истечения таймаута.
     */
    static function actionRead() {
        $sock = $_POST['sock'];
        $time = time();
        while ((time() - $time) < 9) {
            if ($data =file_get_contents(self::$sockets_path.$sock)) {
               $f =fopen(self::$sockets_path.$sock, 'r+b');
			   if ( !$f ) continue;
               flock($f, LOCK_EX);
               ftruncate($f, 0);
               fwrite($f, '');
               fclose($f);
               $data = trim($data, "\r\n");
               foreach (explode("\r\n", $data) as $action) {
                   self::$actions[] = $action;
                }
               self::Send();
            }
			sleep(1);
        }
    }
	
	static function notifyManagers($message) {		
		if ( empty($message) ) return false;
		if ( !is_array($message) )
			$message = array('message'=>$message);
		self::AddToSock('Print', $message);
		return true;
	}
}

?>
