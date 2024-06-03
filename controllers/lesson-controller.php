<?php
require_once('Controller.php');
require_once('../models/LessonModel.php');
class LessonController extends Controller {
    public function __construct() {
        $this->params = $this->parseParams();
    }

    /**
     * Inserts a lesson into the database. Does not perform the availability check by itself.
     *
     * @param integer $start_time Starting time of the lesson as a UNIX timestamp
     * @param boolean $isOnline Is the lesson online of offline
     * @param integer $duration Duration of the lesson in seconds
     * @param integer $teacher_id Teacher's ID
     * @param integer $user_id User's ID
     * @return int|false Returns last inserted lesson's ID on success, false on query or connection failure
     */
    public function insertLesson(int $start_time, bool $isOnline, int $duration, int $teacher_id, int $user_id) {
        $start_time_string = date("h:i:s", $start_time);
        $end_time_string = date("h:i:s", $start_time + $duration);
        $isOnlineInt = ($isOnline) ? 1 : 0;
        $date = date("Y-m-d", $start_time);

        $l = new Lesson;
        return $l->insertLesson($start_time_string, $end_time_string, $date, $duration, $isOnlineInt, $teacher_id, $user_id);
    }

    public function checkIfDateInSchedule(int $timestamp, int $teacher_id) {
        $l = new Lesson;
        $res = $l->checkIfDateInSchedule($timestamp, $teacher_id);
        if (gettype($res) != 'integer') {
            $response['status'] = 'error';
            $response['error'] = 'Query error';
        }
        else {
            $response['status'] = 'success';
            $response['isAvailable'] = ($res * $this->checkIfDateIsAppropriate($timestamp));
        }
        return json_encode($response);
    }

    public function checkIfDateIsAppropriate(int $timestamp){
        $today = new DateTime("today");
        $dateString = date('Y-m-d', $timestamp);
        $matchDate = new DateTime($dateString);
        $matchDate->setTime(0, 0, 0);
        $diff = $today->diff($matchDate);
        $diffDays = (integer)$diff->format( "%R%a" );

        if($diffDays < 0){
            return 0;
        }
        return 1;
    }

    public function getTimeSlots(int $timestamp, int $teacher_id) {
        $l = new Lesson;
        $timeSlots = $l->getTimeSlots($timestamp, $teacher_id);
        $takenSlots = $timeSlots['lessons'];
        $scheduleStart = $timeSlots['schedule_start'];
        $scheduleEnd = $timeSlots['schedule_end'];
        $lessons = []; // [start_time]=>[isTaken]

        usort($takenSlots, function($a, $b) {
            return $a['lesson_start'] - $b['lesson_start'];
        });

        for ($time = $scheduleStart; $time < $scheduleEnd; $time += 3600) {
            $isTaken = false;
            
            // Check if the time slot overlaps with any lesson
            foreach ($takenSlots as $lesson) {
                if (
                    ($time >= $lesson['lesson_start'] && $time < $lesson['lesson_end']) || 
                    ($time + 3600 > $lesson['lesson_start'] && $time + 3600 <= $lesson['lesson_end']) ||
                    ($time <= $lesson['lesson_start'] && $time + 3600 >= $lesson['lesson_end'])
                ) {
                    $isTaken = true;
                    break;
                }
            }
        
            // Add the time slot to the array with the 'isTaken' property
            $lessons[] = [
                'start_time' => $time,
                'isTaken' => $isTaken
            ];
        }

        return json_encode($lessons);
    }

    /**
     * Checks availability of a certain lesson.
     *
     * @param integer $teacher_id Teacher's ID
     * @param integer $start_time Starting time of the lesson as a UNIX timestamp
     * @param integer $duration Duration of the lesson in seconds
     * @return string JSON with status (error|success) and isAvailable boolean result
     */
    public function checkAvailability(int $teacher_id, int $start_time, int $duration) {
        if(!$this->checkIfDateIsAppropriate($start_time)) {
            $response['status'] = 'success';
            $response['isAvailable'] = 0;
            return json_encode($response);
        }

        $start_time_string = date("h:i:s", $start_time);
        $end_time_string = date("h:i:s", $start_time + $duration);
        $date = date("Y-m-d", $start_time);

        $l = new Lesson;
        $res = $l->checkAvailability($teacher_id, $start_time_string, $end_time_string, $date);
        if (gettype($res) != 'integer') {
            $response['status'] = 'error';
            $response['error'] = 'Query error';
        }
        else {
            $response['status'] = 'success';
            $response['isAvailable'] = $res;
        }
        return json_encode($response);
    }

    public function getTotalPrice(int $hourPrice, int $duration) {
        return $hourPrice * $duration / 3600;
    }

    public function deleteLessonDetails() {
        session_start();
        unset($_SESSION['lesson']);
    }

    public function saveLessonDetails(int $teacher_id, int $start_time, bool $isOnline, int $duration) {
        try {
            session_start();
            $_SESSION['lesson'] = [
                'teacher_id' => $teacher_id,
                'start_time' => $start_time,
                'isOnline' => $isOnline,
                'duration' => $duration
            ];
            $response['status'] = 'success';
            $response['redirect'] = '../appointment_confirm';
            return json_encode($response);
        } catch (Exception $ex) {
            $response['status'] = 'error';
            $response['error'] = $ex->getMessage();
            return json_encode($response);
        }
    }
}
?>