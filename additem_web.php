<html>
<body>
<form action="addserver.php" method="post" style="width:500px;">
 	<fieldset>
	<legend><strong>服务器监控系统</strong></legend>
		<table border="0" width="500" cellspacing="0" bordercolor="#000" style="border-collapse:collapse;">
			<tr height="28">
				<td> 服务器域名: </td>
				<td><input name="ser_name" type="textbox" size="30"/></td>
			</tr>
			<tr height="28">
				<td> 名称（如电院网站）: </td>
				<td><input name="ser_nameZh" type="textbox" size="30"/></td>
			</tr>
			<tr height="28">
				<td> ip地址: </td>
				<td><input name="ser_ip" type="textbox" size="30"/></td>
			</tr>
			<tr height="28">
				<td>电子邮箱: </td>
				<td><input name="ser_email" type="textbox" size="30"/></td>
			</tr>
            <tr>
            	<td colspan="2" align="center"><input type="submit" value="Add item"/></td>
            </tr>
		</table>
		    </fieldset>
 </form>   
</body>
</html>