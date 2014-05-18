<?php
    /**
     * MagicPHP Docs
     * 
     * @package     MagicPHP Docs
     * @author      André Ferreira <andrehrf@gmail.com>
     * @link        https://github.com/magicphp/docs MagicPHP(tm)
     * @license     MIT License (http://www.opensource.org/licenses/mit-license.php)
     */
    
    class MagicDocs{
        /**
         * Função para configurar template padrão
         * 
         * @static
         * @access public
         * @param string $sTemplate
         * @return void
         */
        public static function LoadTemplateDefault($sTemplate){
            Output::SetNamespace("MagicPHP-Docs");
            
            if(!empty($sTemplate))
                Output::SetTemplate(Storage::Join("module.docs.shell.tpl", $sTemplate), Storage::Join("module.docs.shell.tpl", "masterpage.tpl"));
            else
                Output::SetTemplate(Storage::Join("module.docs.shell.tpl", "masterpage.tpl"));
            
            Output::AppendCSS(Storage::Join("module.docs.shell.css", "bootstrap.min.css"));
            Output::AppendCSS(Storage::Join("module.docs.shell.css", "highlight" . SP . "default.css")); 
            Output::AppendCSS(Storage::Join("module.docs.shell.css", "hightlight.custom.css"));
            Output::AppendCSS(Storage::Join("module.docs.shell.css", "jquery.cleditor.css"));
            Output::AppendCSS(Storage::Join("module.docs.shell.css", "masterpage.css"));
            Output::AppendJS(Storage::Join("module.docs.shell.js", "jquery-2.1.0.min.js"));
            Output::AppendJS(Storage::Join("module.docs.shell.js", "bootstrap.min.js"));
            Output::AppendJS(Storage::Join("module.docs.shell.js", "highlight.pack.js"));
            Output::AppendJS(Storage::Join("module.docs.shell.js", "jquery.cleditor.min.js"));
            Output::AppendJS(Storage::Join("module.docs.shell.js", "masterpage.js"));
        }
        
        /**
         * Função para carregar menu
         * 
         * @access public
         * @return void
         */
        public static function LoadNavbar($sRoute = ""){
            $aNavbar = json_decode(file_get_contents(Storage::Join("docs.dir.data", "navbar.json")), true);
            
            if(array_key_exists(Storage::Get("user.language", Storage::Get("docs.language.default", "pt")), $aNavbar))
                $aNavbar = $aNavbar[Storage::Get("user.language", Storage::Get("docs.language.default", "pt"))];
            else
                $aNavbar = $aNavbar[Storage::Get("docs.language.default", "pt")];
            
            foreach($aNavbar as $sKey => $aItem){
                if($sRoute.".html" == $aItem["route"])
                    Storage::Set("dynamic.title", $aItem["title"] . " &middot; " . Storage::Get("app.title"));
            }
            
            Output::ReplaceList("navbar", $aNavbar);
        }

        /**
         * Função para exibição da tela principal
         * 
         * @static
         * @access public
         * @return void
         */
        public static function Index(){
            Storage::Set("title", Storage::Get("app.title"));
            MagicDocs::LoadTemplateDefault("index.tpl");
            MagicDocs::LoadNavbar();
            Output::Send();
        }
        
        /**
         * Função para criação/edição de página generica, caso não haja login, apenas exibição
         * 
         * @static
         * @access public
         * @return void
         */
        public static function GenericPage($sRoute){
            $sRoute = str_replace(".html", "", $sRoute);
            MagicDocs::LoadTemplateDefault("generic.tpl");
            MagicDocs::LoadNavbar($sRoute);
            
            //var_dump($sRoute); die();
            
            if(file_exists(Storage::Join("docs.dir.data", strtolower($sRoute).".txt"))){
                $sBuffer = file_get_contents(Storage::Join("docs.dir.data", strtolower($sRoute).".txt"));
                Storage::Set("page.buffer", base64_encode(utf8_decode($sBuffer)));
                $sOutput = MagicDocs::ParseWikiPage($sBuffer);
                Storage::Set("dynamic.contents", $sOutput);
                Output::Send();
            }
            else{
                //if(Storage::Get("user.id", 0) > 0){
                    Storage::Set("page.buffer", "");
                    Output::ReplaceList("headers", array());
                    Storage::Set("dynamic.contents", "");
                    Output::Send();
                /*}
                else{
                    Output::SendHTTPCode(404);
                }*/
            }          
        }
        
        /**
         * Função para realizar o preview da edição
         * 
         * @static
         * @access public
         * @return void
         */
        public static function Preview(){
            $sBuffer = html_entity_decode(MagicDocs::ParseWikiPage(utf8_encode(base64_decode(Storage::Get("post.contents"))), false));
            die($sBuffer);
        }
        
        /**
         * Função para formatação de texto Wiki antes de ser processado para HTML
         * 
         * @static
         * @access public
         * @return void
         */
        public static function ParseWikiPage($sBuffer, $bHeaders = true){  
            $aSources = array();
            $aPre = array();
            
            //Title and description
            if(preg_match_all("/<title>(.*?)<\/title>/si", $sBuffer, $aMatch)){
                if(is_array($aMatch)){
                    Storage::Set("title", $aMatch[1][0]);
                    $sBuffer = str_replace($aMatch[0][0], "", $sBuffer);
                }
            }
            
            if(preg_match_all("/<description>(.*?)<\/description>/si", $sBuffer, $aMatch)){
                if(is_array($aMatch)){
                    Storage::Set("description", $aMatch[1][0]);
                    $sBuffer = str_replace($aMatch[0][0], "", $sBuffer);
                }
            }
            
            //Sources
            /*$iStart = 1;
                 
            while($iStart){
                $iStart = strpos($sBuffer, "<source>");
                $iEnd = strpos($sBuffer, "</source>", $iStart);
                
                if($iStart >= 0 && $iEnd > 0){
                    $sR = substr($sBuffer, $iStart, ($iEnd-$iStart)+9);
                    $sHash = md5($sR);
                    $aSources[$sHash] = highlight_string(str_replace(array("<source>", "</source>"), array("", ""), $sR), true);
                    $sBuffer = str_replace($sR, $sHash, $sBuffer);  
                }
                else{
                    break;
                }
                
                $iStart = strpos($sBuffer, "<source>");
            }      */
            
            //Pre
            $iStart = 1;
                 
            while($iStart){
                $iStart = strpos($sBuffer, "<pre>");
                $iEnd = strpos($sBuffer, "</pre>", $iStart); 
                   
                if($iStart >= 0 && $iEnd > 0){
                    $sR = substr($sBuffer, $iStart, ($iEnd-$iStart)+6);

                    $sHash = md5($sR);
                    $aPre[$sHash] = $sR;
                    $sBuffer = str_replace($sR, $sHash, $sBuffer);  
                }
                else{
                    break;
                }
                
                $iStart = strpos($sBuffer, "<pre>");
            }
                                
            $aOutput = WikiTextToHTML::convertWikiTextToHTML(explode("\n", $sBuffer));
            $sOutput = "";
   
            foreach($aOutput as $sLine)
                $sOutput .= $sLine."\n";

            foreach($aSources as $sKey => $sValue)
                $sOutput = str_replace($sKey, "<div class=\"code\">".str_replace(array("{", "}", "&"), array("&#123;", "&#125;", "&amp;"), $sValue)."</div>", $sOutput);
                        
            foreach($aPre as $sKey => $sValue)
                $sOutput = str_replace($sKey, "<div class=\"code\">".str_replace(array("{", "}", "<", ">", "&"), array("&#123;", "&#125;", "&lt;", "&gt;", "&amp;"), $sValue)."</div>", $sOutput);
                             
            $sOutput = str_replace(array("&amp;lt;pre&amp;gt;", "&amp;lt;code&amp;gt;", "&amp;lt;code", "'&amp;gt;", "&amp;lt;/code&amp;gt;", "&amp;lt;/pre&amp;gt;"), array("<pre>", "<code>", "<code", "'>", "</code>", "</pre>"), $sOutput);//Bugfix
            
            //H1  
            if($bHeaders){
                $aHeaders = array();
                $iStart = 1;

                while($iStart){
                    $iStart = strpos($sOutput, "<h1>");
                    $iEnd = strpos($sOutput, "</h1>", $iStart);

                    if($iEnd > 0){
                        $sR = substr($sOutput, $iStart, ($iEnd-$iStart)+5);
                        $sId = strtolower(MagicDocs::StringToSeo(strip_tags($sR)));
                        $aHeaders[] = array("value" => str_replace(array("<h1>", "</h1>"), "", $sR), "id" => $sId);
                        $sOutput = str_replace($sR, "<h1 id=\"".$sId."\">".str_replace(array("<h1>", "</h1>"), "", $sR)."</h1>", $sOutput);
                    }

                    $iStart = strpos($sOutput, "<h1>");
                }            

                Storage::Set("has.headers", count($aHeaders) > 0);
                Output::ReplaceList("headers", $aHeaders);
            }

            return $sOutput;
        }
        
        /**
         * Função para remover acentuação, caracteres especiais para IDs
         * 
         * @param string $s
         * @return string
         */
        public static function StringToSeo($s){
             $s = str_replace(' ', '-', $s);
             $aA1 = array("á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï", "ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç", "Á", "À", "Â", "Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô", "Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç");
             $aA2 = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c", "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C");
             $s = str_replace($aA1, $aA2, $s);
             $s = preg_replace("/[^a-zA-Z0-9\-]/", "", $s);
             return iconv("CP1252", "UTF-8", $s);
         }
                
        /**
         * Função para alterar idioma 
         * 
         * @static
         * @access public
         * @return void
         */
        public static function ChangeLanguage($sSig){
            if($sSig == "pt" || $sSig == "us")
                Session::Set("user.language", $sSig);
            
            Output::Redirect(Storage::Get("route.root"));
        }
                     
        /**
         * Função para exibição da tela de login
         * 
         * @static
         * @access public
         * @return void
         */
        public static function DisplayLogin(){
            Storage::Set("title", "MagicPHP");
            Output::SetNamespace("MagicPHP-Docs-Login");
            Output::SetTemplate(Storage::Join("module.docs.shell.tpl", "login.tpl"));
            Output::AppendCSS(Storage::Join("module.docs.shell.css", "login.css"));
            Output::Send();
        }
        
        /**
         * Função para realizar login
         * 
         * @static
         * @access public
         * @return void
         */
        public static function Login(){
            $aUsers = json_decode(file_get_contents(Storage::Join("docs.dir.data", "login.json")), true);
            
            foreach($aUsers as $iKey => $aUser){
                if($aUser["user"] == Storage::Get("post.login_user") && $aUser["pwd"] == sha1(Storage::Get("post.login_pwd"))){
                    Session::Login($iKey, $aUser["user"], $aUser["name"]);
                    Output::Redirect(Storage::Get("route.root"));
                }
            }
            
            Output::Redirect(Storage::Get("route.root")."login");
        }
        
        /**
         * Função para realizar logout
         * 
         * @static
         * @access public
         * @return void
         */
        public static function Logout(){
            Session::Logout();
            Output::Redirect(Storage::Get("route.root"));
        }
        
        /**
         * Função para salvar conteúdo editado
         * 
         * @static
         * @access public
         * @param string $sRoute
         * @return void
         */
        public static function SaveGenericPage($sRoute){
            $sRoute = str_replace(".html", "", $sRoute);
            $sFilename = Storage::Join("docs.dir.data", strtolower($sRoute).".txt");
            file_put_contents($sFilename, utf8_encode(base64_decode(Storage::Get("post.contents"))));
            die('ok');
        }
    }