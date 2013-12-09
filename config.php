<?php
    error_reporting(E_ALL | E_NOTICE);
    require_once("taskmodel.php");

    //
    $locale = 'en_US.UTF-8';
    $language = 'en';
    $iface_list = array();
    $ser_names = getAllNames();
    foreach($ser_names as $ser_name)
    {
      array_push($iface_list, $ser_name[0]);
    }
    // list of network interfaces monitored by vnStat
    #$iface_list = array('life.xidian.edu.cn', 'see.xidian.edu.cn');

    //
    // optional names for interfaces
    // if there's no name set for an interface then the interface identifier
    // will be displayed instead
    //
    $iface_title['eth0'] = 'Internal';
   // $iface_title['sixxs'] = 'SixXS IPv6';

    //
    //   vnstat --dumpdb -i $iface > /path/to/data_dir/vnstat_dump_$iface
    //
    $vnstat_bin = '/usr/bin/vnstat';
    $data_dir = './dumps';

    // graphics format to use: svg or png
    $graph_format='svg';

    // Font to use for PNG graphs
    define('GRAPH_FONT',dirname(__FILE__).'/VeraBd.ttf');

    // Font to use for SVG graphs
    define('SVG_FONT', 'Verdana');

    // Default theme
    define('DEFAULT_COLORSCHEME', 'light');

?>
