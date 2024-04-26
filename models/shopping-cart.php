<?php
    require_once('list-item.php');
    class cart {
        public $items = array(); // an array containing item titles and their quantities as key-value pairs

        public function __construct(array $array) {
            $this->items = $array;
        }

        public function increment_item($name) {
            $this->items[$name]++;
        }

        public function set_quantity($name, $quantity) {
            if ($this->items[$name] >= 0) {
                $this->items[$name] = $quantity;
            }
        }

        public function decrement_item($name) {
            if ($this->items[$name] > 0) {
                $this->items[$name]--;
            }
        }

        public function delete_item($name) {
            unset($this->items[$name]);
        }

        public function add_item($name, $quantity = 1) {
            array_push($this->items, array($name=>$quantity));
        }

        public function calculate_total_price() {
            foreach($this->items as $i) {

            }
        }

        public function clear() {
            $this->items = array();
        }
    }
?>