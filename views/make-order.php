<?php
class MakeOrderBlock {
    private $offset;
    private $total_sum;
    private $style_path = "../public/make-order.css";
    public function __construct($offset, $total_sum)
    {
        $this->total_sum = $total_sum;
        $this->offset = $offset;
    }
    public function render() {
        echo "<link href=\"$this->style_path\" type=\"text/css\" rel=\"stylesheet\"/>";
        echo "
        <div class=\"block make_order_block\" style=\"$this->offset\">
            <div class=\"header \">
                <p class=\"header_make_order\"><span>Загальна сума замовлення:</span></p>
                <p id=\"total_cost\"><span>$this->total_sum</span></p>
            </div>
            <div class=\"block_button_make_order\">
                <button class=\"button button_make_order\" onclick=\"makeOrder()\"><p class=\"button_make_order_text\">Оформити замовлення</p></button>
            </div>
        </div>";
    }
}
?>