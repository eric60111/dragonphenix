
	/*//////////////////////////////////////////////*/
	/*// fortawesome.github.io/Font-Awesome/icons //*/
	/*//////////////////////////////////////////////*/
	checkUser({
		login: "php/api_User_Login.php",
		logout: "php/api_User_Logout.php"
	}, function(){
		createWebsite({title: "龍凰甲子焿", sub: "庫存管理系統"});
		createControl("control", ['bars', 'minus']);
		createSetting("setting", 'cog');
		createModel("ingredientPurchase", 'ip', 'file-powerpoint-o', '原料進貨單', {ces: "light"}).trigger("click");
		createModel("ingredientShipment", 'is', 'file-text-o', '原料出貨單', {ces: "light"});
		createModel("ingredientInventory", 'ii', 'file-archive-o', '原料盤點單', {ces: "light"});
		createModel("materialPurchase", 'mp', 'file-powerpoint-o', '配方料進貨單', {ces: "light"});
		createModel("materialShipment", 'ms', 'file-text-o', '配方料出貨單', {ces: "light"});
		createModel("materialInventory", 'mi', 'file-archive-o', '配方料盤點單', {ces: "light"});
		createModel("productPurchase", 'pp', 'file-powerpoint-o', '產品進貨單', {ces: "light"});
		createModel("productShipment", 'ps', 'file-text-o', '產品出貨單', {ces: "light"});
		createModel("productInventory", 'pi', 'file-archive-o', '產品盤點單', {ces: "light"});
		createModel("ingredient", 'i', 'cube', '原料');
		createModel("material", 'm', 'cubes', '配方料');
		createModel("product", 'p', 'gift', '產品');
		createModel("company", 'c', 'flag', '廠商');
		/*//////////////////////////////////////////////*/
		/*///////////////// ADMIN-PAGE /////////////////*/
		/*//////////////////////////////////////////////*/
		UserField = {ID: "EmployeeID", Name: "EmployeeName"};
		UserDisabled = User.Permission < 2 ? false : true;
		createModel("employee", 'e', 'briefcase', '使用者', {ces: "dark", disabled: UserDisabled});
	});
	/*//////////////////////////////////////////////*/
	var now = new Date(),
		year = now.getFullYear(),
		month = ("0" + (now.getMonth() + 1)).slice(-2),
		day = ("0" + (now.getDate())).slice(-2);
		date = year + '-' + month + '-' + day;
	/*//////////////////////////////////////////////*/
	/*//////////////////////////////////////////////*/
	/*//////////////////////////////////////////////*/
	

	/*//////////////////////////////////////////////*/
	setModel("ingredientShipment", {
		view: function(s, r){
			createPanelItem(r.Barcode).appendTo(s);
			createPanelItem(r.CompanyName).appendTo(s);
			createPanelItem(r.SetupEmployee).appendTo(s);
			createPanelItem(r.SetupDate).appendTo(s);
			createPanelItem(r.VerifyEmployee, {replace: [{cond: "", text: "未核銷"}]}).appendTo(s);
			createPanelItem(r.VerifyDate, {replace: [{cond: "0000-00-00", text: "未核銷"}]}).appendTo(s);
		},
		mark: [{
			model: "IngredientShipmentDetail",
			name: "ingredient-shipment-detail", 
			text: "出貨單明細",
			required: true,
			unique: ["IngredientID"],
			api: {
				search: "php/api_IngredientShipmentDetail_Search.php",
				add: "php/api_IngredientShipmentDetail_Add.php",
				edit: "php/api_IngredientShipmentDetail_Edit.php"
			},
			view: function(s, r){
				createPanelItem(r.IngredientName).appendTo(s);
				createPanelItem(Float(r.SoldPrice)).appendTo(s);
				createPanelItem(Float(r.Quantity, 1)).appendTo(s);
				createPanelItem(r.Unit).appendTo(s);
			},
			form: function(m){
				var model = "ingredient",
					modelID = "IngredientID",
					modelText = "選擇原料";
				return setModelMarkForm(m, this, {
					add: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "required"})).appendTo(f);
						createFormAddon("text", "SoldPrice", "售價*", {placeholder: "0", attribute: "required"}).appendTo(f);
						createFormAddon("text", "Quantity", "數量*", {placeholder: "0", attribute: "required"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b().appendTo(f);
					},
					edit: false,
					verify: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "disabled"})).appendTo(f);
						createFormAddon("text", "SoldPrice", "售價", {placeholder: "0", attribute: (UserDisabled ? "disabled" : "required")}).appendTo(f);
						createFormAddon("text", "Quantity", "數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "ActualQuantity", "核實數量", {placeholder: "0"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: UserDisabled}}).appendTo(f);
					},
					disabled: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "disabled"})).appendTo(f);
						createFormAddon("text", "SoldPrice", "售價", {placeholder: "0", attribute: (UserDisabled ? "disabled" : "required")}).appendTo(f);
						createFormAddon("text", "Quantity", "數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "ActualQuantity", "核實數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: true, edit: UserDisabled}}).appendTo(f);
					}
				}, {layer: 2});
			},
			cond: [{form: "verify", field: "VerifyState", value: "0"}, {form: "disabled", field: "VerifyState", value: "1"}],
			scan: [{type: "1", field: "IngredientID", focus: "Quantity"}],
			store: [{
				based: "IngredientID", field: "Unit", data: false},{
				based: "IngredientID", field: "SoldPrice", data: false
			}]
		}],
		page: function(m){
			var api = {
				print: "php/FormGrenrator/api_IngredientShipment_pdf.php",
				verify: "php/api_IngredientShipment_Edit.php"
			};
			return setModelForm(m, {
				add: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: "required", preset: 1})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled", preset: User[UserField.ID]})).appendTo(f);
					createFormAddon("date", "SetupDate", "建立日期", {placeholder: date, attribute: "required", preset: date}).appendTo(f);
					createFormMark(f, "ingredient-shipment-detail", "出貨單明細", ["原料", "售價", "數量", "單位"]).appendTo(f);
					createFormCheckbox("IsPrintPdf", "列印原料出貨單", {attribute: "checked", fct: function(j){getActionWithWindow(api.print, {json: j})}}).appendTo(f);
					b().appendTo(f);
				},
				edit: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: (UserDisabled ? "disabled" : "required")})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled"})).appendTo(f);
					createFormAddon("text", "SetupDate", "建立日期", {placeholder: date, attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyEmployee", "核銷人員", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyDate", "核銷日期", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormHidden("VerifyState").appendTo(f);
					createFormHidden("VerifyAction", 1).appendTo(f);
					createFormMark(f, "ingredient-shipment-detail", "出貨單明細", ["原料", "售價", "數量", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印原料出貨單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					b({
						append: [createFormActionButton("verify", "確定核銷", {icon: "clipboard"}, function(evt){postActionVerify(api.verify ,f)})],
						disabled: {edit: true}
					}).appendTo(f);
				},
				disabled: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: (UserDisabled ? "disabled" : "required")})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled"})).appendTo(f);
					createFormAddon("text", "SetupDate", "建立日期", {placeholder: date, attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyEmployee", "核銷人員", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyDate", "核銷日期", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormHidden("VerifyState").appendTo(f);
					createFormMark(f, "ingredient-shipment-detail", "出貨單明細", ["原料", "售價", "數量", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印原料出貨單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					b({disabled: {remove: UserDisabled, edit: UserDisabled}}).appendTo(f);
				}
			});
		},
		init: function(m, p){
			createPanelSearch(m, {
				action: {name: "print", required: true, icon: "print", api: "php/FormGrenrator/api_IngredientShipmentList_pdf.php", method: "window", json: {EmployeeName: User.Name}},
				search: [{
					text: "依條碼號碼", field: "Barcode"},{
					text: "依廠商名稱", field: "CompanyName"},{
					text: "依人員名稱", field: "SetupEmployee"
				}],
				filter: [{
					text: "顯示全部"},{
					text: "未核銷", field: "VerifyState", value: "0"},{
					text: "已核銷", field: "VerifyState", value: "1"
				}],
				date: {after: "SetupDate", before: "SetupDate"}
			}).appendTo(p[1]);
			createPanelTitle(p[1], ["條碼號碼", "廠商", "建單人員", "建立日期", "核銷人員", "核銷日期"]).appendTo(p[1]);
			defineSearchScript(p[1]);
			defineActionScript(p);
			defineRemoveScript(p);
			defineResetScript(p);
			defineModalScript(p);
			defineMarkScript(p);
		},
		cond: [{form: "disabled", field: "VerifyState", value: "1"}],
		scan: {type: "7", mark: [{cond: "000002", index: ["0"], type: "1", layer: 1, field: "IngredientID", focus: "Quantity"}]},
		select: [{type: "C", field: "CompanyID"}]
	});
	
	/*//////////////////////////////////////////////*/
	setModel("materialShipment", {
		view: function(s, r){
			createPanelItem(r.Barcode).appendTo(s);
			createPanelItem(r.CompanyName).appendTo(s);
			createPanelItem(r.SetupEmployee).appendTo(s);
			createPanelItem(r.SetupDate).appendTo(s);
			createPanelItem(r.VerifyEmployee, {replace: [{cond: "", text: "未核銷"}]}).appendTo(s);
			createPanelItem(r.VerifyDate, {replace: [{cond: "0000-00-00", text: "未核銷"}]}).appendTo(s);
		},
		mark: [{
			model: "MaterialShipmentDetail",
			name: "material-shipment-detail", 
			text: "出貨單明細",
			required: true,
			unique: ["MaterialID"],
			api: {
				search: "php/api_MaterialShipmentDetail_Search.php",
				add: "php/api_MaterialShipmentDetail_Add.php",
				edit: "php/api_MaterialShipmentDetail_Edit.php"
			},
			view: function(s, r){
				createPanelItem(r.MaterialName).appendTo(s);
				createPanelItem(Float(r.SoldPrice)).appendTo(s);
				createPanelItem(Float(r.Quantity, 1)).appendTo(s);
				createPanelItem(r.Unit).appendTo(s);
			},
			form: function(m){
				var model = "material",
					modelID = "MaterialID",
					modelText = "選擇配方料";
				return setModelMarkForm(m, this, {
					add: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "required"})).appendTo(f);
						createFormAddon("text", "SoldPrice", "售價*", {placeholder: "0", attribute: "required"}).appendTo(f);
						createFormAddon("text", "Quantity", "數量*", {placeholder: "0", attribute: "required"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b().appendTo(f);
					},
					edit: false,
					verify: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "disabled"})).appendTo(f);
						createFormAddon("text", "SoldPrice", "售價", {placeholder: "0", attribute: (UserDisabled ? "disabled" : "required")}).appendTo(f);
						createFormAddon("text", "Quantity", "數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "ActualQuantity", "核實數量", {placeholder: "0"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: UserDisabled}}).appendTo(f);
					},
					disabled: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "disabled"})).appendTo(f);
						createFormAddon("text", "SoldPrice", "售價", {placeholder: "0", attribute: (UserDisabled ? "disabled" : "required")}).appendTo(f);
						createFormAddon("text", "Quantity", "數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "ActualQuantity", "核實數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: true, edit: UserDisabled}}).appendTo(f);
					}
				}, {layer: 2});
			},
			cond: [{form: "verify", field: "VerifyState", value: "0"}, {form: "disabled", field: "VerifyState", value: "1"}],
			scan: [{type: "2", field: "MaterialID", focus: "Quantity"}],
			store: [{
				based: "MaterialID", field: "Unit", data: false},{
				based: "MaterialID", field: "SoldPrice", data: false
			}]
		}],
		page: function(m){
			var api = {
				print: "php/FormGrenrator/api_MaterialShipment_pdf.php",
				verify: "php/api_MaterialShipment_Edit.php"
			};
			return setModelForm(m, {
				add: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: "required"})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled", preset: User[UserField.ID]})).appendTo(f);
					createFormAddon("date", "SetupDate", "建立日期", {placeholder: date, attribute: "required", preset: date}).appendTo(f);
					createFormMark(f, "material-shipment-detail", "出貨單明細", ["配方料", "售價", "數量", "單位"]).appendTo(f);
					createFormCheckbox("IsPrintPdf", "列印配方料出貨單", {attribute: "checked", fct: function(j){getActionWithWindow(api.print, {json: j})}}).appendTo(f);
					b().appendTo(f);
				},
				edit: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: (UserDisabled ? "disabled" : "required")})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled"})).appendTo(f);
					createFormAddon("text", "SetupDate", "建立日期", {placeholder: date, attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyEmployee", "核銷人員", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyDate", "核銷日期", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormHidden("VerifyState").appendTo(f);
					createFormHidden("VerifyAction", 1).appendTo(f);
					createFormMark(f, "material-shipment-detail", "出貨單明細", ["配方料", "售價", "數量", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印配方料出貨單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					b({
						append: [createFormActionButton("verify", "確定核銷", {icon: "clipboard"}, function(evt){postActionVerify(api.verify ,f)})],
						disabled: {edit: true}
					}).appendTo(f);
				},
				disabled: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: (UserDisabled ? "disabled" : "required")})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled"})).appendTo(f);
					createFormAddon("text", "SetupDate", "建立日期", {placeholder: date, attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyEmployee", "核銷人員", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyDate", "核銷日期", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormHidden("VerifyState").appendTo(f);
					createFormMark(f, "material-shipment-detail", "出貨單明細", ["配方料", "售價", "數量", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印配方料出貨單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					b({disabled: {remove: UserDisabled, edit: UserDisabled}}).appendTo(f);
				}
			});
		},
		init: function(m, p){
			createPanelSearch(m, {
				action: {name: "print", required: true, icon: "print", api: "php/FormGrenrator/api_MaterialShipmentList_pdf.php", method: "window", json: {EmployeeName: User.Name}},
				search: [{
					text: "依條碼號碼", field: "Barcode"},{
					text: "依廠商名稱", field: "CompanyName"},{
					text: "依人員名稱", field: "SetupEmployee"
				}],
				filter: [{
					text: "顯示全部"},{
					text: "未核銷", field: "VerifyState", value: "0"},{
					text: "已核銷", field: "VerifyState", value: "1"
				}],
				date: {after: "SetupDate", before: "SetupDate"}
			}).appendTo(p[1]);
			createPanelTitle(p[1], ["條碼號碼", "廠商", "建單人員", "建立日期", "核銷人員", "核銷日期"]).appendTo(p[1]);
			defineSearchScript(p[1]);
			defineActionScript(p);
			defineRemoveScript(p);
			defineResetScript(p);
			defineModalScript(p);
			defineMarkScript(p);
		},
		cond: [{form: "disabled", field: "VerifyState", value: "1"}],
		scan: {type: "8", mark: [{cond: "000002", index: ["0"], type: "2", layer: 1, field: "MaterialID", focus: "Quantity"}]},
		select: [{type: "C", field: "CompanyID"}]
	});
	
	/*//////////////////////////////////////////////*/
	setModel("productShipment", {
		view: function(s, r){
			createPanelItem(r.Barcode).appendTo(s);
			createPanelItem(r.CompanyName).appendTo(s);
			createPanelItem(r.SetupEmployee).appendTo(s);
			createPanelItem(r.SetupDate).appendTo(s);
			createPanelItem(r.VerifyEmployee, {replace: [{cond: "", text: "未核銷"}]}).appendTo(s);
			createPanelItem(r.VerifyDate, {replace: [{cond: "0000-00-00", text: "未核銷"}]}).appendTo(s);
		},
		mark: [{
			model: "ProductShipmentDetail",
			name: "product-shipment-detail", 
			text: "出貨單明細",
			required: true,
			unique: ["ProductID"],
			api: {
				search: "php/api_ProductShipmentDetail_Search.php",
				add: "php/api_ProductShipmentDetail_Add.php",
				edit: "php/api_ProductShipmentDetail_Edit.php"
			},
			view: function(s, r){
				createPanelItem(r.ProductName).appendTo(s);
				createPanelItem(Float(r.SoldPrice)).appendTo(s);
				createPanelItem(Float(r.Quantity, 1)).appendTo(s);
				createPanelItem(r.Unit).appendTo(s);
			},
			form: function(m){
				var model = "product",
					modelID = "ProductID",
					modelText = "選擇產品";
				return setModelMarkForm(m, this, {
					add: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "required"})).appendTo(f);
						createFormAddon("text", "SoldPrice", "售價*", {placeholder: "0", attribute: "required"}).appendTo(f);
						createFormAddon("text", "Quantity", "數量*", {placeholder: "0", attribute: "required"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b().appendTo(f);
					},
					edit: false,
					verify: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "disabled"})).appendTo(f);
						createFormAddon("text", "SoldPrice", "售價", {placeholder: "0", attribute: (UserDisabled ? "disabled" : "required")}).appendTo(f);
						createFormAddon("text", "Quantity", "數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "ActualQuantity", "核實數量", {placeholder: "0"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: UserDisabled}}).appendTo(f);
					},
					disabled: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "disabled"})).appendTo(f);
						createFormAddon("text", "SoldPrice", "售價", {placeholder: "0", attribute: (UserDisabled ? "disabled" : "required")}).appendTo(f);
						createFormAddon("text", "Quantity", "數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "ActualQuantity", "核實數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: true, edit: UserDisabled}}).appendTo(f);
					}
				}, {layer: 2});
			},
			cond: [{form: "verify", field: "VerifyState", value: "0"}, {form: "disabled", field: "VerifyState", value: "1"}],
			scan: [{type: "3", field: "ProductID", focus: "Quantity"}],
			store: [{
				based: "ProductID", field: "Unit", data: false},{
				based: "ProductID", field: "SoldPrice", data: false
			}]
		}],
		page: function(m){
			var api = {
				print: "php/FormGrenrator/api_ProductShipment_pdf.php",
				verify: "php/api_ProductShipment_Edit.php"
			};
			return setModelForm(m, {
				add: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: "required"})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled", preset: User[UserField.ID]})).appendTo(f);
					createFormAddon("date", "SetupDate", "建立日期", {placeholder: date, attribute: "required", preset: date}).appendTo(f);
					createFormMark(f, "product-shipment-detail", "出貨單明細", ["產品", "售價", "數量", "單位"]).appendTo(f);
					createFormCheckbox("IsPrintPdf", "列印產品出貨單", {attribute: "checked", fct: function(j){getActionWithWindow(api.print, {json: j})}}).appendTo(f);
					b().appendTo(f);
				},
				edit: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: (UserDisabled ? "disabled" : "required")})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled"})).appendTo(f);
					createFormAddon("text", "SetupDate", "建立日期", {placeholder: date, attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyEmployee", "核銷人員", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyDate", "核銷日期", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormHidden("VerifyState").appendTo(f);
					createFormHidden("VerifyAction", 1).appendTo(f);
					createFormMark(f, "product-shipment-detail", "出貨單明細", ["產品", "售價", "數量", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印產品出貨單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					b({
						append: [createFormActionButton("verify", "確定核銷", {icon: "clipboard"}, function(evt){postActionVerify(api.verify ,f)})],
						disabled: {edit: true}
					}).appendTo(f);
				},
				disabled: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: (UserDisabled ? "disabled" : "required")})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled"})).appendTo(f);
					createFormAddon("text", "SetupDate", "建立日期", {placeholder: date, attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyEmployee", "核銷人員", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyDate", "核銷日期", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormHidden("VerifyState").appendTo(f);
					createFormMark(f, "product-shipment-detail", "出貨單明細", ["產品", "售價", "數量", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印產品出貨單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					b({disabled: {remove: UserDisabled, edit: UserDisabled}}).appendTo(f);
				}
			});
		},
		init: function(m, p){
			createPanelSearch(m, {
				action: {name: "print", required: true, icon: "print", api: "php/FormGrenrator/api_ProductShipmentList_pdf.php", method: "window", json: {EmployeeName: User.Name}},
				search: [{
					text: "依條碼號碼", field: "Barcode"},{
					text: "依廠商名稱", field: "CompanyName"},{
					text: "依人員名稱", field: "SetupEmployee"
				}],
				filter: [{
					text: "顯示全部"},{
					text: "未核銷", field: "VerifyState", value: "0"},{
					text: "已核銷", field: "VerifyState", value: "1"
				}],
				date: {after: "SetupDate", before: "SetupDate"}
			}).appendTo(p[1]);
			createPanelTitle(p[1], ["條碼號碼", "廠商", "建單人員", "建立日期", "核銷人員", "核銷日期"]).appendTo(p[1]);
			defineSearchScript(p[1]);
			defineActionScript(p);
			defineRemoveScript(p);
			defineResetScript(p);
			defineModalScript(p);
			defineMarkScript(p);
		},
		cond: [{form: "disabled", field: "VerifyState", value: "1"}],
		scan: {type: "9", mark: [{cond: "000002", index: ["0"], type: "3", layer: 1, field: "ProductID", focus: "Quantity"}]},
		select: [{type: "C", field: "CompanyID"}]
	});
	
	/*//////////////////////////////////////////////*/
	setModel("ingredientPurchase", {
		view: function(s, r){
			createPanelItem(r.Barcode).appendTo(s);
			createPanelItem(r.CompanyName).appendTo(s);
			createPanelItem(r.SetupEmployee).appendTo(s);
			createPanelItem(r.SetupDate).appendTo(s);
			createPanelItem(r.VerifyEmployee, {replace: [{cond: "", text: "未核銷"}]}).appendTo(s);
			createPanelItem(r.VerifyDate, {replace: [{cond: "0000-00-00", text: "未核銷"}]}).appendTo(s);
		},
		mark: [{
			model: "IngredientPurchaseDetail",
			name: "ingredient-purchase-detail", 
			text: "進貨明細",
			required: true,
			unique: ["IngredientID"],
			api: {
				search: "php/api_IngredientPurchaseDetail_Search.php",
				add: "php/api_IngredientPurchaseDetail_Add.php",
				edit: "php/api_IngredientPurchaseDetail_Edit.php"
			},
			view: function(s, r){
				createPanelItem(r.IngredientName).appendTo(s);
				createPanelItem(Float(r.UnitPrice)).appendTo(s);
				createPanelItem(Float(r.Quantity, 1)).appendTo(s);
				createPanelItem(r.Unit).appendTo(s);
			},
			form: function(m){
				var model = "ingredient",
					modelID = "IngredientID",
					modelText = "選擇原料";
				return setModelMarkForm(m, this, {
					add: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "required"})).appendTo(f);
						createFormAddon("text", "UnitPrice", "單位價格*", {placeholder: "0", attribute: "required"}).appendTo(f);
						createFormAddon("text", "Quantity", "數量*", {placeholder: "0", attribute: "required"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b().appendTo(f);
					},
					edit: false,
					verify: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "disabled"})).appendTo(f);
						createFormAddon("text", "UnitPrice", "單位價格", {placeholder: "0", attribute: (UserDisabled ? "disabled" : "required")}).appendTo(f);
						createFormAddon("text", "Quantity", "數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "ActualQuantity", "核實數量", {placeholder: "0"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: UserDisabled}}).appendTo(f);
					},
					disabled: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "disabled"})).appendTo(f);
						createFormAddon("text", "UnitPrice", "單位價格", {placeholder: "0", attribute: (UserDisabled ? "disabled" : "required")}).appendTo(f);
						createFormAddon("text", "Quantity", "數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "ActualQuantity", "核實數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: true, edit: UserDisabled}}).appendTo(f);
					}
				}, {layer: 2});
			},
			cond: [{form: "verify", field: "VerifyState", value: "0"}, {form: "disabled", field: "VerifyState", value: "1"}],
			scan: [{type: "1", field: "IngredientID", focus: "Quantity"}],
			store: [{
				based: "IngredientID", field: "Unit", data: false},{
				based: "IngredientID", field: "UnitPrice", data: false}]
		}],
		page: function(m){
			var api = {
				print: "php/FormGrenrator/api_IngredientPurchase_pdf.php",
				verify: "php/api_IngredientPurchase_Edit.php"
			};
			return setModelForm(m, {
				add: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: "required"})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled", preset: User[UserField.ID]})).appendTo(f);
					createFormAddon("date", "SetupDate", "建立日期", {placeholder: date, attribute: "required", preset: date}).appendTo(f);
					createFormMark(f, "ingredient-purchase-detail", "進貨明細", ["原料", "單位價格", "數量", "單位"]).appendTo(f);
					createFormCheckbox("IsPrintPdf", "列印原料進貨單", {attribute: "checked", fct: function(j){getActionWithWindow(api.print, {json: j})}}).appendTo(f);
					b().appendTo(f);
				},
				edit: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: (UserDisabled ? "disabled" : "required")})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled"})).appendTo(f);
					createFormAddon("text", "SetupDate", "建立日期", {placeholder: date, attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyEmployee", "核銷人員", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyDate", "核銷日期", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormHidden("VerifyState").appendTo(f);
					createFormHidden("VerifyAction", 1).appendTo(f);
					createFormMark(f, "ingredient-purchase-detail", "進貨明細", ["原料", "單位價格", "數量", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印原料進貨單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					createFormCheckbox("IsPrintBarcode", "列印條碼貼紙", {attribute: "checked", val: 1}).appendTo(f);
					b({
						append: [createFormActionButton("verify", "確定核銷", {icon: "clipboard"}, function(evt){postActionVerify(api.verify ,f)})],
						disabled: {edit: true}
					}).appendTo(f);
				},
				disabled: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: (UserDisabled ? "disabled" : "required")})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled"})).appendTo(f);
					createFormAddon("text", "SetupDate", "建立日期", {placeholder: date, attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyEmployee", "核銷人員", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyDate", "核銷日期", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormHidden("VerifyState").appendTo(f);
					createFormMark(f, "ingredient-purchase-detail", "進貨明細", ["原料", "單位價格", "數量", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印原料進貨單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					b({disabled: {remove: UserDisabled, edit: UserDisabled}}).appendTo(f);
				}
			});
		},
		init: function(m, p){
			createPanelSearch(m, {
				action: {name: "print", required: true, icon: "print", api: "php/FormGrenrator/api_IngredientPurchaseList_pdf.php", method: "window", json: {EmployeeName: User.Name}},
				search: [{
					text: "依條碼號碼", field: "Barcode"},{
					text: "依廠商名稱", field: "CompanyName"},{
					text: "依人員名稱", field: "SetupEmployee"
				}],
				filter: [{
					text: "顯示全部"},{
					text: "未核銷", field: "VerifyState", value: "0"},{
					text: "已核銷", field: "VerifyState", value: "1"
				}],
				date: {after: "SetupDate", before: "SetupDate"}
			}).appendTo(p[1]);
			createPanelTitle(p[1], ["條碼號碼", "廠商", "建單人員", "建立日期", "核銷人員", "核銷日期"]).appendTo(p[1]);
			defineSearchScript(p[1]);
			defineActionScript(p);
			defineRemoveScript(p);
			defineResetScript(p);
			defineModalScript(p);
			defineMarkScript(p);
		},
		cond: [{form: "disabled", field: "VerifyState", value: "1"}],
		scan: {type: "4", mark: [{cond: "000001", index: ["0"], type: "1", layer: 1, field: "IngredientID", focus: "Quantity"}]},
		select: [{type: "C", field: "CompanyID"}]
	});
		
	/*//////////////////////////////////////////////*/
	setModel("materialPurchase", {
		view: function(s, r){
			createPanelItem(r.Barcode).appendTo(s);
			createPanelItem(r.CompanyName).appendTo(s);
			createPanelItem(r.SetupEmployee).appendTo(s);
			createPanelItem(r.SetupDate).appendTo(s);
			createPanelItem(r.VerifyEmployee, {replace: [{cond: "", text: "未核銷"}]}).appendTo(s);
			createPanelItem(r.VerifyDate, {replace: [{cond: "0000-00-00", text: "未核銷"}]}).appendTo(s);
		},
		mark: [{
			model: "MaterialPurchaseDetail",
			name: "material-purchase-detail", 
			text: "進貨明細",
			required: true,
			unique: ["MaterialID"],
			api: {
				search: "php/api_MaterialPurchaseDetail_Search.php",
				add: "php/api_MaterialPurchaseDetail_Add.php",
				edit: "php/api_MaterialPurchaseDetail_Edit.php"
			},
			view: function(s, r){
				createPanelItem(r.MaterialName).appendTo(s);
				createPanelItem(Float(r.UnitPrice)).appendTo(s);
				createPanelItem(Float(r.Quantity, 1)).appendTo(s);
				createPanelItem(r.Unit).appendTo(s);
			},
			form: function(m){
				var model = "material",
					modelID = "MaterialID",
					modelText = "選擇配方料";
				return setModelMarkForm(m, this, {
					add: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "required"})).appendTo(f);
						createFormAddon("text", "UnitPrice", "單位價格*", {placeholder: "0", attribute: "required"}).appendTo(f);
						createFormAddon("text", "Quantity", "數量*", {placeholder: "0", attribute: "required"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b().appendTo(f);
					},
					edit: false,
					verify: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "disabled"})).appendTo(f);
						createFormAddon("text", "UnitPrice", "單位價格", {placeholder: "0", attribute: (UserDisabled ? "disabled" : "required")}).appendTo(f);
						createFormAddon("text", "Quantity", "數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "ActualQuantity", "核實數量", {placeholder: "0"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: UserDisabled}}).appendTo(f);
					},
					disabled: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "disabled"})).appendTo(f);
						createFormAddon("text", "UnitPrice", "單位價格", {placeholder: "0", attribute: (UserDisabled ? "disabled" : "required")}).appendTo(f);
						createFormAddon("text", "Quantity", "數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "ActualQuantity", "核實數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: true, edit: UserDisabled}}).appendTo(f);
					}
				}, {layer: 2});
			},
			cond: [{form: "verify", field: "VerifyState", value: "0"}, {form: "disabled", field: "VerifyState", value: "1"}],
			scan: [{type: "2", field: "MaterialID", focus: "Quantity"}],
			store: [{
				based: "MaterialID", field: "Unit", data: false},{
				based: "MaterialID", field: "UnitPrice", data: false}]
		}],
		page: function(m){
			var api = {
				print: "php/FormGrenrator/api_MaterialPurchase_pdf.php",
				verify: "php/api_MaterialPurchase_Edit.php"
			};
			return setModelForm(m, {
				add: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: "required", preset: 1})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled", preset: User[UserField.ID]})).appendTo(f);
					createFormAddon("date", "SetupDate", "建立日期", {placeholder: date, attribute: "required", preset: date}).appendTo(f);
					createFormMark(f, "material-purchase-detail", "進貨明細", ["配方料", "單位價格", "數量", "單位"]).appendTo(f);
					createFormCheckbox("IsPrintPdf", "列印配方料進貨單", {attribute: "checked", fct: function(j){getActionWithWindow(api.print, {json: j})}}).appendTo(f);
					b().appendTo(f);
				},
				edit: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: (UserDisabled ? "disabled" : "required")})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled"})).appendTo(f);
					createFormAddon("text", "SetupDate", "建立日期", {placeholder: date, attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyEmployee", "核銷人員", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyDate", "核銷日期", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormHidden("VerifyState").appendTo(f);
					createFormHidden("VerifyAction", 1).appendTo(f);
					createFormMark(f, "material-purchase-detail", "進貨明細", ["配方料", "單位價格", "數量", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印配方料進貨單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					createFormCheckbox("IsPrintBarcode", "列印條碼貼紙", {attribute: "checked", val: 1}).appendTo(f);
					b({
						append: [createFormActionButton("verify", "確定核銷", {icon: "clipboard"}, function(evt){postActionVerify(api.verify ,f)})],
						disabled: {edit: true}
					}).appendTo(f);
				},
				disabled: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: (UserDisabled ? "disabled" : "required")})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled"})).appendTo(f);
					createFormAddon("text", "SetupDate", "建立日期", {placeholder: date, attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyEmployee", "核銷人員", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyDate", "核銷日期", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormHidden("VerifyState").appendTo(f);
					createFormMark(f, "material-purchase-detail", "進貨明細", ["配方料", "單位價格", "數量", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印配方料進貨單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					b({disabled: {remove: UserDisabled, edit: UserDisabled}}).appendTo(f);
				}
			});
		},
		init: function(m, p){
			createPanelSearch(m, {
				action: {name: "print", required: true, icon: "print", api: "php/FormGrenrator/api_MaterialPurchaseList_pdf.php", method: "window", json: {EmployeeName: User.Name}},
				search: [{
					text: "依條碼號碼", field: "Barcode"},{
					text: "依廠商名稱", field: "CompanyName"},{
					text: "依人員名稱", field: "SetupEmployee"
				}],
				filter: [{
					text: "顯示全部"},{
					text: "未核銷", field: "VerifyState", value: "0"},{
					text: "已核銷", field: "VerifyState", value: "1"
				}],
				date: {after: "SetupDate", before: "SetupDate"}
			}).appendTo(p[1]);
			createPanelTitle(p[1], ["條碼號碼", "廠商", "建單人員", "建立日期", "核銷人員", "核銷日期"]).appendTo(p[1]);
			defineSearchScript(p[1]);
			defineActionScript(p);
			defineRemoveScript(p);
			defineResetScript(p);
			defineModalScript(p);
			defineMarkScript(p);
		},
		cond: [{form: "disabled", field: "VerifyState", value: "1"}],
		scan: {type: "5", mark: [{cond: "000001", index: ["0"], type: "2", layer: 1, field: "MaterialID", focus: "Quantity"}]},
		select: [{type: "C", field: "CompanyID"}]
	});
		
	/*//////////////////////////////////////////////*/
	setModel("productPurchase", {
		view: function(s, r){
			createPanelItem(r.Barcode).appendTo(s);
			createPanelItem(r.CompanyName).appendTo(s);
			createPanelItem(r.SetupEmployee).appendTo(s);
			createPanelItem(r.SetupDate).appendTo(s);
			createPanelItem(r.VerifyEmployee, {replace: [{cond: "", text: "未核銷"}]}).appendTo(s);
			createPanelItem(r.VerifyDate, {replace: [{cond: "0000-00-00", text: "未核銷"}]}).appendTo(s);
		},
		mark: [{
			model: "ProductPurchaseDetail",
			name: "product-purchase-detail", 
			text: "進貨明細",
			required: true,
			unique: ["ProductID"],
			api: {
				search: "php/api_ProductPurchaseDetail_Search.php",
				add: "php/api_ProductPurchaseDetail_Add.php",
				edit: "php/api_ProductPurchaseDetail_Edit.php"
			},
			view: function(s, r){
				createPanelItem(r.ProductName).appendTo(s);
				createPanelItem(Float(r.UnitPrice)).appendTo(s);
				createPanelItem(Float(r.Quantity, 1)).appendTo(s);
				createPanelItem(r.Unit).appendTo(s);
			},
			form: function(m){
				var model = "product",
					modelID = "ProductID",
					modelText = "選擇產品";
				return setModelMarkForm(m, this, {
					add: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "required"})).appendTo(f);
						createFormAddon("text", "UnitPrice", "單位價格*", {placeholder: "0", attribute: "required"}).appendTo(f);
						createFormAddon("text", "Quantity", "數量*", {placeholder: "0", attribute: "required"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b().appendTo(f);
					},
					edit: false,
					verify: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "disabled"})).appendTo(f);
						createFormAddon("text", "UnitPrice", "單位價格", {placeholder: "0", attribute: (UserDisabled ? "disabled" : "required")}).appendTo(f);
						createFormAddon("text", "Quantity", "數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "ActualQuantity", "核實數量", {placeholder: "0"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: UserDisabled}}).appendTo(f);
					},
					disabled: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "disabled"})).appendTo(f);
						createFormAddon("text", "UnitPrice", "單位價格", {placeholder: "0", attribute: (UserDisabled ? "disabled" : "required")}).appendTo(f);
						createFormAddon("text", "Quantity", "數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "ActualQuantity", "核實數量", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: true, edit: UserDisabled}}).appendTo(f);
					}
				}, {layer: 2});
			},
			cond: [{form: "verify", field: "VerifyState", value: "0"}, {form: "disabled", field: "VerifyState", value: "1"}],
			scan: [{type: "3", field: "ProductID", focus: "Quantity"}],
			store: [{
				based: "ProductID", field: "Unit", data: false},{
				based: "ProductID", field: "UnitPrice", data: false}]
		}],
		page: function(m){
			var api = {
				print: "php/FormGrenrator/api_ProductPurchase_pdf.php",
				verify: "php/api_ProductPurchase_Edit.php"
			};
			return setModelForm(m, {
				add: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: "required"})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled", preset: User[UserField.ID]})).appendTo(f);
					createFormAddon("date", "SetupDate", "建立日期", {placeholder: date, attribute: "required", preset: date}).appendTo(f);
					createFormMark(f, "product-purchase-detail", "進貨明細", ["產品", "單位價格", "數量", "單位"]).appendTo(f);
					createFormCheckbox("IsPrintPdf", "列印產品進貨單", {attribute: "checked", fct: function(j){getActionWithWindow(api.print, {json: j})}}).appendTo(f);
					b().appendTo(f);
				},
				edit: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: (UserDisabled ? "disabled" : "required")})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled"})).appendTo(f);
					createFormAddon("text", "SetupDate", "建立日期", {placeholder: date, attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyEmployee", "核銷人員", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyDate", "核銷日期", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormHidden("VerifyState").appendTo(f);
					createFormHidden("VerifyAction", 1).appendTo(f);
					createFormMark(f, "product-purchase-detail", "進貨明細", ["產品", "單位價格", "數量", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印產品進貨單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					createFormCheckbox("IsPrintBarcode", "列印條碼貼紙", {attribute: "checked", val: 1}).appendTo(f);
					b({
						append: [createFormActionButton("verify", "確定核銷", {icon: "clipboard"}, function(evt){postActionVerify(api.verify ,f)})],
						disabled: {edit: true}
					}).appendTo(f);
				},
				disabled: function(f, b){
					createFormSelectModelOptions("company", createFormSelect("company", "CompanyID", "選擇廠商", {attribute: (UserDisabled ? "disabled" : "required")})).appendTo(f);
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled"})).appendTo(f);
					createFormAddon("text", "SetupDate", "建立日期", {placeholder: date, attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyEmployee", "核銷人員", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormAddon("text", "VerifyDate", "核銷日期", {placeholder: "未核銷", attribute: "disabled"}).appendTo(f);
					createFormHidden("VerifyState").appendTo(f);
					createFormMark(f, "product-purchase-detail", "進貨明細", ["產品", "單位價格", "數量", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印產品進貨單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					b({disabled: {remove: UserDisabled, edit: UserDisabled}}).appendTo(f);
				}
			});
		},
		init: function(m, p){
			createPanelSearch(m, {
				action: {name: "print", required: true, icon: "print", api: "php/FormGrenrator/api_ProductPurchaseList_pdf.php", method: "window", json: {EmployeeName: User.Name}},
				search: [{
					text: "依條碼號碼", field: "Barcode"},{
					text: "依廠商名稱", field: "CompanyName"},{
					text: "依人員名稱", field: "SetupEmployee"
				}],
				filter: [{
					text: "顯示全部"},{
					text: "未核銷", field: "VerifyState", value: "0"},{
					text: "已核銷", field: "VerifyState", value: "1"
				}],
				date: {after: "SetupDate", before: "SetupDate"}
			}).appendTo(p[1]);
			createPanelTitle(p[1], ["條碼號碼", "廠商", "建單人員", "建立日期", "核銷人員", "核銷日期"]).appendTo(p[1]);
			defineSearchScript(p[1]);
			defineActionScript(p);
			defineRemoveScript(p);
			defineResetScript(p);
			defineModalScript(p);
			defineMarkScript(p);
		},
		cond: [{form: "disabled", field: "VerifyState", value: "1"}],
		scan: {type: "6", mark: [{cond: "000001", index: ["0"], type: "3", layer: 1, field: "ProductID", focus: "Quantity"}]},
		select: [{type: "C", field: "CompanyID"}]
	});
	
	/*//////////////////////////////////////////////*/
	setModel("ingredientInventory", {
		view: function(s, r){
			createPanelItem(r.Barcode).appendTo(s);
			createPanelItem(r.EmployeeName).appendTo(s);
			createPanelItem(r.Date).appendTo(s);
		},
		mark: [{
			model: "IngredientInventoryDetail",
			name: "ingredient-inventory-detail", 
			text: "盤點單明細",
			required: true,
			unique: ["IngredientID"],
			api: {
				search: "php/api_IngredientInventoryDetail_Search.php",
				add: "php/api_IngredientInventoryDetail_Add.php",
				edit: "php/api_IngredientInventoryDetail_Edit.php"
			},
			view: function(s, r){
				createPanelItem(r.IngredientName).appendTo(s);
				createPanelItem(Float(r.Inventory, 1)).appendTo(s);
				createPanelItem(r.Difference).appendTo(s);
				createPanelItem(r.Unit).appendTo(s);
			},
			form: function(m){
				var model = "ingredient",
					modelID = "IngredientID",
					modelText = "選擇原料";
				return setModelMarkForm(m, this, {
					add: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "required"})).appendTo(f);
						createFormAddon("text", "Inventory", "庫存數量*", {placeholder: "0", attribute: "required"}).appendTo(f);
						//createFormAddon("text", "Difference", "數量差", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: true}}).appendTo(f);
					},
					edit: false
				}, {layer: 2});
			},
			scan: [{type: "1", field: "IngredientID", focus: "Inventory"}],
			store: [{based: "IngredientID", field: "Unit", data: false}]
		}],
		page: function(m){
			var api = {
				print: "php/FormGrenrator/api_IngredientInventory_pdf.php",
				verify: "php/api_IngredientInventory_Edit.php"
			};
			return setModelForm(m, {
				add: function(f, b){
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled", preset: User[UserField.ID]})).appendTo(f);
					createFormAddon("date", "Date", "日期", {placeholder: date, attribute: "required", preset: date}).appendTo(f);
					createFormMark(f, "ingredient-inventory-detail", "盤點單明細", ["原料", "庫存數量", "數量差", "單位"]).appendTo(f);
					//createFormCheckbox("IsPrintPdf", "列印原料盤點單", {attribute: "checked", fct: function(j){getActionWithWindow(api.print, {json: j})}}).appendTo(f);
					b().appendTo(f);
				},
				edit: function(f, b){
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled", preset: User[UserField.ID]})).appendTo(f);
					createFormAddon("date", "Date", "日期", {placeholder: date, attribute: "required", preset: date}).appendTo(f);
					createFormMark(f, "ingredient-inventory-detail", "盤點單明細", ["原料", "庫存數量", "數量差", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印原料盤點單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					//createFormCheckbox("IsPrintBarcode", "列印條碼貼紙", {attribute: "checked", val: 1}).appendTo(f);
					b().appendTo(f);
				}
			});
		},
		init: function(m, p){
			createPanelSearch(m, {
				action: {name: "print", required: true, icon: "print", api: "php/FormGrenrator/api_IngredientInventoryList_pdf.php", method: "window", json: {EmployeeName: User.Name}},
				search: [{
					text: "依條碼號碼", field: "Barcode"},{
					text: "依人員名稱", field: "EmployeeName"
				}],
				date: {after: "Date", before: "Date"}
			}).appendTo(p[1]);
			createPanelTitle(p[1], ["條碼號碼", "建單人員", "日期"]).appendTo(p[1]);
			defineSearchScript(p[1]);
			defineActionScript(p);
			defineRemoveScript(p);
			defineResetScript(p);
			defineModalScript(p);
			defineMarkScript(p);
		},
		scan: {type: "I", mark: [{cond: "000003", index: ["0"], type: "1", layer: 1, field: "IngredientID", focus: "Inventory"}]}
	});
	
	/*//////////////////////////////////////////////*/
	setModel("materialInventory", {
		view: function(s, r){
			createPanelItem(r.Barcode).appendTo(s);
			createPanelItem(r.EmployeeName).appendTo(s);
			createPanelItem(r.Date).appendTo(s);
		},
		mark: [{
			model: "MaterialInventoryDetail",
			name: "material-inventory-detail", 
			text: "盤點單明細",
			required: true,
			unique: ["MaterialID"],
			api: {
				search: "php/api_MaterialInventoryDetail_Search.php",
				add: "php/api_MaterialInventoryDetail_Add.php",
				edit: "php/api_MaterialInventoryDetail_Edit.php"
			},
			view: function(s, r){
				createPanelItem(r.MaterialName).appendTo(s);
				createPanelItem(Float(r.Inventory, 1)).appendTo(s);
				createPanelItem(r.Difference).appendTo(s);
				createPanelItem(r.Unit).appendTo(s);
			},
			form: function(m){
				var model = "material",
					modelID = "MaterialID",
					modelText = "選擇配方料";
				return setModelMarkForm(m, this, {
					add: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "required"})).appendTo(f);
						createFormAddon("text", "Inventory", "庫存數量*", {placeholder: "0", attribute: "required"}).appendTo(f);
						//createFormAddon("text", "Difference", "數量差", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: true}}).appendTo(f);
					},
					edit: false
				}, {layer: 2});
			},
			scan: [{type: "1", field: "MaterialID", focus: "Inventory"}],
			store: [{based: "MaterialID", field: "Unit", data: false}]
		}],
		page: function(m){
			var api = {
				print: "php/FormGrenrator/api_MaterialInventory_pdf.php",
				verify: "php/api_MaterialInventory_Edit.php"
			};
			return setModelForm(m, {
				add: function(f, b){
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled", preset: User[UserField.ID]})).appendTo(f);
					createFormAddon("date", "Date", "日期", {placeholder: date, attribute: "required", preset: date}).appendTo(f);
					createFormMark(f, "material-inventory-detail", "盤點單明細", ["原料", "庫存數量", "數量差", "單位"]).appendTo(f);
					//createFormCheckbox("IsPrintPdf", "列印原料盤點單", {attribute: "checked", fct: function(j){getActionWithWindow(api.print, {json: j})}}).appendTo(f);
					b().appendTo(f);
				},
				edit: function(f, b){
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled", preset: User[UserField.ID]})).appendTo(f);
					createFormAddon("date", "Date", "日期", {placeholder: date, attribute: "required", preset: date}).appendTo(f);
					createFormMark(f, "material-inventory-detail", "盤點單明細", ["原料", "庫存數量", "數量差", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印配方料盤點單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					//createFormCheckbox("IsPrintBarcode", "列印條碼貼紙", {attribute: "checked", val: 1}).appendTo(f);
					b().appendTo(f);
				}
			});
		},
		init: function(m, p){
			createPanelSearch(m, {
				action: {name: "print", required: true, icon: "print", api: "php/FormGrenrator/api_MaterialInventoryList_pdf.php", method: "window", json: {EmployeeName: User.Name}},
				search: [{
					text: "依條碼號碼", field: "Barcode"},{
					text: "依人員名稱", field: "EmployeeName"
				}],
				date: {after: "Date", before: "Date"}
			}).appendTo(p[1]);
			createPanelTitle(p[1], ["條碼號碼", "建單人員", "日期"]).appendTo(p[1]);
			defineSearchScript(p[1]);
			defineActionScript(p);
			defineRemoveScript(p);
			defineResetScript(p);
			defineModalScript(p);
			defineMarkScript(p);
		},
		scan: {type: "M", mark: [{cond: "000003", index: ["0"], type: "1", layer: 1, field: "MaterialID", focus: "Inventory"}]}
	});
	
	/*//////////////////////////////////////////////*/
	setModel("productInventory", {
		view: function(s, r){
			createPanelItem(r.Barcode).appendTo(s);
			createPanelItem(r.EmployeeName).appendTo(s);
			createPanelItem(r.Date).appendTo(s);
		},
		mark: [{
			model: "ProductInventoryDetail",
			name: "product-inventory-detail", 
			text: "盤點單明細",
			required: true,
			unique: ["ProductID"],
			api: {
				search: "php/api_ProductInventoryDetail_Search.php",
				add: "php/api_ProductInventoryDetail_Add.php",
				edit: "php/api_ProductInventoryDetail_Edit.php"
			},
			view: function(s, r){
				createPanelItem(r.ProductName).appendTo(s);
				createPanelItem(Float(r.Inventory, 1)).appendTo(s);
				createPanelItem(r.Difference).appendTo(s);
				createPanelItem(r.Unit).appendTo(s);
			},
			form: function(m){
				var model = "product",
					modelID = "ProductID",
					modelText = "選擇原料";
				return setModelMarkForm(m, this, {
					add: function(f, b){
						createFormSelectModelOptions(model, createFormSelect(model, modelID, modelText, {attribute: "required"})).appendTo(f);
						createFormAddon("text", "Inventory", "庫存數量*", {placeholder: "0", attribute: "required"}).appendTo(f);
						//createFormAddon("text", "Difference", "數量差", {placeholder: "0", attribute: "disabled"}).appendTo(f);
						createFormAddon("text", "Unit", "單位", {placeholder: "Unit", attribute: "disabled"}).appendTo(f);
						b({disabled: {remove: true}}).appendTo(f);
					},
					edit: false
				}, {layer: 2});
			},
			scan: [{type: "1", field: "ProductID", focus: "Inventory"}],
			store: [{based: "ProductID", field: "Unit", data: false}]
		}],
		page: function(m){
			var api = {
				print: "php/FormGrenrator/api_ProductInventory_pdf.php",
				verify: "php/api_ProductInventory_Edit.php"
			};
			return setModelForm(m, {
				add: function(f, b){
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled", preset: User[UserField.ID]})).appendTo(f);
					createFormAddon("date", "Date", "日期", {placeholder: date, attribute: "required", preset: date}).appendTo(f);
					createFormMark(f, "product-inventory-detail", "盤點單明細", ["原料", "庫存數量", "數量差", "單位"]).appendTo(f);
					//createFormCheckbox("IsPrintPdf", "列印原料盤點單", {attribute: "checked", fct: function(j){getActionWithWindow(api.print, {json: j})}}).appendTo(f);
					b().appendTo(f);
				},
				edit: function(f, b){
					createFormSelectModelOptions("employee", createFormSelect("employee", "EmployeeID", "選擇人員", {attribute: "disabled", preset: User[UserField.ID]})).appendTo(f);
					createFormAddon("date", "Date", "日期", {placeholder: date, attribute: "required", preset: date}).appendTo(f);
					createFormMark(f, "product-inventory-detail", "盤點單明細", ["原料", "庫存數量", "數量差", "單位"], {attribute: "disabled"}).appendTo(f);
					createFormPrintButton({btn: "print"}, "列印產品盤點單", {ces: "hidden-xs", icon: "print", fct: function(){getActionWithWindow(api.print, {form: f})}}).appendTo(f);
					//createFormCheckbox("IsPrintBarcode", "列印條碼貼紙", {attribute: "checked", val: 1}).appendTo(f);
					b().appendTo(f);
				}
			});
		},
		init: function(m, p){
			createPanelSearch(m, {
				action: {name: "print", required: true, icon: "print", api: "php/FormGrenrator/api_ProductInventoryList_pdf.php", method: "window", json: {EmployeeName: User.Name}},
				search: [{
					text: "依條碼號碼", field: "Barcode"},{
					text: "依人員名稱", field: "EmployeeName"
				}],
				date: {after: "Date", before: "Date"}
			}).appendTo(p[1]);
			createPanelTitle(p[1], ["條碼號碼", "建單人員", "日期"]).appendTo(p[1]);
			defineSearchScript(p[1]);
			defineActionScript(p);
			defineRemoveScript(p);
			defineResetScript(p);
			defineModalScript(p);
			defineMarkScript(p);
		},
		scan: {type: "P", mark: [{cond: "000003", index: ["0"], type: "1", layer: 1, field: "ProductID", focus: "Inventory"}]}
	});
	
	/*//////////////////////////////////////////////*/
	setModel("employee", {
		view: function(s, r){
			createPanelItem(r.Name).appendTo(s);
			createPanelItem(r.BirthDate).appendTo(s);
			createPanelItem(r.Address).appendTo(s);
			createPanelItem(r.Phone).appendTo(s);
			createPanelItem(r.Account).appendTo(s);
			createPanelItem(r.Permission, {replace: [{cond: "0", text: "Admin"}, {cond: "1", text: "管理者"}, {cond: "2", text: "一般人員"}]}).appendTo(s);
		},
		page: function(m){
			return setModelForm(m, {
				add: function(f, b){
					createFormText("Name", "姓名*", {attribute: "required"}).appendTo(f);
					createFormAddon("date", "BirthDate", "生日").appendTo(f);
					createFormAddon("date", "HireDate", "就職日").appendTo(f);
					createFormAddon("text", "Address", "地址", {placeholder: "Address"}).appendTo(f);
					createFormAddon("tel", "Phone", "電話", {placeholder: "Phone"}).appendTo(f);
					createFormAddon("text", "Account", "帳號*", {placeholder: "Account", attribute: "required"}).appendTo(f);
					createFormAddon("password", "Password", "密碼*", {placeholder: "Password", attribute: "required"}).appendTo(f);
					createFormSelectOptions(createFormSelect("permission", "Permission", "選擇權限", {preset: 2}),[{
						text: "管理者", value: 1},{
						text: "一般人員", value: 2
					}]).appendTo(f);
					b().appendTo(f);
				},
				edit: function(f, b){
					createFormText("Name", "姓名*", {attribute: "required"}).appendTo(f);
					createFormAddon("date", "BirthDate", "生日").appendTo(f);
					createFormAddon("date", "HireDate", "就職日").appendTo(f);
					createFormAddon("text", "Address", "地址", {placeholder: "Address"}).appendTo(f);
					createFormAddon("tel", "Phone", "電話", {placeholder: "Phone"}).appendTo(f);
					createFormAddon("text", "Account", "帳號*", {placeholder: "Account", attribute: "required"}).appendTo(f);
					createFormAddon("password", "Password", "密碼", {placeholder: "********"}).appendTo(f);
					createFormSelectOptions(createFormSelect("permission", "Permission", "選擇權限", {preset: 2}),[{
						text: "管理者", value: 1},{
						text: "一般人員", value: 2
					}]).appendTo(f);
					b({remove: "離職"}).appendTo(f);
				}
			});
		},
		init: function(m, p){
			createPanelSearch(m, {
				search: [{
					text: "依姓名", field: "Name"},{
					text: "依帳號", field: "Account"
				}]
			}).appendTo(p[1]);
			createPanelTitle(p[1], ["姓名", "生日", "地址", "電話", "帳號", "權限"]).appendTo(p[1]);
			defineSearchScript(p[1]);
			defineActionScript(p);
			defineRemoveScript(p);
			defineResetScript(p);
			defineModalScript(p);
		}
	});
	
	/*//////////////////////////////////////////////*/
	setModel("product", {
		view: function(s, r){
			createPanelItem(r.ProductID).appendTo(s);
			createPanelItem(r.Name).appendTo(s);
			createPanelItem(Float(r.Inventory, 1), {alarm: {model: "product"}}).appendTo(s);
			createPanelItem(r.Unit).appendTo(s);
			createPanelItem(r.Cost).appendTo(s);
		},
		page: function(m){
			return setModelForm(m, {
				add: function(f, b){
					createFormText("Name", "名稱*", {attribute: "required"}).appendTo(f);
					createFormAddon("text", "Unit", "單位*", {placeholder: "Unit", attribute: "required"}).appendTo(f);
					createFormAddon("text", "Number", "警告數量", {placeholder: "Number", disabled: UserDisabled}).appendTo(f);
					createFormAddon("color", "Color", "警示顏色", {disabled: UserDisabled}).appendTo(f);
					b().appendTo(f);
				},
				edit: function(f, b){
					createFormText("Name", "名稱*", {attribute: "required"}).appendTo(f);
					createFormAddon("text", "Inventory", "庫存數量", {placeholder: "無庫存", disabled: true}).appendTo(f)
					createFormAddon("text", "Unit", "單位*", {placeholder: "Unit", attribute: "required"}).appendTo(f);
					createFormHidden("Cost").appendTo(f);
					createFormOutput("AverageCost", "平均成本", {placeholder: "0", disabled: true}).appendTo(f);
					createFormAddon("text", "Number", "警告數量", {placeholder: "Number", disabled: UserDisabled}).appendTo(f);
					createFormAddon("color", "Color", "警示顏色", {disabled: UserDisabled}).appendTo(f);
					defineInputScript(f, {dividend: "Cost", divisor: "Inventory", result: "AverageCost"});
					createFormPrintButton({btn: "print", input: "Quantity"}, "列印條碼", {ces: "hidden-xs", icon: "print", placeholder: "份數", preset: "1", fct: function(evt){
						var api = "php/api_Product_Barcode.php",
							data = new FormData(f[0]);
							postAction(api, data);
					}}).appendTo(f);
					b().appendTo(f);
				}
			});
		},
		init: function(m, p){
			createPanelSearch(m, {
				action: {name: "print", required: true, icon: "barcode", wrapper: "file-o", api: "php/Barcode/api_ProductBarcode_pdf.php", method: "window", json: {column: 2}},
				search: [{
					text: "依ID", field: "ProductID"},{
					text: "依名稱", field: "Name"
				}]
			}).appendTo(p[1]);
			createPanelTitle(p[1], ["ID", "產品名稱", "庫存數量", "單位", "總成本"]).appendTo(p[1]);
			defineSearchScript(p[1]);
			defineActionScript(p);
			defineRemoveScript(p);
			defineResetScript(p);
			defineModalScript(p);
			defineMarkScript(p);
		},
		scan: {type: "3"},
		saver: [{load: {field: "isMaterial", value: "1"}}]
	});
	
	/*//////////////////////////////////////////////*/
	setModel("material", {
		view: function(s, r){
			createPanelItem(r.MaterialID).appendTo(s);
			createPanelItem(r.Name).appendTo(s);
			createPanelItem(Float(r.Inventory, 1), {alarm: {model: "material"}}).appendTo(s);
			createPanelItem(r.Unit).appendTo(s);
			createPanelItem(r.Cost).appendTo(s);
		}, 
		page: function(m){
			return setModelForm(m, {
				add: function(f, b){
					createFormText("Name", "名稱*", {attribute: "required"}).appendTo(f);
					createFormAddon("text", "Unit", "單位*", {placeholder: "Unit", attribute: "required"}).appendTo(f);
					createFormAddon("text", "Number", "警告數量", {placeholder: "Number", disabled: UserDisabled}).appendTo(f);
					createFormAddon("color", "Color", "警示顏色", {disabled: UserDisabled}).appendTo(f);
					b().appendTo(f);
				},
				edit: function(f, b){
					createFormText("Name", "名稱*", {attribute: "required"}).appendTo(f);
					createFormAddon("text", "Inventory", "庫存數量", {placeholder: "無庫存", disabled: true}).appendTo(f);
					createFormAddon("text", "Unit", "單位*", {placeholder: "Unit", attribute: "required"}).appendTo(f);
					createFormHidden("Cost").appendTo(f);
					createFormOutput("AverageCost", "平均成本", {placeholder: "0", disabled: true}).appendTo(f);
					createFormAddon("text", "Number", "警告數量", {placeholder: "Number", disabled: UserDisabled}).appendTo(f);
					createFormAddon("color", "Color", "警示顏色", {disabled: UserDisabled}).appendTo(f);
					defineInputScript(f, {dividend: "Cost", divisor: "Inventory", result: "AverageCost"});
					createFormPrintButton({btn: "print", input: "Quantity"}, "列印條碼", {ces: "hidden-xs", icon: "print", placeholder: "份數", preset: "1", fct: function(evt){
						var api = "php/api_Material_Barcode.php",
							data = new FormData(f[0]);
							postAction(api, data);
					}}).appendTo(f);
					b().appendTo(f);
				}
			});
		},
		init: function(m, p){
			createPanelSearch(m, {
				action: {name: "print", required: true, icon: "barcode", wrapper: "file-o", api: "php/Barcode/api_MaterialBarcode_pdf.php", method: "window", json: {column: 2}},
				search: [{
					text: "依ID", field: "MaterialID"},{
					text: "依名稱", field: "Name"
				}]
			}).appendTo(p[1]);
			createPanelTitle(p[1], ["ID", "配方料名稱", "庫存數量", "單位", "總成本"]).appendTo(p[1]);
			defineSearchScript(p[1]);
			defineActionScript(p);
			defineRemoveScript(p);
			defineResetScript(p);
			defineModalScript(p);
			defineMarkScript(p);
		},
		scan: {type: "2"}
	});
	
	/*//////////////////////////////////////////////*/
	setModel("ingredient", {
		view: function(s, r){
			createPanelItem(r.IngredientID).appendTo(s);
			createPanelItem(r.Name).appendTo(s);
			createPanelItem(Float(r.Inventory, 1), {alarm: {model: "ingredient"}}).appendTo(s);
			createPanelItem(r.Unit).appendTo(s);
			createPanelItem(r.Cost).appendTo(s);
		}, 
		page: function(m){
			return setModelForm(m, {
				add: function(f, b){
					createFormText("Name", "名稱*", {attribute: "required"}).appendTo(f);
					createFormAddon("text", "Unit", "單位*", {placeholder: "Unit", attribute: "required"}).appendTo(f);
					createFormAddon("text", "Number", "警告數量", {placeholder: "Number", disabled: UserDisabled}).appendTo(f);
					createFormAddon("color", "Color", "警示顏色", {disabled: UserDisabled}).appendTo(f);
					b().appendTo(f);
				},
				edit: function(f, b){
					createFormText("Name", "名稱*", {attribute: "required"}).appendTo(f);
					createFormAddon("text", "Inventory", "庫存數量", {placeholder: "無庫存", disabled: true}).appendTo(f);
					createFormAddon("text", "Unit", "單位*", {placeholder: "Unit", attribute: "required"}).appendTo(f);
					createFormHidden("Cost").appendTo(f);
					createFormOutput("AverageCost", "平均成本", {placeholder: "0", disabled: true}).appendTo(f);
					createFormAddon("text", "Number", "警告數量", {placeholder: "Number", disabled: UserDisabled}).appendTo(f);
					createFormAddon("color", "Color", "警示顏色", {disabled: UserDisabled}).appendTo(f);
					defineInputScript(f, {dividend: "Cost", divisor: "Inventory", result: "AverageCost"});
					createFormPrintButton({btn: "print", input: "Quantity"}, "列印條碼", {ces: "hidden-xs", icon: "print", placeholder: "份數", preset: "1", fct: function(evt){
						var api = "php/api_Ingredient_Barcode.php",
							data = new FormData(f[0]);
							postAction(api, data);
					}}).appendTo(f);
					b().appendTo(f);
				}
			});
		},
		init: function(m, p){
			createPanelSearch(m, {
				action: {name: "print", required: true, icon: "barcode", wrapper: "file-o", api: "php/Barcode/api_IngredientBarcode_pdf.php", method: "window", json: {column: 2}},
				search: [{
					text: "依ID", field: "IngredientID"},{
					text: "依名稱", field: "Name"
				}]
			}).appendTo(p[1]);
			createPanelTitle(p[1], ["ID", "原料名稱", "庫存數量", "單位", "總成本"]).appendTo(p[1]);
			defineSearchScript(p[1]);
			defineActionScript(p);
			defineRemoveScript(p);
			defineResetScript(p);
			defineModalScript(p);
			defineMarkScript(p);
		},
		scan: {type: "1"}
	});
	
	/*//////////////////////////////////////////////*/
	setModel("company", {
		view: function(s, r){
			createPanelItem(r.CompanyName).appendTo(s);
			createPanelItem(r.ContactName).appendTo(s);
			createPanelItem(r.Address).appendTo(s);
			createPanelItem(r.Phone).appendTo(s);
			createPanelItem(r.TaxID).appendTo(s);
			createPanelItem(r.Remark).appendTo(s);
		}, 
		page: function(m){
			return setModelForm(m, {
				add: function(f, b){
					createFormText("CompanyName", "公司名稱*", {attribute: "required"}).appendTo(f);
					createFormAddon("text", "ContactName", "聯絡人", {placeholder: "ContactName"}).appendTo(f);
					createFormAddon("text", "Address", "地址", {placeholder: "Address"}).appendTo(f);
					createFormAddon("tel", "Phone", "電話", {placeholder: "Phone"}).appendTo(f);
					createFormAddon("text", "TaxID", "統編", {placeholder: "TaxID"}).appendTo(f);
					createFormTextarea("Remark", {placeholder: "備註"}).appendTo(f);
					b().appendTo(f);
				}
			});
		},
		init: function(m, p){
			createPanelSearch(m, {
				action: {name: "print", required: true, icon: "barcode", wrapper: "file-o", api: "php/Barcode/api_CompanyBarcode_pdf.php", method: "window", json: {column: 2}},
				search: [{
					text: "依公司名稱", field: "CompanyName"},{
					text: "依聯絡人", field: "ContactName"},{
					text: "依統編", field: "TaxID"
				}]
			}).appendTo(p[1]);
			createPanelTitle(p[1], ["公司名稱", "聯絡人", "地址", "電話", "統編", "備註"]).appendTo(p[1]);
			defineSearchScript(p[1]);
			defineActionScript(p);
			defineRemoveScript(p);
			defineResetScript(p);
			defineModalScript(p);
		}
	});
	