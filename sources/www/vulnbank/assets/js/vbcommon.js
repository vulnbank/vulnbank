function hideURLbar() {
    window.scrollTo(0,1);
}

function upld(data) {
    $.ajax({
                url: "api.php",
                cache: false,
                dataType: "json",
                contentType: false,
                processData: false,
                type: "POST",
                data: data,
                success: function (data) {
                                $("#userinfo-avatar").attr("src", data.source);
                            }
        });
};

function strrev(str) {
    if (!str) return '';

    var revstr='';

    for (var i = str.length-1; i>=0; i--)
        revstr+=str.charAt(i)

    return revstr;
}

function get_cc() {
    ccnumber = "4556";

    while ( ccnumber.length < 15  ) {
        ccnumber += Math.floor(Math.random()*10);
    }

    var reversedCCnumberString = strrev( ccnumber  );
    var reversedCCnumber = new Array();

    for ( var i=0; i < reversedCCnumberString.length; i++  ) {
        reversedCCnumber[i] = parseInt( reversedCCnumberString.charAt(i)  );
    }

    var sum = 0;
    var pos = 0;

    while ( pos < length - 1  ) {
        var odd = reversedCCnumber[ pos  ] * 2;
        if ( odd > 9  ) { odd -= 9;  }
        sum += odd;
        if ( pos != (length - 2)  ) {sum += reversedCCnumber[ pos +1  ]; }
        pos += 2;
    }

    var checkdigit = (( Math.floor(sum/10) + 1 ) * 10 - sum) % 10;
    ccnumber += checkdigit;
    return ccnumber;
}

function get_acc() {
    acc = "DE";

    while ( acc.length < 22 ) {
        acc += Math.floor(Math.random()*10);
    }

    return acc;
}

function send_ajax(data, api, action) {
    var contentype = "application/x-www-form-urlencoded";
    var url = "api.php";
    var post_data = data;

    if (api == "xml") {
        var contenttype = "text/xml";
        var processdata = false;
        var url = url + "?xml";
        var post_data = form_xml(data);
    } else if (api == "rest") {
        var contenttype = "application/json; charset=utf-8";
        var processdata = true;
        var url = url + "?rest";
        var post_data = JSON.stringify(data);
    }

    $.ajax({url: url,
            type: "POST",
            data: post_data,
            contentType: contenttype,
            dataType: "json",
            processData: processdata,
            async: true,
            success: function (data) {
                if (data.message.indexOf("Code") != -1 && currentPage == "transactions.php") {
                    $("#transactions-modal").modal("toggle");
                    $("#transactions-id").val(data.id);
                }
                if (action.notify) {
                    notify("success", data.icon, data.message);
                }
                if (action.redirect) {
                    window.location.replace(action.redirect);
                }
                if (action.reload) {
                    location.reload();
                }
                if (action.highlight) {
                    $.each(action.highlight, function (key, value) {
                        prefix = currentPage.split(".")[0] + "-";
                        $("#" + prefix + value).val(data[value]);
                        $("#" + prefix + value).css({"border-color": "green"}, {"box-shadow": "0 0 10px green"})
                    });
                }
                if (data.balance) {
                    $("#balance").text("Balance: " + data.balance);
                }
            },
            error: function (data) {
                var data = $.parseJSON(data.responseText)
                //if (action.notify) {
                    notify("danger", data.icon, data.message);
                //}
            }
    });
};

function form_xml(data) {
    result = "<" + "?xml version=\"1.0\" encoding=\"utf-8\"?><api>";
    result += "<currentuser>" + $('meta[name=currentUser]').attr("type") + "</currentuser>";
    $.each(data, function (key, value) {
        result +="<" + key + ">";
        if (key=="comment") {
            result += "<![CDATA[" + value + "]]>";
        } else {
            result += value;
        }
        result += "</" + key + ">";
    });
    result += "</api>";
    return result;
};

function notify(state, icon, message) {
    $.notify({icon: icon, message: message},
        {type: state, timer: 4000, placement: {from: "top", align: "center"}});
}

function validate(input, re) {
    var is_valid=re.test(input.val());
    if(is_valid) {
        input.css({"border-color": "green"}, {"box-shadow": "0 0 10px green"})
        return true;
    } else {
        input.css({"border-color": "red"}, {"box-shadow": "0 0 10px red"})
        return false;
    }
}

function fillfield(name, value) {
    console.log(value);
    $("#" + name).val(value).change();
}

var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

function generate_graph(data) {
    var dataSales = {
        labels: data.months,
        series: [data.recieved, data.sent]
    };

    var optionsSales = {
        lineSmooth: false,
        low: 0,
        high: Math.max(Math.max(...data.recieved), Math.max(...data.sent)),
        showArea: true,
        height: "245px",
        axisX: { showGrid: false,  },
        lineSmooth: Chartist.Interpolation.simple({divisor: 3}),
        showLine: false,
        showPoint: false,
    };

    var responsiveSales = [
        ['screen and (max-width: 640px)', {
            axisX: {
                labelInterpolationFnc: function (value) {
                    return value[0];
                }
            }
        }]
    ];

    Chartist.Line('#chartHours', dataSales, optionsSales, responsiveSales);
}

function send_ajax_status(data, api, action) {
    $.ajax({url: "api.php?rest",
            type: "POST",
            data: JSON.stringify({"type":"status","action":"get"}),
            contentType: "application/json; charset=utf-8",
            preocessData: true,
            dataType: "json",
            async: true,
            success: function(data) {
                $("#access80").html("OK");
                $("#access80").css({"color":"green"});
                $("#response80").html(data.code);
                $("#time80").html(data.time.toFixed(5));
                $("#diskfree").html(data.disk.free);
                $("#memfree").html(data.memory.free);
                $("#swpfree").html(data.swap.free);
                $("#diskused").html(data.disk.used);
                $("#memused").html(data.memory.used);
                $("#swpused").html(data.swap.used);
                $("#disktotal").html(data.disk.total);
                $("#memtotal").html(data.memory.total);
                $("#swptotal").html(data.swap.total);
            },
            error: function(data) {
                $("#access80").html("ERROR");
                $("#access80").css({"color":"red"});
                $("#response80").html(data.code);
                $("#time80").html(data.time.toFixed(5));
                $("#diskfree").html(data.disk.free);
                $("#memfree").html(data.memory.free);
                $("#swpfree").html(data.swap.free);
                $("#diskused").html(data.disk.used);
                $("#memused").html(data.memory.used);
                $("#swpused").html(data.swap.used);
                $("#disktotal").html(data.disk.total);
                $("#memtotal").html(data.memory.total);
                $("#swptotal").html(data.swap.total);
            }
    });
};
