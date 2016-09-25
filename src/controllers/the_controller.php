<?php
echo '<script src="jquery-1.12.4.min.js"></script>';
echo '<script>

var $ = jQuery;		
		
var form = $.get("https://fs26.formsite.com/psettle/form1/fill?1=1&2=1&3=YourValue3");
		
		
var div = $("</div>");
		
div.html(form);

		
</script>';