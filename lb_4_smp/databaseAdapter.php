<?php
interface DatabaseAdapter {
    public function connect();
    public function query($query);
    public function close();
}
