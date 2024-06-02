<?php
require_once('Model.php');
class Lesson extends Model
{
    private $id;
    private $start_time;
    private $end_time;
    private $date;
    private $isOnline;
    private $total_sum;
    private $teacher_id;
    private $user_id;
    protected $table = "lesson";

    protected function sqlResponseToJson($sql_result)
    {
        while ($row = $sql_result->fetchassoc())
            $lessonData = [
                'id' => $row['id'],
                'start_time' => $row['start_time'],
                'end_time' => $row['end_time'],
                'date' => $row['date'],
                'isOnline' => $row['isOnline'],
                'total_sum' => $row['total_sum'],
                'teacher_id' => $row['teacher_id'],
                'user_id' => $row['user_id']
            ];
        return json_encode($lessonData);
    }

    public function getByUserId(int $user_id)
    {
        if ($user_id < 0) {
            throw new InvalidArgumentException("User ID must be greater than 0.");
        }

        $sql = "SELECT * FROM lesson WHERE user_id = ?";
        $sql_result = $this->mysqliParametrizedQuery($sql, $user_id);

        while ($row = $sql_result->fetchassoc()) {
                $this->setId($row['id']);
                $this->setStartTime($row['start_time']);
                $this->setEndTime($row['end_time']);
                $this->setDate($row['date']);
                $this->setIsOnline($row['isOnline']);
                $this->setTotalSum($row['total_sum']);
                $this->setTeacherId($row['teacher_id']);
                $this->setUserId($row['']);
        }
    }

    public function getByUserIdAsJson(int $user_id) {
        if ($user_id < 0) {
            throw new InvalidArgumentException("User ID must be greater than 0.");
        }
        $sql = "SELECT * FROM lesson WHERE user_id = ?";
        $sql_result = $this->mysqliParametrizedQuery($sql, $user_id);
        return $this->sqlResponseToJson($sql_result);
    }

    public function checkIfDateInSchedule(int $timestamp, int $teacher_id){
        $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, __DATABASE__);
        if ($mysqli->connect_error) {
            return false;
        }
        $dateString = date("Y-m-d", $timestamp);
        $sql = "SELECT COUNT(sc.id) AS isInSchedule FROM schedule sc WHERE FIND_IN_SET(DAYNAME(?), sc.days) > 0 AND teacher_id = ?";

        if (!$stmt = $mysqli->prepare($sql)) {
            return false;
        }

        if (!$stmt->bind_param("si", $dateString, $teacher_id)) {
            return false;
        }

        $stmt->execute();
        $res = $stmt->get_result();

        $stmt->close();
        $mysqli->close();
        while ($row = $res->fetch_assoc()) {
           if(intval($row['isInSchedule']) > 0) {
                return 1;
           }
        }
        return 0;
    }

    /**
     * Gets taken time slots for a particular teacher on a particular day.
     *
     * @param integer $timestamp The timestamp in UNIX format
     * @param integer $teacher_id Teacher's ID
     * @return array|false Returns an associative array of UNIX timestamps on success, false on failure
     */
    public function getTimeSlots(int $timestamp, int $teacher_id) {
        $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, __DATABASE__);
        if ($mysqli->connect_error) {
            return false;
        }
        $dateString = date("Y-m-d", $timestamp);
        $sql = "SELECT l.start_time AS lesson_start,
                    l.end_time AS lesson_end,
                    sc.start_time AS schedule_start,
                    sc.end_time AS schedule_end
                FROM lesson l
                join schedule sc on sc.teacher_id = l.teacher_id
                where l.teacher_id = ? and l.date = ?;";
        if (!$stmt = $mysqli->prepare($sql)) {
            return false;
        }

        if (!$stmt->bind_param("is", $teacher_id, $dateString)) {
            return false;
        }

        $stmt->execute();
        $sql_result = $stmt->get_result();

        $stmt->close();
        $mysqli->close();

        $result = ['lessons'=>[]];
        // We process the first row explicitly to avoid calling
        // strtotime($row['schedule_start']) on each iteration.
        if ($first_row = $sql_result->fetch_assoc()) {
            $result['schedule_start'] = strtotime($first_row['schedule_start']);
            $result['schedule_end'] = strtotime($first_row['schedule_end']);
        
            // Process the first row
            $lesson = [];
            $lesson['lesson_start'] = strtotime($first_row['lesson_start']);
            $lesson['lesson_end'] = strtotime($first_row['lesson_end']);
            array_push($result['lessons'], $lesson);
        
            // Process remaining rows
            while ($row = $sql_result->fetch_assoc()) {
                $lesson = [];
                $lesson['lesson_start'] = strtotime($row['lesson_start']);
                $lesson['lesson_end'] = strtotime($row['lesson_end']);
                array_push($result['lessons'], $lesson);
            }
        }
        return $result;
    }

    /**
     * Checks availability of a certain lesson. Returns 0 either if lesson doesn't fit teacher's schedule or the time slot is already taken.
     *
     * @param integer $teacher_id Teacher's ID
     * @param string $start_time Lesson's start time in 'HH:MM::SS' format
     * @param string $end_time Lesson's end time in 'HH:MM::SS' format
     * @param string $date Lesson's date in 'YYYY-MM-DD' format
     * @return int|false Returns false on connection error, 1 if the lesson is available, 0 if the lesson is not available
     */
    public function checkAvailability(int $teacher_id, string $start_time, string $end_time, string $date)
    {
        $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, __DATABASE__);
        if ($mysqli->connect_error) {
            return false;
        }
        $sql =
            "SELECT
            IF(
                (
                SELECT COUNT(sc.id)
                    FROM schedule sc
                    WHERE sc.teacher_id = ?
                    AND FIND_IN_SET(DAYNAME(?), sc.days) > 0
                    AND sc.start_time <= ?
                    AND sc.end_time >= ?
                ) = 1
                AND 
                (
                SELECT COUNT(l.id)
                    FROM lesson l
                    WHERE l.teacher_id = ?
                    AND l.date = ? 
                    AND (l.start_time < ? AND l.end_time > ? )
                ) = 0,
                1,
                0
            ) AS is_available;
        ";

        if (!$stmt = $mysqli->prepare($sql)) {
            return false;
        }

        if (!$stmt->bind_param("isssisss", $teacher_id, $date, $start_time, $end_time, $teacher_id, $date, $end_time, $start_time)) {
            return false;
        }

        $stmt->execute();
        $sql_res = $stmt->get_result();

        $stmt->close();
        $mysqli->close();

        while ($row = $sql_res->fetch_assoc()) {
            $res = intval($row['is_available']);
        }
        return $res;
    }

    public function insertLesson(string $start_time, string $end_time, string $date, int $isOnline, int $teacher_id, int $user_id)
    {
        /* $lessonData = [
            'start_time' => $start_time,
            'end_time' => $end_time,
            'date' => $date,
            'isOnline' => $isOnline,
            'teacher_id' => $teacher_id,
            'user_id' => $user_id
        ];
        return $this->insert($lessonData, __DATABASE__); */

        $sql = "INSERT INTO lesson (start_time, end_time, date, isOnline, total_sum, teacher_id, user_id) VALUES
                (?, ?, ?, ?, (SELECT price FROM teacher WHERE id = ?), ?, ?);";
        $mysqli = new mysqli(__HOSTNAME__, __USERNAME__, __PASSWORD__, __DATABASE__);
        if ($mysqli->connect_error) {
            return false;
        }

        if(!$stmt = $mysqli->prepare($sql)) {
            return false;
        }

        if(!$stmt->bind_param("sssiiii", $start_time, $end_time, $date, $isOnline, $teacher_id, $teacher_id, $user_id)) {
            return false;
        }

        $stmt->execute();
        $lastInserted = $mysqli->insert_id;

        $stmt->close();
        $mysqli->close();

        return $lastInserted;
    }

    // Getter and setter methods for id
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    // Getter and setter methods for start_time
    public function getStartTime()
    {
        return $this->start_time;
    }

    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
    }

    // Getter and setter methods for end_time
    public function getEndTime()
    {
        return $this->end_time;
    }

    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;
    }

    // Getter and setter methods for date
    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    // Getter and setter methods for isOnline
    public function getIsOnline()
    {
        return $this->isOnline;
    }

    public function setIsOnline($isOnline)
    {
        $this->isOnline = $isOnline;
    }

    // Getter and setter methods for total_sum
    public function getTotalSum()
    {
        return $this->total_sum;
    }

    public function setTotalSum($total_sum)
    {
        $this->total_sum = $total_sum;
    }

    // Getter and setter methods for teacher_id
    public function getTeacherId()
    {
        return $this->teacher_id;
    }

    public function setTeacherId($teacher_id)
    {
        $this->teacher_id = $teacher_id;
    }

    // Getter and setter methods for user_id
    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
}
