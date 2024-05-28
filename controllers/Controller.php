<?php
class Controller {
    protected $params;

    public function getParams() {
        return $this->params;
    }

    protected function parseParams() {
        $query_params = [];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
        if (!empty($uri)) {
            parse_str($uri, $query_params);
        }
        return $query_params;
    }
}
?>