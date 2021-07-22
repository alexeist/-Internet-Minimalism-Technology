<?php
/*************************************************************************
Autopublicator v 3.02 Authentication. Автор Алекс Эйст 2021 aekap ITC Пожалуйста, не удаляйте эту строку комментария при использовании
*
* Этот сценарий публикует содержимое каталога $dir.
* Каждый файл/расширение файла или каталог может быть включен или нет, отключен или нет к публикации
* В этой версии используется пользователь с любым именем пользователя
* История входа в сайт сохраняется в логфайле - log.txt   При желании можно изменить его местоположение и/или имя 
* В логфайле сохраняется времявхода
* Поле пользователя включено по соображениям безопасности и не используется при разблокировании процесса публикации
* ------------ README ---------------------
* 1 Что можно изменить перед использованием?
* 1.1 USER_PASSWORD                                                          // капчу - фильтр спама
* 1.2 APP_TITLE                                                              // Заголовок ХТМЛ страницы
* 1.3 $dis_files                                                             // аррей запрещенных к публикации файлы
* 1.4 $mode                                                                  // можно использовать "все запрещены кроме..." ENABLED_EXTENTIONS или "все запрещены кроме.." DISABLED_EXTENTIONS)
* 1.5 $file_extentions                                                       // при использовании $mode = ENABLED_EXTENTIONS
* 1.6 $ disabled_extentions                                                  // при использовании $mode = DISABLED_EXTENTIONS
*  2
 * 2.1 Если вы хотите отключить все и включить только несколько расширений файлов для публикации, используйте режим EXTENTION_ENABLED и отредактируйте переменную $ enabled _ extentions
* 2.2 Если вы предпочитаете включить и отключить только несколько расширений файлов для публикации, используйте режим EXTENTION_DISABLED и отредактируйте переменную $ disabled _ extentions
* ПРИМЕЧАНИЕ: имена файлов/каталогов, перечисленные в массиве $ dis _ files, не будут опубликованы в обоих режимах EXTENTION_DISABLED и EXTENTION_ENABLED
 **************************************************************************/
//////////// CONSTANTS ///////////////////////
define('EXTENTION_DISABLED',                                0);              // для моды: РАЗРЕШЕНЫ все расширения кроме перечисленных в $disabled_extentions
define('EXTENTION_ENABLED',                                 1);              // для моды ЗАПРЕЩЕНЫ все расширения кроме перечисленных в  $enabled_extentions
define('en',                                                 0);
define('ru',                                                 1);
define('cz',                                                 2);
define('cn',                                                 3);
define('it',                                                 4);
define('fr',                                                 5);
define('es',                                                 6);
define('de',                                                 7);
//*****************    START TO CHANGE ****************************************//
//                   CONST                                                     //  
define ('APP_TITLE', "ЭКСТЕРНЕТ МИНИ. Автопубликатор LiLi 3.02");            // измените значение этой постоянной согласно ваших нужд
define('USER_PASSWORD',                              19510104  );              // Капча для входа для фильтра спама. Регулярно меняйте ее и соотв. текст    
define('LNG_DEFAULT',                                      ru  );              // измените значение языка по-умолчанию на ваш язык
define('LOG_FILE_DIR',                                      '.');              // по-умолчанию папка для ЛОГфайла - корневая. Измените ее на любое недоступное для публикации место
define('LOG_FILE_NAME',                               'log.txt');              // по-умолчанию логфайл доступа к библиотеке                    VARS                                                    //
$disabled_extensions = array("exe", "com", "bat", "php", "js", "c", "h", "py", "java", "ini", "cfg"); // работет в моде 0 DISABLED_EXTENTION  добавьте или удалите ненужные к запрещению публикации расширения
$enabled_extensions  = array("txt", "pdf", "odt", "doc","docx", "mp3","mp4", "ogg", "avi");           // работает в моде ENABLED_EXTENTION добавьте или уберите не нужные к публикации расширения файлов
$dis_files           = array("index.php", ".", "log.txt");                     // Безусловно запрещены текущий каталог, log.txt и index.php.
$dir                 = "./";                                                   // По умолчанию сканировать будем текущей каталог.
$mode                = EXTENSION_DISABLED;                                     // mode 0 разрешены к публикации файлы всех расширений, кроме перечисленных  в $disabled_extensions
//  $mode            = EXTENSION_ENABLED;                                      // mode 1 запрещены файлы всех расширений кроме перечисленныъ в $enabled_extensions
//*****************    END TO CHANGE    ***************************************//

///////////// VARIABLES //////////////////////                                 // 
    $debug  = 0; 
    $debug1 = false;                                                           // вкл/выкл флаг debug.
    $debug2 = false;                                                           // вкл/выкл флаг debug2. 
    $scan = [];                                                                // Сканируем $dir в аррей $scan.                                                               
    $html = "<div class='container'> <div class='row'>";                       //
    $us   = '';                                                                // здесь будет вычисляться пароль
    $usr  = '';                                                                // здесь будет храниться юзернейм
    $sub  = '';
    $langs_code   = array('en','ru','cz','cn', 'it', 'fr', 'es', 'de');
    $langs_labels = array('English', 'Русский', 'Český', 'Chines', 'Italian', 'French', 'Espanol', 'Deutch');
    $warn_message = array('Incorrect username or/and kaptcha','Неверный Логин и/или Капча', 'Nesedí Login a/nebo Kaptča') ;  
    $btn_value    = array('Enter', 'Войти','Vhod','Enter','Enter','Enter','Enter','Enter','Enter','Enter','Enter','Enter');
    $lbl_value    = array('Password', 'Пароль', 'Heslo', 'Password', 'Password', 'Password', 'Password', 'Password');
    $label        = array(
                         array("For entering into libraryCreate the nickname please",
                               "Для входа в библиотеку Красной Армии, создайте имя пользователя",
                               "For entering into libraryCreate the nickname please",
                               "For entering into libraryCreate the nickname please",
                               "For entering into libraryCreate the nickname please",
                               "For entering into libraryCreate the nickname please",
                               "For entering into libraryCreate the nickname please",
                               "For entering into libraryCreate the nickname please"
                         ),
                         array('Kaptcha. (antispam).<br><small> Enter into the field abow:<br>onehundredninteenfiveonehandredonezerofour by numbers',
                               'КАПЧА (защита от спама).<br><small> Введите в поле ниже:<br> стодевяностопятьстоодиннольчетыре цифрами </small>' ,
                               'Kaptcha. (antispam).<br><small> Enter into the field abow:<br>onehundredninteenfiveonehandredonezerofour by numbers',
                               'Kaptcha. (antispam).<br><small> Enter into the field abow:<br>onehundredninteenfiveonehandredonezerofour by numbers',
                               'Kaptcha. (antispam).<br><small> Enter into the field abow:<br>onehundredninteenfiveonehandredonezerofour by numbers',
                               'Kaptcha. (antispam).<br><small> Enter into the field abow:<br>onehundredninteenfiveonehandredonezerofour by numbers',
                               'Kaptcha. (antispam).<br><small> Enter into the field abow:<br>onehundredninteenfiveonehandredonezerofour by numbers',
                               'Kaptcha. (antispam).<br><small> Enter into the field abow:<br>onehundredninteenfiveonehandredonezerofour by numbers'                         
                         )
                    );
    $lbl          = array('Kaptcha', "Капча", 'Kaptcha', 'Kaptcha', 'Kaptcha', 'Kaptcha', 'Kaptcha', 'Kaptcha', 'Kaptcha', 'Kaptcha');
    $usr_label    = array('User', 'Пользователь', 'Uživátel', 'User', 'User', 'User', 'User', 'User' );
    $lng          = LNG_DEFAULT ;                                                     //язык по-умолчанию - Английский
   
/////////////// FUNCTIONS /////////////////////
    function my_name() {
       return  (basename(__FILE__));        
    }
    //-------------------------------------------------//
    function disabled($b) {
        GLOBAL $dis_files, $disabled_extensions, $debug;
        if($debug){echo("<pre>" . __LINE__ . ": ");print_r($dis_files);echo("</pre>");}
        $c = false;                                                             // $c возвращяет false что онзначает не запрещено.                            
        if(in_array($b, $dis_files)) return true;                                      // in_array(mixed needle, array haystack, [bool strict])         
        if(in_array(get_extension($b),  $disabled_extensions)) return true;            //substr(string string, int start, [int length]) 
        return $c;                                                               //  strrpos($b, '.')
    }
    //--------------------------------------------------
    function get_extension($a='.htsccess'){
      GLOBAL $disabled_extensions;
     // extract($_REQUEST);
      $posr   = strrpos($a, ".") + 1;
      $posl   = strpos($a,".") + 1;                                            //
      $len_   = strlen($a);
      if($len_- $posr){
        if($posl){
         return substr($a, ($posr - $len_)); 
        }else{
         return substr($a, ($posr - $len_ + 1));
        } 
      }
      return  false;   // substr($a, die ("Extension Not Exists"));
    }
    //------------------------------------------------
    function get_ip_address() {
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP',
              'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER) === true) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
                    return $ip;
                }else{
                    return "";
                }
            }
        }
    }
}
    //-----------------------------------------------
    function log_data($usr){
      GLOBAL $debug1;
      $log_mess   =  "\n" . date(" d.m.Y H:i ") . "  ||  " . get_ip_address(). "  ||  " . $usr . "  ||  " .  $_SERVER['HTTP_USER_AGENT'];   //создаем строку для ЛОГФАЙЛА
      $log_header = " ____ДАТА и ВРЕМЯ__ || ___IP____   || ПОСЕТИТЕЛЬ  || УСТРОЙСТВО...";
      $logfile    = LOG_FILE_DIR . DIRECTORY_SEPARATOR . LOG_FILE_NAME;
      if($debug1) echo("<hr> index.php in " .__LINE__. " \$log_mess = $log_mess");
      if (FALSE === is_dir(LOG_FILE_DIR)){mkdir(LOG_FILE_DIR); }
      if((FALSE == file_exists($logfile)) || (0)) { $log_mess = $log_header . $log_mess;}     // добавляем строку заголовка     
      $_t = fopen($logfile, "a");
      $need_h = file($logfile);                                                     // прочитаем логфайл в аррей 
      if(count($need_h)%20 ==19) { $log_mess = "\n" . $log_header . $log_mess;}     // если 19 строк уже прошло с последнего заголовка, то добавляем новый заголовок
      fwrite($_t, $log_mess);
      fclose($_t);

    }
////////////////// MAIN ver 3.xx /////////////////////////////    
     extract($_REQUEST);                                                                      // примем все переменные из GET и перепишем из значение по-умолчанию
     extract($_POST);                                                                         // перепишем переменные данными, полученными из POST (у нас имеет наивысший прриоритет)
     if (($usr=="")||($us!= USER_PASSWORD)) {                                                 // проверяем корректность входа и отсеиваем спам
     
        $htm = "<html><head>
        <link rel=\"stylesheet\" href=\"https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css\">
        <style> input{
                      padding:10px;
                      margin:10px;
                      font-family: Arial, Tahoma;
                      font-size:  xx-large;
                      size:10;
                      color:#353;
                 }
                 #footer{
                      padding:0 10px;
                      text-align:center;
                 }
                 .pannel{
                      margin:20px;
                      padding-min:10px;
                      text-align: center;
                      font-size: xx-large;
                      border: dotted gray 2px;      
                  }
                  .podskazka{
                      font-style: italic;
                      color: green;
                  }
                  </style> </head><body>
                   <div class='container'>";
     if ($sub===$btn_value[$lng]) $htm .= "<div class= 'row'>
                                             <div class='btn-warning'>
                                               <h4><span style='color:red;'>". $warn_message[$lng] . "</span></h4>
                                             </div>
                                           </div>";
        echo($htm . '<div class="row"><div class="pannel btn-info">
        <h4>'. $label [0][$lng]. '</h4>
        <form action="#" name="form" id="form"  method="post" target="_self">
               <label for="usr" title="'.$usr_label[$lng].'">'.$usr_label[$lng].'</label>
                <input type="text" name="usr" value="">   
                <br>
                <div class="podskazka"><hr>'.$label[1][$lng].' </div>
                <br>
                <label for="us" title="'.$lbl[$lng].'">'.$lbl[$lng].'</label>
                <input type="text" name="us" 
                <br>
                <br>
                <input type="submit" name="sub" value="'.$btn_value[$lng].'">    
                </form></div></div></div></body></html>');
                
        exit;   
     }
     /////////////////////////////////////
     log_data($usr);
    $scan=scandir($dir)  ;
    if($debug2) {echo("<pre>"); print_r($scan);echo("</pre>"); }                 // Конструкция для проверки.
    $disabled_files = my_name();                                                // Запрещяем работать с самими собой.
    
    
    for($i=0;$i<count($scan); $i++) {                                           // Читаем содержимое каталога по-штучно.       
       switch ($scan[$i]) {                                                     // Включаем фильтр для определение $nadpis.
    //    case(".")  : break;                                                     // $nadpis = "Повторить сканирование"; break;                  // Меняем . на Повторить сканирование для $nadpis.
        case("..") : $nadpis = "<< Возврат";   break;                  // Меняем .. на Выйти из директории для $nadpis.
        default    : $nadpis = $scan[$i]; break;                                    // По-умолчанию берём имя файла.
       }
       
       if($i===0)    {$html  .= "<a href=\"javascript:history.back();\"> <div class='col-lg-12 place'> << ВОЗВРАТ </div> </a>";                                              //            <a href='"   . $scan[$i] . "'><div class='col-lg-12 place'>" . $nadpis . "</div></a>";
       }elseif($i===1){$html .= ""; 
       }else{
      if(!disabled($scan[$i])) $html                 .= "<a href='"  . $dir. $scan[$i] . "?lng=".$lng."&amp;usr=".$usr."'><div class='col-lg-2 col-md-3 col-sm-4 place'>" . $nadpis . "</div></a> " ;                  // Собираем html строку из содержимого.
       }
    }  
    $html    .= "</div></div>";
   /*
   <div class="card border-info mb-3" style="max-width: 20rem;">
  <div class="card-header">Header</div>
  <div class="card-body">
    <h4 class="card-title">Info card title</h4>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
  </div>
</div>
   
   
   */ 
    
?> 
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php echo(APP_TITLE)?></title>
    <style>
    h1,h2,h3{
    color: #58a!important;
    }
    input{
    padding:10px;
    font-family: Arial, Tahoma;
    font-size:  xx-large;
    }
    #footer{
    padding:0 10px;
    text-align:center;
    }
    .pannel{
    text-align: center;
    font-size: xx-large;
    border: dotted gray 2px;      
    }
    .place{
    padding:5px; 
    margin:0px;
    border:thin solid #fff;
    background:#dde;
    color:#04a;
    border-radius:3px;
    text-align:center;
     transition:  1s easyin;
    }
    .place:hover{
    background-color:#ccf;
    }
    .gray-black{
    background-color: #aaa;
    color:#fff;
    font-family:Arial; 
    padding: 0, 10px 0, 10px;   
    }
    
    </style>
    <script type="text/javascript">
     
    </script>
</head>
<body>
    <div class="container">
        <div class="row">
    <h2><?php echo(APP_TITLE)?></h2>    
            <?php echo($html);?>
        </div>
    </div>
    <div class="container">
    <div class="row">
    <footer class="gray-black">
  <footer class="gray-black" id="footer">
    Internet-Mini. Однофайловый Сайт-визитка АвтоПубликатор . &copy; aekap ITC 2021, web: <a href="http://aekap.c-europe.eu">aekap.c-europe.eu</a>
    <br> git: <a href="https://github.com/alexeist/-Internet-Minimalism-Technology">Internet Minimalism Technology</a>   
    </footer>
    </footer>
    </div>
    </div>
    
    
</body>
</html>
<?php


?>
