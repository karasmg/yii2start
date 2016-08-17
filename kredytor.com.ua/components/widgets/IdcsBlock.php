<?php
class IdcsBlock extends CWidget
{
    public $zayavkaNumb = false;
    public function run()
    {
        if(!$this->zayavkaNumb){
            return false;
        }
        $paysysHelper = new paysystemClass();
        $id = $paysysHelper->buildIdfromDogNumb($this->zayavkaNumb);
        $cs = $paysysHelper->buildCs($id);
        $this->render('IdcsBlock', array(
            'id'  => $id,
            'cs'  => $cs,
		));
    }
}
?>