<?php

class PdfBaseHelper
{
    public $_pdf;


    public function __construct() {
        Yii::import('application.extensions.tfpdf.tFPDF');
        $this->_pdf = new tFPDF('P','mm', 'A4');
    }


    public function Write($text, $fontWeight='') {
        $pdf = $this->_pdf;
        $cellHeight = 3.2;
        $paragraphFontSize = 8;
        $pdf->SetFont('DejaVu', $fontWeight, $paragraphFontSize );
        return $pdf->Write($cellHeight, $text);
    }

    public function WhiteLine() {
        $pdf = $this->_pdf;
        $cellWidth = 190;
        $cellHeight = 3.2;
        $paragraphFontSize = 8;
        $pdf->SetFont('DejaVu','',$paragraphFontSize);
        return $pdf->MultiCell($cellWidth, $cellHeight, '', 0, 'J', 0);
    }


    public function CellCol($leftMargin,  $height, $text, $chars, $y=false, $width=60) {
        $pdf = $this->_pdf;
        if(!$y)
            $y = $pdf->GetY();
        $pdf->SetXY($leftMargin, $y);
        $pdf->MultiCell($width, $height, $text);
        return;
    }


    public function LineBreak() {
        $pdf = $this->_pdf;
        return $pdf->Ln();
    }
	
	public function printIDCS($zayavka) {
		//Вывод id cs
		$paysysHelper = new paysystemClass();
        $paragraphFontSize = 8;
		$id = $paysysHelper->buildIdfromDogNumb($zayavka->zayavkaNumb);
		$cs = $paysysHelper->buildCs($id);
		$this->_pdf->SetFont('DejaVu','',$paragraphFontSize);
		$this->_pdf->MultiCell(80, 4, 'Оплата он-лайн', 'TLR', 'L');
		$this->_pdf->SetFont('DejaVu','B',$paragraphFontSize);
        if(empty($zayavka->zayavkaNumb)){
            $this->_pdf->MultiCell(80, 4, ('ID: #проект договору#' ), 'LR', 'L');
            $this->_pdf->MultiCell(80, 4, ('CS: #проект договору#' ), 'LRB', 'L');
        } else {
            $this->_pdf->MultiCell(80, 4, ('ID: ' . $this->formatidcs($id)), 'LR', 'L');
            $this->_pdf->MultiCell(80, 4, ('CS: ' . $this->formatidcs($cs)), 'LRB', 'L');
        }
	}
	
	public function formatidcs($str) {
		$return = '';
		do {
			$substr = substr($str, 0, 4);
			$return.= $substr.' ';
			$str	= substr($str, 4);
		} while ( strlen($substr) == 4 );
		return $return;
	}

    public static function createContract($zayavka) {
        $anketa = Anketa::model()->findByAttributes(array('iid' => $zayavka->iid));
        if ($zayavka->credit_targeted == '1') {
            switch($zayavka->calc_type){
                case 'dayly':
                    $pdf = new DaylyTargetedContract();
                    break;
                case 'annuitet':
                    $pdf = new AnnuitetTargetedContract();
                    break;
                case 'partspay':
                    $pdf = new PartspayTargetedContract();
                    break;
            }
        } else {
            if ( $_POST['Zayavka']['_money_type'] === 'online' || !empty($zayavka->card) )
                $pdf = new DaylyOnlineContract();
            else
                $pdf = new DaylyCashContract();
        }

        $pdf->renderPdf($anketa, $zayavka);
    }

    public static function createToken($uri, $date) {
        return md5(strtoupper(
            $uri.
            $date->format('Y-m-d H:i').
            '2C99'
        ));
    }

    public static function outputPDF( $contract, $fileContract )
    {
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $contract . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . filesize($fileContract));
        header('Accept-Ranges: bytes');

        @readfile($fileContract);
    }
}