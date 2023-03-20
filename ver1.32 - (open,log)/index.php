<?php
/**********************************************************************************
Autopublicator v 1.32 Open. Автор Алекс Эйст 2021 aekap ITC Пожалуйста, не удаляйте эту строку комментария при использовании
* Этот скрипт предназначен для совместной работы с Автопубликатором версии З.02 и выше, которая передает в запросе имя пользователя.
* Для самостоятельной работы без логистики трафика используйте версию Autopublicator v 1.31
* Этот сценарий публикует содержимое каталога $dir и записывает в данные о посещении: время, IP имя пользователя и с какого устройства входил.
* Каждый файл/extentrion или каталог может быть включен или нет, отключен или нет от публикации
* В этой версии история посещений хранится в файле ./log.txt
* ------------ README -------------------------------------------------------------
* 1 Что  МоЖНО изменить перед использованием?
*  1.2 APP_TITLE                                                                 // очень желательно
*  1.3 $dis_files                                                                // если нужно запретить к публикации конкретные файлы в текущей директории
*  1.4 $mode                                                                     // можно использовать ENABLED_EXTENTIONS или DISABLED_EXTENTIONS)
*  1.5 $enabled_extentions                                                       // при использовании $mode = ENABLED_EXTENTIONS
*  1.6 $disabled_extentions                                                      // при использовании $mode = DISABLED_EXTENTIONS
* 2
*  2.1 Если вы хотите отключить все и включить только несколько расширений файлов для публикации, используйте режим EXTENSION_ENABLED и отредактируйте переменную $ enabled _ extentions
*  2.2 Если вы предпочитаете включить и отключить только несколько расширений файлов для публикации, используйте режим EXTENSION_DISABLED и отредактируйте переменную $ disabled _ extentions
*---------------------------------------------------------------------------------- 
* ПРИМЕЧАНИЕ: имена файлов/каталогов, перечисленные в массиве $ dis _ files, не будут опубликованы в обоих режимах EXTENSION_DISABLED и EXTENSION_ENABLED
 **********************************************************************************/
//////////// CONSTANTS ////////////////////////////////////////////////////////////
define('EXTENSION_DISABLED',                                0);                  // для моды: РАЗРЕШЕНЫ все расширения кроме перечисленных в $disabled_extentions
define('EXTENSION_ENABLED',                                 1);                  // для моды ЗАПРЕЩЕНЫ все расширения кроме перечисленных в  $enabled_extentions
define('en',                                                 0);
define('ru',                                                 1);
define('cz',                                                 2);
define('cn',                                                 3);
define('it',                                                 4);
define('fr',                                                 5);
define('es',                                                 6);
define('de',                                                 7);
//*****************    START TO CHANGE ******************************************//
//                   CONST                                                       //  
define ('APP_TITLE', "EXTERNET MINI. Autopublicator v 1.32 Open");               // измените значение этой постоянной согласно ваших нужд
define('USER_PASSWORD',                              19510104  );                // Капча - антиспам. Регулярно меняйте его.   
define('LNG_DEFAULT',                                      ru  );                // измените значение языка по-умолчанию на ваш язык
define('LOG_FILE_DIR',                                      '.');                // по-умолчанию папка для ЛОГфайла - корневая. Измените ее на любое недоступное для публикации место
define('LOG_FILE_NAME',                               'log.txt');                // по-умолчанию логфайл доступа к библиотеке                    VARS                                                    //
$disabled_extensions = array("exe", "com", "bat", "php", "js", "c", "h", "py", "java", "ini", "cfg");// работет в моде 0 DISABLED_EXTENTION  добавьте или удалите ненужные к запрещению публикации расширения
$enabled_extentions  = array("txt", "pdf", "odt", "doc","docx", "mp3","mp4", "ogg", "avi");          // работает в моде ENABLED_EXTENTION добавьте или уберите не нужные к публикации расширения файлов
$dis_files           = array("index.php", ".", "log.txt");                       // Запрещенные безусловно файлы
$dir                 = "./";                                                     // По умолчанию сканировать будем текущей каталог.
$mode                = EXTENSION_DISABLED;                                       // mode 0 разрешены к публикации все расширения, кроме перечисленных  в $disabled_extentions
//  $mode            = EXTENSION_ENABLED;                                        // mode 1 запрещены все расширения кроме перечисленныъ в $enabled_extensions
//*****************    END TO CHANGE    ******************************************/

///////////// VARIABLES /////////-------------------------------------------------- 
    $debug  = 0; 
    $debug1 = false;                                                             // вкл/выкл флаг debug.
    $debug2 = false;                                                             // вкл/выкл флаг debug2. 
    $scan = [];                                                                  // Сканируем $dir в аррей $scan.                                                               
    $html = "<div class='container'> <div class='row'>";                         //
    $us   = '';                                                                  // здесь будет вычисляться пароль
    $usr  = '';                                                                  // здесь будет храниться юзернейм
    $sub  = '';
    $langs_code   = array('en','ru','cz','cn', 'it', 'fr', 'es', 'de');
    $langs_labels = array('English', 'Русский', 'Český', 'Chines', 'Italian', 'French', 'Espanol', 'Deutch');
    $warn_message = array('Incorrect username or/andл kaptcha','Неверный Логин и/или Капча', 'Nesidi Logon a/nego Kaptcha', 'Incorrect username or/andл kaptcha', 'Incorrect username or/andл kaptcha', 'Incorrect username or/andл kaptcha', 'Incorrect username or/andл kaptcha', 'Incorrect username or/andл kaptcha') ;  
    $btn_value    = array('Send', 'Послать','Poslat','Send','Send','Send','Send','Send');
    $lbl_value    = array('Password', 'Пароль', 'Heslo', 'Password', 'Password', 'Password', 'Password', 'Password');
    $usr_label    = array('User', 'Пользователь', 'Uživátel', 'User', 'User', 'User', 'User', 'User' );
    $lng          = LNG_DEFAULT ;                                                //язык по-умолчанию - Английский
   
/////////////// FUNCTIONS /////////////////////////////////////////////////////////
    function my_name() {
       return  (basename(__FILE__));        
    }
    //-----------------------------------------------------------------------------
    function disabled($b) {
        GLOBAL $dis_files, $disabled_extensions, $debug;
        if($debug){echo("<pre>" . __LINE__ . ": ");print_r($dis_files);echo("</pre>");}
        $c = false;                                                              // $c возвращяет false что онзначает не запрещено.                            
        if(in_array($b, $dis_files)) return true;                                // in_array(mixed needle, array haystack, [bool strict])         
        if(in_array(get_extension($b),  $disabled_extensions)) return true;      //substr(string string, int start, [int length]) 
        return $c;                                                               //  strrpos($b, '.')
    }
    //-----------------------------------------------------------------------------
    function get_extension($a='.htsccess'){
      GLOBAL $disabled_extensions;
     // extract($_REQUEST);
      $posr   = strrpos($a, ".") + 1;
      $posl   = strpos($a,".") + 1;                                              //
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
   
////////////////// MAIN //////////////////////////////////////    
    $usr = "Guest";
     extract($_REQUEST);
    
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
