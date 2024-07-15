<?php

class ErrorController extends Controller
{
    public function error404()
    {
        http_response_code(404);
        $this->view('error/404');
    }
}