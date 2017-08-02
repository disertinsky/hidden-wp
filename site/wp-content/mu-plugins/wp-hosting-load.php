<?php
/*
Plugin Name: WP Hosting load
Plugin URI: http://disertinsky.com/plugins/wp-hosting-load
Description: Shows hosting load averages changing in percent
Author: Alex A. Disertinsky
Version: 0.9
Author URI: http://nashbryansk.ru/
*/


function getServerLoad($windows = false){
    $os=strtolower(PHP_OS);
    if(strpos($os, 'win') === false){
        if(file_exists('/proc/loadavg')){
            $load = file_get_contents('/proc/loadavg');
            $load = explode(' ', $load, 1);
            $load = $load[0];
        }elseif(function_exists('shell_exec')){
            $load = explode(' ', `uptime`);
            $load = $load[count($load)-1];
        }else{
            return false;
        }

        if(function_exists('shell_exec'))
            $cpu_count = shell_exec('cat /proc/cpuinfo | grep processor | wc -l');        

        return array('load'=>$load, 'procs'=>$cpu_count);
    }elseif($windows){
        if(class_exists('COM')){
            $wmi=new COM('WinMgmts:\\\\.');
            $cpus=$wmi->InstancesOf('Win32_Processor');
            $load=0;
            $cpu_count=0;
            if(version_compare('4.50.0', PHP_VERSION) == 1){
                while($cpu = $cpus->Next()){
                    $load += $cpu->LoadPercentage;
                    $cpu_count++;
                }
            }else{
                foreach($cpus as $cpu){
                    $load += $cpu->LoadPercentage;
                    $cpu_count++;
                }
            }
            return array('load'=>$load, 'procs'=>$cpu_count);
		}
	}
}

add_action( 'admin_bar_menu', 'toolbar_link_to_avg', 999 );

		function toolbar_link_to_avg( $wp_admin_bar ) {

		$load = getServerLoad();

		list ($_min_1, $_min_5, $_min_15, $_processes, $_PID)  =  explode(" ", $load['load']);

		if ($_min_1 < $_min_5) $status = " <span style='color:#00CC33;'>снизилась до</span> "; 
		if ($_min_5 < $_min_1) $status = " <span style='color:#FF0000;'>выросла до </span> "; 
		if ($_min_5 == $_min_1) $status = " "; 

		$_avg_percents = ($_min_1 * 100) / $load['procs'];

		$average = "Текущая нагрузка сервера " . $status . $_avg_percents . "%";


	$args = array(
		'id'    => 'average',
		'title' => $average,
		// 'href'  => 'http://mysite.com/my-page/',
		'meta'  => array( 'class' => 'my-toolbar-page' )
	);
	$wp_admin_bar->add_node( $args );
}

