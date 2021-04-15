<?php
    // VARIABLES
    $debug  = 0;                                                             // вкл/выкл флаг debug.
    $debug2 = false;                                                            // вкл/выкл флаг debug2.
    $dir = "./";                                                                // По умолчанию сканировать будем текущей каталог. 
    $scan = [];                                                      // Сканируем $dir в аррей $scan.                                                               
    $html = "<div class='container'><div class='row'>";
    $dis_files = array(my_name(),"1");                                                       // По-умолчанию разрешены все файлы.
    $disabled_extentions = [];                                                   // По-умолчанию разрешены все форматы.
    $title = "Magic Stick Автопубликатор";
    $dir   = './';
    // FUNCTIONS
    function my_name() {
        GLOBAL $debug;
       return  (basename(__FILE__));        
    }
    //-------------------------------------------------//
    function disabled($b) {
        GLOBAL $dis_files,$debug;
        if($debug){echo("<pre>" . __LINE__ . ": ");print_r($dis_files);echo("</pre>");}
        $c = false;                                                             // $c возвращяет false что онзначает не запрещено.                            
        if(in_array($b, $dis_files)) return true;                                      // in_array(mixed needle, array haystack, [bool strict])         
        if(in_array(get_extension($b), $dis_files)) return true;            //substr(string string, int start, [int length]) 
        return $c;                                                               //  strrpos($b, '.')
    }
    //--------------------------------------------------
    function get_extension($a='.htsccess'){
      GLOBAL $disabled_extensions;
     // extract($_REQUEST);
      $posr   = strrpos($a, ".")+1;
      $posl   = strpos($a,".")+1;                                            //
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
    
     extract($_REQUEST);
    $scan=scandir($dir)  ;
    if($debug2) {echo("<pre>"); print_r($scan);echo("</pre>"); }                 // Конструкция для проверки.
    $disabled_files = my_name();                                                // Запрещяем работать с самими собой.
    
    
    for($i=0;$i<count($scan); $i++) {                                           // Читаем содержимое каталога по-штучно.       
       switch ($scan[$i]) {                                                     // Включаем фильтр для определение $nadpis.
        case(".")  : $nadpis = "Повторить сканирование"; break;                  // Меняем . на Повторить сканирование для $nadpis.
        case("..") : $nadpis = "Выйти из директории";   break;                  // Меняем .. на Выйти из директории для $nadpis.
        default    : $nadpis = $scan[$i]; break;                                    // По-умолчанию берём имя файла.
       }
       
       if($i===0)    {$html  .= "<a href='"   . $scan[$i] . "'><div class='col-lg-6 place'>" . $nadpis . "</div></a>";
       }elseif($i===1){$html .= "<a href='"   . $scan[$i] . "'><div class='col-lg-6 place'>" . $nadpis . "</div></a>"; 
       }else{
      if(!disabled($scan[$i])) $html                 .= "<a href='" . $scan[$i] . "'><div class='col-lg-2 col-md-3 col-sm-4 place'>" . $nadpis . "</div></a>" ;                  // Собираем html строку из содержимого.
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