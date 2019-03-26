// Put a page break after the label to match the rest of the style.
document.addEventListener('DOMContentLoaded', function () {
    var br = document.createElement('BR');
    var tag_label = document.getElementById('wpas_tags');
    if (tag_label !== null)
    {
    	tag_label.labels[0].after(br);
	}
   
    var filter = document.getElementById('wpas_filter');

    if (filter !== null) {
    	filter.addEventListener('keyup', 
    		function (event) 
    		{
    			if (event.keyCode !== 13)
    			{
    				return;
    			}

    			var val = this.value.toLowerCase();
        		var list = document.getElementById('wpas_ticketlist');
        		var rows = list.children[1].children;
        		for (let i=0; i < rows.length; i++) {
        			if (!rows[i].innerText.toLowerCase().includes(val))
        			{
        				rows[i].hidden = true;
        			}
        			else
        			{
        				rows[i].hidden = null;
        			}
        		}
    		}, false);

    	var clear_filter = document.getElementsByClassName('wpas-clear-filter');
    	if (clear_filter.length != 0)
    	{
    		clear_filter[0].addEventListener('click', function (event) {
    				filter.value = "";
    				var list = document.getElementById('wpas_ticketlist');
    				var rows = list.children[1].children;
    				for (let i=0; i < rows.length; i++) 
    				{  
						rows[i].hidden = null;
					}
    		}, false);
    	}
    }



}, false);