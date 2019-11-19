<?php
    if (isset($_POST['Submit'])) {
        $start = $_POST['start'];
        $input = $_POST['input'];
        $input = explode(" ", $input);
        $end = $_POST['end'];
        $que = $_POST['que'];
        $dstype =  $_POST['dstype'];

        $time = 0;
        switch($dstype) {
            case "None":
                echo "Select a disk scheduling type!";
                break;
            case "1":
                $time += abs($start-$input[0]);
                for ($i=0; $i<count($input); $i++) {
                    if ($i+1 < count($input)) {
                        $time += abs($input[$i+1]-$input[$i]);                
                    }
                }
                break;
            case "2":
                //create and fill temporary array queue
                $queue = array();
                for ($i=0; $i<3; $i++) {
                    array_push($queue, array_shift($input));
                }
                //while there are requests in the queue, find the closest to the current position
                while (count($queue)>0) {
                    $closest = null;
                    for ($i=0; $i < count($queue); $i++) {
                        if ($closest === null || abs($queue[$i]-$start) < abs($closest)) {
                            $closest = $queue[$i]-$start;
                        }
                    }
                    //add time required to reach the closest position, and set new position
                    $time += abs($closest);
                    $start = $closest + $start;
                    //remove current position from queue and add new requests to queue if available
                    array_splice($queue, array_search($start, $queue), 1);
                    if ($input) {
                        array_push($queue, array_shift($input));
                    }
                }
                break;
            case "3":
                $queue = array();
                for ($i=0; $i<3; $i++) {
                    array_push($queue, array_shift($input));
                }
                $direction = 1;
                while (count($queue)>0) {
                    if ($direction == 1) {
                        for ($i=$start; $i <= $end; $i++) {
                            if (in_array($i, $queue)) {
                                $time += $i-$start;
                                $start = $i;
                                array_splice($queue, array_search($i, $queue), 1);
                                if ($input) {
                                    array_push($queue, array_shift($input));
                                }
                            }
                        }
                        $direction *= -1;
                    } 
                    if ($direction == -1) {
                        for ($i=$start; $i>0; $i--) {
                            if (in_array($i, $queue)) {
                                $time += $start-$i;
                                $start = $i;
                                array_splice($queue, array_search($i, $queue), 1);
                                if ($input) {
                                    array_push($queue, array_shift($input));
                                }
                            }
                        }
                        $direction *= -1;
                    }
                }
                break;
            
            case "4":
                $queue = array();
                for ($i=0; $i<3; $i++) {
                    array_push($queue, array_shift($input));
                }
                for ($i=$start; $i <= $end; $i++) {
                    if (in_array($i, $queue)) {
                        $time += $i-$start;
                        $i = $start;
                        array_splice($queue, array_search($start, $queue), 1);
                        if ($input) {
                            array_push($queue, array_shift($input));
                        }
                    }
                }
                break;
        }
    }
    
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>Disk Scheduling Speed Calculator</title>
        <link rel="stylesheet" href="styles.css" type="text/css">
    </head>
    <body>
        <form method="post">
            <h1>Disk Scheduling Speed Calculator</h1>
            <ul>
                <li>
                    <label for="start">Starting Position: </label>
                    <input type="text" name="start">
                    <span>Enter the starting memory location</span>
                </li>
                <li>
                    <label for="que">Queue Size: </label>
                    <input type="text" name="que">
                    <span>Enter the queue size</span>
                </li> 
                <li>
                    <label for="end">Ending Position: </label>
                    <input type="text" name="end">
                    <span>Enter the end of the disk memory</span>
                </li>
                <li>
                    <label for="input">Inputs: </label>
                    <input type="text" name="input">
                    <span>Enter the memory location inputs (separated by spaces)</span>
                </li>
                <li>
                    <label for="dstype">Type: </label>
                    <table>
                        <tr>
                            <td><input type="radio" name="dstype" value="1">FCFS</td>
                            <td><input type="radio" name="dstype" value="2">SSTF</td>
                        </tr>
                        <tr>
                            <td><input type="radio" name="dstype" value="3">SCAN</td>
                            <td><input type="radio" name="dstype" value="4">C-SCAN</td>
                        </tr>
                    </table>
                    <span>Select disk scheduling type</span>
                </li>
                <input type="submit" name="Submit" value="Submit">
                <li id="answer">
                    <p><?php echo $time ?? "", "\n"; ?> blocks</p>
                </li>
            </ul>
        </form>
        <p></p>
        

​
​
    </body>
</html>