<?php
// This file is part of Moodle Course Rollover Plugin
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package     local_attendance
 * @author      Prgathiswaran
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @var stdClass $plugin
 */

 require_once(__DIR__.'/../../config.php'); 

 echo $OUTPUT->header();
    echo $OUTPUT->heading('Chart Example');
//  $sales = new core\chart_series('Sales',[100,200,300,400,500,600,700,800,900,1000]);
// $expenses = new core\chart_series('Expenses',[50,100,150,200,250,300,350,400,450,500]);
// $labels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct'];
// $chart = new core\chart_pie();
// // $chart = new core\set_smooth(true);
// // $chart->add_series($sales);
// $chart->add_series($expenses);
// $chart->set_labels($labels);
// echo $OUTPUT->render($chart);

// $xaxis = new chart_axis();
// $axis->set_stepsize(5);
$chart = new \core\chart_pie(); // Create a bar chart instance.
$chart -> set_doughnut(true);

$series1 = new \core\chart_series('Series 1 (Bar)', [1000, 1170, 660, 1030]);
$series2 = new \core\chart_series('Series 2 (Line)', [400, 460, 1120, 540]);
// $series2->set_type(\core\chart_series::TYPE_LINE); // Set the series type to line chart.
$chart->add_series($series2);
// $chart->add_series($series1);
$chart->set_labels(['2004', '2005', '2006', '2007']);
echo $OUTPUT->render($chart);

echo $OUTPUT->footer();
