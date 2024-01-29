<?php
class Menu {
    private $items;

    public function __construct() {
        $this->items = [];
    }

    public function addItem($label, $url) {
        $this->items[] = ['label' => $label, 'url' => $url];
    }

    public function render() {
        echo '<ul>';
        foreach ($this->items as $item) {
            echo '<li><a href="' . $item['url'] . '">' . $item['label'] . '</a></li>';
        }
        echo '</ul>';
    }
}
?>
