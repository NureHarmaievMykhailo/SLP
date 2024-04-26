<?php
    require_once('list-item.php');
    /**
     * Represents shopping cart.
     * 
     * Represents shopping cart. Contains an associative array of (ListItem->title => ListItem) key-value pairs. Contains methods for processing common shopping cart operations.
     */
    class Cart {
        private $items = array(); // an array containing ListItem titles and their quantities as key-value pairs

        public function __construct(array $array) {
            foreach ($array as $new_item) {
                array_push($this->items, array($new_item->get_title()=>$new_item));
            }
        }

        public function clear() {
            $this->items = array();
        }

        public function get_items() {
            return $this->items;
        }

        public function set_items(array $array) {
            $this->clear();
            foreach ($array as $new_item) {
                array_push($this->items, array($new_item->get_title()=>$new_item));
            }
        }

        public function increment_item(string $name) {
            $new_value = $this->items[$name]->get_quantity() + 1;
            $this->items[$name]->set_quantity($new_value);
        }

        /**
         * Sets the quantity of a specific ListItem in the shopping cart.
         * 
         * @param string $name The name (title) of the item whose quantity is to be set.
         * @param int $quantity The quantity to set for the specified item.
         * 
         * @return string|null Returns a string indicating success or failure. If an exception occurs during the process, it returns "Unable to set quantity". Otherwise, returns null.
         */
        public function set_quantity(string $name, int $quantity) {
            try {
                $this->items[$name]->set_quantity($quantity);
            }
            catch (Exception $ex) {
                return "Unable to set quantity";
            }
        }

        public function decrement_item(string $name) {
            $new_value = $this->items[$name]->get_quantity() - 1;
            if ($new_value >= 0) {
                $this->items[$name]->set_quantity($new_value);
            }
            else {
                $this->items[$name]->set_quantity(0);
            }
        }

        public function delete_item(string $name) {
            unset($this->items[$name]);
        }

        /**
         * Adds a new (ListItem->title => ListItem) key-value pair to the array.
         * @param ListItem $new_item The new ListItem object to be inserted.
         */
        public function add_item(ListItem $new_item) {
            array_push($this->items, array($new_item->get_title()=>$new_item));
        }

        public function calculate_total_price() {
            $sum = 0;
            foreach($this->items as $i) {
                $sum += $i->get_price() * $i->get_quantity();
            }
            return $sum;
        }
    }
?>