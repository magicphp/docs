<?php
    /**
     * Frontend Controller
     * 
     * @package     MagicPHP Docs
     * @author      André Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/magicphp/docs MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */
     
    Routes::SetOverloadFrontend(true);
    Routes::Set("", "GET", "MagicDocs::Index");
    Routes::Set("language/{sig}", "GET", "MagicDocs::ChangeLanguage");
    Routes::Set("login", "GET", "MagicDocs::DisplayLogin");
    Routes::Set("login", "POST", "MagicDocs::Login");
    Routes::Set("logout", "GET", "MagicDocs::Logout");
    Routes::Set("preview", "POST", "MagicDocs::Preview");
    Routes::Set("{route}", "GET", "MagicDocs::GenericPage");
    Routes::Set("{route}", "POST", "MagicDocs::SaveGenericPage");
    Routes::SetDynamicRoute(function(){ Output::SendHTTPCode(404);});