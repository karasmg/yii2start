<?php
class CalculatorCred extends CWidget
{
    public function run()
    {
		$tempZayavka = false;
        if( !Yii::app ()->user->isGuest ) {
			$tempZayavka = TempZayavkaHelper::getTempZayavka(false, true);
        }

        $this->render('CalculatorCred', array(
            'tempZayavka' =>  $tempZayavka
		));
    }
}
?>