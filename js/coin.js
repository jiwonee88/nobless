// 이체 비밀번호 검사
var mb_deposite_pass_check = function(mb_id,pass) {
    var result = "";
    $.ajax({
        type: "POST",
        url: g5_url+"/for_common/ajax.mb_deposite_pass_check.php",
        data: {
            "mb_deposite_pass": pass,
			"mb_id": mb_id,
        },
        cache: false,
        async: false,
        success: function(data) {
            result = data;
        }
    });
    return result;
}


// 지갑 검사
function deposite_wallet_check(tokentype,addr) {
    var result = "";
    $.ajax({
        type: "POST",
        url: g5_url+"/for_common/ajax.deposite_wallet_check.php",
        data: {
            "tokentype": tokentype,
			"addr": addr,
        },
        cache: false,
        async: false,
        success: function(data) {
            result = data;
        }
    });
    return result;
}


// 회원검
function deposite_mb_check(mb_id) {
    var result = "";
    $.ajax({
        type: "POST",
        url: g5_url+"/for_common/ajax.deposite_mb_check.php",
        data: {
            "mb_id": mb_id
        },
        cache: false,
        async: false,
        success: function(data) {
            result = data;
			console.log(result);
        }
    });
    return result;
}