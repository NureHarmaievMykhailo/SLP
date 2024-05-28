<?php
$root = __DIR__ . "/..";
require_once("$root/models/MaterialCategoryModel.php");
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
    private $material_view_path = "material?id=";
    private $all_materials_view_path = "learning_materials_all?category=";
    private $style_path = "../public/learning-material-block.css";

    public function __construct($id, $title, $short_info, $categories = array(), $offset = "") {
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
                    <a class=\"link_hidden paragraph full_text\" href=\"$this->material_view_path$this->id\">Повний текст</a>
                </div>

                <div class=\"category_div\">";
                foreach($this->categories as $category) {
                    echo "<a class=\"button button_category\" href=\"$this->all_materials_view_path{$category->getId()}\">{$category->getCategoryName()}</a>";
                }
                echo "</div>

            </div>";
    }

    /**
     * Intended for rendering in a shortened manner. For example, on a front page.
     */
    public function render_small() {
        echo "<link href=\"$this->style_path\" type=\"text/css\" rel=\"stylesheet\"/>";
        echo "
            <div class=\"block block_small\" style=\"$this->offset\">
                <div class=\"header header_material\" style=\"padding-left: 20px;\">
                    <h><span>$this->title</span></p>
                </div>

                <div class=\"paragraph paragraph_material\" style=\"padding-left: 20px;padding-right: 20px;padding-bottom: 10px;\">
                    <p><span>$this->short_info</span></p>
                </div>

                <div class=\"details_div\">
                    <a class=\"button details_button\" href=\"$this->material_view_path$this->id\">Детальніше</a>
                </div>
            </div>";
    }
}
?>