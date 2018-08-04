	
	/*//////////////////////////////////////////////*/
	/*/////////////// JQUERY.VISICON ///////////////*/
	/*//////////// VERSION : 1.150826A  ////////////*/
	/*////////////    EDIT : 2015/08/26 ////////////*/
	/*////////////    FORM : 2015/06/01 ////////////*/
	/*//////////////////////////////////////////////*/
	
	/*//////////////////////////////////////////////*/
	/*////////////////// USER.SET //////////////////*/
	/*//////////////////////////////////////////////*/
	var UserField,
		UserDisabled,
		UserMobile = (navigator.userAgent.match(/Android|iPhone|SymbianOS|Windows Phone|iPad|iPod|MQQBrowser/i) && navigator.userAgent.indexOf("Windows NT") == -1) ? true : false;
	function checkUser(param, fct){
		window.User = $.cookie('_mngd') ? JSON.parse($.cookie('_mngd')) : false;
		window.LoginURL = 'login.html';
		if(User){
			User['Mobile'] = UserMobile;
			if(param.login) checkLogin(param.login);
			if(param.logout) defineLogoutScript(param.logout);
			fct();
		}else{
			$(location).attr('href', LoginURL);
		}
	}
	function checkLogin(api){
		postAction(api, getDataByJSON(User), function(data){
			var json = JSON.parse(data),
				res = json.result;
			if(res == 'success'){
				
			}else if(res == 'logged'){
				User = false;
				$(location).attr('href', LoginURL);
			}
		});
	}
	/*//////////////////////////////////////////////*/
	/*///////////////// MODEL.JSON /////////////////*/
	/*//////////////////////////////////////////////*/
	var Model = {
		/* name 模組名稱 */
			name: [],
		/* code 簡易代碼 */
			code: {word:[], add:[], edit:[]},
		/* text 中文 */
			text: [],
		/* modal 視窗 */
			modal: [],
		/* api 應用程序 */
			api: [],
		/* view 表格畫面 */
			view: [],
		/* count 表格筆數 // 依搜尋條件 */
			count: [],
		/* mark 關係實體 */
			mark: [],
		/* able 可否搜尋 */
			able: [],
		/* data 搜尋資料 // 所有 */
			data: [],
		/* saver 搜尋資料 // 依欄位參數 */
			saver: [],
		/* cond 視窗條件 // 依欄位參數 */
			cond: [],
		/* capi 呼叫程序 // 依欄位參數 */
			capi: [],
		/* scan 掃條碼機 // 跳出窗口 & 選擇欄位 */
			scan: [],
		/* select 掃條碼機 // 選擇欄位 */
			select: [],
		/* store 暫存同步 */
			store: [],
		/* finish 程序結束 */
			finish: [],
		/* search 搜尋參數 */
			search: []
	};
	/*//////////////////////////////////////////////*/
	/*///////////////// MODEL.MARK /////////////////*/
	/*//////////////////////////////////////////////*/
	{
		/* model 模組名稱 */
			/* :<string> */
		/* name 結構名稱 */
			/*  :<string> */
		/* text 中文 */
			/*  :<string> */
		/* modal 視窗 */
			/*  :[$object] */
		/* option 動態選項 */
			/*  :{model: <string>, trigger: <string>, select: <string>} */
			/*  :{model: 'color', trigger: 'ProductID', select: 'ColorID'} */
		/* required 必須填寫 */
			/*  :<boolean> */
		/* unique 限制重複 // 以覆蓋處理 */
			/* :[<string>] */
		/* api 應用程序 */
			/* :{(action): <string>} */
		/* view 表格畫面 */
			/* :function(s, r){} */
		/* form 表單定義 */
			/* :function(m){ return [$object] } */
		/* cond 視窗條件 // 依欄位參數 */
			/* [{form: <string>, field: <string>, value: <value>}, {form: <string>, field: <string>, value: <value>}] */
		/* scan 掃條碼機 // 跳出窗口 & 選擇欄位 */
			/* :[{ type: <word>, field: <string>, focus: <string> }] */
		/* scan : target 掃條碼機 // 跨模組掃描 */
			/* :[{ type: <word>, field: <string>, focus: <string>, target: {field: <string>, model: <string>} }] */
		/* store 暫存同步 */
			/* :[{ based: <string>, field: <string>, data: <boolean> }] */
		/* scratch 資料暫存 */
			/* :[<json>] */
		/* child 資料暫存 // 子關係實體 */
			/* :[<json>] */
	};
	/*//////////////////////////////////////////////*/
	/*///////////////// STYLE.LIST /////////////////*/
	/*//////////////////////////////////////////////*/
	var FormGroup = "form-group col-xs-12 ",
		FormControl = "form-control ",
		FormCheckbox = "form-checkbox ",
		InputGroup = "input-group ",
		InputButton = "input-group-btn ",
		InputAddon = "input-group-addon ",
		InputControl = "input-group-control ",
		Item = "item ",
		SortControl = "sort-control ";
	/*//////////////////////////////////////////////*/
	/*/////////////////// OBJECT ///////////////////*/
	/*//////////////////////////////////////////////*/
	var Body = $("body"),
		PageNavbar,	PageControl, PagePanel;
	function createWebsite(param){
		var obj = $("<div></div>").addClass("container-fluid view").appendTo(Body),
			row = $("<div></div>").addClass("row zero").appendTo(obj),
			top = $("<div></div>").addClass("top-panel").appendTo(row),
			title = $("<p></p>").addClass("top-title").html(param.title).appendTo(top),
			sub = $("<p></p>").addClass("top-sub").html(param.sub).appendTo(title)
			wrapper = $("<div></div>").addClass("nav-wrapper").appendTo(row);
		PageNavbar = $("<ul></ul>").addClass("nav nav-tabs").attr('id', "navbar").appendTo(wrapper),
		PagePanel = $("<div></div>").addClass("tab-content col-xs-12 zero").attr('id', "panel").appendTo(row);
		return obj;
	}
	function createControl(name, icon){
		var wrapper = PageNavbar.parent(),
			item = $("<li></li>").attr('ID', 'control').addClass("active nav-control").appendTo(Body),
			link = $("<a></a>").appendTo(item)
			i = $("<i></i>").addClass("fa fa-" + icon[1] + " fa-2x").appendTo(link);
		PageControl = item.on('click', function(evt){
			if($(this).hasClass('active')){
				wrapper.addClass('toggle');
				$(this).removeClass('active');
				i.removeClass('fa-' + icon[1]).addClass('fa-' + icon[0]);
			}else{
				wrapper.removeClass('toggle');
				$(this).addClass('active');
				i.removeClass('fa-' + icon[0]).addClass('fa-' + icon[1]);
			}
		});
		return item;
	}
	function createSetting(name, icon){
		var id = "panel-" + name,
			item = $("<li></li>").attr("role", "presentation").attr('ID', 'setting').addClass("nav-setting").appendTo(PageNavbar),
			link = $("<a></a>").attr("href", "#" + id).attr("aria-controls", "profile").attr("role", "tab").attr("data-toggle", "tab").appendTo(item),
			i = $("<i></i>").addClass("fa fa-" + icon + " fa-2x").appendTo(link),
			panel = $("<div></div>").attr("id", id).addClass("tab-pane").appendTo(PagePanel),
			card = $("<div></div>").addClass("container card").appendTo(panel),
			imageSrc = "images/icon-login-2x.png",
			image = $("<img>").addClass("card-image").attr('src', imageSrc).appendTo(card),
			account = $("<div></div>").addClass("center").append($("<b></b>").html(User.Account)).appendTo(card),
			desc = $("<div></div>").addClass("center").html(User.Name).appendTo(card),
			br = $("<br>").appendTo(card),
			/*set = $("<button></button>").attr("type", "buuton").attr("id", "btn-set").addClass("btn btn-block btn-primary").html("編輯個人資料").appendTo(card),*/
			logout = $("<button></button>").attr("type", "buuton").attr("id", "btn-logout").addClass("btn btn-block btn-primary").html("登出").appendTo(card);
		link.on('tap', function(evt){
			if(PageControl.hasClass('active'))
				PageControl.trigger('click');	
		});
		logout.on('tap', function(evt){
			$.cookie('_mngd', '', {expires: -1 });
			$(location).attr('href', 'login.html');
		});
		return link;
	}
	/*//////////////////////////////////////////////*/
	/*//////////////// CREATE.PANEL ////////////////*/
	/*//////////////////////////////////////////////*/
	function createPanel(model, key){
		var obj = $("<div></div>").attr('id', "panel-" + model);
		return obj;
	}
	function createPanelGroup(model, key){
		var index = $.inArray(model, Model.name),
			obj = $("<div></div>").addClass("group-" +model),
			link = $("<a></a>").attr('id', "btn-modal-" + Model.code.edit[index]).attr('data-key', key).appendTo(obj),
			item = $($("<ul></ul>")[0]).addClass(Item).appendTo(link);
		return obj;
	}
	function createPanelImage(code, param){
		var obj = $("<li></li>");
		if(code)
			obj.addClass("image").css({'background-image': "url('data:image/jpeg;base64," + code + "')"});
		if(param){
			if(param.ces) obj.addClass(param.ces);
		}
		return obj;
	}
	function createPanelItem(text, param){
		var obj = $("<li></li>").html(text).val(text);
		if(param){
			if(param.ces) obj.addClass(param.ces);
			if(param.replace){
				$.each(param.replace, function(key, value){
					if(text == value.cond || !text){
						obj.html(value.text)
						return false;
					}
				});
			}
			if(param.color)	$("<label></label>").addClass("color").css({"background-color": param.color}).prependTo(obj);
			if(param.base64) obj.addClass("image").css({'background-image': "url('data:image/jpeg;base64," + param.base64 + "')"});
			if(param.image)	obj.addClass("image").css({'background-image': "url('" + param.image + "')"});
			if(param.alarm) obj.attr("data-alarm", true).data("alarm", param.alarm);
		}
		return obj;
	}
	function createPanelSearch(model, param){
		var obj = $("<div></div>").addClass("panel-search");
		createPanelSearchAdd(model).appendTo(obj);
		if(param){
			if(param.search){
				var group = $("<div></div>").addClass("panel-search-group").prependTo(obj),
					icon = $("<i></i>").addClass("fa fa-search").appendTo(group),
					control = $("<input>").attr("type", "text").addClass("panel-search-control").attr("placeholder", "搜尋").attr('id', "search-text").appendTo(group)
				createPanelSearchDropdownOptions(createPanelSearchDropdown("type", "欄位", {stop: false, icon: "ellipsis-v"}), param.search).prependTo(obj);
			}
			if(param.date)
				createPanelSearchDropdownDate(createPanelSearchDropdown("data", "時間", {stop: true, icon: "clock-o"}), param.date).prependTo(obj);
			if(param.filter)
				createPanelSearchDropdownOptions(createPanelSearchDropdown("filter", "篩選", {stop: false, icon: "filter"}), param.filter).prependTo(obj);
			if(param.action)
				createPanelSearchAction(model, param.action).prependTo(obj);
		}
		return obj;
	}
	function createPanelSearchAdd(model){
		var index = $.inArray(model, Model.name),
			link = $("<a></a>").addClass("btn").attr('id', "btn-add-" + model).attr("data-toggle", "modal").attr("data-target", "#" + Model.code.add[index] + "Modal"),
			text = $("<span></span>").addClass("hidden-xs").html("新增").appendTo(link),
			stack = $("<span></span>").addClass("fa-stack fa-lg").appendTo(link),
			wrapper = $("<i></i>").addClass("fa fa-circle-thin fa-stack-1x").appendTo(stack),
			icon = $("<i></i>").addClass("fa fa-plus fa-stack-1x").appendTo(stack);
		return link;
	}
	function createPanelSearchAction(model, action){
		var index = $.inArray(model, Model.name),
			link = $("<a></a>").addClass("btn").attr('id', "btn-" + action.name + "-" + model),
			text = $("<span></span>").addClass("hidden-xs").html("列印").appendTo(link),
			stack = $("<span></span>").addClass("fa-stack fa-lg").appendTo(link),
			wrapper = $("<i></i>").addClass("fa fa-" + (action.wrapper ? action.wrapper : "circle-thin") + " fa-stack-1x").appendTo(stack),
			icon = $("<i></i>").addClass("fa fa-" + action.icon + " fa-stack-1x").appendTo(stack);
		if(action.required) link.attr("count-required", action.required);
		link.on('tap', function(evt){
			var param = {
				form: action.form,
				json: action.json,
				search: Model.search[index] ? Model.search[index] : false
			};
			if(action.method == "window")
				getActionWithWindow(action.api, param);
			if(action.method == "post")
				postAction(action.api);
			if(action.method == "get")
				getAction(action.api);
		});
		return link;
	}
	function createPanelSearchDropdown(name, text, param){
		var obj = $("<div></div>").addClass("dropdown"),
			btn = $("<span></span>").attr('id', "option-" + name).attr("role", "button").attr("data-toggle", "dropdown").attr("aria-haspopup", true).attr("aria-expanded", false).appendTo(obj),
			text = $("<span></span>").addClass("dropdown-text hidden-xs").attr("role", "text").html(text).appendTo(btn),
			stack = $("<span></span>").addClass("fa-stack fa-lg hidden-xs").appendTo(btn),
			circle = $("<i></i>").addClass("fa fa-circle-thin fa-stack-1x").appendTo(stack),
			icon = $("<i></i>").addClass("fa fa-angle-down fa-stack-1x").appendTo(stack),
			menu = $("<span></span>").addClass("dropdown-menu").attr("role", "menu").attr("aria-labelledby", "option-" + name).appendTo(obj);
		if(param){
			if(param.icon)
				$("<i></i>").addClass("fa fa-" + param.icon + " fa-fw show-xs").appendTo(btn);
			if(param.stop)
				menu.on('click', function(evt){
					if(evt.target.localName != "li")
						evt.stopPropagation();
				});
		}
		return obj;
	}
	function createPanelSearchDropdownOptions(dropdown, options){
		var btn	= dropdown.find("[role='button']"),
			text = dropdown.find("[role='text']"),
			menu = dropdown.find("[role='menu']");
		if(options)
			$.each(options, function(key, option){
				var obj = $("<li></li>").attr("role", "menuitem").appendTo(menu);
				if(option.text) obj.html(option.text);
				if(option.field) obj.attr("data-field", option.field);
				if(option.value) obj.attr("data-value", option.value);
			});
		dropdown.on('tap', "[role='menuitem']", function(){
			text.html($(this).html());
			var field = $(this).attr("data-field"),
				value = $(this).attr("data-value");
			$(this).addClass('active').siblings().removeClass('active');
			if(field) btn.attr("data-field", field); else btn.removeAttr("data-field");
			if(value) btn.attr("data-value", value); else btn.removeAttr("data-value");
			btn.trigger("change");
		}).find("[role='menuitem']:first").trigger('tap');
		return dropdown;
	}
	function createPanelSearchDropdownDate(dropdown, search){
		var menu = dropdown.find("[role='menu']");
		$("<label></label>").html("由 / 之後").appendTo(menu);
		$("<input>").attr("type", "date").addClass(FormControl).attr('id', "option-date-after").attr("date-field", search.after).appendTo(menu);
		$("<label></label>").html("到 / 之前").appendTo(menu);
		$("<input>").attr("type", "date").addClass(FormControl).attr('id', "option-date-before").attr("date-field", search.before).appendTo(menu);
		return dropdown;
	}
	function createPanelTitle(page, array){
		var model = page.attr('ID').split("-")[1],
			obj = $("<div></div>").addClass("panel-title");
			list = $("<ul></ul>").addClass(Item).appendTo(obj);
		$.each(array, function(key, value){
			$("<li></li>").addClass(SortControl).html(value).appendTo(list);
		});
		defineSortScript(page, model, ".panel-title");
		return obj;
	}
	/*//////////////////////////////////////////////*/
	/*//////////////// CREATE.FORM /////////////////*/
	/*//////////////////////////////////////////////*/
	function createForm(model, code, api){
		var obj = $("<form></form>").attr("role", "form").attr("name", code + "Form");
		if(model) obj.attr('id', "form-" + model);
		if(api) obj.attr("method", "post").attr("target", "_blank").attr("action", api);
		return obj;
	}
	function createFormHidden(name, value){
		var obj = $("<hidden></hidden>"),
			input = $("<input>").attr("type", "hidden").attr("name", name).attr('id', "section-" + name).appendTo(obj);
		if(value) input.val(value);
		return obj;
	}
	function createFormText(name, text, param){
		var obj = $("<div></div>").addClass(FormGroup),
			input = $("<input>").attr("type", "text").attr("name", name).addClass(FormControl).attr('id', "section-" + name).attr("placeholder", text).appendTo(obj);
		if(param){
			if(param.attribute) input.attr(param.attribute, true);
			if(param.preset) input.attr("data-preset", param.preset);
		}
		return obj;
	}
	function createFormInput(type, name, param){
		var obj = $("<div></div>").addClass(FormGroup),
			input = $("<input>").attr("type", type).attr("name", name).addClass(FormControl).attr('id', "section-" + name).attr("placeholder", placeholder).appendTo(obj);
		if(param){
			if(param.placeholder) input.attr("placeholder", param.placeholder);
			if(param.attribute) input.attr(param.attribute, true);
			if(param.preset) input.attr("data-preset", param.preset);
		}
		return obj;
	}
	function createFormOutput(name, text, param){
		var obj = $("<div></div>").addClass(FormGroup),
			group = $("<div></div>").addClass(InputGroup).appendTo(obj),
			addon = $("<div></div>").addClass(InputAddon).html(text).appendTo(group),
			input = $("<output>").attr("name", name).addClass(FormControl).attr('id', "section-" + name).appendTo(group);
		if(param){
			if(param.placeholder) input.attr("placeholder", param.placeholder);
			if(param.attribute) input.attr(param.attribute, true);
			if(param.disabled) input.attr("disabled", param.disabled);
			if(param.preset) input.attr("data-preset", param.preset);
			if(param.alarm) input.on("change", function(evt){handleViewAlarm(input, param.alarm)});
		}
		return obj;
	}
	function createFormTextarea(name, param){
		var obj = $("<div></div>").addClass(FormGroup),
			area = $("<textarea></textarea>").attr("type", "text").attr("name", name).addClass(FormControl).attr('id', "section-" + name).attr("rows", 3).appendTo(obj);
		if(param){
			if(param.placeholder) area.attr("placeholder", param.placeholder);
			if(param.attribute) area.attr(param.attribute, true);
		}
		return obj;
	}
	function createFormCheckbox(name, text, param){
		var now = new Date(),
			time = now.getTime(),
			obj = $("<div></div>").addClass(FormGroup),
			group = $("<label></label>").addClass(FormCheckbox).appendTo(obj),
			input = $("<input>").attr("type", "checkbox").attr("name", name).attr('id', "section-" + name + time).appendTo(group),
			label = $("<label></label>").attr("for", "section-" + name + time).appendTo(group),
			block = $("<b></b>").html(text).appendTo(group);
		if(param){
			if(param.val) input.val(param.val);
			if(param.value) input.attr("value", param.value);
			if(param.prop) input.prop(param.prop, true);
			if(param.attribute) input.attr(param.attribute, true);
			if(param.disabled) input.attr("disabled", param.disabled);
			if(param.fct) input.attr("data-fct", true).data("fct", param.fct);
		}
		input.on('change', function(){
			if(input.attr("checked")){
				input.removeAttr("checked");
			}else{
				input.attr("checked", true);
			}
		});
		return obj;
	}
	function createFormAddon(type, name, text, param){
		var obj = $("<div></div>").addClass(FormGroup),
			group = $("<div></div>").addClass(InputGroup).appendTo(obj),
			addon = $("<div></div>").addClass(InputAddon).html(text).appendTo(group),
			input = $("<input>").attr("type", type).attr("name", name).addClass(FormControl).attr('id', "section-" + name).appendTo(group);
		if(param){
			if(param.placeholder) input.attr("placeholder", param.placeholder);
			if(param.attribute) input.attr(param.attribute, true);
			if(param.disabled) input.attr("disabled", param.disabled);
			if(param.preset) input.attr("data-preset", param.preset);
			if(param.alarm) input.on("change", function(evt){handleViewAlarm(input, param.alarm)});
		}
		return obj;
	}
	function createFormSelect(model, name, text, param){
		if(param && param.add){
			var obj = $("<div></div>").addClass(FormGroup),
				group = $("<div></div>").addClass(InputGroup).appendTo(obj),
				select = $("<select></select>").addClass(FormControl + "select-" + model).attr("name", name).attr('id', "section-" + name).appendTo(group),
				option = $("<option value></option>").html("- " + text + " -").appendTo(select),
				btn = $("<a></a>").addClass("input-group-addon btn btn-primary").attr('id', "btn-add-" + model).appendTo(group),
				icon = $("<i></i>").addClass("fa fa-plus fa-fw").appendTo(btn);
		}else{
			var obj = $("<div></div>").addClass(FormGroup),
				select = $("<select></select>").addClass(FormControl + "select-" + model).attr("name", name).attr('id', "section-" + name).appendTo(obj),
				option = $("<option value></option>").html("- " + text + " -").appendTo(select);
		}
		if(param){
			if(param.value) select.attr("value", param.value);
			if(param.attribute) select.attr(param.attribute, true);
			if(param.disabled) select.attr("disabled", param.disabled);
			if(param.preset) select.attr("data-preset", param.preset);
			if(param.load) select.data("load", param.load);
		}
		return obj;
	}
	function createFormSelectOptions(obj ,json){
		var select = obj.find("select");
		$.each(json, function(key, value){
			$("<option></option>").html(value.text).val(value.value).appendTo(select);
		});
		return obj;
	}
	function createFormSelectModelOptions(model, obj){
		var index = $.inArray(model, Model.name),
			data = Model.data[index] ? Model.data[index] : false,
			savers = Model.saver[index] ? Model.saver[index] : false,
			select = obj.find('select');
		if(data && data.length > 0)
			$.each(data, function(key, value){
				var id = value[Upper(model) + 'ID'];
				$("<option></option>").html(getOptionText(value)).val(id).appendTo(select);
			});
		if(savers && savers.length > 0)
			$.each(savers, function(n, saver){
				if(JSON.stringify(saver.load) == JSON.stringify(select.data("load"))){
					if(saver.data)
						$.each(saver.data, function(key, value){
							var id = value[Upper(model) + 'ID'];
							$("<option></option>").html(getOptionText(value)).val(id).appendTo(select);
						});
				}
			});
		return obj;
	}
	function createFormSelectMarkOptions(model, name, obj){
		var index = $.inArray(model, Model.name),
			marks = Model.mark[index],
			select = obj.find('select');
		if(marks){
			$.each(marks, function(n, mark){
				if(mark.name == name){
					if(mark.data && mark.data.length > 0){
						$.each(mark.data, function(key, value){
							var id = value[mark.model + 'ID'];
							$("<option></option>").html(getOptionText(value)).val(id).appendTo(select);
						});
					}else{
						getSearchMark(model, mark, false, function(){
							if(mark.data && mark.data.length > 0){
								$.each(mark.data, function(key, value){
									var id = value[mark.model + 'ID'];
									$("<option></option>").html(getOptionText(value)).val(id).appendTo(select);
								});
							}
						});
					}
				}
			});
		}
		return obj;
	}
	function createFormMultText(model, mark, name, param){
		var id = "btn-" + mark,
			obj = $("<div></div>").addClass(FormGroup),
			group = $("<div></div>").addClass(InputGroup).appendTo(obj),
			input = $("<input>").attr("type", "text").addClass(FormControl + "fixed").attr('id', "section-" + name).appendTo(group),
			btn = $("<a></a>").addClass("input-group-addon btn btn-primary").attr('id', id).appendTo(group),
			icon = $("<i></i>").addClass("fa fa-plus fa-fw").appendTo(btn),
			panel = $("<div></div>").attr('id', "panel-" + model + "-" + mark).appendTo(obj);
		if(param){
			if(param.placeholder) input.attr("placeholder", param.placeholder);
			if(param.attribute) input.attr(param.attribute, true);
			if(param.preset) input.attr("data-preset", param.preset);
		}
		return obj;
	}
	function createCanvas(id, param){
		var obj = $("<canvas></canvas>").attr('id', id).attr('width', 0).attr('height', 0).css("margin-bottom", "10px");
		if(param){
			if(param.attribute) obj.prop(param.attribute, true);
		}
		return obj;
	}
	function createFormImage(code, name, text, param){
		var now = new Date(),
			time = now.getTime(),
			mark = "image",
			id = "btn-" + mark + "-" + code + time,
			obj = $("<div></div>").addClass(FormGroup),
			label = $("<label></label>").attr("for", id).addClass("btn btn-primary").html(text).appendTo(obj),
			input = $("<input>").attr("type", "file").attr("name", name).attr('id', id).attr("accept", "image/png, image/gif, image/jpg, image/jpeg").hide().appendTo(obj);	
		if(param){
			if(param.attribute) input.attr(param.attribute, true);
		}
		defineImageScript(id, name);
		return obj;
	}
	function createFormPrintButton(name, text, param){
		var now = new Date(),
			time = now.getTime(),
			id = "btn-" + name.btn + "-" + time,
			icon = $("<i></i>").addClass("fa fa-fw"),
			obj = $("<div></div>").addClass(FormGroup);
		if(name.input){
			var group = $("<div></div>").addClass(InputGroup).appendTo(obj),
				input = $("<input>").attr("type", "text").attr("name", name.input).addClass(InputControl).appendTo(group),
				btn = $("<a></a>").attr('id', id).addClass("btn btn-primary").append(icon).append(text).appendTo(group);
		}else{
			var btn = $("<a></a>").attr('id', id).addClass("btn btn-primary").append(icon).append(text).appendTo(obj);
		}
		if(param){
			if(param.icon) icon.addClass("fa-" + param.icon);
			if(param.ces) obj.addClass(param.ces);
			if(param.attribute) btn.attr(param.attribute, true);
			if(param.placeholder) input.attr("placeholder", param.placeholder);
			if(param.preset) input.attr("value", param.preset);
			if(param.scratch) btn.attr("data-scratch", param.scratch);
			if(param.fct) btn.on('tap', param.fct);
		}
		return obj;
	}
	function createFormActionButton(name, text, param, fct){
		var now = new Date(),
			time = now.getTime(),
			id = "btn-" + name + "-" + time,
			icon = $("<i></i>").addClass("fa fa-fw"),
			btn = $("<a></a>").attr("role", "action").addClass("btn btn-primary right").attr('id', id).append(icon).append(text);
		if(param){
			if(param.icon) icon.addClass("fa-" + param.icon);
			if(param.ces) btn.addClass(param.ces);
			if(param.attribute) btn.attr(param.attribute, true);
			if(param.scratch) btn.attr("data-scratch", param.scratch);
		}
		if(fct) btn.on('click', fct);
		return btn;
	}
	function createFormButtonAdd(param){
		var obj = $("<div></div>").addClass("modal-footer clear"),
			reset = $("<button></button>").attr("type", "reset").addClass("btn btn-warning left").attr('id', "btn-reset").html("重新填寫").appendTo(obj),
			close = $("<button></button>").attr("type", "button").addClass("btn btn-default right").attr("data-dismiss", "modal").html("關閉").appendTo(obj),
			add = $("<button></button>").attr("type", "submit").addClass("btn btn-primary right").attr('id', "btn-add").html("確認新增").appendTo(obj);
		if(param && param.option){
			
		}
		return obj;
	}
	function createFormButtonEdit(param){
		var text = {};
		text.remove = param && param.remove ? param.remove : "刪除";
		text.edit = param && param.edit ? param.edit : "確認修改";
		var obj = $("<div></div>").addClass("modal-footer clear"),
			reset = $("<button></button>").attr("type", "reset").attr('id', "btn-reset").prop("hidden", true).appendTo(obj),
			remove = $("<button></button>").attr("type", "button").addClass("btn btn-danger left").attr('id', "btn-remove").html(text.remove).data("text", text.remove).appendTo(obj),
			close = $("<button></button>").attr("type", "button").addClass("btn btn-default right").attr("data-dismiss", "modal").html("關閉").appendTo(obj),
			edit = $("<button></button>").attr("type", "submit").addClass("btn btn-primary right").attr('id', "btn-edit").html(text.edit).appendTo(obj);
		if(param && param.disabled){
			if(param.disabled.reset) reset.remove();
			if(param.disabled.remove) remove.remove();
			if(param.disabled.close) close.remove();
			if(param.disabled.edit) edit.remove();
		}
		if(param && param.append){
			$.each(param.append, function(key, btn){
				btn.appendTo(obj);
			});
		}
		return obj;
	}
	/*//////////////////////////////////////////////*/
	/*//////////////// CREATE.MARK /////////////////*/
	/*//////////////////////////////////////////////*/
	function createFormMark(form, name, text, title, param){
		var obj = $("<div></div>").addClass(FormGroup),
			group = $("<div></div>").addClass(InputGroup + "mark-control").appendTo(obj),
			slide = {};
			slide.id = "btn-slide-" + name;
			slide.addon = $("<span></span>").addClass(InputButton).appendTo(group);
			slide.btn = $("<a></a>").addClass("btn btn-primary").attr('id', slide.id).appendTo(slide.addon);
			slide.icon = $("<i></i>").addClass("fa fa-angle-up fa-fw").appendTo(slide.btn);
		var block = $("<b></b>").addClass(FormControl).html(text).appendTo(group),
			add = {};
			add.id = "btn-add-" + name;
			add.addon = $("<span></span>").addClass(InputButton).appendTo(group);
			add.btn = $("<a></a>").addClass("btn btn-primary").attr('id', add.id).appendTo(add.addon);
			add.icon = $("<i></i>").addClass("fa fa-plus fa-fw").appendTo(add.btn);
		if(param && param.attribute){
			add.btn.attr(param.attribute, true);
		}
		if(title){
			createFormMarkTitle(title).appendTo(obj);
			defineSortScript(form, name, ".mark-title");
		}
		var panel = $("<div></div>").attr('id', "panel-" + name).appendTo(obj);
		return obj;
	}
	function createFormMarkTitle(title){
		var obj = $("<div></div>").addClass("mark-title");
			list = $("<ul></ul>").addClass(Item).appendTo(obj);
		$.each(title, function(key, value){
			$("<li></li>").addClass(SortControl).html(value).appendTo(list);
		});
		return obj;
	}
	function createFormMarkGroup(mark, data, id){
		var code = Frist(mark.name),
			obj = $("<div></div>").addClass("group-" + mark.name),
			link = $("<a></a>").attr('id', "btn-mark-" + code).attr(data[0] + '-key', data[1]).appendTo(obj),
			item = $($("<ul></ul>")[0]).addClass(Item).appendTo(link);
		if(id) link.attr('id-key', id);
		return obj;
	}
	/*//////////////////////////////////////////////*/
	/*//////////////// CREATE.MODAL ////////////////*/
	/*//////////////////////////////////////////////*/
	function createModal(code, model, text, action, param){
		var index = $.inArray(model, Model.name),
			obj = $("<div></div>").addClass("modal fade").attr('id', code + "Modal").attr("data-index", index).attr("tabindex", -1).attr("role", "dialog").attr("aria-hidden", true),
			dialog = $("<div></div>").addClass("modal-dialog").appendTo(obj),
			content = $("<div></div>").addClass("modal-content").appendTo(dialog),
			header = $("<div></div>").addClass("modal-header").appendTo(content),
			button = $("<button></button>").attr("type", "button").addClass("close").attr("data-dismiss", "modal").attr("aria-label", "Close").appendTo(header),
			span = $("<span></span>").attr("aria-hidden", true).html("&times;").appendTo(button),
			title = $("<h4></h4>").addClass("modal-title").html(text).appendTo(header),
			label = $("<label></label>").addClass("label label-sm label-primary").html(action).appendTo(title),
			body = $("<div></div>").addClass("modal-body").appendTo(content),
			form = createForm(model, code).appendTo(body),
			bottom = $("<div></div>").addClass("form-group").appendTo(body),
			alert = $("<div></div>").attr('id', "result").attr("role", "alert").hide().appendTo(bottom),
			point = $("<b></b>").addClass("modal-point").attr("role", "point").hide().appendTo(title);
		if(param){
			if(param.layer) obj.attr("data-layer", param.layer);
		}
		return obj;
	}
	/*//////////////////////////////////////////////*/
	/*/////////////// DEFINE.SCRIPT ////////////////*/
	/*//////////////////////////////////////////////*/	
	function defineLoginScript(param){
		if(param.form){
			var form = $(param.form).submit(function(e){
				e.preventDefault();
				var data = new FormData(form[0]),
					btn = $("#btn-login", form).prop('disabled', true),
					alert = $("#result", form).attr('class', "alert").empty();
				data.append('Mobile', UserMobile);
				$.ajax({
					url: param.api,
					type: 'POST',
					data: data,
					cache: false,
					contentType: false,
					processData: false,
					success: function(data){
						var json = JSON.parse(data),
							res = json.result,
							message;
						if(res == 'success'){
							$.cookie("_mngd", data);
							$(location).attr('href', param.file);
						}else if(res == 'logged'){
							message = '此帳號已登入系統！';
						}else if(res == 'full'){
							message = '已超出可登入使用者數量！';
						}else if(res == 'fault'){
							message = '您輸入的帳號或密碼無效！';
						}
						btn.prop('disabled', false);
						if(message)
							alert.addClass('alert-danger').html(message).show();
					}
				});
			});
		}
	}
	function defineLogoutScript(api){
		$(window).on('beforeunload', handleLogout);
		function handleLogout(evt){
			if(User){
				var data = getDataByJSON(User);
				$.ajax({
					url: api,
					type: 'POST',
					data: data,
					cache: false,
					contentType: false,
					processData: false,
					async: false
				});
				return;
			}
		}
	}
	function defineSearchScript(page){
		var model = page.attr('ID').split("-")[1],
			index = $.inArray(model, Model.name),
			SearhText = "#search-text",
			OptionType = "#option-type",
			OptionFilter = "#option-filter",
			OptionDateA = "#option-date-after",
			OptionDateB = "#option-date-before";
		page.on("keypress", SearhText, function(evt){
			if(evt.which == 13){
				evt.preventDefault();
				triggerSearch(evt);
			}
		}).on("change", SearhText, function(evt){
			triggerSearch(evt);
		}).on("change", OptionFilter, function(evt){
			triggerSearch(evt);
		}).on("change", OptionDateA, function(evt){
			triggerSearch(evt);
		}).on("change", OptionDateB, function(evt){
			triggerSearch(evt);
		});
		function triggerSearch(evt){
			Model.search[index] = {
				cond: {
					field: $(OptionFilter, page).attr("data-field"),
					value: $(OptionFilter, page).attr("data-value")
				},
				like: {
					field: $(OptionType, page).attr("data-field"),
					keyword: $(SearhText, page).val()
				},
				date: {
					after: {
						field: $(OptionDateA, page).attr("date-field"),
						value: $(OptionDateA, page).val()
					},
					before: {
						field: $(OptionDateB, page).attr("date-field"),
						value: $(OptionDateB, page).val()
					}
				}
			};
			handleViewData(page, 'panel', Model.search[index]);
		}
	}
	function defineSortScript(parent, model, ces){
		parent.on('tap', ces + " li." + SortControl, function(evt){
			var item = $(this),
				index = item.index();
			if(item.hasClass("active up")){
				item.removeClass("up").addClass("down");
				doSort(true, index);
			}else if(item.hasClass("active down")){
				item.removeClass("down").addClass("up");
				doSort(false, index);
			}else{
				item.addClass("active down").siblings('li').removeClass("active up down");
				doSort(true, index);
			}
		}).on('sort', function(evt){
			var item = $(this).find(ces + " li.active." + SortControl),
				index = item.index();
			if(item.hasClass("active up")){
				doSort(false, index);
			}else if(item.hasClass("active down")){
				doSort(true, index);
			}
		});
		function doSort(down, index){
			var panel = $("#panel-" + model, parent),
				groups = $(".group-" + model, panel).get().sort(function(a, b){
				var key = {
					a: $($("li", a)[index]).text(),
					b: $($("li", b)[index]).text()
				};
				if($.isNumeric(key.a))
					key.a = parseInt(key.a);
				if($.isNumeric(key.b))
					key.b = parseInt(key.b);
				if(down)
					return (key.a < key.b) ? 1 : ((key.a > key.b) ? -1 : 0);
				else
					return (key.a < key.b) ? -1 : ((key.a > key.b) ? 1 : 0);
			});
			panel.empty();
			$.each(groups, function(key, group){
				panel.append(group);
			});
		}
	}
	function defineStoreScript(parent, store){
		if(store){
			$.each(store, function(n, item){
				if(item){
					var model = item.based.replace(/ID/g, ''),
					index = $.inArray(Lower(model), Model.name);
					parent.on("change", "[name='" + item.based + "']", function(evt){
						var select = $(this),
							parents = select.parentsUntil(".modal"),
							modal = $(parents[parents.length - 1]).parent(),
							obj = item.name ? $("[name='" + item.name + "']", modal) : $("[name='" + item.field + "']", modal),
							value = select.val();
						if(!handleStoreData(Model.data[index]))
							$.each(Model.saver[index], function(key, saver){
								if(JSON.stringify(saver.load) == JSON.stringify(select.data("load"))){
									if(handleStoreData(saver.data))
										return false;
								}
							});
						function handleStoreData(data){
							if(data){
								var res;
								$.each(data, function(key, json){
									if(item.data[value]){
										obj.val(item.data[value]);
										res = true;
										return false;
									}else if(value == json[item.based]){
										obj.val(json[item.field]);
										res = true;
										return false;
									}else{
										obj.val('');
									}
								});
								return res;
							}
						}
					});
				}
			});
		}
	}
	function defineImageScript(id, name){
		Body.on("change", "[id='"+ id +"']", function(e){
			handleFileImage(e, this, function(modal, file){
				var cvs = $("canvas", modal),
					ctx = cvs[0].getContext("2d"),
					image = new Image();
				$("[name='" + name + "']", modal).val(file.data);
				image.onload = function(){
					var width = image.width,
						height = image.height,
						size = 150;
					if(width < height){
						width = width / height * size;
						height = size;
					}else{
						height = height / width * size;
						width = size;
					}
					ctx.clearRect(0, 0, cvs.width(), cvs.height());
					cvs.attr('width', width).attr('height', height);
					ctx.drawImage(image, 0, 0, width, height);
				};
				image.src = 'data:image/jpeg;base64,' + file.data;
			});
		});
	}
	function defineActionScript(pages){
		$.each(pages, function(n, page){
			var model = page.attr('ID').split("-")[1],
				action = page.attr('ID').split("-")[2],
				index = $.inArray(model, Model.name);
			$("#form-" + model, page).on("submit", function(evt){
					evt.preventDefault();
					var btn = $("#btn-" + action, page).prop('disabled', true),
						form = $(this),
						state = $("[name='State']", form).val();
					if(checkActionRequired(form, function(){
						var res = false,
							marks = Model.mark[index];
						if(marks){
							$.each(marks, function(key, mark){
								if(mark.required == true){
									var panel = $("#panel-" + mark.name, page),
										group = $(".group-" + mark.name, panel),
										btn = panel.parent().find("#btn-add-" + mark.name);
									if(group.length == 0 && !mark.scratch){
										if(state == 0){
											res = false;
										}else{
											btn.parent().addClass('has-error');
											res = true;
										}
									}
								}
							});
						}
						return res;
					})){
						btn.prop('disabled', false);
						return;
					}else{
						var data = new FormData(form[0]),
							api = (Model.api[index] && Model.api[index][action]) ? Model.api[index][action] : ("php/api_" + Upper(model) + "_" + Upper(action) + ".php");
						if(User && UserField){
							if($("[name='" + UserField.ID + "']:disabled", form).length > 0)
								data.append(UserField.ID, User[UserField.ID]);
						}
						if(Model.capi[index]){
							$.each(Model.capi[index], function(key, cond){
								if($("[name='" + cond.field + "']", form).val() == cond.value){
									api = "php/api_" + Upper(model) + "_" + Upper(cond.action) + ".php";
									return false;
								}
							});
						}
						postAction(api, data, function(data){
							btn.prop('disabled', false);
							var reset = (n == 0 || state == 0) ? true : false,
								json = JSON.parse(data),
								modal = $(".modal.fade.in", page),
								btns = $("[data-scratch='empty']", modal);
							if(json.result == 'successful'){
								btns.attr("disabled", false);
								finishAction(modal, json, reset);
								getSearch(model);
								postActionMark(page, json);
							}
						});
						return true;
					}
			});
		});
	}
	function defineScratchScript(marks, mark){
		$.each(mark.modal, function(action, modal){
			var btn = $("#btn-" + action, modal),
				form = $("#form-" + mark.name, modal).submit(function(evt){
					evt.preventDefault();
					btn.prop('disabled', true);				
					if(checkActionRequired(form)){
						btn.prop('disabled', false);
						return;
					}else{
						initScratch();
						var json = getJSONByForm(form),
							frame = $(".modal.fade.in:not(#" + modal.attr('ID') + ")"),
							panel = $("#panel-" + mark.name, frame),
							key = {
								data: form.attr('data-key'),
								scratch: form.attr('scratch-key')
							};
						setStore(json);
						if(action == 'add'){
							if((key.repeat = handleRepeatData(json)) >= 0){
								deleteScratch(key.repeat);
								var link = $("[scratch-key='" + key.repeat + "']", panel),
									group = link.parent().remove();
							}
							var index = setScratch(json),
								group = createFormMarkGroup(mark, ['scratch', index]).appendTo(panel),
								item = group.find("ul." + Item);
							setChild(key.scratch, index);
							if(mark.view)
								mark.view(item, json);
						}else{
							var state = $("[name='State']", form).val(),
								link = key.scratch ? $("[scratch-key='" + key.scratch + "']", panel) : $("[data-key='" + key.data + "']", panel);
							if(state == 0){
								if(key.data)
									setScratch(json);
								var group = link.parent().remove();
							}else{
								$("[data-scratch='empty']", frame).attr("disabled", true);
								var index = setScratch(json),
									item = link.attr('scratch-key', index).find("ul." + Item).empty();
								setChild(key.scratch, index);
								if(mark.view)
									mark.view(item, json);
							}
							deleteScratch(key.scratch);
						}
						if(mark.limit)
							setModalMarkLimit(marks, frame);
						btn.prop('disabled', false);
						modal.modal('toggle');
						return true;
					}
				}).on("click", "#btn-remove", function(evt){
					var state = $("[name='State']", form),
						remove = $(this);
					if(state.val() == 0){
						state.val(1);
						remove.html(remove.data("text"));
					}else{
						state.val(0);
						remove.html("取消");
					}
				}).on("click", "#btn-reset", function(evt){
					evt.preventDefault();
					form.trigger("reset");
					if(mark.mark){
						$.each(mark.mark, function(k, m){
							$("#panel-" + m.name, form).empty();
						});
					}
					var remove = $("#btn-remove", form);
					remove.html(remove.data("text"));
					form.each(function(){
						var parents = $(this).parentsUntil(".modal"),
							modal = $(parents[parents.length - 1]).parent();
						$("[role='point']", modal).html('').hide();
					});
					$("[name='State']", form).val(1);
					$("[data-preset]", form).each(function(){
						$(this).val($(this).attr("data-preset"));
					});
					$("[class*='has-error']", form).removeClass("has-error");
					$("#result", modal).attr('class', "alert");
					$("canvas", form).attr('width', 0).attr('height', 0);
				});
		});
		function setStore(json){
			if(mark.store){
				$.each(mark.store, function(n, item){
					if(!item.data)
						item.data = [];
					item.data[json[item.based]] = json[item.field];
				});
			}
		}
		function setChild(key, index){
			if(mark.mark){
				$.each(mark.mark, function(k, m){
					if(mark.child[k] && mark.child[k][key]){
						delete mark.child[k][key];
					}else if(!mark.child[k]){
						mark.child[k] = [];
					}
					mark.child[k][index] = m.scratch;
					delete m.scratch;
				});
			}
		}
		function initScratch(){
			if(!mark.scratch){
				mark.scratch = [];
				if(mark.mark){
					if(!mark.child)
						mark.child = [];
				}
			}
		}
		function setScratch(json){
			if(json){
				mark.scratch.push(json);
				return $.inArray(json, mark.scratch);
			}
		}
		function deleteScratch(key){
			if(key >= 0){
				delete mark.scratch[key];
			}
		}
		function handleRepeatData(json){
			var res;
			if(mark.scratch)
				$.each(mark.scratch, function(key, scratch){
					if(scratch)
						$.each(scratch, function(field, value){
							if(mark.unique)
								$.each(mark.unique, function(n, cond){
									if(field == cond){
										if(value == json[field]){
											res = key;
											return false;
										}
									}
								});
						});
				});
			return res;
		}
	}
	function defineModalScript(pages){
		$.each(pages, function(n, page){
			var model = page.attr('id').split("-")[1],
				action = page.attr('id').split("-")[2],
				index = $.inArray(model, Model.name),
				modal = Model.modal[index][action],
				marks = Model.mark[index],
				stores = Model.store[index];
			defineStoreScript(modal, stores);
			defineScanScript();
			
			page.on('shown.bs.modal', modal, function(){
				Body.css("overflow-y", "hidden");
			}).on('hidden.bs.modal', '.modal', function(evt){
				Body.css("overflow-y", "auto");
			});
			if(action == 'add'){
				page.on('shown.bs.modal', modal, function(){
					if(User && UserField){
						var obj = $("select[name='" + UserField.ID + "']", this);
						if(!obj.val()) obj.val(User[UserField.ID]);
					}
				});
			}else if(action == 'edit'){
				var btn = "[id^='btn-modal']",
					alert = $("#result", page);
				page.on("doubletap", btn, function(evt){
					if($(this).find("ul." + Item).hasClass("click")){
						var frame = modal,
							form = $("#form-" + model, frame),
							key = $(this).attr('data-key'),
							json = Model.data[index][key];
						if(Model.cond[index]){
							$.each(Model.cond[index], function(i, cond){
								if(json[cond.field] == cond.value){
									frame = Model.modal[index][cond.form];
									return false;
								}
							});
						}
						frame.modal('toggle');
						$("#btn-reset", form).trigger("click");
						form.hide();
						alert.html($("<i></i>").addClass("fa fa-spinner fa-spin fa-3x")).show();
						setModalField(frame, json);
						alert.empty().hide();
						form.show();
					}
				}).on("tap", btn, function(evt){
					$(".click", page).removeClass("click");
					$(this).find("ul." + Item).addClass("click");
				}).on('tap', "[data-target='#" + Model.modal[index].add.attr('ID') + "']", function(evt){
					$("#btn-reset", Model.modal[index].add).trigger("click");
					if(marks){
						$.each(marks, function(key, mark){
							if(mark.mark) delete mark.child;
							if(mark.scratch) delete mark.scratch;
						});
					}
				});
			}
		});
	}
	function defineScanScript(){
		if(!Body.data('scan')){
			window.Scan = {able: true};
			var barcode = '',
				timer;
			$(window).on('keyup', function(evt){
				if(!Scan.able)
					return;
				if(evt.which >= 48 && evt.which <= 57){
					barcode += (evt.which - 48).toString();
				}else if(evt.which >= 65 && evt.which <= 90){
					barcode += String.fromCharCode(evt.which);
				}else if(evt.which == 13){
					evt.preventDefault();
					if(evt.target.localName != "input"){
						var modals = $(".modal.fade.in"),
							length = modals.length;
						$.each(modals, function(n, modal){
							if($(modal).attr("data-layer") == length){
								var submit = $("[type='submit']:not([disabled])", modal),
									action = $("[role='action']:not([disabled])", modal);
								if(submit.length > 0)
									submit.trigger("click");
								else if(action.length > 0)
									action.trigger("click");
								return false;
							}
						});
					}
				}
				if(barcode.length >= 10){
					barcode = barcode.substr(-10);
					var date = barcode.substr(0, 6),
						type = barcode.substr(6, 1),
						id = parseInt(barcode.substr(7, 3)),
						length = $(".modal.fade.in").length;
					if(length > 0){
						if(type){
							var focus = $("input:focus");
							if(focus.length > 0){
								focus.val(focus.val().replace(barcode, ''));
							}
							$.each(Model.mark, function(index, marks){
								doScanMark(1, marks, Model.modal[index]);
							});
							$.each(Model.select, function(index, select){
								doScanSelect(select, Model.modal[index]);
							});
							function doScanMark(layer, marks, modals){
								if(marks && modals){
									var modal = false;
									$.each(modals, function(a, m){
										if(m.hasClass("modal fade in")){
											modal = m;
											return false;
										}
									});
									$.each(marks, function(key, mark){
										doScanSelect(mark.scan, mark.modal);
										doScanSelect(mark.select, mark.modal);
										if(!modal) return false;
										if(mark.scan && length == layer){
											$.each(mark.scan, function(key, scan){
												if(scan.type == type){
													Scan.able = false;
													Scan.field = scan.field ? scan.field : false;
													Scan.value = id;
													Scan.focus = scan.focus ? scan.focus : false;
													Scan.target = scan.target ? scan.target : false;
													$("[id='btn-add-" + mark.name + "']:not([disabled])", modal).trigger('click');
													return true;
												}
											});
										}else if(mark.mark){
											layer++;
											doScanMark(layer, mark.mark, mark.modal);
										}
									});
								}
							}
							function doScanSelect(selects, modals){
								if(selects && modals){
									var modal = false;
									$.each(modals, function(a, m){
										if(m.hasClass("modal fade in")){
											modal = m;
											return false;
										}
									});
									if(!modal) return false;
									$.each(selects, function(key, select){
										if(select && select.type == type){
											Scan.field = select.field ? select.field : false;
											Scan.value = id;
											Scan.focus = select.focus ? select.focus : false;
											Scan.target = select.target ? select.target : false;
											handleScanData(modal);
											return true;
										}
									});							
								}
							}
						}
					}else{
						$.each(Model.scan, function(index, scan){
							if(scan){
								var panel = "#panel-" + Model.name[index] + "-edit",
									btn = $("[href='" + panel + "']");
								if(scan.type == type){
									if(date.substr(0, 5) != '00000' && id != 0){
										var field = Upper(Model.name[index]) + 'ID';
										$.each(Model.data[index], function(key, value){
											if(value[field] == id){
												Scan.point = '20' + date;
												btn.trigger('click');
												$("[data-key='" + key + "']", panel).trigger('tap').trigger('doubletap');
												barcode = '';
												return true;
											}
										});
									}else if(date == '000000' && id == 0){
										btn.trigger('click');
										$("[data-target='#" + Model.modal[index].add.attr('ID') + "']").trigger('click');;
									}
								}else if(scan.mark){
									$.each(scan.mark, function(n, s){
										if(s.type  == type){
											if(date == s.cond && id != 0){
												btn.trigger('click');
												Scan.layer = s.layer;
												Scan.index = 0;
												Scan.mark = [];
												var marks = Model.mark[index],
													mark = marks && s.index[0] ? marks[s.index[0]] : false,
													m = mark.mark && s.index[1] ? mark.mark[s.index[1]] : false;
												if(mark) Scan.mark.push(mark);
												if(m) Scan.mark.push(m);
												Scan.able = false;
												Scan.field = s.field ? s.field : false;
												Scan.value = id;
												Scan.focus = s.focus ? s.focus : false;
												Scan.target = s.target ? s.target : false;
												$("[data-target='#" + Model.modal[index].add.attr('ID') + "']").trigger('tap').trigger('click');
												return true;
											}
										}
									});
								}
							}
						});
					}
				}
				if(timer) clearTimeout(timer);
				timer = setTimeout(function(){
					barcode = '';
					Scan.able = true;
				}, 100);
			});
			Body.on('shown.bs.modal', ".modal", function(){
				if(Scan.layer){
					Scan.layer--;
					var mark = Scan.mark[Scan.index],
						btn = $("[id='btn-add-" + mark.name + "']:not([disabled])", this);
					Scan.index++;
					btn.trigger('click');
					if(Scan.layer <= 0){
						delete Scan.layer;
						delete Scan.index;
						delete Scan.mark;
					}
				}else if(Scan.field && Scan.value){
					handleScanData(this);
				}else if(Scan.point){
					$("[role='point']", this).html(Scan.point).show();
					delete Scan.point;
				}
			}).data('scan', true);
			function handleScanData(modal){
				barcode = '';
				Scan.able = true;
				if(Scan.target){
					var index = $.inArray(Scan.target.model ,Model.name),
						data = Model.data[index];
					if(data){
						$.each(data, function(key, value){
							if(value[Scan.field] == Scan.value){
								Scan.field = Scan.target.field;
								Scan.value = value[Scan.target.field];
								delete Scan.target;
								return false;
							}
						});
					}
				}
				var obj = $("[name='" + Scan.field + "']:not([disabled])", modal).val(Scan.value).trigger("change");
				if(!obj.val()){
					obj.prop('selectedIndex', 0);
				}else{
					if(Scan.focus){
						$("[name='" + Scan.focus + "']:not([disabled])", modal).trigger('focus');
						delete Scan.focus;
					}
				}				
				delete Scan.field;
				delete Scan.value;
			}
		}
	}
	function defineResetScript(pages){
		$.each(pages, function(n, page){
			var model = page.attr('ID').split("-")[1],
				index = $.inArray(model, Model.name),
				marks = Model.mark[index],
				form = $("#form-" + model, page);
			form.on("click", "#btn-reset", function(evt){
				evt.preventDefault();
				form.trigger("reset");
				if(marks){
					$.each(marks, function(key, mark){
						$("#panel-" + mark.name, form).empty();
					});
				}
				var remove = $("#btn-remove", form);
				remove.html(remove.data("text"));
				form.each(function(){
					var parents = $(this).parentsUntil(".modal"),
						modal = $(parents[parents.length - 1]).parent();
					$("[role='point']", modal).html('').hide();
				});
				$("[name='State']", form).val(1);
				$("[data-preset]", form).each(function(){
					$(this).val($(this).attr("data-preset"));
				});
				$("[class*='has-error']", form).removeClass("has-error");
				$("#result", page).attr('class', "alert");
			});
		});
	}
	function defineRemoveScript(pages){
		$.each(pages, function(n, page){
			var model = page.attr('ID').split("-")[1],
				form = $("#form-" + model, page).on("click", "#btn-remove", function(evt){
				evt.preventDefault();
				var remove = $(this),
					parents = remove.parentsUntil("#form-" + model),
					form = $(parents[parents.length - 1]).parent(),
					state = $("[name='State']", form);
				if(state.val() == 0){
					form.trigger("submit");
				}else if(state.val() == 1){
					state.val(0);
					remove.html("確認" + remove.data("text"));
				}
			});
		});
	}
	function defineMarkScript(pages){
		$.each(pages, function(n, page){
			var model = page.attr('ID').split("-")[1],
				action = page.attr('ID').split("-")[2],
				index = $.inArray(model, Model.name);
			doDefine(Model.mark[index]);
			function doDefine(marks){
				if(marks){
					$.each(marks, function(key, mark){
						var add = "[id^='btn-add-" + mark.name + "']",
							slide = "[id^='btn-slide-" + mark.name + "']",
							item = "[id^='btn-mark-" + Frist(mark.name) + "']";
						page.on("click", add, function(evt){
							if(!mark.modal){
								mark.modal = mark.form(model);
								defineScratchScript(marks, mark);
							}
							var modal = mark.modal.add.modal('toggle'),
								reset = $("#btn-reset", modal).trigger('click'),
								form = $("#form-" + model, page),
								field = "[id$='" + Upper(model) + "ID']",
								id = $(field, form).val(),
								block = $(this).parent();
							if(id)
								$(field, modal).val(id);
							else
								$(field, modal).removeAttr('value');
							if(block.hasClass('has-error'))
								block.removeClass('has-error');
						}).on("click", slide, function(e){
							var obj = $(this),
								btn = obj.find('i'),
								panel = $("#panel-" + mark.name, page);
							if(btn.hasClass("fa-angle-up")){
								btn.removeClass("fa-angle-up").addClass("fa-angle-down");
								panel.stop().slideUp();
							}else{
								btn.removeClass("fa-angle-down").addClass("fa-angle-up");
								panel.stop().slideDown();
							}
						}).on("doubletap", item, function(evt){
							if($(this).find("ul." + Item).hasClass("click")){
								if(!mark.modal){
									mark.modal = mark.form(model);
									defineScratchScript(marks, mark);
								}
								var modal = mark.modal.edit,
									frame;
								$.each(Model.modal[index], function(k, m){
									if(m.hasClass("modal fade in")){
										frame = m;
										return false;
									}
								});
								if(mark.cond){
									$.each(mark.cond, function(i, cond){
										if($("[name='" + cond.field + "']" ,frame).val() == cond.value){
											modal = mark.modal[cond.form];
											return false;
										}
									});
								}
								if(modal) modal.modal('toggle'); else return;
								var form = $("#form-" + mark.name, modal),
									alert = $("#result", modal);
								$("#btn-reset", form).trigger("click");
								form.hide();
								alert.html($("<i></i>").addClass("fa fa-spinner fa-spin fa-3x")).show();
								var key = [$(this).attr('data-key'), $(this).attr('scratch-key')];
								if(key[0]) form.attr('data-key', key[0]); else form.removeAttr('data-key');
								if(key[1]) form.attr('scratch-key', key[1]); else form.removeAttr('scratch-key');
								setModalMarkField(modal, mark, key);
								alert.empty().hide();
								form.show();
							}
						}).on("tap", item, function(evt){
							$(".click", page).removeClass("click");
							$(this).find("ul." + Item).addClass("click");
						});
						defineOptionScript(page, mark.option);
						defineStoreScript(page, mark.store);
						doDefine(mark.mark);
					});
				}
			}
		});
	}
	function defineInputScript(form, formula){
	form.on('input', function(){
			if(formula.augend && formula.addend){
				/* doMath : + */
				var augend = parseFloat($("[name='" + formula.augend + "']" ,form).val()),
					addend = parseFloat($("[name='" + formula.addend + "']" ,form).val()),
					result = $("[name='" + formula.result + "']" ,form);
				result.val(parseFloat(augend + addend));
			}else if(formula.minuend && formula.subtrahend){
				/* doMath : - */
				var minuend = parseFloat($("[name='" + formula.minuend + "']" ,form).val()),
					subtrahend = parseFloat($("[name='" + formula.subtrahend + "']" ,form).val()),
					result = $("[name='" + formula.result + "']" ,form);
				result.val(parseFloat(minuend - subtrahend));
			}else if(formula.multiplicand && formula.multiplier){
				/* doMath : x */
				var multiplicand = parseFloat($("[name='" + formula.multiplicand + "']" ,form).val()),
					multiplier = parseFloat($("[name='" + formula.multiplier + "']" ,form).val()),
					result = $("[name='" + formula.result + "']" ,form);
				result.val(parseFloat(multiplicand * multiplier));
			}else if(formula.dividend && formula.divisor){
				/* doMath : / */
				var dividend = parseFloat($("[name='" + formula.dividend + "']" ,form).val()),
					divisor = parseFloat($("[name='" + formula.divisor + "']" ,form).val()),
					result = $("[name='" + formula.result + "']" ,form);
				if(dividend > 0){
					result.val(parseFloat(dividend / divisor)); 
				}else{
					result.val(result.attr("placeholder"));
				}
			}
		});
	}
	function defineOptionScript(parent, option){
		if(option){
			parent.on('change', "[name='" + option.trigger + "']", function(e){
				var $trigger = $(this),
					$select = $("select[name='" + option.select + "']", parent),
					index = $.inArray(option.model, Model.name),
					data = Model.data[index];
				if(data){
					$.each(data, function(key, value){
						if(value){
							$select.find("option").each(function(){
								if(!$trigger.val())
									return;
								if(value[option.trigger] == $trigger.val()){
									$(this).removeAttr('hidden');
								}else{
									$(this).attr('hidden', true);
								}
							});
						}
					});
				}
			});
		}
	}
	/*//////////////////////////////////////////////*/
	/*///////////// MODEL.INITIALIZE ///////////////*/
	/*//////////////////////////////////////////////*/
	function createModel(model, code, icon, text, param){
		Model.name.push(model);
		var index = $.inArray(model, Model.name);
		Model.code.word[index] = code;
		Model.code.add[index] = code + "a";
		Model.code.edit[index] = code + "e";
		Model.text[index] = text;
		
		var id = ["panel-" + model + "-add", "panel-" + model + "-edit"],
			add = $("<div></div>").attr("id", id[0]).appendTo(PagePanel),
			edit = $("<div></div>").attr("id", id[1]).addClass("tab-pane").appendTo(PagePanel);
		if(!param || !param.disabled){
			var item = $("<li></li>").attr("role", "presentation").appendTo(PageNavbar),
				link = $("<a></a>").attr("href", "#" + id[1]).attr('id', code).attr("aria-controls", "profile").attr("role", "tab").attr("data-toggle", "tab").appendTo(item),
				i = $("<i></i>").addClass("fa fa-" + icon).appendTo(link),
				p = $("<p></p>").html(text).appendTo(link);
			link.on('tap', function(evt){
				if(PageControl.hasClass('active'))
					PageControl.trigger('click');
				var page = $("[id='" + id[1] + "']");
				$("#search-text", page).val('');
				$("#search-text", page).trigger('focus');
				delete Model.search[index];
				getSearch(model);
			});
			if(param){
				if(param.ces) item.addClass(param.ces);
			}
			return link;
		}
	}
	function setModel(model, ele){
		var index = $.inArray(model, Model.name);
		if(index == -1){
			console.log(model + " isn't a defined model.");
			return false;
		}else{
			Model.able[index] = true;
			Model.api[index] = ele.api ? ele.api : false;
			Model.view[index] = ele.view ? ele.view : false;
			Model.mark[index] = ele.mark ? ele.mark : false;
			Model.cond[index] = ele.cond ? ele.cond : false;
			Model.capi[index] = ele.capi ? ele.capi : false;
			Model.scan[index] = ele.scan ? ele.scan : false;
			Model.select[index] = ele.select ? ele.select : false;
			Model.saver[index] = ele.saver ? ele.saver : false;
			Model.store[index] = ele.store ? ele.store : false;
			Model.finish[index] = ele.finish ? ele.finish : false;
			
			var ModelPage = ele.page(model);
			ele.init(model, ModelPage);
			return getSearch(model);
		}
	}
	function setModelForm(model, fcts, param){
		var index = $.inArray(model, Model.name),
			page = [$("#panel-" + model + "-add"),
					$("#panel-" + model + "-edit")],
			form = {},
			modal = {};
		param = param ? param : {};
		param.layer = 1;
		modal.add = createModal(Model.code.add[index], model, Model.text[index], "新增", param).appendTo(page[0]);
		modal.edit = createModal(Model.code.edit[index], model, Model.text[index], "編輯", param).appendTo(page[1]);
		form.add = modal.add.find("#form-" + model);
		form.edit = modal.edit.find("#form-" + model);
		Model.modal[index] = modal;
		
		createFormHidden(Upper(model) + 'ID').appendTo(form.edit);
		createFormHidden("State", 1).appendTo(form.edit);
		fcts.add(form.add, createFormButtonAdd);
		fcts.edit = fcts.edit ? fcts.edit : fcts.add;
		fcts.edit(form.edit, createFormButtonEdit);
		
		$.each(fcts, function(action, fct){
			if(action != 'add' && action != 'edit'){
				var code = Model.code.word[index] + Frist(action);
				modal[action] = createModal(code, model, Model.text[index], "編輯", param).appendTo(page[1]);
				form[action] = modal[action].find("#form-" + model);
				createFormHidden(Upper(model) + 'ID').appendTo(form[action]);
				createFormHidden("State", 1).appendTo(form[action]);
				fct(form[action], createFormButtonEdit);
			}
		});
		
		return page;
	}
	function setModelMarkForm(model, mark, fcts, param){
		var index = $.inArray(model, Model.name),
			code = [Model.code.add[index] + Frist(mark.name),
					Model.code.edit[index] + Frist(mark.name)],
			page = [$("#panel-" + model + "-add"),
					$("#panel-" + model + "-edit")],
			stack = 1040 + page[0].children().length,
			form = {},
			modal = {};
		param = param ? param : {};
		modal.add = createModal(code[0], mark.name, mark.text, "新增", param).css('z-index', stack).appendTo(page[0]);
		modal.edit = createModal(code[1], mark.name, mark.text, "編輯", param).css('z-index', stack).appendTo(page[1]);
		form.add = modal.add.find("#form-" + mark.name);
		form.edit = modal.edit.find("#form-" + mark.name);
		
		createFormHidden(mark.model + 'ID').appendTo(form.edit);
		createFormHidden("State", 1).appendTo(form.edit);
		fcts.add(form.add, createFormButtonAdd);
		fcts.edit = fcts.edit ? fcts.edit : fcts.add;
		fcts.edit(form.edit, createFormButtonEdit);

		$.each(fcts, function(action, fct){
			if(action != 'add' && action != 'edit'){
				var code = Model.code.word[index] + Frist(action) + Frist(mark.name);
				modal[action] = createModal(code, mark.name, mark.text, "編輯", param).css('z-index', stack).appendTo(page[1]);
				form[action] = modal[action].find("#form-" + mark.name);
				createFormHidden(mark.model + 'ID').appendTo(form[action]);
				createFormHidden("State", 1).appendTo(form[action]);
				fct(form[action], createFormButtonEdit);
			}
		});
		
		return modal;
	}
	/*//////////////////////////////////////////////*/
	/*/////////////// MODEL.SEARCH /////////////////*/
	/*//////////////////////////////////////////////*/
	function getSearch(){
		var args = arguments,
			model = args[0],
			index = $.inArray(model, Model.name),
			page = $("#panel-" + model + "-edit"),
			api = (Model.api[index] && Model.api[index].search) ? Model.api[index].search : ('php/api_' + Upper(model) + '_Search.php');
		if(index >= 0){
			if(args[1]){
				var data = getDataByJSON(args[1]);
				$.ajax({
					url: api,
					type: 'post',
					data: data,
					cache: false,
					contentType: false,
					processData: false,
					success: function(data){ handleSearchData(data, false); }
				});
			}else{
				$.post(api, function(data){ handleSearchData(data, true); });
			}
			Model.able[index] = false;
			var status = $("<i></i>").attr('id', 'load').attr('class', 'fa fa-spinner fa-spin fa-3x').appendTo(page);
		}
		
		function handleSearchData(data, res){
			finishSearch(page);
			var json = JSON.parse(data);
			if(res) Model.data[index] = json;
			Model.able[index] = true;
			handleViewData(page, 'panel');
			if(res)	handleViewData(page, 'select');
			if(json.result != 'empty'){
				$.each(json, function(key, value){
					if(Model.store[index]){
						$.each(Model.store[index], function(n, item){
							if(item && !item.data){
								item.data = [];
								var model = item.based.replace(/ID/g, ''),
									index = $.inArray(Lower(model), Model.name);
								if(Model.data[index]){
									$.each(Model.data[index], function(key, json){
										item.data[json[item.based]] = json[item.field];
									});
								}
							}
						});
					}
				});	
				return true;
			}else{
				return false;
			}
		}
	}
	function getSearchMark(model, mark, json, fct){
		var data = new FormData(),
			field = Upper(model) + 'ID';
		if(json && json[field])
			data.append(field, json[field]);
		$.ajax({
			url: mark.api.search,
			type: 'post',
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data){
				var json = JSON.parse(data);
				if(data && data.result != 'empty'){
					mark.data = json;
					if(fct)	fct();
					return true;
				}else{
					return false;
				}
			}
		});
	}
	function getOptionText(data){
		var str,
			comment = '';
		$.each(data, function(field, value){
			if(!str){
				if(field.match(/Name/g)){
					str = '[' + value + ']';
				}
			}	
			if(!field.match(/ID/g)){
				comment+= ',' + value;
			}
		});
		return str;
	}
	function handleViewData(page, view, param){
		var model = page.attr('ID').split("-")[1],
			index = $.inArray(model, Model.name),
			data = Model.data[index],
			php = 'php/api_' + Upper(model) + '_Search.php';
		if(view == "panel"){
			Model.count[index] = 0;
			$("#panel-" + model, page).each(function(){
				$(this).remove();
			});
			var panel = createPanel(model).appendTo(page);
			if(data && data.result != 'empty'){
				$.each(data, function(key, value){
					if(param){
						if(param.cond && param.cond.value){
							if(value[param.cond.field] && value[param.cond.field] != param.cond.value) return;
						}
						if(param.like && param.like.keyword){
							if(value[param.like.field] && !value[param.like.field].match(param.like.keyword)) return;
						}
						if(param.date && param.date.after.value){
							if(value[param.date.after.field] != "0000-00-00" && value[param.date.after.field] < param.date.after.value) return;
						}
						if(param.date && param.date.before.value){
							if(value[param.date.before.field] != "0000-00-00"){
								if(value[param.date.before.field] > param.date.before.value) return;
							}else if(value[param.date.after.field] != "0000-00-00"){
								if(value[param.date.after.field] > param.date.before.value) return;
							}
						}
					}
					var group = createPanelGroup(model, key).appendTo(panel),
						sub = group.find("ul." + Item);
					Model.view[index](sub, value);
					sub.find("[data-alarm]").each(function(){
						handleViewAlarm($(this), $(this).data("alarm"), sub);
					});
					Model.count[index]++;
				});
				page.trigger("sort");
			}
			if(Model.count[index] > 0){
				$("[count-required='true']", page).removeAttr("disabled").prop("disabled", false);
			}else{
				$("[count-required='true']", page).attr("disabled", true).prop("disabled", true);
			}
		}else if(view == "select"){
			var select = $(".select-" + model).each(function(){
				$(this).find('option').each(function(){
					if($(this).val() > 0)
						$(this).remove();
				});
			});
			if(data && data.result != 'empty'){
				$.each(select, function(i, obj){
					$.each(data, function(key, value){
						var id = value[Upper(model) + 'ID'];
						$("<option></option>").html(getOptionText(value)).val(id).appendTo(obj);
					});
				});
				handleSaverData();
			}
		}
		function handleSaverData(){
			if(Model.saver[index]){
				$.each(Model.saver[index], function(key, saver){
					var data = new FormData();
					data.append(saver.load.field, saver.load.value);
					$.ajax({
						url: php,
						type: 'post',
						data: data,
						cache: false,
						contentType: false,
						processData: false,
						success: function(data){
							var json = JSON.parse(data);
							if(json.result != 'empty'){
								Model.saver[index][key].data = json;
								$.each(select, function(i, obj){
									if(JSON.stringify(saver.load) == JSON.stringify(select.data("load")))
										$.each(json, function(n, value){
											var id = value[Upper(model) + 'ID'];
											$("<option></option>").html(getOptionText(value)).val(id).appendTo(obj);
										});
								});
							}
						}
					});
					
				});
			}
		}
	}
	function handleViewAlarm(ref, alarm, obj){
		var index = $.inArray(alarm.model, Model.name),
			data = Model.data[index],
			field = "Number",
			obj = obj ? obj : ref,
			res,
			value = data[ref.prev().prev().val() - 1],
			num = parseInt(value[field]),
			val = ref.val();
		if(val < num && num != 0) {
			obj.css({"background-color": value.Color});
			res = true;
		}
		if(!res)
			obj.removeAttr("style");
	}
	function finishSearch(page){
		var status = $("#load", page).remove();
	}
	/*//////////////////////////////////////////////*/
	/*////////////// ACTION.GET/POST ///////////////*/
	/*//////////////////////////////////////////////*/
	function getAction(api, data, fct){
		$.ajax({
			url: api,
			type: 'get',
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data){
				if(fct)
					fct(data);
			}
		});
	}
	function getActionWithWindow(api, param){
		if(param){
			var punc = "?";
			if(param.form){
				$.each(getFormID(param.form[0]), function(field, value){
					api+= punc + field + "=" + value;
					punc = "&";
				});
			}
			if(param.json){
				$.each(param.json, function(field, value){
					if(value && field != 'result'){
						api+= punc + field + "=" + value;
						punc = "&";
					}
				});
			}
			if(param.search){
				$.each(param.search, function(type, search){
					if(search){
						$.each(search, function(field, value){
							if(value){
								var str = Upper(type) + Upper(field);
								if(typeof(value) == 'object'){
									$.each(value, function(f, v){
										if(v){
											api+= punc + str + Upper(f) + "=" + v;
											punc = "&";
										}
									});
								}else{
									api+= punc + str + "=" + value;
									punc = "&";
								}
							}
						});
					}
				});
			}
		}
		window.open(api, "_blank", "top=0, left=0, width=" + screen.width + ", height=" + screen.height);
	}
	function postAction(api, data, fct){
		$.ajax({
			url: api,
			type: 'post',
			data: data,
			cache: false,
			contentType: false,
			processData: false,
			success: function(data){
				if(fct)
					fct(data);
			}
		});
	}
	function postActionMark(page, json){
		if(page){
			var model = page.attr('ID').split("-")[1],
				index = $.inArray(model, Model.name);
			doAction(Model.mark[index], json);
			function doAction(marks, json, parent){
				if(marks){
					$.each(marks, function(n, mark){
						if(parent){
							model = parent.mark.model;
							mark.scratch = parent.mark.child[n][parent.key];
						}
						if(mark.scratch){
							$.each(mark.scratch ,function(key, scratch){
								if(scratch){
									var data = new FormData(),
										api = 'add';
									$.each(json, function(field, value){
										if(field != 'result')
											scratch[field] = value;
									});
									$.each(scratch ,function(field, value){
										if(field == mark.model + 'ID' && value > 0) api = 'edit';
										data.append(field, value);
									});
									postAction(mark.api[api], data, function(data){
										var json = JSON.parse(data);
										if(mark.mark){
											doAction(mark.mark, json, [mark, key]);
										}
									});
									delete scratch;
								}
							});
						}
					});
				}
			}
		}
	}
	function postActionVerify(api, form){
		var model = form.attr('ID').split("-")[1],
			index = $.inArray(model, Model.name),
			btn = $("#btn-verify", form).prop('disabled', true),
			marks = Model.mark[index],
			state = $("[name='VerifyState']" ,form).val(1),
			data = new FormData(form[0]);
		if(User && UserField) data.append(UserField.ID, User[UserField.ID]);
		$.each(marks, function(n, mark){
			if(mark.scratch)
				data.append(mark.model, JSON.stringify(mark.scratch));
		});
		postAction(api, data, function(data){
			btn.prop('disabled', false);
			var json = JSON.parse(data),
				parents = form.parentsUntil(".modal"),
				modal = $(parents[parents.length - 1]).parent();
			if(json.result == 'successful'){
				finishAction(modal, json, true);
				getSearch(model);
			}
		});
	}
	function checkActionRequired(form, fct){
		var alert = form.parent().find("#result");
		alert.attr('class', "alert").empty().hide();
		$('input[required]', form).each(function(){
			if($(this).val() == ''){
				alert.addClass('alert-danger').html("所有必要欄位必須確實填寫！").show();
				setTimeout(function(){
					alert.attr('class', "alert").empty().hide();
				}, 1500);
				return true;
			}
		});
		if(fct){
			if(fct()){
				alert.addClass('alert-danger').html("所有必要欄位必須確實填寫！").show();
				setTimeout(function(){
					alert.attr('class', "alert").empty().hide();
				}, 1500);
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	function finishAction(modal, json, reset){
		var index = modal.attr("data-index"),
			input = $("input[data-fct]:checked", modal);
		if(reset){
			modal.modal('toggle');
			$("#btn-reset", modal).trigger("click");
		}else{
			var alert = $("#result", modal).addClass('alert-success').html("資料成功送出！").show();
			setTimeout(function(){
				alert.attr('class', "alert").empty().hide();
			}, 1500);
		}
		if(input.length > 0){
			$.each(input, function(i, obj){
				$(obj).data("fct")(json);
			});
		}
		if(Model.finish[index]){
			Model.finish[index]();
		}
	}
	/*//////////////////////////////////////////////*/
	/*///////////////// SET.MODAL //////////////////*/
	/*//////////////////////////////////////////////*/
	function setModalField(modal, json){
		var index = modal.attr("data-index"),
			model = Model.name[index],
			form = $("#form-" + model, modal),
			marks = Model.mark[index];
		$.each(json, function(field, value){
			var obj = $("[name='" + field + "']", form);
			obj.val(getModalFieldValue(field, value)).trigger('change');
		});
		form.trigger('input');
		if(marks){
			$.each(marks, function(key, mark){
				delete mark.scratch;
				getSearchMark(model, mark, json, function(){
					var name = Upper(model) + 'ID',
						panel = $("#panel-" + mark.name, modal);
					$.each(mark.data, function(key, value){
						if(value[name] == json[name]){
							var group = createFormMarkGroup(mark, ['data', key]).appendTo(panel),
								item = group.find("ul." + Item);
							if(mark.view)
								mark.view(item, value);
						}
					});
					if(mark.limit)
						setModalMarkLimit(marks, modal);
				});
			});
		}
	}
	function setModalMarkField(modal, mark, key){
		var json = key[1] ? mark.scratch[key[1]] : mark.data[key[0]],
			form = $("#form-" + mark.name, modal),
			marks = mark.mark;
		$.each(json, function(field, value){
			var obj = $("[name='" + field + "']", form);
			obj.val(getModalFieldValue(field, value)).trigger('change');
		});
		if(marks){
			var model = mark.model;
			$.each(marks, function(k, m){
				delete m.scratch;
				getSearchMark(model, m, json, function(){
					var name = Upper(model) + 'ID',
						id = m.model + 'ID',
						panel = $("#panel-" + m.name, modal);
					if(m.data.result != 'empty'){
						$.each(m.data, function(key, value){
							if(value[name] == json[name]){
								var group = createFormMarkGroup(m, ['data', key], value[id]).appendTo(panel),
									item = group.find("ul." + Item);
								if(m.view)
									m.view(item, value);
							}
						});
						if(m.limit)
							setModalMarkLimit(marks, modal);
					}
					if(mark.child && mark.child[k][key[1]]){
						m.scratch = mark.child[k][key[1]];
						$.each(m.scratch, function(key, value){
							if(value){
								var item;
								if(value[id])
									item = $("[id-key='" + value[id] + "']" ,panel).attr('scratch-key', key).find("ul." + Item).empty();
								else
									item = createFormMarkGroup(m, ['scratch', key]).appendTo(panel).find("ul." + Item);
								if(m.view)
									m.view(item, value);
								if(value.State == '0')
									item.remove();
							}
						});
						if(m.limit)
							setModalMarkLimit(marks, modal);
					}
				});
			});
		}
	}
	function getModalFieldValue(field, value){
		if(!value || value == "0000-00-00"){
			return '';
		}else{
			return value;
		}
	}
	/*/////////////// LIMIT.ONE.MARK ///////////////*/
	function setModalMarkLimit(marks, modal){
		var limit = false,
			picks = [];
		$.each(marks, function(key, mark){
			if(mark.limit){
				var panel = $("#panel-" + mark.name, modal),
					group = $(".group-" + mark.name, panel),
					btn = panel.siblings('.mark-title').find('#btn-' + mark.name).removeAttr('disabled').removeClass('disabled');;
				if(group.length > 0){
					limit = true;
				}else{
					picks.push(mark);
				}
			}
		});
		if(limit){
			$.each(picks, function(key, mark){
				panel = $("#panel-" + mark.name, modal);
				btn = panel.siblings('.mark-title').find('#btn-' + mark.name).prop('disabled', true).addClass('disabled');
			});
		}
	}
	/*//////////////////////////////////////////////*/
	/*////////////// HANDLE.SOMETHING //////////////*/
	/*//////////////////////////////////////////////*/
	function getFormID(form){
		var json = {},
			obj = $($("[name$='ID']", form)[0]),
			field = obj.attr('ID').split("-")[1],
			value = obj.val();
			json[field] = value;
		return json;
	}
	function getDataByJSON(json){
		if(json){
			var data = new FormData();
			$.each(json ,function(field, value){
				data.append(field, value);
			});
			return data;
		}
	}
	function getJSONByForm(form){
		var json = {};
		$("[id^='section']", form).each(function(){
			var field = $(this).attr('ID').split("-")[1],
				value = $(this).val();
			json[field] = value;
		});
		$.each(json, function(field, value){
			if(field.match(/ID/g)){
				var select = $("select[name='" + field + "']", form);
				if(select.length > 0){
					var field = field.replace(/ID/g, '') + 'Name',
						option = $("option[value='" + value + "']", select).html(),
						value = option.replace(/\[[0-9]+\]/g, '');
					json[field] = value;
				}
			}
		});
		return json;
	}
	
	function handleFileImage(evt, obj, fct){
		var files = evt.target.files,
			file = files[0];
		if (files && file){
			var reader = new FileReader();
			reader.onload = function(readerEvt) {
				var binaryString = readerEvt.target.result,
					file = {},
					modal = $(obj).parentsUntil(".modal");
				file.name = file.name;
				file.data = btoa(binaryString);
				if(fct)
					fct(modal, file);
				$(obj).val('');
			};
			reader.readAsBinaryString(file);
		}
	}
	/*//////////////////////////////////////////////*/
	/*//////////////// HANDLE.WORD /////////////////*/
	/*//////////////////////////////////////////////*/
	function Float(str, pos){
		return $.isNumeric(str) ? parseFloat(str).toFixed(pos) : str;
	}
	function Frist(str){
		var strs = str.split("-"),
			res = '';
		$.each(strs, function(n, word){
			res+= word.substring(0, 1); 
		});
		return res;
	}
	function Upper(str){
		return str.substring(0, 1).toUpperCase() + str.substring(1);
	}
	function Lower(str){
		return str.toLowerCase();
	}