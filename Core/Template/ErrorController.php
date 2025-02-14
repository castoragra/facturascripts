<?php
/**
 * This file is part of FacturaScripts
 * Copyright (C) 2017-2023 Carlos Garcia Gomez <carlos@facturascripts.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace FacturaScripts\Core\Template;

use Exception;
use FacturaScripts\Core\Contract\ErrorControllerInterface;
use FacturaScripts\Core\Kernel;

abstract class ErrorController implements ErrorControllerInterface
{
    /** @var Exception */
    protected $exception;

    /** @var string */
    protected $url;

    public function __construct(Exception $exception, string $url = '')
    {
        $this->exception = $exception;
        $this->url = $url;
    }

    protected function html(string $title, string $bodyHtml, string $bodyCss): string
    {
        return '<!doctype html>'
            . '<html lang="en">'
            . '<head>'
            . '<meta charset="utf-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1">'
            . '<title>' . $title . '</title>'
            . '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"'
            . ' integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">'
            . '</head>'
            . '<body class="' . $bodyCss . '">'
            . $bodyHtml
            . '</body>'
            . '</html>';
    }

    protected function htmlCard(string $title, string $cardBody, string $bodyCss): string
    {
        $info = Kernel::getErrorInfo(
            $this->exception->getCode(),
            $this->exception->getMessage(),
            $this->exception->getFile(),
            $this->exception->getLine()
        );

        $body = '<div class="container">'
            . '<div class="row justify-content-center">'
            . '<div class="col-sm-6">'
            . '<div class="card shadow mt-5 mb-5">'
            . '<div class="card-body">'
            . '<img src="' . $info['report_qr'] . '" class="float-end" alt="QR" />'
            . $cardBody
            . '</div>'
            . '<div class="card-footer">'
            . '<form method="post" action="' . $info['report_url'] . '" target="_blank">'
            . '<input type="hidden" name="error_code" value="' . $info['code'] . '">'
            . '<input type="hidden" name="error_message" value="' . $info['message'] . '">'
            . '<input type="hidden" name="error_file" value="' . $info['file'] . '">'
            . '<input type="hidden" name="error_line" value="' . $info['line'] . '">'
            . '<input type="hidden" name="error_hash" value="' . $info['hash'] . '">'
            . '<input type="hidden" name="error_url" value="' . $info['url'] . '">'
            . '<input type="hidden" name="error_core_version" value="' . $info['core_version'] . '">'
            . '<input type="hidden" name="error_plugin_list" value="' . $info['plugin_list'] . '">'
            . '<input type="hidden" name="error_php_version" value="' . $info['php_version'] . '">'
            . '<input type="hidden" name="error_os" value="' . $info['os'] . '">'
            . '<button type="submit" class="btn btn-secondary">Read more / Leer más</button>'
            . '</form>'
            . '</div>'
            . '</div>'
            . '</div>'
            . '</div>'
            . '</div>';

        return $this->html($title, $body, $bodyCss);
    }
}
