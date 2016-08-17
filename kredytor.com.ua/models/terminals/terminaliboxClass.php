<?php
class terminaliboxClass extends paysystemClass {
	public $_paysys = 'terminal_i';
	private $_accessip = array(
		'213.160.149.229',
		'185.46.150.122',
        '213.160.154.26',
		'185.46.148.218',//офис Скарбниця
		'46.182.81.50',//офис Скарбниця
		'213.160.149.230', //тестовый
	);
	public $_request        = null;
    private $_account_data  = null;
    private $_txn_date = null;



	public function __construct($_request) {
		$this->logRequest($_request);
		if ( !in_array($_SERVER['REMOTE_ADDR'], $this->_accessip)  ) {
            $this->output(2, array('comment' => ' Получен запрос с неразрешенного IP-адреса'));
		}
        if ( empty($_request) ) {
            $this->output(2, array('comment' => ' Запрос отсутствует'));
        }

        $this->_request = $_request;

        if( empty($this->_request['account']) || empty($this->_request['command']) ){
            $this->output(2, array('comment' => 'Неверный тип запроса'));
        }

        $this->_account_data = explode('|', $this->_request['account']);
        if ( !is_array($this->_account_data) || count($this->_account_data) != 3) {
            $this->output(300, array('comment' => 'Неверный тип запроса'));
        }

        if ( empty($this->_request['txn_id']) ) {
            $this->output(2, array('comment' => 'В запиті відсутній або невірний унікальний ідентифікатор'));
        }

        $commandName = 'command_'.$this->_request['command'];
        if ( method_exists($this, $commandName ) ) {
            call_user_func( array($this, $commandName ) );
        }

        $this->output(2, array('comment' => 'Неверный тип запроса'));

    }
	
	public function output($error, $data = null, $exit = true) {
        header('Content-Type: text/xml');
        ob_start();
        $response = '<?xml version="1.0" encoding="utf-8" ?>';
        $response.= '	<response>';
        $response.= '		<ibox_txn_id>'.$this->_request['txn_id'].'</ibox_txn_id>';
        $response.= '		<prv_txn>'.(!empty($data['ext_transaction_id']) ? $data['ext_transaction_id'] : '').'</prv_txn>';
        $response.= '		<prv_txn_date>'.(!empty($data['prv_txn_date']) ? date('Y-m-d H:i:s', $data['prv_txn_date']) : '').'</prv_txn_date>';
        $response.= '		<sum>'.(!empty($data['sum_pay']) ? number_format($data['sum_pay'], 2, '.', '') : '').'</sum>';
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
        $response .= '</response>';
        $this->logRequest($response);
		echo $response;
		ob_flush();
		flush();
		if ($exit) {
			exit(0);
		}
	}

	public function command_check() {
        if(!preg_match('/^\d{11}$/', $this->_account_data[0]) || !preg_match('/^\d{12}$/', $this->_account_data[1]))
            $this->output(2);
        $id = $this->_account_data[0];
        $cs = $this->_account_data[1];

        if ( !$this->checkIdCs($id, $cs) ) {
            $this->output(2, array('comment' => 'Невірно введено ID або CS'));
        }

		$dogovor = $this->getDogovorById($id);
		if ( !$dogovor ) {
            $this->output(2, array('comment' => 'Невірно введено ID '.$id.', немає даних по заборгованності'));
		}
        $dog_numb = $this->dogNumbFromId($id);
		$dateToday = date('d.m.Y');

		$min_sum = $dogovor->minimalPayment();
		$max_sum = $dogovor->countTotalSumm($dateToday);

		if ( !$max_sum ) {
            $this->output(2, array('comment' => 'Станом на сьогоднішній день ('.date("d.m.Y").') по ID '.$id.' заборгованість відсутня'));
		}
		
		$this->output(0, array(
                'fields'        => array(
			        'sum_max'	=> $max_sum,
			        'sum_min'	=> $min_sum,
                    'izdnum'    => $dog_numb,
		            ),
                )
        );
	}
	
	public function command_pay() {
        if(!preg_match('/^\d{11}$/', $this->_account_data[0]) || !preg_match('/^\d{12}$/', $this->_account_data[1]))
            $this->output(2);
        $id = $this->_account_data[0];
        $cs = $this->_account_data[1];
        $phone = $this->_account_data[2];

        if(empty($this->_request['txn_date']) || !preg_match('/^(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})$/', $this->_request['txn_date'], $matches) ){
            $this->output(2, array('comment' => 'Неверная дата'));
        }
        $this->_txn_date = strtotime($matches[1].'-'.$matches[2].'-'.$matches[3].' '.$matches[4].':'.$matches[5].':'.$matches[6]);

        if ( !$this->checkIdCs($id, $cs) ) {
            $this->output(2, array('comment' => 'Невірно введено ID або CS'));
        }

        $pay_sum = number_format((string)$this->_request['sum'], 2, '.', '');
		$this->_apprcode = $this->_request['txn_id'];

        $invoice = $this->findInvoiceByAppcode($this->_apprcode);
        if ( !empty($invoice) ) {
            $this->output(0, array(
                    'ext_transaction_id'    => $invoice->i_id,
                    'sum_pay'               => $pay_sum,
                    'prv_txn_date'  => $this->_txn_date,
                    'fields'        => array(
                    ),
                )
            );
		}

        $dogovor = $this->getDogovorById($id);
		if ( !$dogovor ) {
            $this->output(2, array('comment' => 'Невірно введено ID '.$id.', немає даних по заборгованності'));
		}

        $dateToday = date('Y-m-d H:i:s', $this->_txn_date);
		$min_sum = $dogovor->minimalPayment();
		$max_sum = $dogovor->countTotalSumm($dateToday);

        if ( !$max_sum ) {
            $this->output(2, array('comment' => 'Станом на сьогоднішній день ('.date("d.m.Y").') по ID '.$id.' заборгованість відсутня'));
        }

        if ( !($min_sum <= $pay_sum) ) {
            $this->output(2, array('comment' => 'Сума менша за мінімальну'));
        }

        if ( !($pay_sum  <= $max_sum) ) {
            $this->output(2, array('comment' => 'Сума більша за максимальну'));
        }

        $state = 'prepaid';
		$pay_params = $dogovor->makePayment($pay_sum, $dateToday, $this->_paysys, $this->_request['trm_id'], $state, $this->_apprcode);
		//$pay_params = false;
		if ( !$pay_params ) {
			$this->output(2, array('comment' => 'Внутрішня помилка'));
		}
		$this->sendInvoiceTo1C($pay_sum, $pay_params, $dogovor, $this->dogNumbFromId($id), $dateToday, $state);

        $this->output(0, array(
                'ext_transaction_id'    => $pay_params['invoice'],
                'sum_pay'               => $pay_sum,
                'prv_txn_date'  => $this->_txn_date,
                'fields'        => array(
                ),
            )
        );
    }

}
?>
