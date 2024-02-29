<?php

namespace App\Http\Interfaces;

interface ArticleInterface
{
    public function getPath();

    public function setUpdatedContent(string $newState);

    public function getFileName();

    public function getRAWContent();
    
    public function logUpdateMetrics(string $newState);
}