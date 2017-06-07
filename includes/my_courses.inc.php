<?php
date_default_timezone_set("America/Puerto_Rico");


$day = date("D");


if($_SESSION['loged_user']['role_idx'] == 3){

    $idx = $_SESSION['loged_user']['idx'];
    $i = 0;
    $sqlquery = "SELECT st.schedule_id, mgr.grade_descr, mgro.group_descr, mc.course_descr, wd.week_day_descr,
                     dh.day_hour_descr, mu.first_name, mu.last_name, mu.second_surname, st.created_date, sp.start_date, sp.end_date,
                     st.grade_id, st.group_id, st.course_id
                     FROM schedules_teacher st
                     INNER JOIN master_grade mgr ON mgr.grade_id = st.grade_id
                     INNER JOIN master_group mgro ON mgro.group_id = st.group_id
                     INNER JOIN master_course mc ON mc.course_id = st.course_id
                     INNER JOIN master_week_day wd ON wd.week_day_id = st.week_day_id
                     INNER JOIN master_day_hour dh ON dh.day_hour_id = st.day_hour_id
                     INNER JOIN master_users mu ON mu.idx = st.teacher_id
                     INNER JOIN scholar_period sp ON sp.active = 1
                     WHERE st.created_date > sp.start_date AND st.created_date < sp.end_date AND st.teacher_id = $idx AND wd.week_day_descr LIKE '%$day%'";

    if(!$results = $objmydbcon->get_result_set($sqlquery)){
        return false;
    }else if(mysqli_num_rows($results) > 0){

        $courses_qty = mysqli_num_rows($results);
        $i = 1;
        while($rs = mysqli_fetch_array($results)){
            extract($rs);

            if(($i % 2) != 0){

                $course_cards .= "
                                    <div class='row'>
                                        <div class='col s12 m6'>
                                            <div class='card blue-grey darken-1'>
                                                <div class='card-content white-text'>
                                                    <span class='card-title'>$course_descr - $day_hour_descr - $group_descr</span>
                                                    <p>
                                                        Click to view your students in this class and start the direct communication with the parents and scholar council!
                                                    </p>
                                                </div>
                                                <div class='card-action'>
                                                    <a href='display_page.php?tpl=course_details&grade_id=$grade_id&group_id=$group_id&course_id=$course_id'>Click to Enter!</a>
                                                </div>
                                            </div>
                                        </div>";

            }else{

                $course_cards .= "         
                                        <div class='col s12 m6'>
                                            <div class='card blue-grey darken-1'>
                                                <div class='card-content white-text'>
                                                    <span class='card-title'>$course_descr - $day_hour_descr - $group_descr</span>
                                                    <p>
                                                        Click to view your students in this class and start the direct communication with the parents and scholar council!
                                                    </p>
                                                </div>
                                                <div class='card-action'>
                                                    <a href='display_page.php?tpl=course_details&grade_id=$grade_id&group_id=$group_id&course_id=$course_id'>Click to Enter!</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>";

            }
            $i++;
        }

        if(($i % 2) != 0){
            $course_cards .= "</div>";
        }
        $objtemplate->set_content("courses_cards", $course_cards);

    }else{

        return "No records found!";

    }
}else{
    header("location: display_page.php?tpl=events");
}