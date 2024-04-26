<?php
    require_once('list-item.php');
    /**
     * Represents shopping cart.
     * 
     * Represents shopping cart. Contains an associative array of (ListItem->title => ListItem) key-value pairs. Contains methods for processing common shopping cart operations.
     */
    class Cart {
        public $items = array(); // an array containing ListItem titles and their quantities as key-value pairs

        public function __construct(array $array) {
            $this->items = $array;
        }

        public function increment_item(string $name) {
            $this->items[$name]++;
        }

        public function set_quantity(string $name, int $quantity) {
            if ($this->items[$name] >= 0) {
                $this->items[$name] = $quantity;
            }
        }

        public function decrement_item(string $name) {
            if ($this->items[$name] > 0) {
                $this->items[$name]--;
            }
        }

        public function delete_item(string $name) {
            unset($this->items[$name]);
        }

        public function add_item(string $name, int $quantity = 1) {
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