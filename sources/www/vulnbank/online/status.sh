#!/bin/bash
echo "Content-type: text/html"
echo ""

cat << EOF
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="api" type="rest">
	<link rel="icon" type="image/png" href="../assets/img/favicon.ico">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>VulnBank ltd.</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name="viewport" />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="../assets/css/animate.min.css" rel="stylesheet"/>

    <!--  Light Bootstrap Table core CSS    -->
    <link href="../assets/css/light-bootstrap-dashboard.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" media="screen" href="../assets/css/bootstrap.min.css" />
    <link href="../assets/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="../assets/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="../assets/css/dataTables.bootstrap.min.css" rel="stylesheet">


    <!--     Fonts and icons     -->
    <link href="../assets/css/font-awesome.min.css" rel="stylesheet">
    <link href='../assets/css/roboto.css' rel="stylesheet" type="text/css">
    <link href="../assets/css/pe-icon-7-stroke.css" rel="stylesheet" />

</head>
<body>
<div class="wrapper">
        <div class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-2">
                            <div class="header">
                                <h4 class="title">System State</h4>
EOF
echo -n "<p>`date`</p>"
cat << EOF
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table id="history" class="table table-hover table-striped">
                                    <thead>
                                        <th>Service</th>
                                        <th>Status</th>
                                    </thead>
                                    <tbody>
EOF

for service in apache php-fpm mysql ssh
do
        if ps ax | grep -v grep | grep $service > /dev/null
        then echo -n "<tr><td>$service</td><td style=\"color:green\"><b>running</b></td></tr>"
        else echo -n "<tr><td>$service</td><td style=\"color:red\"><b>not running</b></td></tr>"; fi
done

cat << EOF
                                    </tbody>
                                </table>
                        </div>
                    </div>

                    <div class="col-md-4">
                            <div class="header">
                                <h4 class="title">Resources</h4>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table id="history" class="table table-hover table-striped">
                                    <thead>
					<th>Port</th>
                                        <th>Accessibility</th>
                                        <th>Response</th>
                                        <th>Time</th>
                                    </thead>
                                    <tbody>
EOF

echo "<tr><td><b>80</b></td>"
curl -s -I localhost | grep HTTP | awk '{print "<td>"$3"</td><td>"$2" "$3"</td><td>"}'
curl -w "time_total %{time_total}" -s localhost | grep time_total | awk '{print $2"</td></tr>"}'
echo "</td></tr>"
echo "<tr><td><b>443</b></td>"
curl -s -I https://localhost -k | grep HTTP | awk '{print "<td>"$3"</td><td>"$2" "$3"</td><td>"}'
curl -w "time_total %{time_total}" -s https://localhost -k | grep time_total | awk '{print $2"</td></tr>"}'
echo "</td></tr>"
echo "<tr><td><b>8080</b></td>"
curl -s -I localhost:8080 | grep HTTP | awk '{print "<td>"$3"</td><td>"$2" "$3"</td><td>"}'
curl -w "time_total %{time_total}" -s localhost:8080 | grep time_total | awk '{print $2"</td></tr>"}'
echo "</td></tr>"
echo "<tr><td><b>8081</b></td>"
curl -s -I localhost:8081 | grep HTTP | awk '{print "<td>"$3"</td><td>"$2" "$3"</td><td>"}'
curl -w "time_total %{time_total}" -s localhost:8081 | grep time_total | awk '{print $2"</td></tr>"}'
echo "</td></tr>"
echo "<tr><td><b>8082</b></td>"
curl -s -I localhost:8082 | grep HTTP | awk '{print "<td>"$3"</td><td>"$2" "$3"</td><td>"}'
curl -w "time_total %{time_total}" -s localhost:8082 | grep time_total | awk '{print $2"</td></tr>"}'
echo "</td></tr>"

cat << EOF
                                    </tbody>
                                </table>
                            </div>
                </div>
                    <div class="col-md-4">
                            <div class="header">
                                <h4 class="title">Resources</h4>
                            </div>
                            <div class="content table-responsive table-full-width">
                                <table id="history" class="table table-hover table-striped">
                                    <thead>
					<th/>
                                        <th>Used</th>
                                        <th>Free</th>
                                        <th>Total</th>
                                        <th>Percentage</th>
                                    </thead>
                                    <tbody>
EOF

df -h --total | awk  ' /total/ { print "<tr><td><b>Disk</b></td><td>"$3"</td><td>"$4"</td><td>"$2"</td><td>"$5"</td></tr>"}'
free -m | grep "Mem" | awk '{print "<tr><td><b>Memory</b></td><td>"$3"M</td><td>"$4"M</td><td>"$2"M</td><td/></tr>"}'

cat << EOF
                                    </tbody>
                                </table>
                            </div>
                </div>
            </div>
        </div>


    </div>
</div>


</body>
</html>
EOF
