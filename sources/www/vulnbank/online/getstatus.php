<?php
include "inc/db.php";

header('Content-Type: application/json');

$args = json_decode(file_get_contents('php://input'), true);

$type = $args["type"];
$action = $args["action"];

switch ($type) {
    case "status":
        switch ($action) {
            case "get":
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://localhost");
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		$rt = curl_exec($ch);
		$info = curl_getinfo($ch);
        $disk = shell_exec("df -h --total | grep total");
        $disk_parts = preg_split('/\s+/', $disk);
        $memory = shell_exec("free -m | grep Mem");
        $memory_parts = preg_split('/\s+/', $memory);
        $swap = shell_exec("free -m | grep Swap");
        $swap_parts = preg_split('/\s+/', $swap);
        if ($info["http_code"] == "200") {
            die(json_encode(array("status" => "success",
                            "code" => $info["http_code"],
                            "time" => $info["total_time"],
                            "disk" => array("total" => $disk_parts[1],
                                            "free" => $disk_parts[3],
                                            "used" => $disk_parts[2]),
                            "memory" => array("total" => $memory_parts[1],
                                            "free" => $memory_parts[3],
                                            "used" => $memory_parts[2]),
                            "swap" => array("total" => $swap_parts[1],
                                            "free" => $swap_parts[3],
                                            "used" => $swap_parts[2]),
                            ))
            );
        } else {
            header(':', true, 400);
            die(json_encode(array("status" => "error",
                            "code" => $info["http_code"],
                            "time" => $info["total_time"],
                            "disk" => array("total" => $disk_parts[1],
                                            "free" => $disk_parts[3],
                                            "used" => $disk_parts[2]),
                            "memory" => array("total" => $memory_parts[1],
                                            "free" => $memory_parts[3],
                                            "used" => $memory_parts[2]),
                            "swap" => array("total" => $swap_parts[1],
                                            "free" => $swap_parts[3],
                                            "used" => $swap_parts[2]),
                        ))
            );
        }
    }
}
?>
