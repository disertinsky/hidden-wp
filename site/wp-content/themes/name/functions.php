<?php

function my_custom_login_logo() {
	/* своя картинка для входа в админку */
    echo '<style type="text/css">
        h1 a { background-image:url('.get_bloginfo('template_directory').'/custom-login-logo.gif) !important; }
    </style>';
}
add_action('login_head', 'my_custom_login_logo');


function get_slug($level)
/* возвращает слаг ( уровень слага )*/
{
	$current_url = rtrim($_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], "/");
	$arr_current_url = split("/", $current_url);
	if ($level<>0) { $res = trim($arr_current_url[$level]);} else 
	{
	$res = trim(end($arr_current_url));
	}
	if (null == $res ) $res = prev($arr_current_url);
	return $res;
}

function rus_date() {
    $translate = array(
    "am" => "дп",   "pm" => "пп",   "AM" => "ДП",   "PM" => "ПП",   "Monday" => "Понедельник",   "Mon" => "Пн",   
	"Tuesday" => "Вторник",   "Tue" => "Вт",   "Wednesday" => "Среда",   "Wed" => "Ср",   "Thursday" => "Четверг",
    "Thu" => "Чт",   "Friday" => "Пятница",    "Fri" => "Пт",    "Saturday" => "Суббота",    "Sat" => "Сб",    "Sunday" => "Воскресенье",
    "Sun" => "Вс",    "January" => "Января",    "Jan" => "Янв",    "February" => "Февраля",    "Feb" => "Фев",    "March" => "Марта",
    "Mar" => "Мар",    "April" => "Апреля",    "Apr" => "Апр",    "May" => "Мая",    "May" => "Мая",    "June" => "Июня",    "Jun" => "Июн",
    "July" => "Июля",    "Jul" => "Июл",    "August" => "Августа",    "Aug" => "Авг",    "September" => "Сентября",    "Sep" => "Сен",
    "October" => "Октября",    "Oct" => "Окт",    "November" => "Ноября",    "Nov" => "Ноя",    "December" => "Декабря",    "Dec" => "Дек",
    "st" => "ое",    "nd" => "ое",    "rd" => "е",    "th" => "ое"
    );
    
    if (func_num_args() > 1) {
        $timestamp = func_get_arg(1);
        return strtr(date(func_get_arg(0), $timestamp), $translate);
    } else {
        return strtr(date(func_get_arg(0)), $translate);
    }
}

function sanatizeItem($var, $type="")
    {
        $flags = NULL;
        switch($type)
        {
            case 'url':
                $filter = FILTER_SANITIZE_URL;
            break;
            case 'int':
                $filter = FILTER_SANITIZE_NUMBER_INT;
            break;
            case 'float':
                $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                $flags = FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND;
            break;
            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_SANITIZE_EMAIL;
            break;
            case 'string':
            default:
                $filter = FILTER_SANITIZE_STRING;
                $flags = FILTER_FLAG_NO_ENCODE_QUOTES;
            break;

        }
        $output = filter_var($var, $filter, $flags);        
        return($output);
    }

function pretty_print($in,$opened = true){
    if($opened)
        $opened = ' open';
    if(is_object($in) or is_array($in)){
        echo '<div>';
            echo '<details'.$opened.'>';
                echo '<summary>';
                    echo (is_object($in)) ? 'Object {'.count((array)$in).'}':'Array ['.count($in).']';
                echo '</summary>';
                pretty_print_rec($in, $opened);
            echo '</details>';
        echo '</div>';
    }
}
function pretty_print_rec($in, $opened, $margin = 10){
    if(!is_object($in) && !is_array($in)) 
        return;

    foreach($in as $key => $value){
        if(is_object($value) or is_array($value)){
            echo '<details style="margin-left:'.$margin.'px" '.$opened.'>';
                echo '<summary>';
                    echo (is_object($value)) ? $key.' {'.count((array)$value).'}':$key.' ['.count($value).']';
                echo '</summary>';
                pretty_print_rec($value, $opened, $margin+10);
            echo '</details>';
        }
        else{
            switch(gettype($value)){
                case 'string':
                    $bgc = 'red';
                break;
                case 'integer':
                    $bgc = 'green';
                break;
            }
            echo '<div style="margin-left:'.$margin.'px">'.$key . ' : <span style="color:'.$bgc.'">' . $value .'</span> ('.gettype($value).')</div>';
        }
    }
}


?>