<?php

namespace App\Http\Traits;

trait MarkdownArticleTrait
{
    /**
     * Gets the meta tag from the contetn of .md file.
     * 
     * @param string $tag Required tag ('title').
     * @return string Value of the tag.
     */
    public function getMeta(string $tag): string
    {
        if (preg_match('/' . $tag . ':\s*"(.*?)"/s', $this->content, $matches))
        {
            $value = trim($matches[1]);
        } elseif (preg_match('/' . $tag . ':\s*(\d+)/', $this->content, $matches))
        {
            $value = $matches[1];
        } elseif (preg_match('/' . $tag . ':\s*\[(.*?)\]/', $this->content, $matches))
        {
            $value = str_replace('"', '', $matches[1]);
        } else 
        {
            $value = '';
        }

        return $value;
    }
}