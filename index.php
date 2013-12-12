<?php
    require 'config.php';
    require 'localize.php';
    require 'parsexml.php';
    require_once "taskmodel.php";

    validate_input();

    require "./themes/$style/theme.php";

    function write_side_bar()
    {
        global $iface, $page, $graph, $script, $style;
        global $iface_list, $iface_title;
        global $page_list, $page_title;

        $p = "&amp;graph=$graph&amp;style=$style";
        

//        print "<ul class=\"iface\">\n";
        print "<div id=\"nav\">";
        print "<li class=\"page\"><a href=\"$script?if=allserver$p&amp;page=a\">概述</a></li>\n";
        $i = 1;
        foreach ($iface_list as $if)
        {
            //print "<li class=\"iface\">";
            print "<div class=\"title\" id=\"menu$i\" onclick=\"showmenu($i)\"> $if</div>";
            print "<div id=\"list$i\" class=\"content\" style=\"display:none\">";
            print "<ul>";
            foreach ($page_list as $pg)
            {
                print "<li><a href=\"$script?if=$if$p&amp;page=$pg\">".$page_title[$pg]."</a></li>\n";
            }
            print "<li><a href='deleteitem.php?id=\"$if\"' onclick='return CmdConfirm();'>delete</a></li>";
            print "</ul>";
            print "</div>";
            $i++;

        }
        print "</div>\n";
        print "<div class=\"right\" style=\"padding-top:6px\">";
        print "<p class=\"btn_create_right\" style=\"width:150px;\"><a href=\"additem_web.php\">创建监控项目</a></p>";
        print "</div>";
    }


    function kbytes_to_string($kb)
    {
        $units = array('TB','GB','MB','KB');
        $scale = 1024*1024*1024;
        $ui = 0;

        while (($kb < $scale) && ($scale > 1))
        {
            $ui++;
            $scale = $scale / 1024;
        }
        return sprintf("%0.2f %s", ($kb/$scale),$units[$ui]);
    }

    function write_summary($iface)
    {
        global $top,$day,$month,$total_rx, $total_tx;
        global $cpu, $mem, $load, $download_rate, $upload_rate;
        global $partition_dir, $partition_total, $partition_used, $partition_pct;

        $trx = $total_rx;
        $ttx = $total_tx;

        //
        // build array for write_data_table
        //

        $sum[1]['act'] = 1;
        $sum[1]['label'] = T('This day');
        $sum[1]['rx'] = $day[0]['rx'];
        $sum[1]['tx'] = $day[0]['tx'];

        $sum[2]['act'] = 1;
        $sum[2]['label'] = T('This month');
        $sum[2]['rx'] = $month[0]['rx'];
        $sum[2]['tx'] = $month[0]['tx'];

        $sum[3]['act'] = 1;
        $sum[3]['label'] = T('All time');
        $sum[3]['rx'] = $trx;
        $sum[3]['tx'] = $ttx;

        write_data_table('Summary', $sum);

        $items = getOneStatus($iface);
        $strtime = date("Y-m-d H:i:s", $items[0][2]);
        $success_rate = getSuccessRate($iface, 7);
        $success_rate .= '%';
        echo "<tr>
              <td>".$strtime."</td>
              <td>".$items[0][3]."</td>
              <td>".$items[0][4]."</td>
              <td>".$success_rate."</td>";
        echo "</tr>";
        print "<tr>";
        print "<td class=\"label\" style=\"width:120px;\">&nbsp;</td>";
        print "<td class=\"label\">".cpu利用率."</td>";
        print "<td class=\"label\"> $cpu </td>";
        print "</tr>\n";
        print "<tr>";
        print "<td class=\"label\" style=\"width:120px;\">&nbsp;</td>";
        print "<td class=\"label\">".内存使用率."</td>";
        print "<td class=\"label\"> $mem</td>";
        print "</tr>\n";
        print "<tr>";
        print "<td class=\"label\" style=\"width:120px;\">&nbsp;</td>";
        print "<td class=\"label\">".系统负载."</td>";
        print "<td class=\"label\"> $load </td>";
        print "</tr>\n";
        print "<tr>";
        print "<td class=\"label\" style=\"width:120px;\">&nbsp;</td>";
        print "<td class=\"label\">".下行速率KB."</td>";
        print "<td class=\"label\"> $download_rate </td>";
        print "</tr>\n";
        print "<tr>";
        print "<td class=\"label\" style=\"width:120px;\">&nbsp;</td>";
        print "<td class=\"label\">".上行速率KB."</td>";
        print "<td class=\"label\"> $upload_rate </td>";
        print "</tr>\n";
        print "<tr>";
        print "<td class=\"label\" style=\"width:120px;\">&nbsp;</td>";
        print "<td class=\"label\">".硬盘使用情况（）."</td>";
        print "<td class=\"label\"> $partition_total </td>";
        print "<td class=\"label\"> $partition_used </td>";
        print "<td class=\"label\"> $partition_pct </td>";
        print "</tr>\n";
        print "<br/>\n";
        #write_data_table('Top 10 days', $top);
    }


    function write_data_table($caption, $tab)
    {
        print "<table width=\"100%\" cellspacing=\"0\">\n";
        print "<caption>$caption</caption>\n";
        print "<tr>";
        print "<th class=\"label\" style=\"width:120px;\">&nbsp;</th>";
        print "<th class=\"label\">".T('In')."</th>";
        print "<th class=\"label\">".T('Out')."</th>";
        print "<th class=\"label\">".T('Total')."</th>";
        print "</tr>\n";

        for ($i=0; $i<count($tab); $i++)
        {
            if ($tab[$i]['act'] == 1)
            {
                $t = $tab[$i]['label'];
                $rx = kbytes_to_string($tab[$i]['rx']);
                $tx = kbytes_to_string($tab[$i]['tx']);
                $total = kbytes_to_string($tab[$i]['rx']+$tab[$i]['tx']);
                $id = ($i & 1) ? 'odd' : 'even';
                print "<tr>";
                print "<td class=\"label_$id\">$t</td>";
                print "<td class=\"numeric_$id\">$rx</td>";
                print "<td class=\"numeric_$id\">$tx</td>";
                print "<td class=\"numeric_$id\">$total</td>";
                print "</tr>\n";
             }
        }
        print "</table>\n";
    }

    function configServer($ser_name)
    {
      $ser_info = getMyConfig($ser_name);
      #print_r($ser_info);
      $test = "test";
      print "<form action=\"updateConfig.php\" method=\"post\">";
      print "<td><input name=\"ser_id\" type=\"hidden\" value= \"".$ser_info[0][0]."\"></td>";
      print "<table border=\"1\">";
      print "<tr>";
      print "<td> 服务器地址 </td>";
      print "<td><input name=\"ser_name\" type=\"testbox\" value=\"".   $ser_info[0][1]."\"></td>";
      print "</tr>";
      print "<tr>";
      print "<td> 电子邮箱 </td>";
      print "<td><input name=\"ser_email\" type=\"testbox\" value=\"".   $ser_info[0][2]."\"></td>";
      print "</tr>";
      print "</table>";
      print "<input type=\"submit\" value=\"config\"/>";
      print "</form>";


    }


    //
    // html start
    //
    header('Content-type: text/html; charset=utf-8');
    print '<?xml version="1.0"?>';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
  <title>traffic counter</title>
  <script language = JavaScript>
    function showmenu(id) {
        var list = document.getElementById("list"+id);
        var menu = document.getElementById("menu"+id);
        if(list.style.display=="none"){
            document.getElementById("list"+id).style.display="block";
            menu.className = "title1";
        }else{
            document.getElementById("list"+id).style.display="none";
            menu.className = "title";
        }
    }
    function CmdConfirm(){
        if(window.confirm("确定要删除此记录?")){
            return true;
        }else{
            return false;
        }
    }

    </script>
    <style type="text/css">
<!--
*{margin:0:padding:0;list-style:none;font-size;14px}
#nav{margin:10px;text-align:center;line-height:25px;width:150px;}
.title{background:#336699;color:#fff;border-bottom:1px solid #fff;cursor:pointer;}
.title1{background:#888;color:#000;border-bottom:1px solid #666;cursor:pointer;}
.content li{color:#336699;background:#ddd;border-bottom:1px solid #fff;}
-->
</style>
     

  <link rel="stylesheet" type="text/css" href="themes/<?php echo $style ?>/style.css"/>
</head>
<body>
<?php
    @session_start();
    if(@$_SESSION["username"])
        echo "";
    else
        echo("<script>window.location='login.html'</script>");
?>
<div id="wrap">
  <div id="sidebar"><?php write_side_bar(); ?></div>
   <div id="content">
    <div id="header"><?php print T('Status for')." $iface_title[$iface] ($iface)";?></div>
    <div id="main">
    <?php
    $graph_params = "if=$iface&amp;page=$page&amp;style=$style";
    $items = getAllStatus();
    if($page =='a'){
        if(!empty($items))
        {
             echo "<table border='1'>
             <tr>
             <th>监控项目</th>
             <th>更新时间</th>
             <th>状态</th>
             <th>状态代码</th>
             <th>可用率</th>
             </tr>";
             foreach($items as $item)
             {
                 $strtime=date("Y-m-d H:i:s", "$item[2]");
                 $success_rate = getSuccessRate($item[1], 7);
                 $succesee_rate =$success_rate.'%';
                 echo "<tr>
                     <td>".$item[1]."</td>
                     <td>".$strtime."</td>
                     <td>".$item[3]."</td>
                     <td>".$item[4]."</td>
                     <td>".$success_rate."</td>";
                 echo "</tr>";
             }
             echo "</table>";
        }
    }



    if ($page != 's' && $page != 'c'&& $page != 'a')
        if ($graph_format == 'svg') {
	     print "<object type=\"image/svg+xml\" width=\"692\" height=\"297\" data=\"graph_svg.php?$graph_params\"></object>\n";
        } else {
	     print "<img src=\"graph.php?$graph_params\" alt=\"graph\"/>\n";
        }

    if ($page == 's')
    {
        get_xml_data($iface);
        write_summary($iface);
    }
    else if ($page == 'd')
    {
        get_xml_data($iface);
        write_data_table(T('Last 30 days'), $day);
    }
    else if ($page == 'm')
    {
        get_xml_data($iface);
        write_data_table(T('Last 12 months'), $month);
    }
    else if ($page == 'c')
    {
        configServer($iface);
    }
    ?>
    </div>
    <div id="footer"><a href="http://www.sqweek.com/">new counter</a> 1.5.1 - &copy;2013 seenic</div>
  </div>
</div>

</body></html>
