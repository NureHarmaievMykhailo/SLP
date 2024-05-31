<?php
/**
 * Respresents a teacher block.
 * 
 * Represents a teacher block to be displayed in a list. Requires public/styles.css to apply styles correctly.
 */
class TeacherBlock {
    private $offset;
    private $id;
    private $teacher_name;
    private $short_info;
    private $teacher_price;
    private $teacher_image_uri;
    private $style_path = "../public/teacher-block.css";
    private $teacher_view_path = "teacher?id=";

    public function __construct($offset, $id, $teacher_name, $short_info, $teacher_price, $teacher_image_uri) {
        $this->offset = $offset;
        $this->id = $id;
        $this->teacher_name = $teacher_name;
        $this->short_info = $short_info;
        $this->teacher_price = $teacher_price;
        $this->teacher_image_uri = $teacher_image_uri;
    }

    public function render() {
        echo "<link href=\"$this->style_path\" type=\"text/css\" rel=\"stylesheet\"/>";
        echo "
            <div class=\"block teacher_block\" style=\"$this->offset\">
                <div class=\"teacher_image_div\">
                    <img class=\"teacher_image teacher_pfp\" src=\"$this->teacher_image_uri\"/>
                    <div class=\"teacher_price_div\">
                        <p><span>" . $this->teacher_price . "грн / година</span></p>
                    </div>
                </div>

                <div class=\"teacher_info_div\">
                    <p class=\"header\"><span>$this->teacher_name</span></p>
                    <p class=\"paragraph \"><span>$this->short_info</span></p>
                </div>

                <div class=\"teacher_buttons_div\">
                    <a href=\"$this->teacher_view_path$this->id\" class=\"button btn_teacher btn_teacher_details\"><p><span>Детальніше</span></p></a>
                    <button class=\"button btn_teacher btn_teacher_appointment\"><p><span>Назначити заняття</span></p></button>
                </div>

            </div>";
    }
}
?>