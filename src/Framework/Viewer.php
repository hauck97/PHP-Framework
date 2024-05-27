<?php

declare(strict_types=1);

namespace Framework;

class Viewer
{
    public function render(string $templateName, array $data = []): string
    {
        extract($data, EXTR_SKIP);

        ob_start();

        require "./../views/$templateName";

        return ob_get_clean();
    }
}