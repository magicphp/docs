<?php
    /**
     * Initializer module
     * 
     * @package     MagicPHP Docs
     * @author      André Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/magicphp/docs MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php) 
     */

    $oModule = Modules::Append("docs", __DIR__ . SP);
    $oModule->Set("name", "MagicPHP Docs")
            ->Set("author", "André Ferreira <andrehrf@gmail.com>")
            ->Set("website", "https://magicphp.org")
            ->Set("licence", "MIT License")
            ->Start();

    require_once(__DIR__ . SP . "core" . SP . "wikitexttohtml.php"); //Rotas   
    require_once(__DIR__ . SP . "core" . SP . "simple_html_dom.php"); //Rotas   