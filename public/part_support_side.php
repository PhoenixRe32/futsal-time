<script type="text/javascript">
$(function(){
	$(".fb-style").bind("click", function(){
	 	$(".fbNotify").jmNotify();
		return false;
	});
	
	$(".msn-style").bind("click", function(){
		 $(".msnNotify").jmNotify({
			methodIn : 'slideDown',
			methodOut : 'slideUp'
		});
		return false;
	});
	
	$(".custom-style").bind("click", function(){
		$(".customNotify").jmNotify();
		return false;
	});

	$(".ubuntu-style").bind("click", function(){
		$(".ubuntuNotify").jmNotify();
		return false;
	});
});
</script>
	
<table border="0" width='160px' class="linksTable">
	<tr>
		<td style='padding:15px 15px 10px 15px;'>
            <a href="#" class="custom-style"><img src="img/academiesfinal.png" align="middle"/></a>
		</td>
	</tr>

	<tr >
		<td style='padding:10px 15px 10px 15px;'>
            <a href="#" class="custom-style"><img src="img/Partyfinal.png" align="middle"/></a>
		</td>
	</tr>

	<tr>
		<td style='padding:10px 15px 10px 15px;'>
            <a href="#" class="custom-style"><img src="img/Tournaments.png" align="middle"/></a>
		</td>
	</tr>
	<div class="customNotify">This section is not yet enabled!</div>
</table>