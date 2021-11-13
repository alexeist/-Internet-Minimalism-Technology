
<?php
/*************************************************************************
* Universal Autopublicator v 4.20211023. Multitypes library, unlimited deep of categories subcategories  Autentication, log-files, Singl root directory, unlimited deep paths. Автор Алекс Эйст 2021 aekap ITC Пожалуйста, не удаляйте эту строку комментария при использовании
* Коды написаны под MIT лицезнией как Свободное Программное Обеспечение для широкого применения как администраторами как ХТТП-приложение, так и разрабочиками как сырье для своих более крупных проектов. 
* Использование кодов не требует оплаты и автор не несет никакой ответственности за возникшие у пользователя проблемы.
* 
* Этот сценарий публикует содержимое каталога $dir и всех его поддиректорий.
* Каждый файл/расширение файла или каталог может быть включен или нет, отключен или нет к публикации
* В этой версии используется пользователь с любым именем пользователя   и одним на всех паролем - защитой от роботов.
* История входа в сайт сохраняется в логфайле - log.txt   При желании можно изменить его местоположение и/или имя 
* В логфайле сохраняется время самого первого входа и регистрации, частота посещений не фиксируется
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
*/

//////////// CONSTNTS ///////////////////////
//            тип мультитеки 
define('EXTENSION_DISABLED',                   0xEf5a222b4580);              // для моды: РАЗРЕШЕНЫ все расширения кроме перечисленных в $disabled_extentions
define('EXTENSION_ENABLED',                    0xEf5a222b4581);              // для моды ЗАПРЕЩЕНЫ все расширения кроме перечисленных в  $enabled_extentions
define('ADMIN_ENABLED',                        0xadfeddeaed72);              // 6 bytes
define('VIDEO_LIBRARY',                        0xEf5a222b1150);                                                                                    
define('BIBLIO_LIBRARY',                       0xEf5a222b1151); 
define('E_SHOP',                               0xEf5a222b1152);
define('APPLICATION_DESK',                     0xEf5a222b1153);
define('BLOG',                                 0xEf5a222b1154);
//           язык интерфейса
define('en',                                      0);
define('ru',                                      1);
define('cz',                                      2);
define('cn',                                      3);
define('it',                                      4);
define('fr',                                      5);
define('es',                                      6);
define('de',                                      7);

//*****************    START TO CHANGE ****************************************//
//          Настраиваемые данные (Персонализация мультитеки)                   //
//                   CONST                                                     //  
define ('APP_TITLE',         " Экстернет мини. Мультитека Б11 ");              // измените значение этой постоянной согласно ваших нужд
define('USER_PASSWORD',                              19510104  );              // Капча для входа для фильтра спама. Регулярно меняйте ее и соотв. текст    
define('LNG_DEFAULT',                                      ru  );              // измените значение языка по-умолчанию на ваш язык
define('LOG_FILE_DIR',                                      '.');              // по-умолчанию папка для ЛОГфайла - корневая. Измените ее на любое недоступное для публикации место
define('LOG_FILE_NAME',                               'log.txt');              // по-умолчанию логфайл доступа к библиотеке                    VARS                                                    //
define('LOG_URI',       'http://schoolonline.unionssr.org/logs');               // пока не используется. лог файл хранится в корневом каталоге
$disabled_extensions = array("exe", "com", "bat", "php", "js", "c", "h", "py", "java", "ini", "cfg"); // работет в моде 0 DISABLED_EXTENTION  добавьте или удалите ненужные к запрещению публикации расширения
$enabled_extensions  = array("txt", "pdf", "odt", "doc","docx", "mp3","mp4", "ogg", "avi");           // работает в моде ENABLED_EXTENTION добавьте или уберите не нужные к публикации расширения файлов

$dis_files           = array("index.php", ".", LOG_FILE_NAME, my_name(), ".htaccess", "include", "system", "sections", "assets");                 // Безусловно запрещены текущий каталог, log.txt и index.php.
$dir                 = ".";                                                     // рабочий директорий без слеша
$current_dir         = $dir . "/";                                              // текущий директорий 
$categories          = array($dir=>[]);                                        // По умолчанию сканировать будем текущей каталог.
$mode                = EXTENSION_DISABLED;                                     // mode 0 разрешены к публикации файлы всех расширений, кроме перечисленных  в $disabled_extensions
$type_site           = VIDEO_LIBRARY;                                          //  вид сайта:  Видеоктека, Библиотека, Е-шоп, Блог
$cards_html          = '';
$cards_array         = [];
//  $mode                = EXTENSION_ENABLED;                                      // mode 1 запрещены файлы всех расширений кроме перечисленныъ в $enabled_extensions
//*****************    END TO CHANGE    ***************************************//


///////////// VARIABLES //////////////////////                                 //  
    $debug2 = false;                                                           // вкл/выкл флаг debug.
    $debug  = false;
    $scan = [];                                                                // Сканируем $dir в аррей $scan.                                                               
    $html = "";                       // ХТМЛ код тела для зарегистрированного посетилитея
    $section_data = '';
    $us   = '';                                                                // здесь будет вычисляться пароль
    $usr  = '';                                                                // здесь будет храниться юзернейм
    $sub  = '';
   
    $langs_code   = array('en','ru','cz','cn', 'it', 'fr', 'es', 'de');
    $langs_labels = array('English', 'Русский', 'Český', 'Chines', 'Italian', 'French', 'Espanol', 'Deutch');
    $warn_message = array('Incorrect username or/and password','Неверный Логин и/или Пароль', 'Nesidi Logon a/nego Heslo', 'Incorrect username or/and password', 'Incorrect username or/and password', 'Incorrect username or/and password', 'Incorrect username or/and password', 'Incorrect username or/and password') ;  
    $btn_value    = array('Enter', 'Войти','Vhod','Enter','Enter','Enter','Enter','Enter','Enter','Enter','Enter','Enter');
    $lbl_value    = array('Password', 'Пароль', 'Heslo', 'Password', 'Password', 'Password', 'Password', 'Password');
    $modal_button_value        = array('See details>', 'Посмотреть детали', 'Smotret podrobností', 'See details>', 'See details>', 'See details>', 'See details>', 'See details>', 'See details>', 'See details>' ); 
    $modal_button_close_value  = array('Close', 'Закрыть', 'Zavřit', 'Close', 'Close', 'Close', 'Close', 'Close', 'Close', 'Close');
    $label        = array(
                         array("For entering into library Create the nickname please",
                               "Для входа в библиотеку создайте/введите имя пользователя",
                               "For entering into library Create/enter the nickname please",
                               "For entering into library Create/enter the nickname please",
                               "For entering into library Create/enter the nickname please",
                               "For entering into library Create/enter the nickname please",
                               "For entering into library Create/enter the nickname please",
                               "For entering into library Create/enter the nickname please"
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
    $open_in_new_window= array('Open in new window', "Открыть в новом окне", 'Open in new window', 'Open in new window', 'Open in new window', 'Open in new window', 'Open in new window', 'Open in new window', 'Open in new window', 'Open in new window');
    $lng          = LNG_DEFAULT ;                                                     //язык по-умолчанию - Английский
    $head= '<head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <sctipt type="text/javascript" src="http://bootstrap/js/bootstrap.min.js">
        <link rel="stylesheet" href="http://bootstrap/css/bootstrap.min.css">
        
        <sctipt type="text/javascript" src="http://bootstrap/js/bootstrap.min.js">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
        
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>' . APP_TITLE .'</title>
        <style>
                h1,h2,h3{
                      color: #58a!important;
                }
                input{
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
                      
                      #footer{
                      padding:0 10px;
                      text-align:center;
                }
                .podskazka{
                      font-style: italic;
                      color: green;
                }      
               .pannel{
                      margin:20px;
                      padding-min:10px;
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
                      
                .welcome{
                      font-size: xx-large;
                      background-color: lightblue;
                      border: navy 4px solid;
                      border-radius: 14px;
                      font-family: Arial, Serif;
                      color: navy;
                      padding: 0 10px;
                
                }
                .debug_mess{
                      border: black 2px solid;
                      border-radius: 3px;
                      padding: 25px;
                      margin: 10px;
                      background-color:#cff;
                      color: #000099;
                }
                .print_mess{
                      border: black 1px solid;
                      border-radius: 3px;
                      padding: 25px;
                      margin: 10px;
                      background-color:#eff;
                      color: #000099;
                }
                .modal_footer{
                      border: thin solid black;
                      background-color:#454545;
                      color:#fff;
                      padding: 0, 5px;
                }
        </style>
        <script type="text/javascript">
         
        </script>
    </head>';

    $html         = "<html>" . $head;
  
    $html2         =  array('content'       => '<section><div class="card_container">',            // класс форматирования секции с карточками
                           'content_end'   => '</div></section>',   
                           'start'         => '<html>',
                           'head'          => '',                         
                           'nav'           => '<div class="container"> <div class="row"><div class = "nav">',
                           'nav_end'       => '</nav></div></div>',
                           'container'     => '<div class="container"><div class ="row">',
                           'container_end' => '</div></div>',
                           'footer'        => '',
                           'end'           => ''
                          );
    
/////////////// FUNCTIONS ///////////////////////////////////////////////////////
    function my_name() {
       return  (basename(__FILE__));        
    }
//--------------------------------------------------    
    function my_path(){
       return(dirname(__FILE__));
    }
//-------------------------------------------------//
    function disabled($b) {
        GLOBAL $dis_files, $disabled_extensions, $debug;   
        // if($debug){echo("<hr> <h3>Function desabled triggered</h3><pre>" . __LINE__ . ": ");print_r($dis_files);echo("</pre>");}
                                                                   // $c возвращяет false что онзначает не запрещено.                            
        if(in_array($b, $dis_files)) return true;                                      // in_array(mixed needle, array haystack, [bool strict])         
        if(in_array(get_extension($b),  $disabled_extensions)) return true;            //substr(string string, int start, [int length]) 
        return false;                                                               //  strrpos($b, '.')
    }
//--------------------------------------------------
    function get_extension($a='.htsccess'){
      $posr   = strrpos($a, ".") + 1;
      $posl   = strpos($a,".") + 1;                                           //
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
}  //--------------------------------------------
    function print_form(){
?>  
<!--
    <div class="container">
      <div class="row">
        <div class = "formular">
          <form action="#" method = "POST">
           <label >Введите ник</label>
           <input name="usr" type="text" />
           <br>
           <label >Пароль</label>
           <input name="kapcha" type="password" />   
           <br>
            <input name="submit" type="submit" value=" Войти " /> 
          </form>
        </div>
      </div>
    </div>
-->    
<?
    }
//-----------------------------------------------
    function create_log_string($f_){
      GLOBAL $debug;
      
      $ret = '';      
      return false;     
}
//-----------------------------------------------
    function log_data($usr){
      GLOBAL $debug;
      $log_mess   =  "\n" . date(" d.m.Y H:i ") . "  ||  " . get_ip_address(). "  ||  " . $usr . "  ||  " .  $_SERVER['HTTP_USER_AGENT'];   //создаем строку для ЛОГФАЙЛА
      $log_header = " ____ДАТА и ВРЕМЯ__ || ___IP____   || ПОСЕТИТЕЛЬ  || УСТРОЙСТВО...";
      $logfile    = LOG_FILE_DIR . DIRECTORY_SEPARATOR . LOG_FILE_NAME;
      if($debug) echo("<hr> index.php in " .__LINE__. " \$log_mess = $log_mess");
      if (FALSE === is_dir(LOG_FILE_DIR)){mkdir(LOG_FILE_DIR); }
      if((FALSE == file_exists($logfile)) || (0)) { $log_mess = $log_header . $log_mess;}     // добавляем строку заголовка     
      $_t = fopen($logfile, "a");
      $need_h = file($logfile);                                                     // прочитаем логфайл в аррей 
      if(count($need_h)%20 ==19) { $log_mess = "\n" . $log_header . $log_mess;}     // если 19 строк уже прошло с последнего заголовка, то добавляем новый заголовок
      fwrite($_t, $log_mess);
      fclose($_t);
    }                                                                               // $d_  имя файла элемента (картинки, видео или объекта)
//--------------------------------------------- создаем  хтмл боди. Аргументы: файл (не дир!) и тип мультитеки
    function make_cards($d_, $f=VIDEO_LIBRARY){                                   // $d_ текущий директорий $f = тип сайта $type_site
      $ret = "";
      switch($f){
                 case VIDEO_LIBRARY:
                         $ret = create_video_card($d_);
                         break;
                 case BIBLIO_LIBRARY:
                         $ret = create_biblio_card($d_);
                         break;
                 case E_SHOP:
                         $ret = create_eshop_card($d_);
                         break;
                 case APPLICATION_DESK:
                         $ret = create_annonce_card($d_);
                         break;
                 case ADMIN:
                         $ret = FALSE;
                         break;
                 default:
                         break;
      }
      
      return $ret;
    }
//-----------------------------------------------------   
    function create_biblio_card($d_){
      $ret="";
      return $ret;
    }
//-----------------------------------------------------   
    function create_eshop_card($d_){
      $ret="";
      return $ret;
    }
//-----------------------------------------------------   
    function create_annonce_card($d_){
      $ret="";
      return $ret;
    }        
//-------------------------------------------------
    function is_video($f){
      if(substr($f, -3) == "mp4") return true;
      return false;
    } 
//------------------------------------------------- htmlentities(string string, [int quote_style], [string charset])                           // получаем имя файла возвращаем хтмл карточки с видео плейером
    function create_video_card ($c_){
      GLOBAL $debug2, $debug, $lng, $current_dir, $open_in_new_window;
      $ret = '';
      $fname = substr($c_, 0, strlen($c_)-4);
 if($debug) print_mess(__LINE__ . " \$current_dir = " . $current_dir . " | \$c_:".$c_. "  | \$fname = " . $fname);    
      $handle = fopen($current_dir . $fname . ".txt", "a");                                     //открываем текстовый файл с описанием. если не имеется, то создаем новый пустой 
      fclose($handle);
                               
      $image = $current_dir . $fname . ".jpg";
      $modal = create_modal($fname);
 if($debug2) print_mess(__LINE__ . "\$modal is created = on \$fname = " . $fname . "   \$modal =<div style='color:green; border:thin black solid; height-min:2pt;background-color:#ccc'>" . $modal . "<hr><br><br></div>" );
     
       $ret = '<div class="row"><div class="col-lg-6 col-md-6 col-sm-12 " style="padding:0; margin: 1px 0">            
                  <div class="container" style="margin-right:2px; padding:2px;border: 1px solid #ffffff; background-color:#eee;">
                           
                    <div class="row card cart-block" style="border:1px thin #000;border-radius:3px;margin-right:2px">                  
                      
                      <!-- Start Player -->
                      <div class="card-img col-lg-6 col-md-8 col-sm-12">                  
                          <div class="mbr-table-cell mbr-center-padding-md-up mbr-valign-top">                     
                              <div class="mbr-figure" style="padding-top:20px">                      
                                  <video controls width="100%" height="auto" poster="'. $image . '" preload="none">                         
                                      <source src="' . $current_dir .  $fname .'.mp4" type="video/mp4"><
                                                                                                                                                   
                                  </video>                                       
                              </div>              
                          </div>                   
                      </div>  
                      <!-- end player-->                                     
                      
                      <div class="col-lg-6 col-md-4 col-sm-12" style="text-align:left; color:#444">                                               
                          <a class="link1" href="' . $current_dir . $fname .'.mp4">  
                          <h5 class=""> ' . $open_in_new_window[$lng] .'</h5></a>                    
                          <p class="card-modal"> ' . 
                          $modal .                                               
                        ' </p>                                                        
                      </div>
                                      
                    </div> 
                  </div>                            
               </div>
               </div>';
      
      //------------------------  
      return $ret;
    }
//-------------------------------------------------                                    //$t_video name
   function create_modal($t_){
   GLOBAL $current_dir, $modal_button_value, $modal_button_close_value, $lng, $debug2,$debug;
   $ret='';
   $f = $current_dir . $t_. ".txt";
   if($debug) {
        if (!file_exists($f)) {
             print_mess(__LINE__ . "<span style=\color:red; border: thin solid red; padding:5px;\"> Can't open file " . $f); 
             return NULL;
        }else{
             print_mess(__LINE__ . " f. create_modal. Open file " . $f);
             
        }
    }
//  if($debug) print_mess(__LINE__ . "")                  
   $_cont= file($f) ;   
  if(count($_cont)>1 ){ 
      
      $content_ = get_modal_content( $_cont);
      
      if($debug2) {                    
                    print_mess(__LINE__ . "  Text file " . t_ . " is readeble array = " . $_cont);
                    print_mess(__LINE__ . "  function get_modal_content was triggered ");
                    print_mess(__LINE__ . "  Text from " . $t_ . " was succes readed, content is ". $content_ . "<hr>");
                   }
    }else{
      print_mess(__LINE__ . "  Can't make array from file " . $f );
    }
   if($debug) {
                 echo("<br>" .__LINE__ . " файл \$f=" . $f . "<br>\$_cont=<pre>");
                 print_r($_cont);
                 echo("<br>\$content_=");
                 print_r($content_);
                 echo("</pre>");
    }             
   $ret = '<div class="modal">' . $content_ . '</div>';
   
   return ($content_ );
   }   
//-------------------------------------------------
   function get_modal_content($_= NULL){                                  // вызывается из 429 стр
   GLOBAL $debug,$debug;
     if($debug) print_mess(__LINE__ . "  triggered get modal content with argument= ", $_);
     if (false == is_array($_)){print_mess(__LINE__."Аргумент должен быть арреем с числом элементов больше нуля"); return NULL;}
     
     $ret='<h2>' . $_[0] . "</h2><h4>" . $_[1] . "</h4><p>";
     $rows= count($_);
     
     for($i=2;$i<$rows-1;$i++){
         if(strlen(trim($_[$i]))==0) {$ret .= "</p><p>";}else {$ret .= $_[$i];}
     }
     
     $ret .= '</p><div class="modal_footer" >' . $_[$rows-1] . "</div>";
     if ($debug) print_mess(__LINE__ . " Content modal=" . $ret);
     return $ret;
   }   
//-------------------------------------------------
   function get_fname($a='.htaccess'){
      GLOBAL  $debug;
     // extract($_REQUEST);
 if($debug) print_mess(__LINE__ . "  triggered get_fname(".$a.")");
      $posr   = strrpos($a, ".") + 1;
      $posl   = strpos($a,".") + 1;                                            //
      $len_   = strlen($a);
      if($len_> $posr){   
 if($debug) print_mess(__LINE__ . "  \$posr:" . $posr . ", \$posl:" . $posl . ", \$len:" . $len);                
         return substr(0, $posr);         
        } 
     
      return  false;   // substr($a, die ("Extension Not Exists"));
    }        
//------------------------------------------------- $d_ - имя файла без расширения $f - moda x-теки
    function make_array_from_file_name($d_, $f){
     $ret = [];
     
     return $ret;
    }       
//------------------------------------------------
    function make_card_from_array($d_){
      GLOBAL $debug, $lng;
      $ret=[];
      
    
      return $ret;
    }
//------------------------------------------------
    function scan_usr($usr){
      if ($usr==NULL) $usr=sha1(__FILE__);
      $r_= file(LOG_FILE_NAME);
      foreach($r_  as $id=>$value){
        if (substr_count($value, $usr)>0) return true;
      }
      return false;    
    }    
//------------------------------------------------
    function debug_mess($t=''){
      echo("<div class='debug_mess'> $t </div>");
    }
//------------------------------------------------
    function print_mess($t='', $ar = NULL){
      echo("<div class='print_mess'> $t </div>");
      if (is_array($ar)){
       echo("<pre>");
       print_r($ar);
       echo("</pre>");
      }
    }
            
////////////////// MAIN ver 3.xx /////////////////////////////    
     extract($_REQUEST);
     if($debug) { 
   //     echo("<div class='debug_mess'> Main in " .__LINE__. " \$_POST =<pre>"); print_r($_POST); echo("</pre></div>"); 
   //     echo("<div class='debug_mess'> Main in " .__LINE__. " \$_REQUEST =<pre>"); print_r($_REQUEST); echo("</pre></div>"); 
          print_mess(basename(__FILE__) .  " (стр." . __LINE__ . ")<div class='welcome'> " . " Добро пожаловать, на наш сайт, " . $usr . "</div>");         
     }                                                                      // примем все переменные из GET и перепишем из значение по-умолчанию
     //extract($_POST);                                                                         // перепишем переменные данными, полученными из POST (у нас имеет наивысший прриоритет)
/////////////////////////////////////
    if (!scan_usr($usr)) {
     if(strlen($usr)>0 ){ 
         log_data($usr);
     if(count($_POST)>0 ) { 
        if($debug)  {echo("<div class='debug_mess'> Main in " .__LINE__. " \$_POST =<pre>"); print_r($_POST); echo("</pre></div>");}   
        print_mess(basename(__FILE__) .  " (стр." . __LINE__ . ")<div class='welcome'> " . " Добро пожаловать, на наш сайт " . $usr . "</div>");         
     }else{
          if(count($_REQUEST)<4)print_form();
     }
    }
    }
    switch($lng){
    case ('ru'): $lng= ru; break;
    case ('en'): $lng= en; break;
    case ('cz'): $lng= cz; break;
    case ('it'): $lng= it; break;
    case ('de'): $lng= de; break;
    default    : $lng= ru; break;
    }
     if (($usr=="")||($us!= USER_PASSWORD)) {                                                 // проверяем корректность входа и отсеиваем спам                  
        if ($sub===$btn_value[$lng]) $html .= "<div class= 'row'>
                                             <div class='btn-warning'>
                                               <h4><span style='color:red;'>". $warn_message[$lng] . "</span></h4>
                                             </div>
                                           </div>";
        echo($html . '<body><div class="row"><div class="pannel btn-info">
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
     }                                                                     // end form

/////////////////////////// MAIN ////////////////////////// 
//--------------- start navigator ------------------------------
   
    
    $scan=scandir($current_dir)  ;  
    //   if($debug) {echo("<div class='debug_mess'> l:" . __LINE__ . ">\$scan = <pre>"); print_r($scan);echo("</pre>"); }                 // Конструкция для проверки.
    //  $dis_files[] = my_name();                                                   // Запрещяем работать с самими собой на случай, если этот файл не index.php.
      
    for($i=0;$i<count($scan); $i++) {                                           // Читаем содержимое каталога по-штучно.       
       $_    = $scan[$i] ;                                                      // очередной элемент сканирования
 // анализ содержимого текущего директория
    if(!disabled($_)){                                                           // если объект не запрещен...
     if((is_dir($_))){                                                           // это директорий, делаем кнопку навигатора        
        switch ($_) {                                                            // Включаем фильтр для определение $nadpis.               
          case("..") : $nadpis   = "<< Возврат";
                       $html2['nav']     .= "<a href=\"javascript:history.back();\"> <div class='col-lg-12 place'>" . $nadpis . "</div> </a>";        // 
                       break;                                                     // Меняем .. на Выйти из директории для $nadpis
          default    :                                                            // Кнопка подкатегории                       
                       $nadpis   = $_;                                                                   
                       $uri  =  htmlentities($current_dir) .                    // путь считать относительно корневого каталога             
                                basename(__FILE__) .                            //htmlentities($_) . 
                                "?lng=" .
                                $lng .
                                "&amp;usr=" . 
                                $usr .
                                "&amp;us=" .
                                USER_PASSWORD .
                                "&amp;current_dir=" .
                                $current_dir . $_ . "/" ;               
                        $html2['nav'] .= "<a href='" . $uri . "'><div class='col-lg-2 col-md-3 col-sm-4 place'>" . 
                                      $nadpis . "</div></a> " ;                  // Собираем html строку из содержимого.
// if($debug) {echo("<div class='debug_mess'> l:" . __LINE__ . ">\$scan = <pre>"); print_r($_);echo("</pre>"); }                 // Конструкция для проверки.            
                      
                       break;                                // По-умолчанию берём имя файла.
        } // end switch
//----------   // конец навигатора Это не директорий. Это какой-то файл -------------- 
     }elseif (is_video($_)){                                  // это видео файл?
      $html2['content']  .= create_video_card($_);
     
     } // end is NOT dir
    }else{ //end ! is_disabled         
//if($debug) {echo("<div class='debug_mess'> l:" . __LINE__ . ">\$_ is Disabled = <pre>"); print_r($_);echo("</pre>"); }                 // Конструкция для проверки.       
    }
  } //end Scans current_dir 
                                            
///////////////////////////////////// main //////////////////////////////////////////////    
  // $html2['content']  = make_cards($dir) ;
   $html2['start']    = '
    <html> ' . 
    $head . '
    
    <body>
        <div class="container">
            <div class="row">
        <h2>' . APP_TITLE . '</h2>
    ';                                       //end   \$html2['start']
    
$html2['content_end'] = '</div></div>';
$html2["footer"]      =  '<div class="container">
    <div class="row"> 
      <footer class="gray-black" id="footer">
        ЭКСТЕРНЕТ МИНИ. Автопубликатор LiLi 3.12. &copy; aekap ITC 2021, site: <a href="http://aekap.c-europe.eu">aekap.c-europe.eu</a>
    <br> git: <a href="https://github.com/alexeist/-Internet-Minimalism-Technology">Internet Minimalism Technology</a>   
    </footer>   
    </div>
    </div>
';        
 //------------------------- PRINT------------------------
 echo("<html>" .  $html2['start'] . 
                  $html2['container'] .
                  $html2['nav']. 
                  $html2['nav_end'].
                  $html2['container_end'] .
                  $html2['container'] .
                  $html2['content'] .
                  $html2['content_end'] .
                  $html2['container_end'] .  
                  $html2["footer"] . 
                  $html2['end']);
 
 ?>
    
    
    
    
</body>
</html>
<?php


?>