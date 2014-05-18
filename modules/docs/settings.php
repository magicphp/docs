<?php
    /**
     * Settings
     * 
     * @package     MagicPHP Docs
     * @author      André Ferreira <andrehrf@gmail.com>
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php) 
     */

    Session::Start("magicphp.docs", Storage::Join("dir.cache", "sessions"));
    
    Storage::Set("app.title", "MagicPHP");
    Storage::Set("docs.dir.data", __DIR__ . DIRECTORY_SEPARATOR . "data" . DIRECTORY_SEPARATOR); 
    Storage::Set("docs.language.default", "pt");
    Storage::Set("user.language", (!is_null(Session::Get("user.language")) ? Session::Get("user.language") : "pt"));
     
    //Carregando conteúdo de linguagem
    $aContents = json_decode(file_get_contents(Storage::Join("docs.dir.data", "contents.json")), true);
    $sLanguage = Storage::Get("user.language", Storage::Get("docs.language.default", "pt"));
    
    if(empty($sLanguage)){
        $sLanguage = "pt";
        Storage::Set("user.language", "pt");
    }
            
    foreach(@$aContents[$sLanguage] as $sKey => $mValue)
        Storage::Set($sKey, $mValue);