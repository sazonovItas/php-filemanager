<?php
declare(strict_types=1);

namespace app\controllers;

use Bayfront\MimeTypes\MimeType;

class VueController extends AbstractController
{
    public function renderTemplate($template): false|string {
        ob_start();
        include $template;
        return ob_get_clean();
    }

    // TODO: change mime type to request content type
    public function run() {
        $prefixPath = "../web/client/static";
        $path = rtrim($this->request->getUrl()->getPath(), " \/\n\r\t\v\0");

        if (file_exists($prefixPath . $path) && !is_dir($prefixPath . $path)) {
            $mimeType = MimeType::fromFile($prefixPath . $path);
            $this->response->header("Content-type: " . $mimeType);
            return $this->renderTemplate($prefixPath . $path);
        } else {
            if (file_exists($prefixPath . $path . "/index.html")) {
                $this->response->header("Content-Type: text/html; charset=utf-8");
                return $this->renderTemplate($prefixPath . $path . "/index.html");
            }
        }

        $this->response->header("Content-type: text/html; charset=utf-8");
        return $this->renderTemplate($prefixPath . "/index.html");
    }
}
