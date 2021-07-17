<?php
/*************************************************************************
 *  Autopublicator v 3.01 Autentication.  Author Alex Eist 2021 aekap ITC   Please do not remove this row of comment when use
 *  
 * This script does publishes the content of directory $dir.
 * Every file/extentrion or directory can be enabled or not, disabled or not 
 * This version uses only single user with any username and hard scripted password
 *  Change the password before use 
 *  The user field is included for security reason and not used in the unblocking the publishing process
 *  -------------  README -------------------------
 *  1 What to change before using?
 *   1.1 USER_PASSWORD
 *   1.2 APP_TITLE
 *   1.3 $dis_files
 *   1.4 $mode                (you can use ENABLED_EXTENTIONS or DISABLED_EXTENTIONS)
 *   1.5 $enabled_extentions  (if use $mode = ENABLED_EXTENTIONS)
 *   1.6 $disabled_extentions (if use $mode = DISABLED_EXTENTIONS)
 *  2 
 *   2.1 If you whant to disable all and to enable only few file extentions to publish please use the mode EXTENTION_ENABLED  and edit the variable  $enabled_extentions
 *   2.2 If you prefer to enable all and disable only few file extentions to publish please use the mode EXTENTION_DISABLED and edit the variable  $disabled_extentions
 *  NOTE: the files/directories names, listed in the array $dis_files will not published in both modes   EXTENTION_DISABLED and EXTENTION_ENABLED 
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
define ('APP_TITLE', "MC CE, aekap ITC, Radio-CE, Reklama-CE"  );              // измените значение этой постоянной согласно ваших нужд
define('USER_PASSWORD',                              19510104  );              // пароль для входа. Обязательно ИЗМЕНИТЕ его   
define('LNG_DEFAULT',                                      ru  );              // измените значение языка по-умолчанию на ваш язык
//                     VARS                                                    //
$disabled_extentions = array("exe", "com", "bat", "php", "js", "c", "h", "py", "java", "ini", "cfg");// работет в моде 0 DISABLED_EXTENTION  добавьте или удалите ненужные к запрещению публикации расширения
$enabled_extentions  = array("txt", "pdf", "odt", "doc","docx", "mp3","mp4", "ogg", "avi");          // работает в моде ENABLED_EXTENTION добавьте или уберите не нужные к публикации расширения файлов
$dis_files           = array("index.php", ".");                                                      // По-умолчанию разрешены все файлы.
$dir                 = "../";                                                                         // По умолчанию сканировать будем текущей каталог.    
$mode                = EXTENTION_DISABLED;                                      // mode 0 разрешены к публикации все расширения, кроме перечисленных  в $disabled_extentions
//  $mode            = EXTENTION_ENABLED;                                       // mode 1 запрещены все расширения кроме перечисленныъ в $enabled_extentions
//*****************    END TO CHANGE    ****************************************//

///////////// VARIABLES //////////////////////                               // 
    $debug  = 0;                                                             // вкл/выкл флаг debug.
    $debug2 = false;                                                         // вкл/выкл флаг debug2. 
    $scan = [];                                                              // Сканируем $dir в аррей $scan.                                                               
    $html = "<div class='container'  ><div class='row'>";                    // файлы запрещенные к публикации
    $us    = '';
    $sub   = [[]] ;
    $langs_code   = array('en','ru','cz','cn', 'it', 'fr', 'es', 'de');
    $langs_labels = array('English', 'Русский', 'Český', 'Chines', 'Italian', 'French', 'Espanol', 'Deutch');
    $warn_message = array('Incorrect username or/and password','Неверный Логин и/или Пароль', 'Nesidi Logon a/nego Heslo') ;  
    $btn_value    = array('Send', 'Послать','Poslat','Send','Send','Send','Send','Send');
    $lbl_value    = array('Password', 'Пароль', 'Heslo', 'Password', 'Password', 'Password', 'Password', 'Password');
    $usr_label    = array('User', 'Пользователь', 'Uživátel', 'User', 'User', 'User', 'User', 'User' );
    $lng          = LNG_DEFAULT ;                                                     //язык по-умолчанию - Английский
/////////////// FUNCTIONS /////////////////////
    function my_name() {
       return  (basename(__FILE__));        
    }
    //-------------------------------------------------//
    function disabled($b) {
        GLOBAL $dis_files, $disabled_extentions, $debug;
        if($debug){echo("<pre>" . __LINE__ . ": ");print_r($dis_files);echo("</pre>");}
        $c = false;                                                             // $c возвращяет false что онзначает не запрещено.                            
        if(in_array($b, $dis_files)) return true;                                      // in_array(mixed needle, array haystack, [bool strict])         
        if(in_array(get_extension($b),  $disabled_extentions)) return true;            //substr(string string, int start, [int length]) 
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
////////////////// MAIN //////////////////////////////////////    
    
     extract($_POST);
     if ($us/812921 != 24 ){
        $html = "<div class='container'>";
        if ($sub===$btn_value[$lng]) $html .= "<div class= 'row'><div class='btn_warning'><h4><span style='color:red;'>". $warn_message[$lng] . "</span></h4></div></div>";
        echo($html . '<div class="row"><div class="pannel btn_info"><form action="#" name="form" id="form"  method="post" target="_self">
               <label for="usr" title="'.$usr_label[$lng].'">'.$usr_label[$lng].'</label>
                <input type="text" name="usr">   
                <br>
                <label for="us" title="'.$lbl_value[$lng].'">'.$lbl_value[$lng].'</label>
                <input type="password" name="us">
                 <br>
                <input type="submit" name="sub" value="'.$btn_value[$lng].'">    
                </form></div></div></div>');
                
        exit;   
     }
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
      if(!disabled($scan[$i])) $html                 .= "<a href='"  . $dir. $scan[$i] . "'><div class='col-lg-2 col-md-3 col-sm-4 place'>" . $nadpis . "</div></a> " ;                  // Собираем html строку из содержимого.
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
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <title><?php echo(APP_TITLE)?></title>
    <style>
    h1,h2,h3{
    color: #58a!important;
    }
    #footer{
    padding:0 10px;
    text-align:center;
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
    </footer>
    </footer>
    </div>
    </div>
    
    
</body>
<?pho

?>