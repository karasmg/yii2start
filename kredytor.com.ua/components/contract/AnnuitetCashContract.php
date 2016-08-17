<?php

class AnnuitetCashContract extends PdfBaseHelper
{

    public function __construct() {
        parent::__construct();
    }

    public function renderPdf($anketa, $zayavka) {
        $isBlanc = false;
        if(empty($zayavka->zayavkaNumb)){
            $isBlanc = true;
        }
        mb_internal_encoding("UTF-8");
        $shortName = $anketa->surname . ' ' . mb_substr(trim($anketa->name), 0, 1) . '. ' . mb_substr(trim($anketa->lastname), 0, 1) . '.';
        $directorShortName = 'Максимов М.В.';
        $address = $anketa->contact_region . ' область';
        if ($anketa->contact_area) $address .= ', ' . $anketa->contact_area . ' район';
        $address .= ', г. ' . $anketa->contact_city;
        $address .= ', ул. ' . $anketa->contact_street;
        $address .= ' ' . $anketa->live_house;
        if ($anketa->live_corp) $address .= ', корпус ' . $anketa->live_corp;
        if ($anketa->live_flat) $address .= ', кв. ' . $anketa->live_flat;
        $fullName = $anketa->surname . ' ' . $anketa->name . ' ' . $anketa->lastname;
        $passport = $anketa->passport_seria . ' №' . $anketa->passport_number . ' виданий ' . $anketa->passport_issued . ' ' . date_create($anketa->passport_date)->format('d.m.Y') .'р.';
        $dogDate = date_create()->format('d.m.Y');
        $math = new daylyCalcClass($zayavka);
        $finishDate = date_create();
        $interval = (int)$zayavka->srok + $math->_creditParams['_termmodifier'];
        $interval = new DateInterval ('P' . $interval . 'D');
        $finishDate = date_add($finishDate, $interval);
        $finishDate = $finishDate->format('d.m.Y');
        $iid = $anketa->iid;
        $name = $zayavka->zayavkaNumb;
        if($isBlanc){
            $name = 'Проект Договору позики';
        }
        $pdf = $this->_pdf;
		$pdf->fontpath = Yii::app()->basePath.'/extensions/tfpdf/font/';
        $pdf->AddFont('DejaVu','','DejaVuSerifCondensed.ttf',true);
        $pdf->AddFont('DejaVu','B','DejaVuSerifCondensed-Bold.ttf',true);
        $pdf->AddFont('DejaVu','I','DejaVuSerifCondensed-Italic.ttf',true);
        $paragraphFontSize = 8;
        $pdf->AddPage();
        $pdf->SetFont('DejaVu','B',8);
        $pdf->Cell(0,10,"ДОГОВІР ПРО НАДАННЯ ФІНАНСОВОГО КРЕДИТУ",0,2,C);
        $this->WhiteLine();
        $this->Write('Кредитодавець  (п. 2 Специфікації до Договору  далі Специфікація), з однієї сторони, та Позичальник (п. 3 Специфікації) з іншої сторони, уклали цей договір про надання фінансового кредиту, місце, номер і дату укладення  якого зазначено в п.п. 1,2 Специфікації,  далі Договір, про нижченаведене: ','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('1. ','B');
        $this->Write('Кредитодавець надає, а Позичальник одержує фінансовий кредит готівкою, надалі - Кредит, у розмірі згідно п.5 Специфікації. Кредит надається Позичальнику на власні потреби та не є споживчим кредитом.','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('2. ','B');
        $this->Write('Кредит надається без застави, поруки та іншого забезпечення.','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('3. ','B');
        $this->Write('Позичальник зобов’язується повернути Кредитодавцю суму Кредиту та сплатити проценти за користування Кредитом згідно п. 6 Специфікації не пізніше дати, зазначеної у п. 4 Специфікації. При цьому, якщо датою повернення Кредиту є не робочий день Кредитодавця, то датою повернення  вважається  його перший наступний робочий день.  ','');
        $this->LineBreak();
        $pdf->SetX($pdf->GetX()+5);
        $this->Write('Позичальник зобов’язаний  повернути суму Кредиту та сплатити проценти за користування Кредитом готівкою  в касу Кредитодавця, за місцезнаходженням будь-якого з його відділень або шляхом зарахування відповідних грошових коштів на його розрахунковий рахунок, при цьому в разі погашення заборгованості  не через  ЦВК, а будь-яким іншим способом (платіжний термінал, сайт Товариства, тощо) Кредитодавець самостійно встановлює порядок відшкодування послуг фінансового посередника (в залежності від досягнутих домовленостей з відповідним фінансовим  посередником).','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('4. ','B');
        $this->Write('Позичальник, має право:','');
        $this->LineBreak();
        $this->Write('4.1. достроково повернути суму Кредиту та сплатити проценти за користування Кредитом, виходячи з фактичного строку користування Кредитом,  у будь-якому випадку при достроковому повернені кредиту Позичальник повинен  сплатити, мінімально  встановлений Товариством, процент за перший день користування кредитом;','');
        $this->LineBreak();
        $this->Write('4.2. за наявності згоди Кредитодавця:','');
        $this->LineBreak();
        $this->Write('4.2.1.подовжити строк дії Договору, за умови часткового погашення заборгованості щодо сплати процентів за користування Кредитом за той строк, на який Позичальник має намір подовжити дію Договору; ','');
        $this->LineBreak();
        $this->Write('4.2.2. за умови повного погашення процентів за користування Кредитом на день звернення – змінити строк користування кредитом в межах строку наданого кредиту, а також повернути частину Кредиту.','');
        $this->LineBreak();
        $this->Write('При цьому в разі сплати Позичальником грошових  коштів в рахунок погашення заборгованості за процентами, що не покриватиме в повному обсязі розрахунковий день, такий залишок буде враховано в наступне погашення процентів за користування кредитом.','');
        $this->LineBreak();
        $pdf->SetX($pdf->GetX()+5);
        $this->Write('У вищевказаних  випадках Специфікацію має бути викладено в новій редакції (або складено Додаток до Специфікації), що, відповідно, припиняє зобов`язання за договором попередньої редакції. ','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('5. ','B');
        $this->Write('У разі невиконання Позичальником своїх зобов’язань по Договору  у повному обсязі та у строк згідно п. 3 Договору, Кредитодавець у будь-який час може звернутись до судових органів з метою стягнення заборгованості Позичальника. У разі невиконання своїх зобов’язань за Договором Позичальник  зобов’язується самостійно виконати всі зобов’язання пов’язані зі сплатою відповідних податків, згідно чинного законодавства.','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('6. ','B');
        $this->Write('У разі, якщо Позичальник не повернув Кредит та не сплатив проценти у встановлений Договором  строк, Позичальник сплачує Кредитодавцю проценти за користування Кредитом в період понад визначений Договором строк у розмірі,  встановленому другим абзацом п. 6. Специфікації. ','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('7. ','B');
        $this->Write('Сума процентів за користування Кредитом, включаючи проценти згідно п. 6 Договору, нараховується Кредитодавцем в день погашення Кредиту чи подовження Договору, при цьому враховуються перший день надання Кредиту/подовження дії Договору та  день погашення Кредиту/сплати процентів за користування Кредитом при подовженні Договору, але в будь-якому випадку мінімальним строком/розміром такого нарахування, є встановлений Кредитодавцем розмір процентів за  перший календарний день. Таким чином, сума, обумовлена у п. 7 Специфікації, підлягає обов’язковому перерахунку з урахуванням фактичного строку користування Кредитом у календарних днях на момент сплати. ','');
        $this->LineBreak();
        $this->Write('Розмір річної процентної ставки за користування кредитом, за умови вчасного виконання Позичальником своїх зобов’язань, визначається за формулою: Р% = Д%*КД (де Р% - це річна процентна ставка; Д% - денна процентна ставка, вказана в п. 8 Специфікації; КД – кількість днів в році).','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('8. ','B');
        $this->Write('Кредитодавець має право без згоди Позичальника у будь-який час передати свої права за Договором  іншим особам (відступити право вимоги). ','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('9. ','B');
        $this->Write('Дані Договору, зазначені в Специфікації, є конфіденційною інформацією та можуть бути розголошені виключно на підставах, прямо передбачених законодавством України та Договором та/або відповідними правилами. Кредитодавець має право без згоди Позичальника ознайомлювати з умовами Договору 1: а) страховиків, які відповідно до договорів, укладених з Кредитодавцем, страхують його ризики; б) третіх осіб, до яких переходить право вимоги до Позичальника на підставі договору, укладеного з Кредитодавцем; в) компетентним органам – в порядку та на умовах передбачених чинним законодавством. ','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('10. ','B');
        $this->Write('Позичальник надає згоду на включення, та у разі необхідності, обробку його персональних даних, наданих при укладенні цього договору, до бази даних клієнтів Кредитодавця та їх передачу іншим суб’єктам надання фінансових послуг. Позичальник також підтверджує, що йому повідомлено про включення його персональних даних до бази персональних даних, а також про  мету збору даних та осіб, яким можуть бути передані його персональні дані та відмовляється від додаткового письмового повідомлення щодо включення інформації про нього до бази персональних даних, а також додаткового повідомлення щодо передачі таких даних третім особам, зазначеним у Договорі. Позичальник також засвідчує, що він/вона ознайомлений(а) зі своїми правами як суб’єкта персональних даних, відповідно до Закону України «Про захист персональних даних» №2297-VI від 01.06.2010 року.','');
        $this->LineBreak();
        $this->Write('Позичальник не заперечує проти використання/надання його персональних даних  бюро кредитних історій для отримання /надання інформації щодо його кредитної історії Позичальника.','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('11. ','B');
        $this->Write('Договір  вважається укладеним з дати підписання Сторонами Специфікації. Строк дії Договору  визначається згідно п. 4 Специфікації. Договір  може бути достроково припинено в порядку, встановленому даним договором чи відповідно до закону, але у будь-якому випадку Договір діє до моменту фактичного задоволення грошових вимог Кредитодавця до Позичальника в повному обсязі. Номер, дата та місце укладення, зазначені в Специфікації, є відповідно номером, датою та місцем укладення договору між Сторонами на умовах визначених Договором.','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('12. ','B');
        $this->Write('Договір  та Специфікацію складено у двох оригінальних примірниках – по одному для кожної із Сторін. ','');
        $this->LineBreak();

        /*****************************Спецификация*****************************************/
        /*
        $pdf->AddPage();
		
		$this->printIDCS($zayavka);
        $this->WhiteLine();
        $this->WhiteLine();
        $this->Write('1. Специфікація до Договору  № '.$name.' вiд '.$dogDate.'.','BU');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('2. Кредитодавець: ','B');
        $this->Write('ТОВ «ФІНАНСОВА КОМПАНІЯ «КРЕДИТОР XXI»  (місцезнаходження 01133, м. Київ, б-р Лесі Українки, 23, офіс 108, ЕДРПОУ 38292552, Свідоцтво про реєстрацію фінансової установи № 377 серія ФК від 20  грудня 2012 року),','');
        $this->LineBreak();
        $this->Write('від імені якого виступає ','');
        $this->Write('                                             ','U');
        $this->Write(', розташоване: ','');
        $this->Write('                                             ','U');
        $this->Write(' тел.:  ','');
        $this->Write('                                             ','U');
        $this->Write(', в особі ','');
        $this->Write('                                             ','U');
        $this->Write(' довір. №','');
        $this->Write('         ','U');
        $this->Write(' від  10/07/2001.','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('3. Позичальник: ','B');
        $this->Write($fullName.', що мешкає за адресою: '.$address.', Паспорт Серii '.$passport,'');
        $this->Write(' Реєстраційний номер облікової картки  платника податків, згідно з Державним реєстром фізичних осіб - платників податків: '.$anketa->iid.'.','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('4. Строк, на який надається Кредит: ','B');
        $this->Write($anketa->srok. "дн.",'U');
        $this->Write(' Дата повернення Кредиту: ','B');
        $this->Write($finishDate.'.','U');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('5. Сума Кредиту,  видана Позичальнику з каси,  становить ','B');
        $this->Write($zayavka->summ.' грн.','U');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('6. ','B');
        $this->Write('При поверненні Кредиту Позичальник сплачує проценти за користування Кредитом iз розрахунку ','');
        $this->Write($math->_creditParams['_percentstage'].'%','U');
        $this->Write(' в день вiд суми Кредиту, ','');
        $this->LineBreak();
        $this->Write('в тому числі не менше, ','');
        $this->Write(number_format(floatval($math->_creditParams['_firstdayminpay']),2).' ','U');
        $this->Write('гривень за перший день користування кредитом, що складає ','');
        $this->Write(number_format(floatval($zayavka->summPercent),2).' грн.','U');
        $this->LineBreak();
        $this->Write('У разi невиконання своїх зобов\'язань у строк,','');
        $this->LineBreak();
        $this->Write('Позичальник сплачує додатково ','');
        $this->Write($math->_creditParams['_penystage'].'% ','U');
        $this->Write('вiд суми Кредиту за кожний день  прострочення, починаючи з ','');
        $this->Write($math->_creditParams['_panydaystart']+1,'U');
        $this->Write('-го  дня прострочення.','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('7. ','B');
        $this->Write('Сума до повернення без урахування фактичного строку користування Кредитом та  перерахунку згiдно п. 6 Специфiкацii: ','');
        $this->Write(number_format(floatval($zayavka->summ+$zayavka->summPercent),2).' грн.','U');
        $this->LineBreak();
        $this->WhiteLine();
        $this->Write('8. ','B');
        $this->Write('Дана Специфікація є невiд`ємною частиною Договору. Підписання Специфікації засвідчує фактичне укладення Договору. Позичальник  своїм підписом засвідчує, що до укладення Договору  зі змістом та умовами Договору  цієї Специфікації, змістом частини другої ст. 12 Закону «Про фінансові послуги та державне регулювання ринків фінансових послуг» та  ч. 2 ст. 11 Закону України «Про захист прав споживачів», а також з внутрішніми правилами/регламентом  Кредитодавця   про надання фінансових кредитів за рахунок власних коштів, які є невід`ємною частиною Договору, ознайомлений, їх положення йому зрозумілі і він повністю з ними погоджується. ','');
        $this->LineBreak();
        $pdf->SetX($pdf->GetX()+5);
        $this->Write('Позичальник також засвідчує, що він/вона ознайомлений(а) зі своїми правами згідно ч. 6 ст. 11 Закону України про захист прав споживачів і ст. 8 Закону України «Про захист персональних даних», як суб’єкта персональних даних, та надає згоду на включення його персональних даних до бази даних клієнтів Кредитодавця відповідно до умов Договору  та внутрішніх правил/регламенту  Кредитодавця про надання фінансових кредитів фізичним особам у відділеннях Товариства за рахунок власних коштів Товариства.    ','');
        $this->LineBreak();
        $pdf->SetX($pdf->GetX()+5);
        $this->Write('Позичальник підтверджує, що до укладення цього Договору він дав згоду ','');
        $this->LineBreak();
        $pdf->SetX($pdf->GetX()+5);
        $this->Write('- на передачу Кредитодавцю своїх персональних даних і їх обробку з метою оцінки фінансового стану Позичальника і його здатності виконати свої зобов\'язання за Договором;','');
        $this->LineBreak();
        $pdf->SetX($pdf->GetX()+5);
        $this->Write('- на те, що Кредитодавець має право звертатися за інформацією про фінансовий стан Позичальника до третіх осіб, які пов\'язані з Позичальником діловими, професійними, особистими, сімейними чи іншими відносинами;','');
        $this->LineBreak();
        $pdf->SetX($pdf->GetX()+5);
        $this->Write('- на те, що у разі невиконання або неналежного виконання зобов\'язань Позичальника на підставі цього Договору Кредитор має право передати персональні дані Позичальника третім особам для захисту своїх законних прав та інтересів, стягнення заборгованості за Договором, збитків;','');
        $this->LineBreak();
        $pdf->SetX($pdf->GetX()+5);
        $this->Write('- на отримання від Кредитодавця інформації за всіма вказаними ним  засобами зв’язку (мобільний телефон, телефон, електронна адреса, тощо)','');
        $this->LineBreak();
        $this->WhiteLine();
        $this->WhiteLine();
        $this->Write('Фінансовий кредит - грошові кошти в сумі ','');
        $this->Write($zayavka->summ,'U');
        $this->Write(' грн отримав (ла).','B');
        $this->LineBreak();
        $this->WhiteLine();
        $this->WhiteLine();
        $currentY = $pdf->GetY();
        $this->CellCol(25, 4, 'Від Кредитодавця: __________________', 60, $currentY+4);
        $this->CellCol(25, 4, '/'.$directorShortName.'/', 60);
        $this->CellCol(115, 4, 'Від Позичальника: __________________', 60, $currentY+4);
        $this->CellCol(115, 4, '/'.$shortName.'/', 60);
        */
        if($isBlanc){
            $dogNum = $iid;
        } else {
            $dogNum = $zayavka->zayavkaNumb;
        }
        $pdf->Output(Yii::app()->basePath.'/data/contracts/' . $dogNum . '.pdf', 'F');
        return $dogNum;

    }
}