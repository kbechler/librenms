<?php

$storage   = dbFetchCell('select count(*) from storage WHERE device_id = ?', array($device['device_id']));
$diskio    = dbFetchCell('select count(*) from ucd_diskio WHERE device_id = ?', array($device['device_id']));
$mempools  = dbFetchCell('select count(*) from mempools WHERE device_id = ?', array($device['device_id'])) + count_mib_mempools($device);
$processor = dbFetchCell('select count(*) from processors WHERE device_id = ?', array($device['device_id'])) + count_mib_processors($device);

$charge       = dbFetchCell("select count(*) from sensors WHERE sensor_class='charge' AND device_id = ?", array($device['device_id']));
$temperatures = dbFetchCell("select count(*) from sensors WHERE sensor_class='temperature' AND device_id = ?", array($device['device_id']));
$humidity     = dbFetchCell("select count(*) from sensors WHERE sensor_class='humidity' AND device_id = ?", array($device['device_id']));
$fans         = dbFetchCell("select count(*) from sensors WHERE sensor_class='fanspeed' AND device_id = ?", array($device['device_id']));
$volts        = dbFetchCell("select count(*) from sensors WHERE sensor_class='voltage' AND device_id = ?", array($device['device_id']));
$current      = dbFetchCell("select count(*) from sensors WHERE sensor_class='current' AND device_id = ?", array($device['device_id']));
$freqs        = dbFetchCell("select count(*) from sensors WHERE sensor_class='frequency' AND device_id = ?", array($device['device_id']));
$runtime      = dbFetchCell("select count(*) from sensors WHERE sensor_class='runtime' AND device_id = ?", array($device['device_id']));
$power        = dbFetchCell("select count(*) from sensors WHERE sensor_class='power' AND device_id = ?", array($device['device_id']));
$dBm          = dbFetchCell("select count(*) from sensors WHERE sensor_class='dBm' AND device_id = ?", array($device['device_id']));
$states       = dbFetchCell("select count(*) from sensors WHERE sensor_class='state' AND device_id = ?", array($device['device_id']));
$load         = dbFetchCell("select count(*) from sensors WHERE sensor_class='load' AND device_id = ?", array($device['device_id']));
$signal       = dbFetchCell("select count(*) from sensors WHERE sensor_class='signal' AND device_id = ?", array($device['device_id']));

unset($datas);
$datas[] = 'overview';
if ($processor) {
    $datas[] = 'processor';
}

if ($mempools) {
    $datas[] = 'mempool';
}

if ($storage) {
    $datas[] = 'storage';
}

if ($diskio) {
    $datas[] = 'diskio';
}

if ($charge) {
    $datas[] = 'charge';
}

if ($temperatures) {
    $datas[] = 'temperature';
}

if ($humidity) {
    $datas[] = 'humidity';
}

if ($fans) {
    $datas[] = 'fanspeed';
}

if ($volts) {
    $datas[] = 'voltage';
}

if ($freqs) {
    $datas[] = 'frequency';
}

if ($runtime) {
    $datas[] = 'runtime';
}

if ($current) {
    $datas[] = 'current';
}

if ($power) {
    $datas[] = 'power';
}

if ($dBm) {
    $datas[] = 'dbm';
}

if ($states) {
    $datas[] = 'state';
}

if ($load) {
    $datas[] = 'load';
}

if ($signal) {
    $datas[] = 'signal';
}

$type_text['overview']    = 'Overview';
$type_text['charge']      = 'Battery Charge';
$type_text['temperature'] = 'Temperature';
$type_text['humidity']    = 'Humidity';
$type_text['mempool']     = 'Memory';
$type_text['storage']     = 'Disk Usage';
$type_text['diskio']      = 'Disk I/O';
$type_text['processor']   = 'Processor';
$type_text['voltage']     = 'Voltage';
$type_text['fanspeed']    = 'Fanspeed';
$type_text['frequency']   = 'Frequency';
$type_text['runtime']     = 'Runtime';
$type_text['current']     = 'Current';
$type_text['power']       = 'Power';
$type_text['dbm']         = 'dBm';
$type_text['state']       = 'State';
$type_text['load']        = 'Load';
$type_text['signal']      = 'Signal';

$link_array = array(
    'page'   => 'device',
    'device' => $device['device_id'],
    'tab'    => 'health',
);

print_optionbar_start();

echo "<span style='font-weight: bold;'>Health</span> &#187; ";

if (!$vars['metric']) {
    $vars['metric'] = 'overview';
}

unset($sep);
foreach ($datas as $type) {
    echo $sep;

    if ($vars['metric'] == $type) {
        echo '<span class="pagemenu-selected">';
    }

    echo generate_link($type_text[$type], $link_array, array('metric' => $type));
    if ($vars['metric'] == $type) {
        echo '</span>';
    }

    $sep = ' | ';
}

print_optionbar_end();

if (is_file('pages/device/health/'.mres($vars['metric']).'.inc.php')) {
    include 'pages/device/health/'.mres($vars['metric']).'.inc.php';
} else {
    foreach ($datas as $type) {
        if ($type != 'overview') {
            $graph_title         = $type_text[$type];
            $graph_array['type'] = 'device_'.$type;

            include 'includes/print-device-graph.php';
        }
    }
}

$pagetitle[] = 'Health';
