<?php
class terminalcity24Class extends paysystemClass {
	public $_paysys = 'terminal_city24';
	private $_accessip = array(
		'62.149.15.210',
		'94.45.58.194',
		'46.182.81.50',//офис Скарбниця
	);
    private $_LOGIN         = 'City24';
	private $_PASSWORD      = 'TukruEcD';
	public $_request        = null;
    private $payTimestamp = null;



	public function __construct($_request) {
		$this->logRequest($_request);
		if ( !in_array($_SERVER['REMOTE_ADDR'], $this->_accessip)  ) {
            $this->output(300, array('comment' => ' Получен запрос с неразрешенного IP-адреса'));
		}
        if ( empty(trim($_request)) ) {
            $this->output(300, array('comment' => ' Запрос отсутствует'));
        }

        libxml_use_internal_errors(true);
        $this->_request = new SimpleXMLElement($_request, LIBXML_NOERROR|LIBXML_ERR_NONE|LIBXML_ERR_FATAL);

        if (!libxml_get_errors()) {
             $this->output(300, array('comment' => ' Неверный формат XML-запроса'));
        }

		if ( !$this->checkSign() ) {
            $this->output(300, array('comment' => 'Неверный логин или пароль'));
		}

        if( empty($this->_request->account) || empty($this->_request->additionalParameter1) || empty($this->_request->command) ){
            $this->output(300, array('comment' => 'Неверный тип запроса'));
        }

        $commandName = 'command_'.$this->_request->command;
        if ( method_exists($this, $commandName ) ) {
            call_user_func( array($this, $commandName ) );
        }

        $this->output(300, array('comment' => 'Неверный тип запроса'));

    }
	
	protected function checkSign() {
        if((string)$this->_request->login == $this->_LOGIN && (string)$this->_request->password == $this->_PASSWORD) return true;
        else return false;
	}
	
	public function output($error, $data = null, $exit = true) {
        header('Content-Type: text/xml');
        ob_start();
        $response = '<?xml version="1.0" encoding="utf-8" ?>';
        $response.= '	<commandResponse>';
        $response.= '		<account>'.(!empty($data['account']) ? $data['account'] : '').'</account>';
        $response.= '		<extTransactionID>'.(!empty($data['ext_transaction_id']) ? $data['ext_transaction_id'] : '').'</extTransactionID>';
        $response.= '		<result>'.$error.'</result>';

        if(is_array($data['fields']) && count($data['fields']) > 0) {
            $response .= '<fields>';
            $i = 1;
            if(isset($data['fields']['sum_max'])) {
                $response .= '<field'.$i.' name="Максимальная сумма платежа">'.$data['fields']['sum_max'].'</field'.$i.'>';
                $i++;
            }
            if(isset($data['fields']['sum_min'])) {
                $response .= '<field'.$i.' name="Минимальная сумма платежа">'.$data['fields']['sum_min'].'</field'.$i.'>';
                $i++;
            }
            if(isset($data['fields']['izdnum']) && !empty($data['fields']['izdnum'])) {
                $response .= '<field'.$i.' name="IzdNumber">'.$data['fields']['izdnum'].'</field'.$i.'>';
                $i++;
            }
            $response .= '</fields>';
        }
        $response .= '<comment>'.(!empty($data['comment']) ? $data['comment'] : '').'</comment>';
        $response .= '</commandResponse>';
        $this->logRequest($response);
		echo $response;
		ob_flush();
		flush();
		if ($exit) {
			exit(0);
		}
	}

	public function command_check() {
        if(!preg_match('/^\d{11}$/', (string) $this->_request->account) || !preg_match('/^\d{12}$/', (string) $this->_request->additionalParameter1))
            $this->output(4);
        $id = (string) $this->_request->account;
        $cs = (string) $this->_request->additionalParameter1;


        if ( !$this->checkIdCs($id, $cs) ) {
            $this->output(79, array('comment' => 'Невірно введено ID або CS'));
        }

		$dogovor = $this->getDogovorById($id);
		if ( !$dogovor ) {
            $this->output(5, array('comment' => 'Невірно введено ID '.$id.', немає даних по заборгованності'));
		}
        $dog_numb = $this->dogNumbFromId($id);
		$dateToday = date('d.m.Y');

		$min_sum = $dogovor->minimalPayment();
		$max_sum = $dogovor->countTotalSumm($dateToday);

		if ( !$max_sum ) {
            $this->output(79, array('comment' => 'Станом на сьогоднішній день ('.date("d.m.Y").') по ID '.$id.' заборгованість відсутня'));
		}
		
		$this->output(0, array(
                'account'   => $dog_numb,
                'fields'        => array(
			        'sum_max'	=> $max_sum,
			        'sum_min'	=> $min_sum,
                    'izdnum'    => substr($id, 0, 3).'-'.substr($id, 3),
		            ),
                )
        );
	}
	
	public function command_pay() {

        if(!preg_match('/^\d{11}$/', (string) $this->_request->account) || !preg_match('/^\d{12}$/', (string) $this->_request->additionalParameter1))
            $this->output(4);
        $id = (string) $this->_request->account;
        $cs = (string) $this->_request->additionalParameter1;
        $phone = (string) $this->_request->tel_number;

        if(empty($this->_request->payTimestamp) || !preg_match('/^(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})$/', (string) $this->_request->payTimestamp, $matches) ){
            $this->output(300, array('comment' => 'Неверная дата'));
        }

        $this->payTimestamp = strtotime($matches[1].'-'.$matches[2].'-'.$matches[3].' '.$matches[4].':'.$matches[5].':'.$matches[6]);

        if ( !$this->checkIdCs($id, $cs) ) {
            $this->output(5, array('comment' => 'Невірно введено ID або CS'));
        }

        $pay_sum = number_format(((string)$this->_request->amount/100), 2, '.', '');
		$this->_apprcode = (string) $this->_request->transactionID;

        $dog_numb = $this->dogNumbFromId($id);

        $invoice = $this->findInvoiceByAppcode($this->_apprcode);
        if ( !empty($invoice) ) {
            $this->output(0, array(
                    'ext_transaction_id'    => $invoice->i_id,
                    'account'   => $dog_numb,
                    'fields'        => array(
                    ),
                )
            );
		}

        $dogovor = $this->getDogovorById($id);
		if ( !$dogovor ) {
            $this->output(5, array('comment' => 'Невірно введено ID '.$id.', немає даних по заборгованності'));
		}
        $dog_numb = $this->dogNumbFromId($id);
        $dateToday = date('Y-m-d H:i:s', $this->payTimestamp);
		$min_sum = $dogovor->minimalPayment();
		$max_sum = $dogovor->countTotalSumm($dateToday);

        if ( !$max_sum ) {
            $this->output(5, array('comment' => 'Станом на сьогоднішній день ('.date("d.m.Y").') по ID '.$id.' заборгованість відсутня'));
        }

        if ( !($min_sum <= $pay_sum) ) {
            $this->output(300, array('comment' => 'Сумма оплаты меньше минимальной'));
        }

        if ( !($pay_sum  <= $max_sum) ) {
            $this->output(300, array('comment' => 'Сумма оплаты больше максимальной'));
        }

        $state = 'prepaid';
		$pay_params = $dogovor->makePayment($pay_sum, $dateToday, $this->_paysys, (string) $this->_request->terminalId, $state, $this->_apprcode);

		if ( !$pay_params ) {
			$this->output(300);
		}
		$this->sendInvoiceTo1C($pay_sum, $pay_params, $dogovor, $this->dogNumbFromId($id), $dateToday, $state);

        $this->output(0, array(
			'ext_transaction_id'    => $pay_params['invoice'],
			'account'				=> $dog_numb,
			'fields'				=> array(),
		));
    }

}
?>
