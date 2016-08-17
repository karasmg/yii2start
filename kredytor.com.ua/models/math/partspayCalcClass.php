<?php

class partspayCalcClass extends annuitetCalcClass {
	public $_srok_limits = array (
			'_minsrok'			=> 1,
			'_maxsrok'			=> 30,
	);
	public $_creditParams = array (
			'_percentstage' => 0, 
			'_penystage' => 1, 
			'_panydaystart' => 1, 
			'_firstdayminpay' => 0,
			'_termmodifier' => -1, 
			'_firstdayPayed' => false,
	);
}
?>