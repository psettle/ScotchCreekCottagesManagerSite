<?php

//this is just for testing the formsite api, don't worry about it yet


echo "<pre>".print_r($_POST, true)."</pre>";


echo "<!DOCTYPE html>\n";
echo "<html><head>\n";
echo "<script src=\"https://code.jquery.com/jquery-1.12.4.js\"></script>\n";
echo '<a name="form778398148" id="formAnchor778398148"></a>
<script type="text/javascript" src="https://fs26.formsite.com/include/form/embedManager.js?778398148"></script>
<script type="text/javascript">
EmbedManager.embed({
	key: "https://fs26.formsite.com/res/showFormEmbed?EParam=m%2FOmK8apOTBqZW9QBc1%2Bv2rYe2Y6sJfY&778398148",
	width: "100%",
	mobileResponsive: true,
	prePopulate: { "1": 2 },
});
</script>';
echo "</head><body></body></html>\n";
