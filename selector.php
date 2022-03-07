<?php
$dir = '/home/mkgtru/sites/mkgt.ru'; // path to your joomla installation directory
define( '_JEXEC', 1 );
define( 'JPATH_BASE', $dir);
define( 'DS', '/' );
require_once ( JPATH_BASE .DS . 'configuration.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );

$db = JFactory::getDbo();


$type='type';
$spec='spec';

$kourse = 'kourse';
$year = 'year';
$base = 'base';
$form = 'form';




    $query = $db->getQuery(true);
    $query->select("distinct ".'spec');
    $query->from($db->quoteName('studyPapers'));
    $db->setQuery($query);
    $result = $db->loadColumn();


    $specs = $result;//Список специальностей

//Переменная отвечает за вывод окошка с фильтрами
$search="
    <head>
<link rel='stylesheet' href='style.css'>
<link href='/media/vendor/awesomplete/css/awesomplete.css?1.1.5' rel='stylesheet'>
<link href='/media/vendor/joomla-custom-elements/css/joomla-alert.min.css?0.2.0' rel='stylesheet'>
	<link href='/components/com_sppagebuilder/assets/css/font-awesome-5.min.css?5029e51e66aaf58bae66a64ddc4a848b' rel='stylesheet'>
	<link href='/components/com_sppagebuilder/assets/css/font-awesome-v4-shims.css?5029e51e66aaf58bae66a64ddc4a848b' rel='stylesheet'>
	<link href='/components/com_sppagebuilder/assets/css/animate.min.css?5029e51e66aaf58bae66a64ddc4a848b' rel='stylesheet'>
	<link href='/components/com_sppagebuilder/assets/css/sppagebuilder.css?5029e51e66aaf58bae66a64ddc4a848b' rel='stylesheet'>
	<link href='/components/com_sppagebuilder/assets/css/jquery.bxslider.min.css' rel='stylesheet'>
	<link href='/media/com_sppagebuilder/css/page-2.css' rel='stylesheet'>
	<link href='/plugins/system/jce/css/content.css?aa754b1f19c7df490be4b958cf085e7c' rel='stylesheet'>
	<link href='/templates/shaper_helixultimate/css/bootstrap.min.css' rel='stylesheet'>
	<link href='/plugins/system/helixultimate/assets/css/system-j4.min.css' rel='stylesheet'>
	<link href='/plugins/system/helixultimate/assets/css/choices.css' rel='stylesheet'>
	<link href='/media/system/css/joomla-fontawesome.min.css?9e9a6c0aad121f072906cdf2eaf830e4' rel='stylesheet'>
	<link href='/templates/shaper_helixultimate/css/template.css' rel='stylesheet'>
	<link href='/templates/shaper_helixultimate/css/presets/preset1.css' rel='stylesheet'>
    </head>

<form action='/custom/studyPapers_db/selector.php'>

<p><b>Тип документа</b></p>
<div class='sppb-addon-wrapper'>
<select name='type' class='select-css'>
    <option value=''>Все</option>
    <option value='ФГОС'>ФГОС</option>
    <option value='ФОС'>ФОС</option>
    <option value='ГИА'>ГИА</option>
    <option value='Календарный учебный график'>Календарный учебный график</option>
    <option value='Учебный план'>Учебный план</option>
    <option value='Образовательная программа'>Образовательная программа</option>
</select>
</div>
<p><b>Форма обучения</b></p>
<div class='select-css'>
<select name='form' class='select-css'>
    <option value=''>Любая</option>
    <option value='Очная'>Очная</option>
    <option value='Заочная'>Заочная</option>
</select>
</div>


<p><b>Код специальности</b></p>
<select name='spec' class='select-css'>
<option value=''>Любой</option>";

foreach ($specs as &$value) {
    $search=$search."<option value='".$value."'>".$value."</option>";}
$search=$search."
</select>
<p><input type='submit' value='Показать' class='sppb-btn  sppb-btn-default sppb-btn-lg sppb-btn-round button'></p>
</form>
";
echo($search);



//Выполняется после нажатия на кнопку
if(isset($_GET['type'])){
    $type = $_GET["type"];
    $spec = $_GET["spec"];
    $kourse = $_GET["kourse"];
    $year = $_GET["year"];
    $base = $_GET["base"];
    $form = $_GET["form"];
    $query = $db->getQuery(true);

    $query->select((' * '));
    $query->from($db->quoteName('studyPapers'));

    //Добавление условий поиска
    if(strlen($type)>0){
        $query->where($db->quoteName('type') . ' = ' . $db->quote($type));
    }
    
    if(strlen($spec)>0){
        $query->where($db->quoteName('spec') . ' = ' . $db->quote($spec));
    }
        //Добавление условий поиска
        if(strlen($kourse)>0){
            $query->where($db->quoteName('kourse') . ' = ' . $db->quote($kourse));
        }
        
        if(strlen($year)>0){
            $query->where($db->quoteName('year') . ' = ' . $db->quote($year));
        }
        
        if(strlen($base)>0){
            $query->where($db->quoteName('base') . ' = ' . $db->quote($base));
        }
        if(strlen($form)>0){
            //Форма обчуния только для учебных планов
            if($type=='Учебный план')
            {
            $query->where($db->quoteName('form') . ' = ' . $db->quote($form));
            }
            
        }
        
    

    $db->setQuery($query);
    $result = $db->loadRowList();
    $path =$result;//Путь до текущего документа
    
    
    
    
echo("<table class='tbl-cust'>
<tr><td>Код специальности</td><td>Тип документа</td><td>Курс</td><td>Год</td><td>База (кл.)</td><td>Форма обучения</td></tr>");
    
    $num = 1;//Счётчик номера документа
    //Вывод списка документов
    foreach ($result as &$value){
        
        echo("
        <tr> <td class='col2'>".$value[1]."</td><td class='col3'><a href='".$value[0]."'>".$value[2]."</a></td><td class='col4'>".$value[3]."</td><td class='col5'>".$value[4]."</td><td class='col6'>".$value[5]."</td><td class='col7'>".$value[6]."</td></tr>");
    }
echo("</table>");
 }














?>