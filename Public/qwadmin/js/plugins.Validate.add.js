var validator = jQuery.validator;

validator.testFn = {
	isUserName : function(s) { 
		return true;
	},
	isPassword : function(s) {
		return /^[0-9a-zA-Z]{8,20}$/.test(s) && /[a-zA-Z]+/.test(s) && /[0-9]+/.test(s);
	},
	//验证身份证
	isIdCard : function(s) {
		//var idCardText = /^\d{6}|\d{18}$/;
		var idCardText = /^[1-9]([0-9]{16}|[0-9]{13})[xX0-9]$/;
		return idCardText.test(s);
	},
	//验证护照
	isPassports : function(s) {		
		var passportsText = /^(P\d{7}|G\d{8}|S\d{7,8}|D\d+|1[4,5]\d{7})$/;
		return passportsText.test(s);
	},
	//邮箱验证
	isEmail : function(s) {
		var isEmailText = /^(?=\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$).{0,30}$/;
		return isEmailText.test(s);
	},
	//校验普通电话
	isPhone : function(s) {
		var re = /^(0[0-9]{2,3}\-)([2-9][0-9]{6,7})+(\-[0-9]{1,4})?$/;
		return re.test(s);
	},
	//校验手机号码
	isMobile : function(s) {
		var re=/^1[3-8]\d{9}$/;
		return re.test(s);
	},
	//校验邮政编码
	isZipCode : function(s) {
		var re = /^[1-9][0-9]{5}$/;
		return re.test(s);
	},
	isNoSel : function(s) {
		var re = /^[1-9][0-9]{5}$/;
		return re.test(s);
	},
	isNoSel : function(s) {
		return s.selectedIndex !== 0;
	},
	isNumber:function(s){
		var re =/^[0-9]*$/;
		return re.test(s);
	}
};

// 添加验证方法
validator.addMethod('isEmail', function(value, element) {
	return this.optional(element) || validator.testFn.isEmail(value);
}, '请填写正确的邮箱！');

validator.addMethod('isIdCard', function(value, element) {
	var that  = this;
	if(!that.optional(element)){
		var $idType = $("#idType");
		if($idType.length>0){
		   if($idType.val() === "1001"){
			   return validator.testFn.isIdCard(value);
		   }else{
			   return true;
		   }
		}else{
		   return validator.testFn.isIdCard(value);
		}
	}else{
		return true;
	}
}, "请填写正确的身份证！");

validator.addMethod('isPassports', function(value, element) {
	if(!this.optional(element)){
		var $idType = $("#idType");
		if($idType.length>0){
		   if($idType.val() === "1002"){
			   return validator.testFn.isPassports(value);
		   }else{
			   return true;
		   }
		}else{
		   return validator.testFn.isPassports(value);
		}
	}else{
		return true;
	}
}, "请填写正确的护照号！");

validator.addMethod('isUserName', function(value, element) {
	return this.optional(element) || validator.testFn.isUserName(value);
}, '');

validator.addMethod('isPassword', function(value, element) {
	return this.optional(element) || validator.testFn.isPassword(value);
}, '请输入由8-20位数字和字母组成的登录密码');

validator.addMethod('isPhone', function(value, element) {
	return this.optional(element) || validator.testFn.isPhone(value);
}, '请填写正确的电话号码！例如：021-88888888');

validator.addMethod('isMobile', function(value, element) {
	return this.optional(element) || validator.testFn.isMobile(value.replace(/\s/ig, ''));
}, '请填写正确的手机号码！');

validator.addMethod('isZipCode', function(value, element) {
	return this.optional(element) || validator.testFn.isZipCode(value);
}, '请填写正确的邮政编码！');

validator.addMethod('isNoSel', function(value, element) {
	return this.optional(element) || validator.testFn.isNoSel(element);
}, '请填写正确的邮政编码！');
validator.addMethod('isNumber', function(value, element) {
	return this.optional(element) || validator.testFn.isNumber(element);
}, '请填写数字！');

// 增加选择出生日期与身份证上出生日期对比方法
// 修改日期: 2015/12/30
validator.addMethod('compareDate', function(value, element,param) {
	if($("#idType").val()=='1001'){
		var card_birthDay = value.substring(6, 10)
		 + value.substring(10, 12)
		 + value.substring(12, 14);
		 var birthDay = jQuery(param).val().replace(/-/g,"");
		 return card_birthDay == birthDay;
	} 
	return true;
},"证件生日与出生日期不一致！");
