<?php
    /////////////// VARIABLES ////////////////////////////////                  // RU comments
    $debug  = 0;                                                                // вкл/выкл флаг debug.
    $debug2 = false;                                                            // вкл/выкл флаг debug2.
    $dir = "./";                                                                // По умолчанию сканировать будем текущей каталог. 
    $scan = [];                                                                 // Будем сканировать $dir в аррей $scan.                                                               
    $html = "<div class='container'><div class='row'>";                         // Здесь соберем весь HTML
    $dis_files = array(my_name(),"1");                                          // Массив запрещенных файловых расширений. По-умолчанию разрешены все файлы.
    $disabled_extentions = [];                                                   // Заголовок сайта. 
    $title = "Magic Stick Автопубликатор";
    $dir   = './';                                                               // по умолчанию директорий - коренной директорий
    ///////////// FUNCTIONS ///////////////////////////////////
    function my_name() {                                                         //вычисляем себя и исключаем из списка файлов для публикации
        GLOBAL $debug;                                                           // подключаемся к глобальным переменным
       return  (basename(__FILE__));                                             // возвращаем собственное имя без пути 
    }
    //-------------------------------------------------//
    function disabled($b) {                                                       // получаем имя папки/файла возвращаем булеан запрещен или нет
        GLOBAL $dis_files,$debug;                                                  // подключаемся к глобальным переменным
        if($debug){echo("<pre>" . __LINE__ . ": ");print_r($dis_files);echo("</pre>");} // выводим наладочную информацию
        $c = false;                                                                     // $c возвращяет false что онзначает не запрещено.                            
        if(in_array($b, $dis_files)) return true;                                       // если запрещенный файл/папка выходим с подтверждением ДА
        if(in_array(get_extension($b), $dis_files)) return true;                        // если расширение запрещено возращаем ДА
        return $c;                                                                      //  возвращаем НЕТ, если прошли все фильтры 
    }
    //--------------------------------------------------
    function get_extension($a='.htsccess'){                                       // функция принимает имя файла/папки возвращает расширение или пустую строку
      GLOBAL $disabled_extensions;                                                // подключаемся к глобальной переменной
     // extract($_REQUEST);                                                       // зарезервировано на случай использования технологии задания переменных в запросе  
      $posr   = strrpos($a, ".")+1;                                               // позиция последней точки в полученном имени
      $posl   = strpos($a,".")+1;                                                 //  позиция первой точки в позиции
      $len_   = strlen($a);                                                       // длина имени файла/папки   
      if($len_- $posr){                                                           // если длина больше позиции правой точки (если за последней точкой имеются символы) 
        if($posl){                                                                // если вообще имеется хотя бы одна точка в имени
         return substr($a, ($posr - $len_));                                      // возвращаем остаток имени после последней точки
        }else{                                                                    // иначе если точек вообще нет
         return substr($a, ($posr - $len_ + 1));                                  // возвращаем остаток имени длиной на единицу меньше чем позиция последней точки
        } 
      }
      return  false;                                                              // если прошли все фильтры возвращаем фальс
    }
////////////////// MAIN //////////////////////////////////////    
    
     extract($_REQUEST);                                                         // распаковываем Запрос в переменные
    $scan=scandir($dir)  ;                                                       // сканируем директорию
    if($debug2) {echo("<pre>"); print_r($scan);echo("</pre>"); }                 // Конструкция для проверки.
    $disabled_files = my_name();                                                 // Запрещяем работать с самими собой.
    for($i=0;$i<count($scan); $i++) {                                           // Читаем содержимое каталога по-штучно.       
       switch ($scan[$i]) {                                                     // Включаем фильтр ключ селектор очередного имени файла/папки для определения надписи
        case(".")  : $nadpis = "Повторить сканирование"; break;                 // Для директории "."  берем "Повторить сканирование" для $nadpis.
        case("..") : $nadpis = "Выйти из директории";   break;                  // Для директории ".." берем "Выйти из директории" для $nadpis.
        default    : $nadpis = $scan[$i]; break;                                // По-умолчанию        берём имя файла.
       }
       
       if($i===0)    {$html  .= '';   //"<a href='"   . $scan[$i] . "'><div class='col-lg-6 place'>" . $nadpis . "</div></a>";  //составляем очередную строку ХТМЛ для "."    
       }elseif($i===1){$html .= "<a href='"   . $scan[$i] . "'><div class='col-lg-12 place'>" . $nadpis . "</div></a>";  //составляем очередную строку ХТМЛ для ".."
       }else{
      if(!disabled($scan[$i])) $html                 .= "<a href='" . $scan[$i] . "'><div class='col-lg-1 col-md-2 col-sm-3 place'>" . $nadpis . "</div></a>" ;                  // Собираем html строку из содержимого.
       }
    }  
    $html    .= "</div></div>";
  
    
?> 
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php echo($title)?></title>
    <style>
    h1,h2,h3{
    color: #8ac;
    }
    .place{
    padding:5px; 
    margin:0px;
    border:thin solid #fff;
    background:#dde;
    color:#04a;
    border-radius:3px;
    text-align:center;
    }
    .place:hover{
    background-color:#ccf;
    }
    .gray-black{
    background-color: #666;
    color:#fff;
    font-family:Arial;    
    }
    
    </style>
    
</head>
<body>
    <div class="container">
        <div class="row">
    <h2><?php echo($title)?></h2>    
            <?php echo($html);?>
        </div>
    </div>
    <div class="container">
    <div class="row">
    <footer class="gray-black">
   <?php include("http://git.uk.tempcloudsite.com/publicator1.1/footer.txt")?>
    </footer>
    </div>
    </div>
</body>
