<?php
class EmailTemplate {
    public $slug;
    public $name;
    public $subject;
    public $content;
    public $content_multiple;

    public function __construct($slug, $name, $subject, $content, $content_multiple) {
        $this->slug = $slug;
        $this->name = $name;
        $this->subject = $subject;
        $this->content = $content;
        $this->content_multiple = $content_multiple;
    }
}
?>