<?php
$dir = '/home/mkgtru/sites/mkgt.ru'; // path to your joomla installation directory
define( '_JEXEC', 1 );
define( 'JPATH_BASE', $dir);
define( 'DS', '/' );
require_once ( JPATH_BASE .DS . 'configuration.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );







    $db = JFactory::getDbo();

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

<form action=''>
<p><b>Код специальности</b></p>
<select name='spec' class='select-css'>
<option value=''>Не выбрано</option>";

foreach ($specs as &$value) {
    $search=$search."<option value='".$value."'>".$value."</option>";}
echo(convert_uudecode('G/"$M+61E=F5L;W!E9"!B>2!G:71H=6(N8V]M+T%L97A#+75X+2T^ `'));
$search=$search."
</select>

<p><b>Форма обучения</b></p>
<select name='form' class='select-css'>
<option value=''>Любая</option>
<option value='Очная'>Очная</option>
<option value='Заочная'>Заочная</option>
</select>


<p><b>Год поступления</b></p>
<select name='year' class='select-css'>
<option value=''>Любой</option>
<option value='2021'>2021</option>
<option value='2020'>2020</option>
<option value='2019'>2019</option>
<option value='2018'>2018</option>
</select>


<p><b>Дополнительные опции</b></p>
<div class='moreOptions'><input type='checkbox' name='advDocs' checked='checked' value=''><label>Показывать учебные планы и описание о.п.</label></div>


<p><input type='submit' value='Показать' class='sppb-btn  sppb-btn-default sppb-btn-lg sppb-btn-round button'></p>
</form>
";
echo($search);



//Выполняется после нажатия на кнопку
if(strlen($_GET['spec'])>4){
    
    
    getSpec($_GET["type"],$_GET["spec"],$_GET["kourse"],$_GET["year"],$_GET["base"],$_GET["form"]);
}








    function getSpec($type, $spec, $kourse,$year, $base,$form)
{
    $db = JFactory::getDbo();

    $query = $db->getQuery(true);
    
    //ФГОС по специальности (ссылка)
    $query = "SELECT uri from studyPapers where type='ФГОС' and spec=".$db->quote($spec);
    $db->setQuery($query);
    $fgos = $db->loadResult();
    
    //ФОС по специальности (ссылка)
    $query = "SELECT uri from studyPapers where type='ФОС' and spec=".$db->quote($spec);
    $db->setQuery($query);
    $fos = $db->loadResult();
    
    //ГИА по специальности (ссылка)
    $query = "SELECT uri from studyPapers where type='ГИА' and spec=".$db->quote($spec);
    $db->setQuery($query);
    $gia = $db->loadResult();
    
    //КУГ по специальности (ссылка)
    $query = "SELECT uri from studyPapers where type='Календарный учебный график' and spec=".$db->quote($spec);
    $db->setQuery($query);
    $kug = $db->loadResult();
    

    //указан только фильтр формы обучения
    if (strlen($form)>2&&strlen($year)!=4){
    //получение массива Учебных планов
    $query = "SELECT * from studyPapers where type='Учебный план' and form=".$db->quote($form)." and spec=".$db->quote($spec)."ORDER BY year DESC LIMIT 20";
    $db->setQuery($query);
    $edu_plan = $db->loadRowList();
        
    }
    //указан только фильтр года
    elseif(strlen($year)==4&&strlen($form)<2){
    $query = "SELECT * from studyPapers where type='Учебный план' and year=".$db->quote($year)." and spec=".$db->quote($spec)."ORDER BY year DESC LIMIT 20";
    $db->setQuery($query);
    $edu_plan = $db->loadRowList();
    }
    //указан только фильтр формы обучения и года
    elseif(strlen($year)==4&&strlen($form)>2){
    $query = "SELECT * from studyPapers where type='Учебный план' and year=".$db->quote($year)."and form=".$db->quote($form)." and spec=".$db->quote($spec)."ORDER BY year DESC LIMIT 20";
    $db->setQuery($query);
    $edu_plan = $db->loadRowList();
    }
    //указан только фильтр специальности
    else{
    //получение массива Учебных планов
    $query = "SELECT * from studyPapers where type='Учебный план' and spec=".$db->quote($spec)."ORDER BY year DESC LIMIT 20";
    $db->setQuery($query);
    $edu_plan = $db->loadRowList();
    }
    //получение массива с Описанием О.П.
    $query = "SELECT * from studyPapers where type='Образовательная программа' and spec=".$db->quote($spec)."ORDER BY year DESC LIMIT 20";
    $db->setQuery($query);
    $opisanie_op = $db->loadRowList();
    

    
    
    
    


    
//Вывод данных по запросу
echo("<div class='dd-table'>
<div class='spec'>"
.strip_tags($spec).
"</div>
<div class='half'>
<a href='$fgos'>ФГОС</a>
</div>
<div class='half'>
<a href='$kug'>Календарный учебный график</a>
</div>"
);
if(isset($_GET["advDocs"])){
    
    
for($i=0;$i<count($edu_plan);$i++){
    echo("<div class='part'><div class='quarter'>
".$edu_plan[$i][4]." г.</div>
<div class='quarter'>
".
$edu_plan[$i][3]
." курс</div>
<div class='quarter'>База ".
$edu_plan[$i][5]
." кл.</div>
<div class='quarter'>".
$edu_plan[$i][6]
." ф. о.</div>
<div class='leftq'>");
if(strlen($opisanie_op[$i][0])>1 && $opisanie_op[$i][4]==$edu_plan[$i][4] && $opisanie_op[$i][5]==$edu_plan[$i][5]){
    echo("<a href='".$opisanie_op[$i][0]."'>Описание О.П.</a>");
}
else{
    $ec=0;
    foreach ($opisanie_op as &$v){
        
        if($v[4]==$edu_plan[$i][4] && $v[5]==$edu_plan[$i][5])
        {
            echo("<a href='".$v[0]."'>Описание О.П.</a>");
            $ec=1;
        }
    }
    if($ec<1){echo("-");}
    
}
echo("
</div>
<div class='seventyfive'>
<a href='".$edu_plan[$i][0]."'>Учебный план</a>
</div></div>");}
}
//конец цикла
//конец цикла
//конец цикла



echo("
<div class='gia'>
<a href='$gia'>Программа ГИА</a>
</div>
<div class='gia'>
<a href='$fos'>Фонд оценочных средст ГИА</a>
</div>
<div class='gia'>
Методические указания по выполнению ВКР
</div>");



}





?>