<?php
//exit;
include "dbConfig.php";
$acadmic_session = 8;
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
$spreadsheet = IOFactory::load("Result BS English 2016-20 Spring.xls");
$sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
echo"<pre>";
//print_r($sheetData);
echo"</pre>";
$index = 2;
$enrollment_flag=0;
$inserted_courses = array();
$teacher_for_courses = array();
$inserted_courses_dpt_ids = array();
foreach($sheetData as $row){
    echo"<pre>";
    //print_r($row);
    switch(trim($row['B'])){
        case"Course Code":
            // print_r($row);
            //echo count($row)/2;
            $course_codes_templates[] = $row['E'];
            $course_codes_templates[] = $row['G'];
            $course_codes_templates[] = $row['I'];
            $course_codes_templates[] = $row['K'];
            $course_codes_templates[] = $row['M'];
            foreach($course_codes_templates as $course_code){
                $course_code = str_replace(" ", "", $course_code);
                $course_codes_templates_clean[] = str_replace(".", "-", $course_code);
            }
           // print_r($course_codes_templates_clean);
            foreach($course_codes_templates_clean as $course_code){
                echo "<br>";
                echo $sql = "SELECT * FROM templatecourses "
                        . " WHERE templatecourse_code='$course_code' "
                        . " limit 0,1 "; 
                //`templatecourse_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                //`templatecourse_title` varchar(60) NOT NULL,
                //`templatecourse_code` varchar(15) NOT NULL,
                //`templatecourse_catalogDescription` varchar(255) DEFAULT NULL,
                //`templatecourse_creditHours` varchar(2) NOT NULL,
                //`deptID` int(10) DEFAULT NULL,
                //`templatecourse_isElective` int(1) DEFAULT '0',
                //`templatecourse_isLab` int(1) NOT NULL DEFAULT '0',
                //`templatecourse_short_title` varchar(20) DEFAULT NULL,
                //`is_active` int(1) NOT NULL DEFAULT '1',
                //`is_virtual` int(1) NOT NULL DEFAULT '0',
                $query = $db->query($sql) or die($db->error.__LINE__);
                if($query->num_rows > 0 )
                {
                    while($row_template_codes = $query->fetch_assoc()){
                            // create course for the given session
                            //print_r($row_template_codes);
                            echo "<br>";
                            echo $sql_exit_course = "SELECT course_id FROM courses "
                        . " WHERE templateCourseID='".$row_template_codes['templatecourse_id']."' and academicSessionID = '$acadmic_session' "
                        . " limit 0,1 ";
                            $query_exist_course = $db->query($sql_exit_course) or die($db->error.__LINE__);
                            //course section managment
                            if($query_exist_course->num_rows > 0 )
                            {
                                $row_course = $query_exist_course->fetch_assoc();
                                $inserted_courses[] = $row_course['course_id'];
                                
                                $inserted_courses_dpt_ids[] = $row_template_codes['deptID'];
                            }else{
                                $course_classAndSection="";
                                echo "<br>";
                                echo $create_course_query = "INSERT INTO `courses` ("
                                        . "`course_id`, `course_title`, `academicSessionID`, `course_classAndSection`, "
                                        . "`templateCourseID`, `question_paper_status`, `checked_answer_book_status`, `award_list_copy`, "
                                        . "`checked_answer_book_file_location`, `checked_answer_book_submit_datetime`, `question_paper_status_final`, `checked_answer_book_status_final`, "
                                        . "`time_slot_id`, `course_group_id`, `is_class`, `lab_course_type`, "
                                        . "`campus_id`, `reference_course_id`) "
                                        . "VALUES ("
                                        . "NULL, '".$row_template_codes['templatecourse_title']."', '$acadmic_session', '$course_classAndSection', "
                                        . "'".$row_template_codes['templatecourse_id']."', '2', '2', '2', "
                                        . "null, null, null, null, "
                                        . "'1', null, '1', '1', "
                                        . "'1', null); ";
                                    //    exit();       
                                  mysqli_query($db, $create_course_query) or die($db->error.__LINE__);
                                  $inserted_courses[] = mysqli_insert_id($db);
                                  $inserted_courses_dpt_ids[] = $row_template_codes['deptID'];
                            }
                    }
                    
                }
            }
            //$inserted_courses[] = 101;
            //$inserted_courses[] = 201;
            //$inserted_courses[] = 301;
            //$inserted_courses[] = 401;
           // $inserted_courses[] = 501;
           // $inserted_courses_dpt_ids[] = 1;
            //$inserted_courses_dpt_ids[] = 2;
           // $inserted_courses_dpt_ids[] = 3;
            //$inserted_courses_dpt_ids[] = 4;
            //$inserted_courses_dpt_ids[] = 5;
            break;
        case"Teacher Information":
            //print_r($inserted_courses);
            $course_teachers[] = $row['E'];
            $course_teachers[] = $row['G'];
            $course_teachers[] = $row['I'];
            $course_teachers[] = $row['K'];
            $course_teachers[] = $row['M'];
            foreach($course_teachers as $course_teacher){
                $course_teacher = str_replace(" ", "", $course_teacher);
                //$course_codes_templates_clean[] = str_replace(".", "-", $course_code);
            }
            //print_r($course_teachers);
            foreach($course_teachers as $course_teacher){
                echo "<br>";
                echo $sql = "SELECT * FROM teachers "
                        . " WHERE userid='$course_teacher' "
                        . " limit 0,1 "; 
                //`teacher_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                //`teacher_title` varchar(1) NOT NULL,
                //`teacher_firstname` varchar(32) NOT NULL,
                //`teacher_lastname` varchar(32) NOT NULL,
                //`deptID` int(10) NOT NULL,
                //`isVisiting` int(1) DEFAULT NULL,
                //`isActive` int(1) DEFAULT '1',
                //`isOnLeave` int(1) DEFAULT '0',
                //`userid` varchar(100) DEFAULT NULL,
                //`personal_email` varchar(50) DEFAULT NULL,
                //`teacher_middlename` varchar(32) DEFAULT NULL,
                //`campus_id` int(1) DEFAULT NULL,
                //`cnic` varchar(255) DEFAULT NULL,
                //`phone` varchar(255) DEFAULT NULL,
                
                $query = $db->query($sql) or die($db->error.__LINE__);
                if($query->num_rows > 0 )
                {
                    while($row_teacher = $query->fetch_assoc()){
                            // create course for the given session
                            //print_r($row_teacher);
                            $teacher_for_courses[]=$row_teacher['teacher_id'];
                            
                    }
                }else{
                    echo "<br>$course_teacher not found<br>";
                }
            }
            $i=0;
            foreach($teacher_for_courses as $teacher_id){
                echo "<br>";
                echo $sql_exit_teacher_allocation = "SELECT courseid FROM course_additional_teacher_allocation "
                    . " WHERE courseid='".$inserted_courses[$i]."' and teacherid = '$teacher_id' "
                    . " limit 0,1 ";
                $query_exist_teacher_allocation = $db->query($sql_exit_teacher_allocation) or die($db->error.__LINE__);
                //course section managment
                if($query_exist_teacher_allocation->num_rows == 0 )
                {
                    echo "<br>";
                    echo $create_course_query = "INSERT INTO `course_additional_teacher_allocation` ("
                                        . "`id`, `courseid`, `teacherid`) "
                                        . "VALUES (NULL, '".$inserted_courses[$i]."', '$teacher_id'); ";

                    mysqli_query($db, $create_course_query) or die($db->error.__LINE__);
                }
                $i++;
            }
            break;
        case"Section Information":
            //print_r($row);
            $course_sections[] = trim($row['E']);
            $course_sections[] = trim($row['G']);
            $course_sections[] = trim($row['I']);
            $course_sections[] = trim($row['K']);
            $course_sections[] = trim($row['M']);
            //print_r($course_sections);
            $i=0;
            foreach($course_sections as $course_section){
                
                $sql = "SELECT * FROM sections "
                        . " WHERE section_title='$course_section' "
                        . " limit 0,1 "; 
                //`section_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
                //`section_title` varchar(50) NOT NULL,
                //`deptid` int(10) unsigned DEFAULT NULL,
                //`isactive` int(1) DEFAULT NULL,
                //`is_first_half` int(1) DEFAULT '1',
                
                $query = $db->query($sql) or die($db->error.__LINE__);
                if($query->num_rows > 0 )
                {
                    while($row_section = $query->fetch_assoc()){
                            // create course for the given session
                           // print_r($row_section);
                            $section_id = $row_section['section_id'];
                            echo "<br>";
                            echo $sql_exist_section_allocation = "SELECT courseid FROM course_section_allocation "
                                . " WHERE courseid='".$inserted_courses[$i]."' and sectionid = '$section_id' "
                                . " limit 0,1 ";
                            $query_exist_section_allocation = $db->query($sql_exist_section_allocation) or die($db->error.__LINE__);
                            if($query_exist_section_allocation->num_rows == 0 )
                            {
                                echo "<br>";
                                echo $create_section_allocation_query = "INSERT INTO `course_section_allocation` ("
                                        . "`id`, `courseid`, `sectionid`) "
                                        . " VALUES ("
                                        . " NULL, '".$inserted_courses[$i]."', '$section_id');";
                                    mysqli_query($db, $create_section_allocation_query) or die($db->error.__LINE__);
                            }
                    }
                }else{
                    // we need to create the section for this course
                    
                    echo "<br>";
                    echo $create_section_query = "INSERT INTO `sections` "
                            . " (`section_id`, `section_title`, `deptid`, "
                            . " `isactive`, `is_first_half`) "
                            . " VALUES (NULL, '$course_section', '".$inserted_courses_dpt_ids[$i]."', "
                            . " '1', '1');";
                    mysqli_query($db, $create_section_query) or die($db->error.__LINE__);
                    //alocate newly created section to course
                    $section_id = mysqli_insert_id($db);
                    //
                    echo "<br>";
                    echo $sql_exist_section_allocation = "SELECT courseid FROM course_section_allocation "
                        . " WHERE courseid='".$inserted_courses[$i]."' and sectionid = '$section_id' "
                        . " limit 0,1 ";
                    $query_exist_section_allocation = $db->query($sql_exist_section_allocation) or die($db->error.__LINE__);
                    if($query_exist_section_allocation->num_rows == 0 )
                    {
                        echo "<br>";
                        echo $create_section_allocation_query = "INSERT INTO `course_section_allocation` ("
                                . "`id`, `courseid`, `sectionid`) "
                                . " VALUES ("
                                . " NULL, '".$inserted_courses[$i]."', '$section_id');";
                            mysqli_query($db, $create_section_allocation_query) or die($db->error.__LINE__);
                    }
                    ///
                    
                }
                $i++;
            }
            
            
            break;
        case"Credit Hours-1":
            print_r($row);
            break;
        case"Subject-1":
            print_r($row);
            break;
        case"Name":
           // print_r($row);
            //for($i=$index;$i<=count($sheetData);$i++){
               // print_r($sheetData[$index]);
           // }
            //from here we can extract roll numbers and results in remainingn rows 
            //exit;
            echo $index;
            $enrollment_flag = 1;
            break;
        default:
            if($enrollment_flag){
                //echo "default<br>";
                $enrollment_data[] =  $row['A'];
                $enrollment_marks_data['FM']['E'][] =  $row['E'];
                $enrollment_marks_data['FM']['G'][] =  $row['G'];
                $enrollment_marks_data['FM']['I'][] =  $row['I'];
                $enrollment_marks_data['FM']['K'][] =  $row['K'];
                $enrollment_marks_data['FM']['M'][] =  $row['M'];
               // print_r($row);
            }
    }
    $index++;
    echo"</pre>";
}
echo"<pre>";
//print_r($enrollment_marks_data);
echo"</pre>";
foreach($enrollment_marks_data as $key=>$data){
    switch($key){
        case"E":
            print_r($data); 
            break;
        case"G":
            print_r($data);
            break;
        case"I":
            print_r($data);
            break;
        case"K":
            print_r($data);
            break;
        case"M":
            print_r($data);
            break;
    }
  
}
// first we need to manage enrollment
$student_ids = array();
foreach($enrollment_data as $roll_number){
    //print($roll_number)."<br>";
    $sql = "SELECT student_id FROM students "
                        . " WHERE regNumber='$roll_number' "
                        . " limit 0,1;";
    $query = $db->query($sql) or die($db->error.__LINE__);
    if($query->num_rows > 0 )
    {
        $row_student = $query->fetch_assoc();
        //print_r($row_student);
        $student_ids["$roll_number"] = $row_student['student_id'];
    }
}
//print_r($student_ids);
echo "<br>";
foreach($student_ids as $student_id){
// fetch student id against enrollment number 
// students, student_id
    foreach($inserted_courses as $course_id){
        echo "<br>";
        echo $sql_exist_course_enrollment = "SELECT courseID FROM enrollments "
            . " WHERE courseID='".$course_id."' and studentID = '$student_id' "
            . " limit 0,1 ";
        $query_exist_teacher_allocation = $db->query($sql_exist_course_enrollment) or die($db->error.__LINE__);
        if($query_exist_teacher_allocation->num_rows == 0 )
        {
            $query_exist_course_enrollment = $db->query($sql_exist_course_enrollment) or die($db->error.__LINE__);
            echo $sql_insert_enrollment = "INSERT INTO `enrollments` ("
                    . " `enrollment_id`, `studentID`, `courseID`, `enrollment_status`) "
                    . " VALUES (NULL, '$student_id', '$course_id', '0');";
            echo "<br>";
            mysqli_query($db, $sql_insert_enrollment) or die($db->error.__LINE__);
        }
    }
 
}
// second manage gradebook