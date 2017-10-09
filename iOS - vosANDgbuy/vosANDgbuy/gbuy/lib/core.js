$(document).ready(function(){
			
				setInterval("check_login()",10000); 
			
				$("#login").click(function(){
					login();
				});
				
				$("#logout").click(function(){
					logout();
				});
                
                              
                              /*$('#order_table tfoot th').each( function () {
                                                          var title = $('#order_table thead th').eq( $(this).index() ).text();
                                                          $(this).html( '<input type="text" placeholder="'+title+'" />' );
                                                          } );*/
                
                              $('#order_table').dataTable({
                                                            "order": [[ 7, "desc" ]],
                                                            "aaSorting": [[0,'desc']]
                              
                                                          });
                              
                              
				$("#product_search").click(function(){
					// var s = $("#search_by").val();
					var s = $('input[name=radioProduct]:checked', '#search_by').val(); 
					var t = $("#product_search_term").val();
					product_search(s, t);
				});
				
				
				$("#add_cart").click(function(){				
					var cart_list = new Array();					
					$(".qty").each(function(n,o){
						var cart_item = new Array();
						//alert("id:"+o.id+",value:"+o.value);
						if(!(o.value.length == 0)) {
							
							cart_item.push(o.id);
							cart_item.push(o.value);
							cart_list.push(cart_item);
							o.value="";
						}
						
					});
					//alert(cart_list);
					add_cart(cart_list);
					// show_tab("cart");
					refresh_cart();//zz
                                     

				});
				
				$("#update_cart").click(function(){
					var cart_list = new Array();
					$(".c_qty").each(function(n,o){
						var cart_item = new Array();
						cart_item.push(o.id);
						cart_item.push(o.value);
						cart_list.push(cart_item);
					});
					update_cart(cart_list);
					// refresh_cart();//zz
					// show_tab("cart");
				});
				
				$("#submit_cart").click(function(){
					var cart_list = new Array();

					//zz 20170113 check added address
                    var x = document.getElementsByName("chkboxes");
                    if(x.length > 0){
                        alert("还有送货地址未添加!");
                        return false;
                    }//

					$(".c_qty").each(function(n,o){
						var cart_item = new Array();
						cart_item.push(o.id);
						cart_item.push(o.value);
						cart_list.push(cart_item);
					});
					submit_cart(cart_list);
					refresh_cart();//zz
					refresh_order();//zz
					alert("购物车已提交");//zz
				});
				
				//select all the a tag with name equal to modal
				$('a[name=modal]').click(function(e) {
					//Cancel the link behavior
					e.preventDefault();

					//Get the A tag
					var id = $(this).attr('href');

					//Get the screen height and width
					var maskHeight = $(document).height();
					var maskWidth = $(document).width();

					//Set heigth and width to mask to fill up the whole screen
					$('#mask').css({'width':maskWidth,'height':maskHeight});

					//transition effect     
					$('#mask').fadeIn(1000);    
					$('#mask').fadeTo("slow",0.9);  

					//Get the window height and width
					var winH = $(window).height();
					var winW = $(window).width();

					//Set the popup window to center
					$(id).css('top',  winH/3-$(id).height()/3);
					$(id).css('left', winW/2-$(id).width()/2);

					//transition effect
					$(id).fadeIn(2000); 

				});

				//if close button is clicked
				$("#window_close").click(function (e) {
					//Cancel the link behavior
					e.preventDefault();

					$('#mask').hide();
					$('.window').hide();
				});     

				//if mask is clicked
				$('#mask').click(function () {
					$(this).hide();
					$('.window').hide();
				});         

				$(window).resize(function () {

					var box = $('#boxes .window');

					//Get the screen height and width
					var maskHeight = $(body).height();
					var maskWidth = $(body).width();

					//Set height and width to mask to fill up the whole screen
					$('#mask').css({'width':maskWidth,'height':maskHeight});

					//Get the window height and width
					var winH = $(window).height();
					var winW = $(window).width();

					//Set the popup window to center
					box.css('top',  winH/2 - box.height()/2);
					box.css('left', winW/2 - box.width()/2);

				});

				function log( message ) {
					$( "<div>" ).text( message ).prependTo( "#log" );
					$( "#log" ).scrollTop( 0 );
			}
				
			/*	$( "#country" ).autocomplete({
					source: function( request, response ) {
						var search_by='name';
						$.ajax({
							type:"post",
							url:"country_search.php",
							dataType:"json",				
							data: {term: request.term , search_by: search_by},
							success: function( data ) {
									response($.map(data,function(item){
										return{
												label:item.name,
												code: item.code,
												id:item.id
										};
									}));		
											
							}
						})
					},
					minLength: 0,
					
					select: function( event, ui ) {						
						document.getElementById('country_id').value = ui.item.id;	
						
										
					},						
					open: function() {
						$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
					},
					close: function() {
						$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
					}	
				});
				*/									
				$.widget( "custom.combobox1", {
							  _create: function() {								
								this.wrapper = $( "<span>" )
								  .addClass( "custom-combobox")
								  .insertAfter( this.element );
						 
								this.element.hide();
								this._createAutocomplete();
								this._createShowAllButton();
								this.input.attr("placeholder", "单选或多选物品,然后选择地址");
							  },
						  	  _createAutocomplete: function() {
								var selected = this.element.children( ":selected" ),
								  value = selected.val() ? selected.text() : "";
								
								
								this.input = $( "<input>" )
								  .appendTo( this.wrapper )
								  .val( value )
								  .attr( "title", "" )
								  .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
								  .autocomplete({
									delay: 0,
									minLength: 0,
									source: $.proxy( this, "_source" )
								  })
								  .tooltip({
									tooltipClass: "ui-state-highlight"
								  });
						 
								this._on( this.input, {
								  autocompleteselect: function( event, ui ) {
									  ui.item.option.selected = true;									
									
									
									
									this._trigger( "select", event, {
									  item: ui.item.option  									  
									  
									});
								  },
						 
								  autocompletechange: "_removeIfInvalid"
								});
							  },
						 
							  _createShowAllButton: function() {
								var input = this.input,
								  wasOpen = false;
						 
								$( "<a>" )
								  .attr( "tabIndex", -1 )
								  .attr( "title", "Show All Items" )
								  .tooltip()
								  .appendTo( this.wrapper )
								  .button({
									icons: {
									  primary: "ui-icon-triangle-1-s"
									},
									text: false
								  })
								  .removeClass( "ui-corner-all" )
								  .addClass( "custom-combobox-toggle ui-corner-right" )
								  .mousedown(function() {
									wasOpen = input.autocomplete( "widget" ).is( ":visible" );
								  })
								  .click(function() {
									input.focus();
						 
									// Close if already visible
									if ( wasOpen ) {
									  return;
									}
						 
									// Pass empty string as value to search for, displaying all results
									input.autocomplete( "search", "" );
								  });
							  },
						 
							  _source: /*function( request, response ) {
								var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
								response( this.element.children( "option" ).map(function() {
								  var text = $( this ).text();
								  if ( this.value && ( !request.term || matcher.test(text) ) )
									return {
									  label: text,
									  value: text,
									  option: this
									};
								}) );
							  },*/
							  
							  function( request, response ) {
									var search_by='name';
									$.ajax({
										type:"post",
										url:"address_search_linked.php",
										dataType:"json",				
										data: {term: request.term , search_by: search_by},
										success: function( data ) {
												response($.map(data,function(item){
													
													return{
															label:item.name+":"+item.street,
															value: item.name+":"+item.street,
															option:this,
															street:item.street,
															id:item.id
													};
												}));		
														
										}
									})
								},
						 
							  _removeIfInvalid: function( event, ui ) {
						 
								// Selected an item, nothing to do
								if ( ui.item ) {
								  return;
								}
						 
								// Search for a match (case-insensitive)
								var value = this.input.val(),
								  valueLowerCase = value.toLowerCase(),
								  valid = false;
								this.element.children( "option" ).each(function() {
								  if ( $( this ).text().toLowerCase() === valueLowerCase ) {
									this.selected = valid = true;
									return false;
								  }
								});
						 
								// Found a match, nothing to do
								if ( valid ) {
								  return;
								}
						 
								// Remove invalid value
								this.input
								  .val( "" )
								  .attr( "title", value + " didn't match any item" )
								  .tooltip( "open" );
								this.element.val( "" );
								this._delay(function() {
								  this.input.tooltip( "close" ).attr( "title", "" );
								}, 2500 );
								this.input.autocomplete( "instance" ).term = "";
							  },
						 
							  _destroy: function() {
								this.wrapper.remove();
								this.element.show();
							  }
							});
							
				
			
			$("#combobox1").combobox1();
				$( ".custom-combobox" ).on( "autocompleteselect", function( event, ui ) {				
					var selected_products = [];
					var partner_id=ui.item.id;
					for (i = document.getElementsByName('chkboxes').length - 1; i >= 0; i--) {
						if (document.getElementsByName('chkboxes')[i].checked) {
						selected_products.push(document.getElementsByName('chkboxes')[i].value)						
						}
					}
					if (selected_products == "") {
					alert ("You must select an item to continue.")
					return false
					}
					else {						
						link_address(selected_products,partner_id);					
					}
				} );
				
				$.widget( "custom.combobox2", {
							  _create: function() {								
								this.wrapper = $( "<span>" )
								  .addClass( "custom-combobox2")
								  .insertAfter( this.element );
						 
								this.element.hide();
								this._createAutocomplete();
								this._createShowAllButton();
								this.input.attr("placeholder", "选择国家: 可以以国家的英文名字搜索，不分大小写");
							  },
						  	  _createAutocomplete: function() {
								var selected = this.element.children( ":selected" ),
								  value = selected.val() ? selected.text() : "";
								
								
								this.input = $( "<input>" )
								  .appendTo( this.wrapper )
								  .val( value )
								  .attr( "title", "" )
								  .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
								  .autocomplete({
									delay: 0,
									minLength: 0,
									source: $.proxy( this, "_source" )
								  })
								  .tooltip({
									tooltipClass: "ui-state-highlight"
								  });
						 
								this._on( this.input, {
								  autocompleteselect: function( event, ui ) {
									  ui.item.value.selected = true;
									  this._trigger( "select", event, {
										  item: ui.item.option  									  
									  
									});
								  },
						 
								  autocompletechange: "_removeIfInvalid"
								});
							  },
						 
							  _createShowAllButton: function() {
								var input = this.input,
								  wasOpen = false;
						 
								$( "<a>" )
								  .attr( "tabIndex", 0 )
								  .attr( "title", "Show All Items" )
								  .tooltip()
								  .appendTo( this.wrapper )
								  .button({
									icons: {
									  primary: "ui-icon-triangle-1-s"
									},
									text: false
								  })
								  .removeClass( "ui-corner-all" )
								  .addClass( "custom-combobox-toggle2 ui-corner-right" )
								  .mousedown(function() {
									wasOpen = input.autocomplete( "widget" ).is( ":visible" );
								  })
								  .click(function() {
									input.focus();
						 
									// Close if already visible
									if ( wasOpen ) {
									  return;
									}
						 
									// Pass empty string as value to search for, displaying all results
									input.autocomplete( "search", "" );
								  });
							  },
						 
							  _source: function( request, response ) {
									var search_by='name';
									$.ajax({
										type:"post",
										url:"country_search.php",
										dataType:"json",				
										data: {term: request.term , search_by: search_by},
										success: function( data ) {
												response($.map(data,function(item){
													
													return{
															label:item.name,
															code: item.code,
															id:item.id
													};
												}));		
														
										}
									})
								},
						 
							  _removeIfInvalid: function( event, ui ) {
						 
								// Selected an item, nothing to do
								if ( ui.item ) {
								  return;
								}
						 
								// Search for a match (case-insensitive)
								var value = this.input.val(),
								  valueLowerCase = value.toLowerCase(),
								  valid = false;
								this.element.children( "option" ).each(function() {
								  if ( $( this ).text().toLowerCase() === valueLowerCase ) {
									this.selected = valid = true;
									return false;
								  }
								});
						 
								// Found a match, nothing to do
								if ( valid ) {
								  return;
								}
						 
								// Remove invalid value
								this.input
								  .val( "" )
								  .attr( "title", value + " didn't match any item" )
								  .tooltip( "open" );
								this.element.val( "" );
								this._delay(function() {
								  this.input.tooltip( "close" ).attr( "title", "" );
								}, 2500 );
								this.input.autocomplete( "instance" ).term = "";
							  },
						 
							  _destroy: function() {
								this.wrapper.remove();
								this.element.show();
							  }
							});
							
				
			
			$("#country").combobox2();
				$( ".custom-combobox2" ).on( "autocompleteselect", function( event, ui ) {				
					var country_id=ui.item.id;					
					if (country_id == "") {
					alert ("You must select an item to continue.")
					return false
					}
					else {						
						document.getElementById('country_id').value = ui.item.id;					
					}
				} );
				
			show_tab();
				
			});
			
			function formatNumber(num, precision, separator) {
				var parts;
    
				if (!isNaN(parseFloat(num)) && isFinite(num)) {
        
        			num = Number(num);
        
					num = (precision ? num.toFixed(precision) : num).toString();
					parts = num.split('.');
        
					parts[0] = parts[0].toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1' + (separator || ','));

					return parts.join('.');
				}
				return NaN;
			}
			
			function login(){
				var username = $("#email").val();
				var password = $("#pwd").val();
				$.ajax({
					type:"post",
					url:"login.php",
					dataType:"json",
					data:'username='+username+'&password='+password,
					success:function(json){
						if (json.login_ok == 1){
							// $("#user_info").html("登录用户: " + json.u_name );
							
							$("#user_info").html(username);
							// document.getElementById('login-pop').style.display="none";
							window.location = "index.php";// zz 转向
							// document.getElementById("welcomm-user").style.padding = "10px";
							// document.getElementById("welcomm-user").innerHTML = username;
							// alert(json.login_name);

							// user_info = "用户名："+json.login_name+"&nbsp;&nbsp;&nbsp;&nbsp;邮件地址：" + json.login_email
							
						}else {
							document.getElementById("login-error").style.padding = "10px";
							document.getElementById("login-error").innerHTML = "Email或密码错误";
							// alert("email或密码错误");
							
						}
					}
				});
			}
			
			function logout(){
				$.ajax({
					type:"post",
					url:"logout.php",
					dataType:"json",
					data:"logout=1",
					success:function(json){
						if (json.login_ok == -1){
							$("#user_info").html("用户已登出." );
							 location.reload();

							// window.location = "test.php";// zz 转向

						}
					}
				});
			}
			
			function check_login(){
				$.ajax({
					type:"post",
					url:"check_login.php",
					dataType:"json",
					data:"",
					success:function(json){
						var user_info_str="";
						if (json.login_ok == 1){
							user_info_str = "用户名："+json.login_name+"&nbsp;&nbsp;&nbsp;&nbsp;邮件地址：" + json.login_email
						}
						else {
							user_info_str = "用户未登录"
							// window.location = "test.php";// zz 转向
						}
						$("#user_info").html(user_info_str);
					}
				});
			}
			
			

			function show_tab1(){
				var tab = arguments[0] ? arguments[0] : "product";
				$("#main").css({ display: "inline" });
				if (tab == "product"){
					$("#product_list").css({ display: "inline" });
					$("#cart_list").css({ display: "none" });
					$("#order_list").css({ display: "none" });
					$("#post_list").css({ display: "none" });
					// $("#impressum").css({ display: "none" });
					
				}
				if (tab == "cart"){
					$("#product_list").css({ display: "none" });
					$("#cart_list").css({ display: "inline" });
					$("#order_list").css({ display: "none" });
					$("#post_list").css({ display: "none" });
					// $("#impressum").css({ display: "none" });
					
					refresh_cart();
				}
				if (tab == "order"){
					$("#product_list").css({ display: "none" });
					$("#cart_list").css({ display: "none" });
					$("#order_list").css({ display: "inline" });
					$("#post_list").css({ display: "none" });
					// $("#impressum").css({ display: "none" });
									
					refresh_order();
				}
				if (tab == "impressum"){
					$("#main").css({ display: "none" });
					// $("#impressum").css({ display: "inline" });
					
					
				}
				if (tab == "post_list"){
					$("#product_list").css({ display: "none" });
					$("#cart_list").css({ display: "none" });
					$("#order_list").css({ display: "none" });
					$("#impressum").css({ display: "none" });
					$("#post_list").css({ display: "inline" });
					
					refresh_post();
				}
			}
			

			function openTab(evt, tabName) {
				var i, tabcontent, tablinks;
				tabcontent = document.getElementsByClassName("tabcontent");
				for (i = 0; i < tabcontent.length; i++) {
					tabcontent[i].style.display = "none";
				}
				tablinks = document.getElementsByClassName("tablinks");
				for (i = 0; i < tablinks.length; i++) {
					tablinks[i].className = tablinks[i].className.replace(" active", "");
				}
				document.getElementById(tabName).style.display = "block";
				evt.currentTarget.className += " active";
			}



			function refresh_cart(){
				var page = arguments[0] ? arguments[0] : 1;
				var last_page = null;
				var next_page = null;
				var html_table = '';
				$.ajax({
					type:"post",
					url:"cart.php",
					dataType:"json",
					data:'page='+page,
					success:function(json){
						if (json.login_ok == -1){
							$("#user_info").html("用户已登出." )
						}
						else {
							if (page > 1){
								last_page = page-1;
							
							}
							if (page < Math.ceil(json.cart_count/40)){
								next_page = page+1;
							}
							$("#c_page_line").html("  ");
							if (last_page){ 
								$("#c_page_line").append("<a href='javascript:void(0)' onclick=\"refresh_cart("+last_page+")\"> &lt; &lt; </a>  ");
							}
							$("#c_page_line").append("page:"+json.page+" / "+Math.ceil(json.cart_count/40));
                       

                       
							if (next_page){
								$("#c_page_line").append("<a href='javascript:void(0)' onclick=\"refresh_cart("+next_page+")\"> &gt; &gt; </a>  ");
							}
							
							$("#cart_table").html("  ");
							// $("#cart_table").append('<tr><th>id</th><th width ="600">物品</th><th width = "100">目录价</th><th width = "100">折扣</th><th width ="50">数量</th><th width = "100">价格</th><th width = "50">状态</th><th width = "30">操作</th><th width ="150">送货地址</th></tr>');

							json.cart_list.reverse();							
								
							$.each(json.cart_list,function(n,o){								
								var elements = "<tr><td>"+o["id"]+"</td><td>"
								+my_decode(o['product_name'])+"</td><td align='center' valign='middle'>"
								+o['list_price']+"</td><td align='center' valign='middle'>"
								+o['discount']+"</td><td >"
								+"<input type='text'   onKeyUp='value=value.replace(/\\D/g,\"\");value=value.replace(/^0/,\"\")' class = 'c_qty' id='cart_"
								+o['id']+"'>"+"</input></td><td>"
								+formatNumber(o['subtotal'], 2)+"</td><td>"
								+o['state']+"</td><td align='center' valign='middle'>"
								+"<a href='javascript:void(0)' class='del_item' id='del_"+o['id']+"'>删除</a></td><td align='center' valign='middle'>"	
								// +"<a href='javascript:void(0)' class='del_item btn btn-danger' id='del_"+o['id']+"'><i class='fa fa-trash-o' aria-hidden='true'></i></a></td><td align='center' valign='middle'>"								;

								if (o["partner_id"] == false) {
								elements +=	
								"<input type='checkbox' name='chkboxes' id='add_"+o['id']+"' value='"+o['id']+"'>等待添加</td></tr>";
								} 
								elements += "<span title='I am hovering over the text'><a href='javascript:void(0)' class='unlink' id='unlink_"+o['id']+"'><font style='float:center'>"+o['partner_name']+"</font></a></span></td></tr>";								
								$("#cart_table").append(elements);								
								var i = 'cart_'+o['id'];
								$('#'+i).val(o['qty']);
								
											
							});
							
														
							$(".del_item").click(function(){
								//alert($(this).attr('id'));
								var order_id = $(this).attr('id');								
								order_id = order_id.substring(4);							
								var order_ids = new Array(order_id);
								//alert(order_ids);
								del_cart_items(order_ids);
							});
							
							$(".unlink").click(function(){								
								var order_id = $(this).attr('id');								
								order_id = order_id.substring(7);							
								var order_ids = new Array(order_id);														
								unlink_address(order_id);
							});
							
							
							
								   
                       var sum=0.00;
                       $.each(json.cart_list,function(n,o){
                              
                              sum=(sum*100+o['subtotal']*100)/100;
                              });
                       
                       $("#o_sum").empty();
                       $("#o_sum").append("购物车总金额为："+formatNumber(sum,2));
                       
                       
						}
					}
				});
			}
			
			function refresh_order(){
				var page = arguments[0] ? arguments[0] : 1;
				var last_page = null;
				var next_page = null;
				var html_table = '';
				$.ajax({
					/*type:"post",
					url:"order.php",
					dataType:"json",
					data:'page='+page,
					success:function(json){
						if (json.login_ok == -1){
							$("#user_info").html("用户已登出." )
						}
						else {
							if (page > 1){
								last_page = page-1;
							
							}
							if (page < Math.ceil(json.order_count/40)){
								next_page = page+1;
							}
							$("#o_page_line").html("  ");
							if (last_page){ 
								$("#o_page_line").append("<a href='javascript:void(0)' onclick=\"refresh_order("+last_page+")\"> &lt; &lt; </a>  ");
							}
							$("#o_page_line").append("page:"+json.page+" / "+Math.ceil(json.order_count/40));
							if (next_page){
								$("#o_page_line").append("<a href='javascript:void(0)' onclick=\"refresh_order("+next_page+")\"> &gt; &gt; </a>  ");
							}
                       
                       $.ajax({pan*/
                              url: 'order.php?method=fetchdata',
                              dataType: 'json',
                              success: function(s){
                              console.dir(s);
                                var p1="id";
                       
                              $('#order_table').dataTable().fnClearTable();
                              for(var i = 0; i < s.length; i++) {
                              $('#order_table').dataTable().fnAddData([
                                               
                                               s[i].id,
                                               my_decode(s[i].product_name),
                                               s[i].list_price,
                                               s[i].discount,
                                               s[i].qty,
                                               formatNumber(s[i].subtotal,2),
                                               s[i].state.replace('confirmed','客户确认').replace('in_so','发货').replace('accepted','订单受理'),
                                               s[i].confirm_time
                                               ]);
                       }
                       
							/*$("#order_table").html("  ");    //pan
                            //$("#order_table tbody").remove();
							//$("#order_table").append('<tr><th>id</th><th width ="400">物品</th><th>目录价</th><th width = "50">折扣</th><th width = "50">数量</th><th>价格</th><th width = "80">状态</th><th>订货日期</th></tr>');   //pan
                            //var jsdata = JSON.parse(json.order_list);
                            //$('#order_table').dataTable().fnAddData(jsdata);
                            //oTable.fnReloadAjax("order.php");

							    $.each(json.order_list,function(n,o){
								$("#order_table").append(
								"<tr><td>"+o["id"]+"</td><td>"
								+my_decode(o['product_name'])+"</td><td>"
								+o['list_price']+"</td><td>"
								+o['discount']+"</td><td>"
								+o['qty']+"</td><td>"
								+formatNumber(o['subtotal'], 2)+"</td><td>"
								+o['state'].replace('confirmed','客户确认').replace('in_so','发货').replace('accepted','订单受理')+"</td><td>"
								+o['confirm_time'].toString().slice(0,19) +" </td></tr>"
                             
                                                         )


							});   pan*/
                   


						
					}
				});

			}
			
			function refresh_post(){				
				var page = arguments[0] ? arguments[0] : 1;
				var last_page = null;
				var next_page = null;
				var html_table = '';
				$.ajax({
					type:"post",
					url:"address_book_linked.php",
					dataType:"json",
					data:'page='+page,
					success:function(json){						
						if (json.login_ok == -1){
							$("#user_info").html("用户已登出." )
						}
						else {
							if (page > 1){
								last_page = page-1;
							
							}
							if (page < Math.ceil(json.address_count/40)){
								next_page = page+1;
							}
							$("#d_page_line").html("  ");
							if (last_page){ 
								$("#d_page_line").append("<a href='javascript:void(0)' onclick=\"refresh_post("+last_page+")\"> &lt; &lt; </a>  ");
							}
							$("#d_page_line").append("page:"+json.page+" / "+Math.ceil(json.address_count/40));
							
							if (next_page){
								$("#d_page_line").append("<a href='javascript:void(0)' onclick=\"refresh_post("+next_page+")\"> &gt; &gt; </a>  ");
							}
							
							$("#post_table").html("  ");
							// $("#post_table").append('<tr><th>id</th><th width ="100">收件人姓名</th><th>国家</th><th width ="60">城市</th><th width ="100">电话</th><th width ="400">收件人地址</th><th width ="100">邮编</th><th width = "40">操作</th></tr>');

							json.address_list.reverse();														
							$.each(json.address_list,function(n,o){
															
								$.each(o,function(index,value){
									if (o[index]==false){
										o[index] = "空"
									};									
								});						
								if (o['street2']=="空"){
									$("#post_table").append(
										"<tr><td>"+o["id"]+"</td><td>"								
										+o['name']+"</td><td>"
										+o['country_id']+"</td><td>"	
										+o['city']+"</td><td>"
										+o['phone']+"</td><td>"
										+o['street']+"</td><td>"								
										+o['zip']+"</td><td>"
										+"<a href='javascript:void(0)' class='del_item' id='del_"+o['id']+"'>删除</a></td></tr>"								
										);
								}
								else{
									$("#post_table").append(
										"<tr><td>"+o["id"]+"</td><td>"								
										+o['name']+"</td><td>"
										+o['country_id']+"</td><td>"	
										+o['city']+"</td><td>"
										+o['phone']+"</td><td>"
										+o['street']+o['street2']+"</td><td>"							
										+o['zip']+"</td><td>"
										+"<a href='javascript:void(0)' class='del_item' id='del_"+o['id']+"'>删除</a></td></tr>"								
								);}
								
								
								
							});
							$(".del_item").click(function(){								
								var address_id = $(this).attr('id');
								address_id = address_id.substring(4);							
								var address_ids = new Array(address_id);								
								del_address(address_ids);
							});         
                       
                       
                       
                       
						}
					}
				});
			}
			
			function add_cart(){
				var cart_list = arguments[0];
				if (cart_list == null){
					alart("None is selected!");
				}
				else {
					
					$.ajax({
						type:"post",
						url:"add_cart.php",
						dataType:"json",
						data:{"cart_list":JSON.stringify(cart_list)},
						traditional: true,
						success:function(json){
							if (json.login_ok == -1){
								$("#user_info").html("User logged out." );
								refresh_cart();
							}
							refresh_cart();
							
						}
					});
				}
			}
			
			function update_cart(){
				var cart_list = arguments[0];				
				if (cart_list == null){
					alart("None is selected!");
				}
				else {
					
					$.ajax({
						type:"post",
						url:"update_cart.php",
						dataType:"json",
						data:{"cart_list":JSON.stringify(cart_list)},
						traditional: true,
						success:function(json){
							if (json.login_ok == -1){
								$("#user_info").html("用户已登出." );
								refresh_cart();

							}
							refresh_cart();
							// show_tab("order");
						}
					});
				}
			}
			
			function del_cart_items(){
				var cart_item_list = arguments[0];
				if (cart_item_list == null){
					alert("None is selected!");
				}
				else {
					$.ajax({
						type:"post",
						url:"del_cart_items.php",
						data:{"cart_item_list":cart_item_list},
						//data:{"cart_item_list":JSON.stringify(cart_item_list)},
						//traditional:true,
						success:function(json){
							if (json.login_ok == -1){
								$("#user_info").html("用户已登出.");
								refresh_cart();
								
							}
							refresh_cart();
						}
						
					});
				}
				
			}
			
			function unlink_address(){
				var cart_item_list = [];
				cart_item_list.push(arguments[0]);
				
				if (cart_item_list == null){
					alert("None is selected!");
				}
				else {
					$.ajax({
						type:"post",
						url:"unlink_address.php",
						data:{"cart_item_list":cart_item_list},						
						success:function(json){
							if (json.login_ok == -1){
								$("#user_info").html("用户已登出.");
								refresh_cart();
								
							}
							refresh_cart();
						}
						
					});
				}
				
			}
			
			function del_address(){
				var address_list = arguments[0];
				if (address_list == null){
					alert("None is selected!");
				}
				else {					
					$.ajax({
						type:"post",
						url:"del_address.php",
						data:{"address_list":address_list},
						
						success:function(json){
							if (json.login_ok == -1){
								$("#user_info").html("用户已登出.");
								refresh_post();
								
							}
							refresh_post();
						}
						
					});
				}
				
			}
			
			function submit_cart(){
				var cart_list = arguments[0];
				if (cart_list == null){
					alart("None is selected!");
				}
				else {
					
					$.ajax({
						type:"post",
						url:"submit_cart.php",
						dataType:"json",
						data:{"cart_list":JSON.stringify(cart_list)},
						traditional: true,
						success:function(json){
							if (json.login_ok == -1){
								$("#user_info").html("用户已登出." );
								refresh_cart();

							}
							refresh_cart();
							// show_tab("order");
						}
					});
				}
			}
			
			function product_search0(){
				var search_by = arguments[0];
				var term = arguments[1];
				var page = arguments[2] ? arguments[2] : 1;
				var last_page = null;
				var next_page = null;
				var html_table = '';
				$.ajax({
					type:"post",
					url:"product_search.php",
					dataType:"json",
					data:'search_by='+search_by+'&term='+term+'&page='+page,
					success:function(json){
						//json.prod_list;
						if (page > 1){
							last_page = page-1;
							
						}
						if (page < Math.ceil(json.product_count/40)){
							next_page = page+1;
						}
						
						$("#p_page_line").html("  ");
						if (last_page){ 
							$("#p_page_line").append("<a href='javascript:void(0)' onclick=\"product_search('"+search_by+"','"+term+"',"+last_page+")\"> &lt; &lt; </a>  ");
						}
						$("#p_page_line").append("page:"+json.page+" / "+Math.ceil(json.product_count/40));
						if (next_page){
							$("#p_page_line").append("<a href='javascript:void(0)' onclick=\"product_search('"+search_by+"','"+term+"',"+next_page+")\"> &gt; &gt; </a>  ");
						}
						
						$("#p_page_line").append("  ");
						$("#product_table").html("  ");
						$("#product_table").html("<tr><th width='25'>id</th><th width='80'>货号</th><th width='500'>品名</th><th width='50'>目录价</th><th width ='50'>库存</th><th width='50'>数量</th></tr>");
						
						
						//$("#product_table").html("Search Results: <br />" + unescape(json.html_str) );
						
						
						$.each(json.prod_list, function(n,o){
							$("#product_table").append("<tr><td>"+o["id"]+"</td><td>"
							+my_decode(o["default_code"])+"</td><td>"
							+my_decode(o["name"])+"</td><td width = '20'>"
							+my_decode(o["list_price"])+"</td><td width = '20'>" 
							+o["virtual_available"]+"</td><td><input type='text' size='5' class = 'qty' onKeyUp='value=value.replace(/\\D/g,\"\");value=value.replace(/^0/,\"\")' id ='"
							+o["id"]+"'></input></td></tr>");
							
						});
					
						
					}
				});
			}
			

			function product_search(){
				var search_by = arguments[0];
				var term = arguments[1];
				var page = arguments[2] ? arguments[2] : 1;
				var last_page = null;
				var next_page = null;
				var html_table = '';
				$.ajax({
					type:"post",
					url:"product_search.php",
					dataType:"json",
					data:'search_by='+search_by+'&term='+term+'&page='+page,
					success:function(json){
						//json.prod_list;
						if (page > 1){
							last_page = page-1;
							
						}
						if (page < Math.ceil(json.product_count/40)){
							next_page = page+1;
						}
						
						$("#p_page_line").html("  ");
						if (last_page){ 
							$("#p_page_line").append("<a href='javascript:void(0)' onclick=\"product_search('"+search_by+"','"+term+"',"+last_page+")\"> &lt; &lt; </a>  ");
						}
						$("#p_page_line").append("page:"+json.page+" / "+Math.ceil(json.product_count/40));
						if (next_page){
							$("#p_page_line").append("<a href='javascript:void(0)' onclick=\"product_search('"+search_by+"','"+term+"',"+next_page+")\"> &gt; &gt; </a>  ");
						}
						
						$("#p_page_line").append("  ");
						$("#product_table").html("  ");
						// $("#product_table").html("<tr><th width='25'>id</th><th width='80'>货号</th><th width='500'>品名</th><th width='50'>目录价</th><th width ='50'>库存</th><th width='50'>数量</th></tr>");
						
						
						////$("#product_table").html("Search Results: <br />" + unescape(json.html_str) );
						
						
						$.each(json.prod_list, function(n,o){
							$("#product_table").append("<tr><td>"+o["id"]+"</td><td>"
							+my_decode(o["default_code"])+"</td><td>"
							+my_decode(o["name"])+"</td><td>"
							+my_decode(o["list_price"])+"</td><td>" 
							+o["virtual_available"]+"</td><td><input type='text' size='5' class = 'qty' onKeyUp='value=value.replace(/\\D/g,\"\");value=value.replace(/^0/,\"\")' id ='"
							+o["id"]+"'></input></td></tr>");
							
						});
					
						
					}
				});
			}

			function my_decode(str){
				s=unescape(str);
				s=s.replace(/\+/g, " ");
				return s;
				
			}
			
			function clean_address(){
				document.getElementById('delivery_name').value="";
				document.getElementById('street').value="";
				document.getElementById('street2').value="";
				document.getElementById('city').value="";
				document.getElementById('phone').value="";
				document.getElementById('country').value="";
				document.getElementById('country_id').value="";
				document.getElementById('postcode').value="";
				document.getElementById('deli_email').value="";
			};
				
			function add_address(){
				var name = document.getElementById('delivery_name').value;
				var street = document.getElementById('street').value;
				var street2 = document.getElementById('street2').value;
				var city = document.getElementById('city').value;
				var phone = document.getElementById('phone').value;
				var country = document.getElementById('country_id').value;
				var country1 = document.getElementById('country').value;
				var postcode = document.getElementById('postcode').value;
				var email = document.getElementById('deli_email').value;											
				if (name == []){
					alert("Please input name!");
				}
				else if (street ==[]){
					alert("Please type in your address!");
				}
				else if (city ==[]){
					alert("Please type in your city!");
				}
				else if (postcode ==[]){
					alert("Please type in your postcode!");
				}
				else if (country ==[] && country1 == []){
					alert("You must select a country!");
				}
				else if (country ==[] && country1 != []){
					alert("Does not match database!");
				}
				else if (phone ==[]){
					alert("Please type in your phone!");
				}
				else {								
					$.ajax({
						type:"post",
						url:"add_address.php",
						data:{name:name, street:street,street2:street2,city:city,phone:phone,country:country,postcode:postcode,email:email},
						
						success:function(json){
							if (json.login_ok == -1){
								$("#user_info").html("用户已登出.");
								$('#mask').hide();
								$('.window').hide();
								refresh_post();
								
							} else if (json.request_ok==-1){
								$("#user_info").html("数据.");
							}
							$('#mask').hide();
							$('.window').hide();
							clean_address();														
							refresh_post();
							
						}
						
					});
				}
				
			}
		
			function link_address(selected_products,partner_id){					
																	
					if (partner_id == []){
						alert("None is added!");
					}
					else {								
						$.ajax({
							type:"post",
							url:"link_address.php",
							data:{product_id: selected_products, partner_id:partner_id},				
							success:function(json){
								if (json.login_ok == -1){
									$("#user_info").html("用户已登出.");								
									
								} else if (json.request_ok==-1){
									$("#user_info").html("数据.");
								}								
								refresh_cart();
							}
							
						});
					}
					
				}