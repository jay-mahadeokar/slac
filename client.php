<!DOCTYPE html>
<head>
	<title>SLAC Messenger - Powered by Yahoo!</title>
	<link rel="stylesheet" href="css/style.css" type="text/css" media="screen" charset="utf-8"/>
	<script src="http://yui.yahooapis.com/3.4.0/build/yui/yui-min.js"></script> 
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
	<script>
		function sendChat(textBox,chat_area){
			var text = document.getElementById(textBox).value;
			var chat = document.getElementById(chat_area);
			var dummy = chat.getElementsByClassName('dummy')[0];
			var newnode = document.createElement("div");
			newnode.setAttribute("class","chat-content");
			newnode.innerHTML = text;
			chat.insertBefore(newnode,dummy);	
			document.getElementById('textBox').setAttribute("value","");
		}
	</script>
	<script>
		
		
	</script>
</head>
<body class="yui3-skin-sam">
		<div id="light" class="white_content"></div>
		<div id="fade" class="black_overlay"></div>
<div id="demo">
</div>

<script type="text/javascript">

YUI().use('tabview', 'escape', 'plugin', function(Y) {
	var el = document.getElementById('fade');
	el.addEventListener("click", closelightBox, false);
	function setAvailable(contact_id,name,state){
		var el = document.getElementById(contact_id).getElementsByClassName("availability")[0];
		if(state == "yes"){
			el.innerHTML = '<img src="images/aim_active.png" cid="'+contact_id+'" name="'+ name+'" id="fraud"/>';
		}else{
			el.innerHTML = '<img src="images/aim_dark.png" cid="'+contact_id+'" name="'+ name+'" id="fraud"/>';
		}
	}
	
	function showlightBox(content){
		document.getElementById('light').innerHTML = content;
		document.getElementById('light').style.display='block';
		document.getElementById('fade').style.display='block';
	}
	
	function closelightBox(){
		document.getElementById('light').style.display='none';
		document.getElementById('fade').style.display='none';
	}
	


    var Removeable = function(config) {
        Removeable.superclass.constructor.apply(this, arguments);
    };

    Removeable.NAME = 'removeableTabs';
    Removeable.NS = 'removeable';

    Y.extend(Removeable, Y.Plugin.Base, {
        REMOVE_TEMPLATE: '<a class="yui3-tab-remove" title="remove tab">x</a>',

        initializer: function(config) {
            var tabview = this.get('host'),
                cb = tabview.get('contentBox');

            cb.addClass('yui3-tabview-removeable');
            cb.delegate('click', this.onRemoveClick, '.yui3-tab-remove', this);

            // Tab events bubble to TabView
            tabview.after('tab:render', this.afterTabRender, this);
        },

        afterTabRender: function(e) {
            // boundingBox is the Tab's LI
            if(e.target.get('label') != "Contacts")
				e.target.get('boundingBox').append(this.REMOVE_TEMPLATE);
        },

        onRemoveClick: function(e) {
            e.stopPropagation();
            var tab = Y.Widget.getByNode(e.target);
            var el = document.getElementById((tab.get("from")));
            el.setAttribute("open","false");
            tab.remove();
        }
    });
	var touchContact = function(e) {
		e.preventDefault();
		var el = e.target;
		var elid = el.getAttribute("id");
		if(elid != "fraud"){
			if(el.getAttribute("open") == "false"){
				var tab = new Y.Tab({
					id: 'slac_'+elid,
					label: el.getAttribute("name"),
					from: elid,
				});
				var content = '<div class="content">Contact Details</div><div class="chat" id="chat_area_'+elid+'"><span class="dummy"></span><input type="text" id="textBox_'+elid+'"/><input type="submit" value="submit" onclick="sendChat(\'textBox_'+elid+'\',\'chat_area_'+elid+'\')"/></div>';
				tab.set('content',content);
				tab.set("from",el.getAttribute("id"));
				tabview.add(tab);
				el.setAttribute("open","true");
			}else{
				el.style.display = 'block';
			}			
		}else{
			$.ajax({
				url: 'do.php?action=showInfo&user='+el.getAttribute("name"),
				success: function(data){
					showlightBox(data);
				}
			});
		}
		//alert('event: ' + e.type + ' target: ' + e.target.get('id')); 
	};
	
    var tabview = new Y.TabView({
		id:'slac_maintab',
        children: [{
			id:'slac_contactbox',
            label: 'Contacts',
            content: '<div id="dummy" class="dummy"> </div>'
        }],
        plugins: [Removeable]
    });
    
    
   tabview.render("#demo");
   var cb = Y.one('#slac_contactbox');
   var dum = Y.one('#dummy');
   var contacts_json;
		
			$.ajax({
				url: 'do.php?action=getContacts',
				success: function(data){
					var returned_data = $.parseJSON(data);
					contacts_json=returned_data['contacts'];
					
					var counter_i = 0;
				   var contacts_len = 2;
				   for(var counter_i=0; counter_i<contacts_len;++counter_i){
				   var item = Y.Node.create('<div class="contact" name="'+contacts_json[counter_i]['contact']['id']+'" id="contact_'+counter_i+'" open="false"><span class="availability"></span>'+contacts_json[counter_i]['contact']['id']+'<br/><span class="status"><em>Status Here</em></span></div>');
				   
				   
				   cb.insertBefore(item,dum);
				   //item.on('dblclick', clickContact);
				   item.on('click', touchContact);
				   setAvailable("contact_"+counter_i,contacts_json[counter_i]['contact']['id'],contacts_json[counter_i]['contact']['presence']['presenceState']!=-1?"yes":"no");
				   }
				}	
			});
   
   

   /*var tab = new Y.Tab({
        label: "Test Contact 1",
        content: 'loading...',
    });
    tabview.add(tab);
   var tab = new Y.Tab({
        label: "Test Contact 2",
        content: 'loading...',
    });
    tabview.add(tab);*/
});
</script>
</body>
</html>
