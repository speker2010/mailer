<?php
    
   //var
    $debug = false;
    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
    $mail = "speker@mail.ru";
    $site = "site.ru";
    //functions
    //diagnos если переменная $debug = true то в браузер выведется текст сообщения письма
    function diagnos($message){
        global $debug;
        if($debug===TRUE){
            echo $message."</br>";
        }
    }
    //dumpArray преобразует массив в маркированный список
	function dumpArray($array){
		$result = "<ul>\n";
		foreach($array as $key => $val){
			$result .="<li>$key:<span style='color: #af0000; '>$val</span></li>\n";
		}
		$result .= "</ul>";
		return $result;
	}
    //getValue получает переменную из массива $_REQUEST и производит преобразования для безопасности
    //если переменную с именем $val получить не удалось, то отправляет письмо с ошибкой и содержимым массивов сервера
    function getValue($val){
        global $mail, $headers;
        if(isset($_REQUEST[$val])&&strlen($_REQUEST[$val])>0){
            $param = trim($_REQUEST[$val]);
            $param = strip_tags($param);
            $param = htmlspecialchars($param);
            return $param;
        } else {

             mail($mail,"Неполадки с отправкой формы $site","<h1>Какие-то проблемы с отправкой форм</h1><p>Проблема с получением одной из переменных форм.</p><p>URL во время ошибки: ".$_SERVER['REQUEST_URI']."</p><h2>Содержимое \$_REQUEST</h2>".dumpArray($_REQUEST).'<h2>Содержимое $_SERVER</h2>'.dumpArray($_SERVER)."", $headers);
            die ("0");//дает понять ajax скрипту что отправка не удалась
        }
        
    }
    //main programm
    //$messageTemplate - шаблон для тела письма
    $messageTemplate = "<h2>Заявка с сайта $site</h2><ul>";
	$messageTemplate .= "<li>Применение (векторные, для электродвигателя и тд): %s </li>";
	$messageTemplate .= "<li>Тип и мощность прибора: %s </li>";
	$messageTemplate .= "<li>Имя и должность: %s </li>";
	$messageTemplate .= "<li>Номер телефона: %s </li>";
    //в переменной t должен передаваться тип формы, в зависимости от типа используются разные заголовки письма и получаются разные переменные, если указан неизвестный тип то придет письмо с ошибкой и содержимым массивов сервера
    switch(getValue('t')){
		case "getConverterService":
            $apply = getValue('apply');
            $type = getValue('type');
            $name = getValue('name');
            $phone = getValue('phone');
            $message = "<h1>Заявка на бесплатный подбор преобразователя частоты</h1>".sprintf($messageTemplate, $apply, $type, $name, $phone)."</ul>";				
            $subject = "$site. Заявка на услугу";
            break;				
        default :            
            mail($mail, "Неполадки с отправкой формы $site","<h1>Какие-то проблемы с отправкой форм</h1><p>В качестве типа формы было передано нестандартное значение.</p><p>URL во время ошибки: ".$_SERVER['REQUEST_URI']."</p><h2>Содержимое \$_REQUEST</h2>".dumpArray($_REQUEST).'<h2>Содержимое $_SERVER</h2>'.dumpArray($_SERVER)."", $headers);
            die("0");//дает понять ajax скрипту что отправка не удалась
        }

    //здесь формируется ответ для ajax скрипта, если 1 то ajax напишет об успешной передаче иначе об ошибке
   if(mail($mail, $subject, $message, $headers)){
       echo "1";
   } else {
       echo "0";
   }
   
?>