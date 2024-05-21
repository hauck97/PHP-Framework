<?php

namespace Framework;

class Viewer
{
    public function render(string $templateName, array $data = [])
    {
        extract($data, EXTR_SKIP);

        require "./../views/$templateName";
    }
}