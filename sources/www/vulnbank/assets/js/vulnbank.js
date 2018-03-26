$.fn.bootstrapSwitch.defaults.onColor = 'success';
$.fn.bootstrapSwitch.defaults.offColor = 'danger';

var baseurl = window.location.pathname.split("/");
var currentPage = baseurl[baseurl.length - 1];

addEventListener("load", function() {
    setTimeout(hideURLbar, 0);
}, false);

$(document).ready(function () {
    if ($("meta[name='csrf-token-name']").length) { $("#sidebar").attr("data-color", "red") }
    $('#login-horizontalTab').easyResponsiveTabs({ type: 'default', width: 'auto', fit: true });
    jQuery.event.props.push('dataTransfer');
    $(":checkbox").bootstrapSwitch();
    $('.selectpicker').selectpicker();
    $('#userinfo-birthdate').datetimepicker({ format: "YYYY-MM-DD"  });
    $("#createuser-creditcard, #login-register-creditcard").val(get_cc().match(/.{1,4}/g).join('-'));
    $("#createuser-account, #login-register-account").val(get_acc());
    $('#createuser-birthdate, #login-register-birthdate, #createuser-birthdate').datetimepicker({ format: "DD-MM-YYYY" });
    $("#createuser-firstname, #createuser-lastname, #createuser-username, #login-reset-username, #login-login-username, #login-register-username, #login-register-firstname, #login-register-lastname, #userinfo-firstname, #userinfo-lastname").on("change", function(event) { validate($(this), /^[a-zA-Z0-9\.-_']*$/); });
    $("#createuser-account").on("change", function(event) { validate($(this), /^[A-Z]{2}[0-9]{20}$/); });
    $("#createuser-phone, #login-login-code, #login-register-phone, #login-reset-code, #transactions-code, #userinfo-phone").on("change", function(event) { validate($(this), /^[0-9]*$/); });
    $("#createuser-creditcard, #userinfo-birthdate").on("change", function(event) { validate($(this), /^[0-9-]*$/); });
    $("#createuser-email, #login-register-email, #userinfo-email").on("change",function(event) {
        validate($(this), /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
    });
    $("#transactions-amount").on("change",function(event) { validate($(this),/^[0-9-\.]*$/); });
    $("#history-searchinfo").html("Searching... " + decodeURIComponent(location.hash.substr(1)));
    $('#history-history').DataTable({"order":[[5, "desc"]], "oSearch": {"sSearch": location.hash.substr(1)}});
    $("#history-searchfield").on("change", function (event) { location.hash = location.hash + $(this).val(); });
    $("#users-createuser").on("click", function(event) { window.location = "createuser.php"; });
    $("#userinfo-avatar").on("click", function() { $("#userinfo-upload").click(); });
    $("#userinfo-newpassword, #userinfo-confirmpass").on("change", function (event) {
        var newpassword = $("#userinfo-newpassword");
        var confirmpass = $("#userinfo-confirmpass");
        if ((newpassword) && (confirmpass)) {
            var is_equal = (newpassword.val() == confirmpass.val());
            if (is_equal) {
                validate(newpassword, /.*/);
                validate(confirmpass, /.*/);
            }
        }
    });
    $("#language").on("change", function(event) {
        var api = $('meta[name=api]').attr("type");
        var data = {"type": "settings",
                    "action": "changelocale",
                    "language": $(this).val()}
        send_ajax(data, api, {"reload":1});
    });

    switch (currentPage) {
        case "createuser.php":
            $("#createuser-password, #createuser-confirmpassword").on("change",function(event) {
                var password = $("#createuser-password").val();
                var confirmpassword = $("#createuser-confirmpassword").val();
                if ((password) && (confirmpassword)) {
                    var is_valid = password == confirmpassword;
                    if(is_valid) {
                        $("#createuser-password").css({"border-color": "green"}, {"box-shadow": "0 0 10px green"})
                        $("#createuser-confirmpassword").css({"border-color": "green"}, {"box-shadow": "0 0 10px green"})
                    } else {
                        $("#createuser-password").css({"border-color": "red"}, {"box-shadow": "0 0 10px red"})
                        $("#createuser-confirmpassword").css({"border-color": "red"}, {"box-shadow": "0 0 10px red"})
                    }
                }
            });

            $("#createuser-createuser").on("submit",function(event) {
                event.preventDefault();
                var api = $('meta[name=api]').attr("type");
                var username = $("#createuser-username").val();
                var password = $("#createuser-password").val();
                var account = $("#createuser-account").val();
                var creditcard = $("#createuser-creditcard").val();
                var creditcard = $("#createuser-creditcard").val();
                var firstname = $("#createuser-firstname").val();
                var lastname = $("#createuser-lastname").val();
                var email = $("#createuser-email").val();
                var phone = $("#createuser-phone").val();
                var birthdate = $("#createuser-birthdate").val();
                var data = {"type": "user",
                            "action": "create",
                            "username": username,
                            "password": password,
                            "firstname": firstname,
                            "lastname": lastname,
                            "account": account,
                            "creditcard": creditcard,
                            "email": email,
                            "phone": phone,
                            "birthdate": birthdate};
                send_ajax(data, api, {"notify":1})
            });
            break;
        case "history.php":
            $("#history-removefailed").on("click",function(event) {
                event.preventDefault();
                var api = $('meta[name=api]').attr("type");
                var data = {"type": "transaction",
                            "action": "clear"};
                send_ajax(data, api, {"notify":1, "reload":1});
            });

            $("#history-canceloperations").on("click",function(event) {
                event.preventDefault();
                var api = $('meta[name=api]').attr("type");
                var data = {"type": "transaction",
                            "action": "cancel"};
                send_ajax(data, api, {"notify":1,"reload":1});
            });
            break;
        case "login.php":
            $("#login-reset-password,#login-reset-confirmpassword").on("change",function(event) {
                var password = $("#login-reset-password").val();
                var confirmpassword = $("#login-reset-confirmpassword").val();
                if ((password) && (confirmpassword)) {
                    valid = password == confirmpassword;
                    if (valid) {
                        $("#login-reset-password").css({"border-color": "green"},{"box-shadow": "0 0 10px green"})
                        $("#login-reset-confirmpassword").css({"border-color": "green"},{"box-shadow": "0 0 10px green"})
                    } else {
                        $("#login-reset-password").css({"border-color": "red"},{"box-shadow": "0 0 10px red"})
                        $("#login-reset-confirmpassword").css({"border-color": "red"},{"box-shadow": "0 0 10px red"})
                    }
                }
            });

            $("#login-register-password,#login-register-confirmpassword").on("change",function(event) {
                var password = $("#login-register-password").val();
                var confirmpassword = $("#login-register-confirmpassword").val();
                if ((password) && (confirmpassword)) {
                    valid = password == confirmpassword;
                    if (valid) {
                        $("#login-register-password").css({"border-color": "green"},{"box-shadow": "0 0 10px green"})
                        $("#login-register-confirmpassword").css({"border-color": "green"},{"box-shadow": "0 0 10px green"})
                    } else {
                        $("#login-register-password").css({"border-color": "red"},{"box-shadow": "0 0 10px red"})
                        $("#login-register-confirmpassword").css({"border-color": "red"},{"box-shadow": "0 0 10px red"})
                    }
                }
            });

            $("#login-loginform").on("submit",function(event) {
                event.preventDefault();
                var api = "none";
                var codefield = $("#login-login-code");
                var username = $("#login-login-username").val();
                var password = $("#login-login-password").val();
                // var api = $('meta[name=api]').attr("type");
                var data = {"type": "user",
                            "action": "login",
                            "username": username,
                            "password": password,
                            "code": codefield.val()};
                var url = "?" + decodeURIComponent(window.location.search.substring(1));
                $.ajax({url: "api.php",
                        type: "POST",
                        data: data,
                        contentType: "application/x-www-form-urlencoded",
                        dataType: "json",
                        async: true,
                        success: function (data) {
                            if (data.message.indexOf("Code") != -1) {
                                codefield.show();
                                $("#login-login-p").show();
                                notify("success", data.icon, data.message);
                            } else {
                                notify("success", data.icon, data.message);
                                window.location.replace(url);
                            }
                        },
                        error: function (data) {
                            var data = $.parseJSON(data.responseText)
                            notify("danger", data.icon, data.message);
                        }
                });
            });

            $("#login-reset-codebutton").on("click",function(event) {
                event.preventDefault();
                var username = $("#login-reset-username").val();
                if (!username) { notify("danger", "pe-7s-key", "Username not set"); }
                else {
                    var api = $('meta[name=api]').attr("type");
                    var data = {"type": "code",
                                "action": "sms",
                                "username": username};
                    send_ajax(data, api, {"notify":1});
                }
            });

            $("#login-reset-submit").on("click",function(event) {
                event.preventDefault();
                var username = $('#login-reset-username');
                var password = $('#login-reset-password');
                var code = $('#login-reset-code');
                var api = $('meta[name=api]').attr("type");
                valid = false;
                valid = valid || validate(username, /^[a-zA-Z0-9\.-_]*$/);
                valid = valid || validate(code, /^[0-9]{3}$/);
                var data = {"type": "user",
                            "action": "forgotpass",
                            "username": username.val(),
                            "password": password.val(),
                            "code": code.val()};
                send_ajax(data, api, {"notify":1});
            });

            $("#login-register-submit").on("click",function(event) {
                event.preventDefault();
                var username = $('#login-register-username');
                var password = $('#login-register-password');
                var firstname = $('#login-register-firstname');
                var lastname = $('#login-register-lastname');
                var birthdate = $('#login-register-birthdate');
                var email = $('#login-register-email');
                var phone = $('#login-register-phone');
                var creditcard = $('#login-register-creditcard');
                var account = $('#login-register-account');
                var api = $('meta[name=api]').attr("type");
                valid = false;
                valid = valid || validate(username, /^[a-zA-Z0-9\.-_]*$/);
                valid = valid || validate(firstname, /^[a-zA-Z0-9\.-_]*$/);
                valid = valid || validate(lastname, /^[a-zA-Z0-9\.-_]*$/);
                valid = valid || validate(password, /^[a-zA-Z0-9\.-_]*$/);
                valid = valid || validate(email, /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
                valid = valid || validate(phone, /^[0-9]*$/);
                var data = {"type": "user",
                            "action": "create",
                            "username": username.val(),
                            "password": password.val(),
                            "firstname": firstname.val(),
                            "lastname": lastname.val(),
                            "birthdate": birthdate.val(),
                            "email": email.val(),
                            "phone": phone.val(),
                            "account": account.val(),
                            "creditcard": creditcard.val()};
                send_ajax(data, api, {"notify":1});
            });
            break;
        case "portal.php":
            $.ajax({
                url: "api.php",
                type: "POST",
                data: {"type":"user", "action":"statistics"},
                contentType: "application/x-www-form-urlencoded",
                success: function (data) {
                    generate_graph(data);
                }
            });
            break;
        case "settings.php":
            $("#settings-dbreset").on("click", function(event) {
                var api = $('meta[name=api]').attr("type");
                var data = { "type": "settings", "action": "resetdb" }
                send_ajax(data, api, {"notify": 1});
            });

            $(".form-control").on("change switchChange.bootstrapSwitch",function(event, state) {
                var vb_api = $("[name='" + $("#settings-vb_api").val() +"']").attr("data")
                var sms_api = $("[name='" + $("#settings-sms_api").val() +"']").attr("data")
                $('meta[name=api]').attr("type", vb_api);
                var api = $('meta[name=api]').attr("type");
                var data = {"type": "settings",
                            "action": "update",
                            "vb_api": vb_api,
                            "sms_api": sms_api,
                            "vb_otp": Boolean($("#settings-vb_otp .bootstrap-switch-on").length),
                            "nexmo_api_key": $("#settings-nexmo_api_key").val(),
                            "nexmo_api_secret": $("#settings-nexmo_api_secret").val(),
                            "upload_path": $("#settings-upload_path").val()
                }
                send_ajax(data, api, {"notify": 1});
            });
            break;
        case "status.php":
            setInterval(send_ajax_status, 1000);
            break;
        case "transactions.php":
            $("#transactions-firstname, #transactions-lastname, #transactions-recipient, #transactions-creditcard").on("change",function(event) {
                var api = $('meta[name=api]').attr("type");
                var firstname = $("#transactions-firstname");
                var lastname = $("#transactions-lastname");
                var recipient = $("#transactions-recipient");
                var creditcard = $("#transactions-creditcard");
                valid = false;
                //valid = valid || validate(firstname, /^[a-zA-Z0-9]*$/);
                valid = valid || validate(lastname, /^[a-zA-Z0-9]*$/);
                valid = valid || validate(recipient, /^[A-Z]{2}[0-9]{20}$/);
                valid = valid || validate(creditcard, /^[0-9-]*$/);
                if (valid) {
                    var data = {"type": "user",
                                "action": "check",
                                "firstname": firstname.val(),
                                "lastname": lastname.val(),
                                "creditcard": creditcard.val(),
                                "recipient": recipient.val()};
                    send_ajax(data, api, {"highlight":["firstname", "lastname", "creditcard", "recipient"]});
                }
            });

            $("#transactions-code-send").on("click",function(event) {
                event.preventDefault();
                var api = $('meta[name=api]').attr("type");
                var id = $("#transactions-id").val();
                var code = $("#transactions-code").val();
                var data = {"type": "transaction",
                            "action": "verify",
                            "id": id,
                            "code": code};
                send_ajax(data, api, {"notify":1});
                $("#transactions-modal").modal("toggle");
            });

            $("#transactions-transaction").on("submit",function(event) {
                event.preventDefault();
                var api = $('meta[name=api]').attr("type");
                var sender = $("#transactions-sender").val();
                var recipient = $("#transactions-recipient").val();
                var creditcard = $("#transactions-creditcard").val();
                var amount = $("#transactions-amount").val();
                var comment = $("#transactions-comment").val();
                var data = {"type": "transaction",
                            "action": "send",
                            "sender": sender,
                            "recipient": recipient,
                            "creditcard": creditcard,
                            "amount": amount,
                            "comment": comment};
                send_ajax(data, api, {"notify":1});
            });
            break;
        case "userinfo.php":
            $("body").on("dragover", function(event) { event.preventDefault; return false; });
            $("body").on("dragleave", function(event) { event.preventDefault(); return false; });

            $("#userinfo-upload").on("change", function() {
                var file = $("#userinfo-upload")[0].files[0];
                var data = new FormData();
                data.append("upload_avatar", file);
                upld(data);
            });

            $("body").on("drop", function(event) {
                event.preventDefault();
                var files = event.dataTransfer.files;
                var data = new FormData();
                data.append("upload_avatar", files[0]);
                upld(data);
            });

            $("#userinfo-userupdate").on("click", function(event) {
                event.preventDefault();
                var api = $('meta[name=api]').attr("type");
                var firstname = $("#userinfo-firstname");
                var lastname = $("#userinfo-lastname");
                var phone = $("#userinfo-phone");
                var email = $("#userinfo-email");
                var birthdate = $("#userinfo-birthdate");
                var about = $("#userinfo-about");
                valid = true;
                valid = valid && validate(firstname, /^[0-9a-zA-Z'_\.]*$/);
                valid = valid && validate(lastname, /^[0-9a-zA-Z'_\.]*$/);
                valid = valid && validate(birthdate, /^[0-9-]*$/);
                valid = valid && validate(phone, /^[0-9\-\(\)]*$/);
                valid = valid && validate(email, /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/);
                if (valid) {
                    var data = {"type": "user",
                                "action": "infoupdate",
                                "firstname": firstname.val(),
                                "lastname": lastname.val(),
                                "phone": phone.val(),
                                "email": email.val(),
                                "birthdate": birthdate.val(),
                                "about": about.val()}
                    send_ajax(data, api, {"notify": 1});
                    $("#userinfo-description").html(about.val().replace(/\n/g, "<br/>"));
                }
            });

            $("#userinfo-changepass").on("submit", function (event){
                event.preventDefault();
                var oldpassword = $("#userinfo-oldpassword")
                var newpassword = $("#userinfo-newpassword")
                var confirmpass = $("#userinfo-confirmpass")
                if (confirmpass.val() != newpassword.val()) {
                    validate(newpassword, /$a/); 
                    validate(confirmpass, /$a/); 
                } else {
                    var api = $('meta[name=api]').attr("type");
                    var data = {"type": "user",
                                "action": "changepass",
                                "oldpassword": oldpassword.val(),
                                "newpassword": newpassword.val()}
                    send_ajax(data, api, {"notify":1})
                }
            });
            break;
        case "users.php":
            $(".btn-danger").on("click", function(event) {
                var api = $('meta[name=api]').attr("type");
                var id = $(this).attr("lineid").replace("users-", "");
                var data = {"type": "user",
                        "action": "delete",
                        "id": id};
                send_ajax(data, api, {"notify":1, "reload":1});
            });

            $(".form-control").on("change switchChange.bootstrapSwitch",function(event, state) {
                var api = $('meta[name=api]').attr("type");
                try { var lineid=$(this).attr("lineid").replace("users-", ""); }
                catch(err) { lineid = $(this).closest("td").attr("lineid").replace("users-", ""); }
                var amountid="#users-amount"+lineid;
                var roleid="#users-roleselect"+lineid;
                var data = {"type": "user",
                            "action": "update",
                            "amount": $(amountid).val(),
                            "id": lineid,
                            "otp": Boolean($("#users-otp"+lineid+" .bootstrap-switch-on").length),
                            "role": $(roleid).val()};
                send_ajax(data, api, {"notify": 1});
            });
            break;
    }
});
