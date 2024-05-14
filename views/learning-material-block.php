<?php
/**
 * Respresents a learning material block.
 * 
 * Represents a learning material block to be displayed in a list. Can be displayed both in normal and small mode.
 * Requires public/styles.css to apply styles correctly.
 */
class LearningMaterialBlock {
    private $offset;
    private $id;
    private $title;
    private $short_info;
    private $categories; // array with material's categories, defaults to an empty array
    private $style_path = "../public/learning-material-block.php";

    public function __construct($offset, $id, $title, $short_info, $categories = array()) {
        $this->offset = $offset;
        $this->id = $id;
        $this->title = $title;
        $this->short_info = $short_info;
        $this->categories = $categories;
    }

    /**
     * Intended for rendering in a general list view.
     */
    public function render_normal() {
        echo "<link href=\"$this->style_path\" type=\"text/css\" rel=\"stylesheet\"/>";
        echo "
            <div class=\"block material_block\" style=\"$this->offset\">
                <div class=\"header header_material\">
                    <h><span>$this->title</span></p>
                </div>

                <div class=\"paragraph paragraph_material\">
                    <p><span>$this->short_info</span></p>
                </div>

                <div class=\"full_text_div\">
                    <p><span><a class=\"link_hidden paragraph full_text\" href=\"<!-- TODO: REDIRECT USER TO MATERIAL'S PAGE -->\">Повний текст</a></span></p>
                </div>

                <!--TODO: find a way to ouput categories here
                <div class=\"category_div\">
                    <a class=\"button button_category\" href=\"< TODO: REDIRECT USER TO CATEGORY'S PAGE >\"><p><span>Готуємося до екзаменів</span></p></a>
                </div>
                -->
            </div>";
    }

    /**
     * Intended for rendering in a shortened manner. For example, on a front page.
     */
    public function render_small() {
        echo "<link href=\"$this->style_path\" type=\"text/css\" rel=\"stylesheet\"/>";
        echo "
            <div class=\"block material_block\" style=\"$this->offset\">
                <div class=\"header header_material\" style=\"padding-left: 20px;\">
                    <h><span>$this->title</span></p>
                </div>

                <div class=\"paragraph paragraph_material\" style=\"padding-left: 20px;padding-right: 20px;padding-bottom: 10px;\">
                    <p><span>$this->short_info</span></p>
                </div>

                <div class=\"details_div\">
                    <a class=\"button details_button\" href=\"<!-- TODO: REDIRECT USER TO MATERIAL'S PAGE -->\"><p><span>Детальніше</span></p></a>
                </div>
            </div>";
    }
}
?>